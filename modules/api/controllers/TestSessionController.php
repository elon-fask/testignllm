<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use app\models\Candidates;
use app\models\CandidateTransactions;
use app\models\CandidateDeclineTestAttestation;
use app\models\TestSession;
use app\models\CandidateSession;
use app\models\PracticalTestSchedule;
use app\models\TestSessionPhoto;
use app\models\ApplicationType;
use app\models\ApplicationTypeFormSetup;
use app\helpers\UtilityHelper;
use app\helpers\NotificationHelper;

class TestSessionController extends ActiveController
{
    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => [getenv('CRANETRX_URL')],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
        ];
    }

    public $modelClass = 'app\models\TestSession';

    public function actionFind($startDate, $endDate, $testSiteId = null)
    {
        $request = \Yii::$app->request;

        try {
            $startDateRange = date_create($startDate);
            $endDateRange = date_create($endDate);
        } catch (Exception $e) {
            throw new yii\web\BadRequestHttpException('Invalid date format. Please use the format YYYY-MM-DD.');
        }

        if ($request->isGet) {
            $testSessionQuery = TestSession::find()->where(['>=', 'start_date', $startDateRange->format('Y-m-d H:i:s')])->andWhere(['<=', 'end_date', $endDateRange->format('Y-m-d H:i:s')]);

            if (isset($testSiteId)) {
                $testSessionQuery->andWhere(['test_site_id' => $testSiteId]);
            }

            $testSessions = $testSessionQuery->all();

            return ArrayHelper::toArray($testSessions, [
                'app\models\TestSession' => [
                    'id',
                    'type' => 'testSessionType',
                    'desc' => 'fullTestSessionDescription',
                    'startDate' => 'start_date',
                    'endDate' => 'end_date'
                ]
            ]);
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionCurrent($startDateRange = null, $endDateRange = null, $daysAhead = 30)
    {
        $request = \Yii::$app->request;

        if ($request->isGet) {
            return TestSession::getAllClasses($daysAhead);
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionPrevious($startDateRange = null, $endDateRange = null, $daysPrevious = 30)
    {
        $request = \Yii::$app->request;

        if ($request->isGet) {
            return TestSession::getAllPreviousClasses($daysPrevious);
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionRosterInfo($id)
    {
        $request = \Yii::$app->request;

        if ($request->isGet) {
            $testSession = TestSession::findOne(['id' => $id]);

            if (isset($testSession)) {
                $testSession = TestSession::findOne($id);
                $candidates = $testSession->getCandidates()->where(['isArchived' => false])->orderBy(['last_name' => SORT_ASC])->all();

                $testSessionArr = ArrayHelper::toArray($testSession, [
                    'app\models\TestSession' => [
                        'id',
                        'counterpartId' => function($testSession) {
                            $counterpart = $testSession->counterpart;

                            if (isset($counterpart)) {
                                return $counterpart->id;
                            }

                            return null;
                        },
                        'testSessionPhotos',
                        'practicalTestSchedules' => 'practicalTestSchedule'
                    ]
                ]);

                $candidatesArr = ArrayHelper::toArray($candidates, [
                    'app\models\Candidates' => [
                        'id',
                        'name' => function($candidate) {
                            return $candidate->last_name . ', ' . $candidate->first_name;
                        },
                        'companyName' => 'company_name',
                        'applicationTypeName' => function($candidate) {
                            return $candidate->applicationType->name;
                        },
                        'cellNumber',
                        'transactions',
                        'pendingTransactions',
                        'photoS3Key' => 'photo_s3_key',
                        'trainingSessions',
                        'declinedTests' => function($candidate) use ($testSession) {
                            $declinedTests = CandidateDeclineTestAttestation::find()->where([
                                'candidate_id' => $candidate->id,
                                'test_session_id' => $testSession->id
                            ])->all();

                            return $declinedTests;
                        },
                        'scoreSheetPhotos' => function($candidate) use ($testSession) {
                            return $candidate->getScoreSheetPhotos()->where(['testSessionId' => $testSession->id])->all();
                        },
                        'practiceTimeCredits' => 'practice_time_credits',
                        'practicalTestSchedules' => function($candidate) use ($testSession) {
                            $testSessionId = isset($testSession->practical_test_session_id) ? $testSession->practical_test_session_id : $testSession->id;

                            return PracticalTestSchedule::findAll([
                                'candidate_id' => $candidate->id,
                                'test_session_id' => $testSessionId
                            ]);
                        },
                        'applicationFormSetup' => function($candidate) {
                            $applicationFormSetups = ApplicationTypeFormSetup::findAll([
                                'application_type_id' => $candidate->application_type_id
                            ]);

                            $applicationFormSetupMerged = array_reduce($applicationFormSetups, function ($acc, $applicationFormSetup) {
                                return array_merge($acc, json_decode($applicationFormSetup->form_setup, true));
                            }, []);

                            $customFormSetup = array_reduce(json_decode($candidate->custom_form_setup, true), function ($acc, $formValues) {
                                return array_merge($acc, $formValues);
                            }, []);

                            $mergedFormSetup = array_merge($applicationFormSetupMerged, $customFormSetup);

                            $coreExamEnabled = isset($mergedFormSetup['W_EXAM_CORE']) && $mergedFormSetup['W_EXAM_CORE'] === 'on';
                            $writtenSWEnabled = isset($mergedFormSetup['W_EXAM_TLL']) && $mergedFormSetup['W_EXAM_TLL'] === 'on';
                            $writtenFXEnabled = isset($mergedFormSetup['W_EXAM_TSS']) && $mergedFormSetup['W_EXAM_TSS'] === 'on';
                            $practicalSWEnabled = isset($mergedFormSetup['P_TELESCOPIC_TLL']) && $mergedFormSetup['P_TELESCOPIC_TLL'] === 'on';
                            $practicalFXEnabled = isset($mergedFormSetup['P_TELESCOPIC_TSS']) && $mergedFormSetup['P_TELESCOPIC_TSS'] === 'on';

                            return [
                                'coreExamEnabled' => $coreExamEnabled,
                                'writtenSWEnabled' => $writtenSWEnabled,
                                'writtenFXEnabled' => $writtenFXEnabled,
                                'practicalSWEnabled' => $practicalSWEnabled,
                                'practicalFXEnabled' => $practicalFXEnabled
                            ];
                        }
                    ]
                ]);

                return [
                    'class' => $testSessionArr,
                    'candidates' => $candidatesArr
                ];
            }

            throw new \yii\web\NotFoundHttpException('Class not found');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionZip($id)
    {
        $request = \Yii::$app->request;

        if ($request->isGet) {
            $testSession = TestSession::findOne(['id' => $id]);

            if (isset($testSession)) {
                $testSessionCounterpart = false;
                $candidates = [];
                if (isset($testSession->practical_test_session_id)) {
                    $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $testSession->id . ', ' . $testSession->practical_test_session_id .')) AND isArchived = 0 ORDER BY last_name')->all();
                    $testSessionCounterpart = TestSession::findOne($testSession->practical_test_session_id);
                } else {
                    $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $testSession->id . ', (SELECT id FROM test_session WHERE practical_test_session_id = '. $testSession->id .'))) AND isArchived = 0 ORDER BY last_name')->all();
                    $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
                }

                $zip = new \ZipArchive();
                $filename = 'mugshot-' . $testSession->session_number . '.zip';
                $fileloc = '/tmp/' . $filename;

                if ($zip->open($fileloc, \ZipArchive::CREATE) !== TRUE) {
                    throw new \yii\web\ServerErrorHttpException('Unable to generate zip file.');
                }

                $s3Client = new \Aws\S3\S3Client([
                    'version' => 'latest',
                    'region' => 'us-west-2'
                ]);

                $s3Client->registerStreamWrapper();
                $s3Bucket = 's3://' . getenv('S3_CANDIDATE_PHOTO_BUCKET') . '/';

                foreach ($candidates as $candidate) {
                    if (isset($candidate->photo_s3_key)) {
                        $s3File = $s3Bucket . $candidate->photo_s3_key;
                        if (is_file($s3File)) {
                            $data = file_get_contents($s3File);
                            $zip->addFromString($candidate->last_name . ', ' . $candidate->first_name . '.png', $data);
                        }
                    }
                }

                $zip->close();

                $file = file_get_contents($fileloc);
                header('Content-Type: ' . 'application/zip');
                header('Content-Length: ' . filesize($fileloc));
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                echo $file;
                flush();
            }

            throw new \yii\web\NotFoundHttpException('Class not found');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionClassCertificate($id)
    {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            $testSession = TestSession::findOne(['id' => $id]);

            if (!isset($testSession)) {
                throw new \yii\web\NotFoundHttpException('Test Session not found.');
            }

            $postData = $request->post();

            if (!isset($postData['instructorName'])) {
                throw new yii\web\BadRequestHttpException('No instructor name provided.');
            }

            if (!isset($postData['certDate'])) {
                throw new yii\web\BadRequestHttpException('No certificate date provided.');
            }

            $reportParams = [
                'instructorName' => $postData['instructorName'],
                'certDate' => $postData['certDate']
            ];

            $filename = 'session-student-certificates.pdf';
            $certsUrl = '/app-forms/test-session/' . $testSession->getFolderDirectory() . '/' . $filename;

            $candidates = UtilityHelper::generateSessionCertificates($testSession->id, true, $reportParams);

            return [
                'status' => 'OK',
                'certsUrl' => $certsUrl
            ];
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionUploadPhoto($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $postData = $request->post();

        if (!isset($postData['photo'])) {
            throw new \yii\web\BadRequestHttpException('No photo in POST request.');
        }

        if (!isset($postData['contentType'])) {
            throw new \yii\web\BadRequestHttpException('No contentType in POST request.');
        }

        $photoData = base64_decode(explode(',', $postData['photo'])[1]);

        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2'
        ]);

        $photoS3Key = 'class-photo' . $testSession->id . '-' . \Yii::$app->getSecurity()->generateRandomString();

        $s3->putObject([
            'Bucket' => getenv('S3_TEST_SESSION_PHOTO_BUCKET'),
            'Key' => $photoS3Key,
            'Body' => $photoData,
            'ContentType' => $postData['contentType'],
            'ContentDisposition' => 'inline; filename=' . $photoS3Key . $postData['fileExtension']
        ]);

        $testSessionPhoto = new TestSessionPhoto();
        $testSessionPhoto->test_session_id = $testSession->id;
        $testSessionPhoto->s3_key = $photoS3Key;

        $tz = 'America/Los_Angeles';
        $ts = time();
        $dt = new \DateTime('now', new \DateTimeZone($tz));
        $dt->setTimestamp($ts);
        $dateTimeStr = $dt->format('Y-m-d H:i:s');

        $testSessionPhoto->date_created = $dateTimeStr;

        if ($testSessionPhoto->save()) {
            return $testSessionPhoto->toArray();
        }

        throw new \yii\web\ServerErrorHttpException('Test Session Photo could not be saved.');
    }

    public function actionRegisterWalkInCandidate($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $wSession = null;
        $pSession = null;

        if (isset($testSession->practical_test_session_id)) {
            $wSession = $testSession;
            $pSession = TestSession::findOne($testSession->practical_test_session_id);
        } else {
            $wSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
            $pSession = $testSession;
        }

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $postData = $request->post();

        $requiredFields = ['applicationTypeId', 'isPurchaseOrder', 'firstName', 'lastName', 'phone', 'email'];

        foreach($requiredFields as $field) {
            if (!isset($postData[$field])) {
                throw new \yii\web\BadRequestHttpException('Missing ' . $field . ' field.');
            }
        }

        $applicationType = ApplicationType::findOne($postData['applicationTypeId']);

        if (!isset($applicationType)) {
            throw new \yii\web\NotFoundHttpException('Application Type not found.');
        }

        $candidate = new Candidates();
        $candidate->application_type_id = $applicationType->id;
        $candidate->first_name = $postData['firstName'];
        $candidate->last_name = $postData['lastName'];

        if (isset($postData['middleName'])) {
            $candidate->middle_name = $postData['middleName'];
        }

        $candidate->isPurchaseOrder = !!$postData['isPurchaseOrder'];

        try {
            $phone = $postData['phone'];
            $candidate->phone = '(' . substr($phone, 0, 3) . ')' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
        } catch(Error $e) {
        }

        $candidate->email = $postData['email'];

        if ($candidate->save()) {

            $wCandidateSession = new CandidateSession();
            $wCandidateSession->candidate_id = $candidate->id;
            $wCandidateSession->test_session_id = $wSession->id;

            $pCandidateSession = new CandidateSession();
            $pCandidateSession->candidate_id = $candidate->id;
            $pCandidateSession->test_session_id = $pSession->id;

            $initialCharge = new CandidateTransactions();
            $initialCharge->candidateId = $candidate->id;
            $initialCharge->amount = $applicationType->price;
            $initialCharge->paymentType = 10;
            $initialCharge->remarks = 'Application Type price for Walk-in Candidate';

            $walkInCharge = new CandidateTransactions();
            $walkInCharge->candidateId = $candidate->id;
            $walkInCharge->amount = 100;
            $walkInCharge->paymentType = 10;
            $walkInCharge->chargeType = 71;
            $walkInCharge->remarks = 'Walk-in Fee added';

            if ($wCandidateSession->save() && $pCandidateSession->save() && $initialCharge->save() && $walkInCharge->save()) {
                $candidateArr = ArrayHelper::toArray($candidate, [
                    'app\models\Candidates' => [
                        'id',
                        'name' => function($candidate) {
                            return $candidate->last_name . ', ' . $candidate->first_name;
                        },
                        'cellNumber',
                        'transactions',
                        'pendingTransactions',
                        'photoS3Key' => 'photo_s3_key',
                        'trainingSessions',
                        'scoreSheetPhotos' => function($candidate) use ($testSession) {
                            return $candidate->getScoreSheetPhotos()->where(['testSessionId' => $testSession->id])->all();
                        }
                    ]
                ]);

                // NotificationHelper::notifySendUserSuccess($candidate->id);

                return $candidateArr;
            }
        }

        throw new \yii\web\ServerErrorHttpException('Unable to register candidate.');
    }

    public function actionSetNcccoTestFeesCredit($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $postData = $request->post();

        if (!isset($postData['amount'])) {
            throw new \yii\web\BadRequestHttpException('No amount in POST request.');
        }

        $testSession->nccco_test_fees_credit = $postData['amount'];

        if ($testSession->save()) {
            return $testSession;
        }

        throw new \yii\web\ServerErrorHttpException('Unable to save Test Session.');
    }

    public function actionUpdatePracticalTestSchedule($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $practicalTestSessionId = null;
        if (isset($testSession->practical_test_session_id)) {
            $practicalTestSessionId = $testSession->practical_test_session_id;
        } else {
            $practicalTestSessionId = $testSession->id;
        }

        $postData = $request->post();

        $reqFields = ['type', 'day', 'new_or_retest', 'time', 'practice_time_only'];
        foreach ($reqFields as $reqField) {
            if (!isset($postData[$reqField])) {
                throw new \yii\web\BadRequestHttpException('No ' . $reqField . ' in POST request.');
            }
        }

        $practicalTestSchedule = new PracticalTestSchedule();
        $practicalTestSchedule->test_session_id = $practicalTestSessionId;

        $practicalTestSchedule->attributes = $postData;

        if ($postData['type'] == 'MAINTENANCE') {
            $practicalTestSchedule->candidate_id = null;
        }

        if (isset($postData['practice_hours']) && isset($postData['candidate_id'])) {
            $candidate = Candidates::findOne($postData['candidate_id']);

            if (!isset($candidate)) {
                throw new \yii\web\NotFoundHttpException('Candidate not found.');
            }

            if (isset($candidate->practice_time_credits)) {
                $newPracticeTimeCredits = number_format($candidate->practice_time_credits - (float) $postData['practice_hours'], 2);
                $candidate->practice_time_credits = $newPracticeTimeCredits;
                $candidate->save();
            }
        }

        if ($practicalTestSchedule->save()) {
            return $practicalTestSchedule;
        }

        throw new \yii\web\ServerErrorHttpException('Unable to save Practical Test Schedule.');
    }

    public function actionAddTestSessionDays($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request;
        $days = $request->post('days');
        if(gettype($days) != 'integer' || $days <= 0) {
            throw new \yii\web\BadRequestHttpException('invalid day input');
        }
        $testSession = TestSession::findOne($id);
        if(!$testSession) {
            throw new \yii\web\NotFoundHttpException('TestSession not found.');
        }
        if($testSession->canAddDays()) {
            throw new \yii\web\BadRequestHttpException('invalid test schedule');
        }
        $end_date = $testSession->end_date;
        $date_str = $end_date. ' + '.$days.' days';
        $testSession->end_date = date('Y-m-d H:i:s', strtotime($date_str));
        $testSession->extra_days = $days;
        if($testSession->save()) {
            return $testSession->getFullTestSessionDescription();
        }
        throw new \yii\web\ServerErrorHttpException('Unable to update Test Session end date');
    }

    public function actionDeletePracticalTestSchedule($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $practicalTestSchedule = PracticalTestSchedule::findOne($id);
        if (!isset($practicalTestSchedule)) {
            throw new \yii\web\NotFoundHttpException('Practical Test Schedule not found.');
        }
        if (isset($practicalTestSchedule->practice_hours)) {
            $candidate = Candidates::findOne($practicalTestSchedule->candidate_id);

            if (!isset($candidate)) {
                throw new \yii\web\ServerErrorHttpException('Practical Test Schedule not associated with a Candidate.');
            }

            if (isset($candidate->practice_time_credits)) {
                $candidate->practice_time_credits += $practicalTestSchedule->practice_hours;
                $candidate->save();
            }
        }

        $maxDay = \Yii::$app->db->createCommand('SELECT max(day) as maxDay FROM practical_test_schedule where test_session_id = '.$practicalTestSchedule->test_session_id)->queryOne();
        $maxDay = intval($maxDay['maxDay']);
        $extra_days = 0;
        $testSession = TestSession::findOne($practicalTestSchedule->test_session_id);
        $newDate = $testSession->end_date;
        if($maxDay - 4 > 0) {
            $extra_days = $maxDay - 4;
            $initial_date = date('Y-m-d H:i:s', strtotime($testSession->end_date. ' - '.$testSession->extra_days.' days'));;
            $date_diff = $extra_days - $testSession->extra_days;
            $date_str = $initial_date. ' + '.$date_diff.' days';
        } else {
            $date_str = $testSession->end_date. ' - '.$extra_days.' days';
        }
        $maxDay = \Yii::$app->db->createCommand('SELECT max(day) as maxDay FROM practical_test_schedule where test_session_id = '.$practicalTestSchedule->test_session_id)->queryOne();
        $maxDay = intval($maxDay['maxDay']);
        $extra_days = 0;
        $testSession = TestSession::findOne($practicalTestSchedule->test_session_id);
        $newDate = $testSession->end_date;
        if($maxDay - 4 > 0) {
            $extra_days = $maxDay - 4;
            $initial_date = date('Y-m-d H:i:s', strtotime($testSession->end_date. ' - '.$testSession->extra_days.' days'));;
            $date_diff = $extra_days - $testSession->extra_days;
            $date_str = $initial_date. ' + '.$date_diff.' days';
        } else {
            $date_str = $testSession->end_date. ' - '.$extra_days.' days';
        }

        $testSession->extra_days = $extra_days;
        $practicalTestSchedule->delete();
        $testSession->end_date = date('Y-m-d H:i:s', strtotime($date_str));;
        $testSession->save();

        return ['status' => 'OK', 'new_date' => $testSession->getFullTestSessionDescription()];
    }
}
