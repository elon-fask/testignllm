<?php

namespace app\modules\api\controllers;

use app\models\User;
use app\models\Candidates;
use app\models\CandidateSession;
use app\models\CandidatePreviousSession;
use app\models\CandidateTrainingSession;
use app\models\CandidateTrainingPhoto;
use app\models\CandidateSessionExamPhoto;
use app\models\CandidateDeclineTestAttestation;
use app\models\TestSession;
use app\helpers\NotificationHelper;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

class CandidatesController extends ActiveController
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

    public $modelClass = 'app\models\Candidates';

    public function actionStartTraining($id, $testSessionId, $type = null)
    {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            $candidate = Candidates::findOne($id);
            if (!isset($candidate)) {
                throw new \yii\web\NotFoundHttpException('Candidate not found.');
            }

            $testSession = TestSession::findOne($testSessionId);

            if (!isset($testSession)) {
                throw new \yii\web\NotFoundHttpException('Test Session not found.');
            }

            $session = CandidateSession::findOne([
                'candidate_id' => $candidate->id,
                'test_session_id' => $testSession->combinedIds
            ]);

            if (!isset($session)) {
                throw new \yii\web\NotFoundHttpException('Candidate not found in Test Session.');
            }

            $trainingSession = new CandidateTrainingSession();
            $trainingSession->candidate_id = $session->candidate_id;
            $trainingSession->test_session_id = $session->test_session_id;
            $trainingSession->type = $type;

            $tz = 'America/Los_Angeles';
            $ts = time();
            $dt = new \DateTime('now', new \DateTimeZone($tz));
            $dt->setTimestamp($ts);
            $dateTimeStr = $dt->format('Y-m-d H:i:s');

            $trainingSession->start_time = $dateTimeStr;
            $trainingSession->date_created = $dateTimeStr;

            if ($trainingSession->save()) {
                return $trainingSession->toArray();
            }

            throw new \yii\web\ServerErrorHttpException('Training Session could not be saved.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionStopTraining($id = null, $new = 1) {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();

            $trainingSession = null;

            if (!!$new) {
                $reqFields = ['candidateId', 'testSessionId'];
                foreach ($reqFields as $reqField) {
                    if (!isset($postData[$reqField])) {
                        throw new \yii\web\BadRequestHttpException('No ' . $reqField . ' in POST request.');
                    }
                }

                $trainingSession = new CandidateTrainingSession();
                $trainingSession->candidate_id = $postData['candidateId'];
                $trainingSession->test_session_id = $postData['testSessionId'];

                $tz = 'America/Los_Angeles';
                $ts = time();
                $dt = new \DateTime('now', new \DateTimeZone($tz));
                $dt->setTimestamp($ts);
                $dateTimeStr = $dt->format('Y-m-d H:i:s');

                $trainingSession->start_time = $dateTimeStr;
                $trainingSession->date_created = $dateTimeStr;

                if (!$trainingSession->save()) {
                    throw new \yii\web\ServerErrorHttpException('Training Session could not be saved.');
                }
            } else {
                $trainingSession = CandidateTrainingSession::findOne($id);
            }

            if (!isset($trainingSession)) {
                throw new \yii\web\NotFoundHttpException('Training Session not found.');
            }

            $tz = 'America/Los_Angeles';
            $ts = time();
            $dt = new \DateTime('now', new \DateTimeZone($tz));
            $dt->setTimestamp($ts);
            $dateTimeStr = $dt->format('Y-m-d H:i:s');

            if (isset($postData['attestationPhoto'])) {
                if (!isset($postData['userId'])) {
                    throw new \yii\web\BadRequestHttpException('No userId in POST request.');

                    $user = User::findOne($postData['userId']);

                    if (!isset($user)) {
                        throw new \yii\web\NotFoundHttpException('User not found.');
                    }
                }

                $photoData = base64_decode(explode(',', $postData['attestationPhoto'])[1]);

                $s3 = new \Aws\S3\S3Client([
                    'version' => 'latest',
                    'region' => 'us-west-2'
                ]);

                $photoS3Key = 'attestation-' . $trainingSession->id . '-' . \Yii::$app->getSecurity()->generateRandomString();

                $s3->putObject([
                    'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                    'Key' => $photoS3Key,
                    'Body' => $photoData,
                    'ContentType' => $postData['contentType'],
                    'ContentDisposition' => 'inline; filename=' . $photoS3Key . $postData['fileExtension']
                ]);

                $trainingSession->attestation_s3_key = $photoS3Key;
            }

            $trainingSession->end_time = $dateTimeStr;

            if (isset($postData['type'])) {
                $trainingSession->type = $postData['type'];
            }

            if (isset($postData['grade'])) {
                $trainingSession->grade = $postData['grade'];
            }

            if ($trainingSession->save()) {
                return $trainingSession->toArray();
            }

            throw new \yii\web\ServerErrorHttpException('Training Session could not be saved.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionDeleteTrainingSession($id) {
        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $trainingSession = CandidateTrainingSession::findOne($id);

        if (!isset($trainingSession)) {
            throw new \yii\web\NotFoundHttpException('Training Session not found.');
        }

        if (isset($trainingSession->attestation_s3_key)) {
            $s3 = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region' => 'us-west-2'
            ]);

            $s3->deleteObject([
                'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                'Key' => $trainingSession->attestation_s3_key
            ]);
        }

        if ($trainingSession->delete()) {
            return [
                'success' => true
            ];
        }

        throw new \yii\web\ServerErrorHttpException('Training Session could not be deleted.');
    }

    public function actionDeclineTest($id, $crane) {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();

            $reqFields = ['testSessionId', 'attestationPhoto', 'contentType', 'fileExtension'];
            foreach ($reqFields as $reqField) {
                if (!isset($postData[$reqField])) {
                    throw new \yii\web\BadRequestHttpException('No ' . $reqField . ' in POST request.');
                }
            }

            $candidate = Candidates::findOne($id);

            if (!isset($candidate)) {
                throw new \yii\web\NotFoundHttpException('Candidate not found.');
            }

            $testSession = TestSession::findOne($postData['testSessionId']);

            if (!isset($testSession)) {
                throw new \yii\web\NotFoundHttpException('Test Session not found.');
            }

            $candidateSession = CandidateSession::findOne(['candidate_id' => $candidate->id, 'test_session_id' => $testSession->id]);

            if (!isset($candidateSession)) {
                $testSession = $testSession->counterpart;

                if (!isset($testSession)) {
                    throw new \yii\web\NotFoundHttpException('Candidate not registered to Test Session.');
                }

                $candidateSession = CandidateSession::findOne(['candidate_id' => $candidate->id, 'test_session_id' => $testSession->id]);

                if (!isset($candidateSession)) {
                    throw new \yii\web\NotFoundHttpException('Candidate not registered to Test Session.');
                }
            }

            $tz = 'America/Los_Angeles';
            $ts = time();
            $dt = new \DateTime('now', new \DateTimeZone($tz));
            $dt->setTimestamp($ts);
            $dateTimeStr = $dt->format('Y-m-d H:i:s');

            $photoData = base64_decode(explode(',', $postData['attestationPhoto'])[1]);

            $s3 = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region' => 'us-west-2'
            ]);

            $photoS3Key = 'decline-test-' . $candidate->id . '-' . $testSession->id . '-' . \Yii::$app->getSecurity()->generateRandomString();

            $s3->putObject([
                'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                'Key' => $photoS3Key,
                'Body' => $photoData,
                'ContentType' => $postData['contentType'],
                'ContentDisposition' => 'inline; filename=' . $photoS3Key . $postData['fileExtension']
            ]);

            $attestation = new CandidateDeclineTestAttestation();

            $attestation->s3_key = $photoS3Key;
            $attestation->candidate_id = $candidate->id;
            $attestation->test_session_id = $testSession->id;
            $attestation->crane = $crane;
            $attestation->created_at = $dateTimeStr;

            if ($attestation->save()) {
                return $attestation->toArray();
            }

            throw new \yii\web\ServerErrorHttpException('Attestation could not be saved.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionUploadPhoto($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();
            $candidate = Candidates::findOne($id);

            if (isset($candidate)) {
                if (isset($postData['photo'])) {
                    $photoData = base64_decode(explode(',', $postData['photo'])[1]);

                    $s3 = new \Aws\S3\S3Client([
                        'version' => 'latest',
                        'region' => 'us-west-2'
                    ]);

                    $photoS3Key = 'photo-' . $candidate->id . '-' . \Yii::$app->getSecurity()->generateRandomString();

                    $s3->putObject([
                        'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                        'Key' => $photoS3Key,
                        'Body' => $photoData,
                        'ContentType' => $postData['contentType'],
                        'ContentDisposition' => 'inline; filename=' . $photoS3Key . $postData['fileExtension']
                    ]);

                    if (isset($candidate->photo_s3_key)) {
                        $s3->deleteObject([
                            'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                            'Key' => $candidate->photo_s3_key,
                        ]);
                    }

                    $candidate->photo_s3_key = $photoS3Key;
                    $candidate->save();

                    return [
                        'id' => $candidate->id,
                        'photoS3Key' => $candidate->photo_s3_key
                    ];
                }
            }

            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionUploadTrainingPhoto($trainingSessionId = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $postData = $request->post();

        if (!isset($postData['userId'])) {
            throw new \yii\web\BadRequestHttpException('No userId in POST request.');
        }

        $user = User::findOne($postData['userId']);

        if (!isset($user)) {
            throw new \yii\web\NotFoundHttpException('User not found.');
        }

        if (!isset($postData['photo'])) {
            throw new \yii\web\BadRequestHttpException('No photo in POST request.');
        }

        $trainingSession = null;

        if (!isset($trainingSessionId)) {
            $reqFields = ['candidateId', 'testSessionId'];
            foreach ($reqFields as $reqField) {
                if (!isset($postData[$reqField])) {
                    throw new \yii\web\BadRequestHttpException('No ' . $reqField . ' in POST request.');
                }
            }

            $trainingSession = new CandidateTrainingSession();
            $trainingSession->candidate_id = $postData['candidateId'];
            $trainingSession->test_session_id = $postData['testSessionId'];

            $tz = 'America/Los_Angeles';
            $ts = time();
            $dt = new \DateTime('now', new \DateTimeZone($tz));
            $dt->setTimestamp($ts);
            $dateTimeStr = $dt->format('Y-m-d H:i:s');

            $trainingSession->start_time = $dateTimeStr;
            $trainingSession->date_created = $dateTimeStr;
            if (!$trainingSession->save()) {
                throw new \yii\web\ServerErrorHttpException('Training Session could not be saved.');
            }
        } else {
            $trainingSession = CandidateTrainingSession::findOne($trainingSessionId);
        }

        if (!isset($trainingSession)) {
            throw new \yii\web\NotFoundHttpException('Training Session not found.');
        }

        $photoData = base64_decode(explode(',', $postData['photo'])[1]);

        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2'
        ]);

        $photoS3Key = 'training-' . $trainingSession->id . '-' . \Yii::$app->getSecurity()->generateRandomString();

        $s3->putObject([
            'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
            'Key' => $photoS3Key,
            'Body' => $photoData,
            'ContentType' => $postData['contentType'],
            'ContentDisposition' => 'inline; filename=' . $photoS3Key . $postData['fileExtension']
        ]);

        $trainingPhoto = new CandidateTrainingPhoto();
        $trainingPhoto->training_session_id = $trainingSession->id;
        $trainingPhoto->s3_key = $photoS3Key;
        $trainingPhoto->uploaded_by = $user->id;

        $tz = 'America/Los_Angeles';
        $ts = time();
        $dt = new \DateTime('now', new \DateTimeZone($tz));
        $dt->setTimestamp($ts);
        $dateTimeStr = $dt->format('Y-m-d H:i:s');

        $trainingPhoto->created_at = $dateTimeStr;

        $trainingPhoto->validate();

        if ($trainingPhoto->save()) {
            return $trainingSession->toArray();
        }

        throw new \yii\web\ServerErrorHttpException('Training Photo could not be saved.');
    }

    public function actionUploadScoreSheet($id, $testSessionId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();
            $candidate = Candidates::findOne($id);

            $session = CandidateSession::findOne([
                'candidate_id' => $candidate->id,
                'test_session_id' => $testSessionId
            ]);

            if (!isset($session)) {
                throw new \yii\web\NotFoundHttpException('Candidate not found in Test Session.');
            }

            if (isset($candidate)) {
                if (isset($postData['photo'])) {
                    $photoData = base64_decode(explode(',', $postData['photo'])[1]);

                    $s3 = new \Aws\S3\S3Client([
                        'version' => 'latest',
                        'region' => 'us-west-2'
                    ]);

                    $photoS3Key = 'score-sheet-' . $candidate->id . '-' . \Yii::$app->getSecurity()->generateRandomString();

                    $s3->putObject([
                        'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                        'Key' => $photoS3Key,
                        'Body' => $photoData,
                        'ContentType' => $postData['contentType'],
                        'ContentDisposition' => 'inline; filename=' . $photoS3Key . $postData['fileExtension']
                    ]);

                    $scoreSheetPhoto = new CandidateSessionExamPhoto();

                    $scoreSheetPhoto->s3_key = $photoS3Key;
                    $scoreSheetPhoto->candidateId = $candidate->id;
                    $scoreSheetPhoto->testSessionId = $testSessionId;

                    if (isset($postData['pageNum'])) {
                        $scoreSheetPhoto->page_num = $postData['pageNum'];
                    }

                    if (isset($postData['pageType'])) {
                        $scoreSheetPhoto->page_type = $postData['pageType'];
                    }

                    $scoreSheetPhoto->save();

                    return [
                        'candidateId' => $candidate->id,
                        'scoreSheetPhoto' => $scoreSheetPhoto
                    ];
                }
            }

            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionDeleteScoreSheet($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $scoreSheet = CandidateSessionExamPhoto::findOne($id);

        if (!isset($scoreSheet)) {
            throw new \yii\web\NotFoundHttpException('Score Sheet not found.');
        }

        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2'
        ]);

        $s3->deleteObject([
            'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
            'Key' => $scoreSheet->s3_key
        ]);

        $scoreSheet->delete();

        return [
            'status' => 'OK'
        ];
    }

    public function actionUpdateChecklist($id, $type)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();
            $candidate = Candidates::findOne($id);

            if (isset($candidate)) {

                $tz = 'America/Los_Angeles';
                $ts = time();
                $dt = new \DateTime('now', new \DateTimeZone($tz));
                $dt->setTimestamp($ts);
                $dateTimeStr = $dt->format('Y-m-d H:i:s');

                $isReset = isset($postData['isReset']) && $postData['isReset'];

                if ($type == 'signed_w_form_received') {
                    $candidate->signed_w_form_received = $isReset ? null : $dateTimeStr;
                }

                if ($type == 'signed_p_form_received') {
                    $candidate->signed_p_form_received = $isReset ? null : $dateTimeStr;
                }

                if ($type == 'confirmation_email_last_sent') {
                    $candidate->confirmation_email_last_sent = $isReset ? null : $dateTimeStr;
                }

                if ($type == 'app_form_sent_to_nccco') {
                    $candidate->app_form_sent_to_nccco = $isReset ? null : $dateTimeStr;
                }

                if ($candidate->save()) {
                    $details = $postData['details'] ?? false;
                    if ($type == 'confirmation_email_last_sent' && $details && $details['sendEmail']) {
                        NotificationHelper::notifyConfirmationEmail($candidate, $details);
                    }

                    return [
                        'id' => $candidate->id,
                        'signed_w_form_received' => $candidate->signed_w_form_received,
                        'signed_p_form_received' => $candidate->signed_p_form_received,
                        'confirmation_email_last_sent' => $candidate->confirmation_email_last_sent,
                        'app_form_sent_to_nccco' => $candidate->app_form_sent_to_nccco
                    ];
                }
                throw new \yii\web\ServerErrorHttpException('Candidate could not be saved.');
            }

            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionBulkUpdateChecklist($type)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();

            if (isset($postData['candidateIDs']) && is_array($postData['candidateIDs'])) {
                $tz = 'America/Los_Angeles';
                $ts = time();
                $dt = new \DateTime('now', new \DateTimeZone($tz));
                $dt->setTimestamp($ts);
                $dateTimeStr = $dt->format('Y-m-d H:i:s');

                $result = [];

                foreach ($postData['candidateIDs'] as $id) {
                    $candidate = Candidates::findOne($id);

                    if (isset($candidate)) {
                        if ($type == 'signed_w_form_received') {
                            $candidate->signed_w_form_received = $dateTimeStr;
                        }
        
                        if ($type == 'signed_p_form_received') {
                            $candidate->signed_p_form_received = $dateTimeStr;
                        }
        
                        if ($type == 'confirmation_email_last_sent') {
                            $candidate->confirmation_email_last_sent = $dateTimeStr;
                        }
        
                        if ($type == 'app_form_sent_to_nccco') {
                            $candidate->app_form_sent_to_nccco = $dateTimeStr;
                        }

                        if ($candidate->save()) {
                            $result[$candidate->id] = [
                                'id' => $candidate->id,
                                'signed_w_form_received' => $candidate->signed_w_form_received,
                                'signed_p_form_received' => $candidate->signed_p_form_received,
                                'confirmation_email_last_sent' => $candidate->confirmation_email_last_sent,
                                'app_form_sent_to_nccco' => $candidate->app_form_sent_to_nccco
                            ];
                        }
                    }
                }

                return $result;
            }
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionFindCompanies()
    {
        $ids = \Yii::$app->request->queryParams['ids'];
        $candidateSessions = CandidateSession::find()->select('candidate_id')->where(['test_session_id' => $ids])->asArray()->all();
        $candidateIds = array_map(function($candidateSession) {
            return $candidateSession['candidate_id'];
        }, $candidateSessions);

        $candidatesCompanies = Candidates::find()->select('company_name')->distinct()->where(['id' => $candidateIds])->asArray()->all();

        $companiesArr = array_reduce($candidatesCompanies, function($acc, $company) {
            $name = $company['company_name'];
            if (isset($name) && $name !== '') {
                $acc[] = $name;
                return $acc;
            }
            return $acc;
        }, []);

        return $companiesArr;
    }

    public function actionUpdateJson($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $candidate = Candidates::findOne($id);

        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $postData = $request->post();

        $candidate->attributes = $postData;

        if ($candidate->save()) {
            return $candidate;
        }

        throw new \yii\web\ServerErrorHttpException('Unable to save Candidate.');
    }

    public function actionSearch()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $postData = $request->post();

        $query = Candidates::find();

        if (isset($postData['lastName'])) {
            $query->andFilterWhere(['like', 'last_name', $postData['lastName']]);
        }

        if (isset($postData['firstName'])) {
            $query->andFilterWhere(['like', 'first_name', $postData['firstName']]);
        }

        if (isset($postData['company'])) {
            $query->andFilterWhere(['like', 'company_name', $postData['company']]);
        }

        if (isset($postData['startDate']) && isset($postData['endDate'])) {
            $query->andWhere(['>=', 'date_created', $postData['startDate']])->andWhere(['<=', 'date_created', $postData['endDate']])->orderBy(['last_name' => SORT_ASC]);
        } else {
            throw new \yii\web\BadRequestHttpException('No startDate and endDate in POST request.');
        }

        $candidates = $query->limit(1000)->all();

        $candidatesArr = ArrayHelper::toArray($candidates, [
            'app\models\Candidates' => [
                'id',
                'idHash' => function($candidate) {
                    return md5($candidate->id);
                },
                'name' => function($candidate) {
                    return $candidate->last_name . ', ' . $candidate->first_name;
                },
                'company' => 'company_name',
                'email',
                'mergedFormSetup',
                'grades'
            ]
        ]);

        if (isset($postData['certifyTwelveMonthWindow']) && $postData['certifyTwelveMonthWindow']) {
            $candidatesArr = array_reduce($candidatesArr, function($cAcc, $candidate) {
                $result = false;

                if (isset($candidate['grades'])) {
                    $passedCoreExam = false;
                    $hasFailedOneOtherExam = false;

                    if (isset($candidate['grades']['W_EXAM_CORE'])) {
                        $passedCoreExam = $candidate['grades']['W_EXAM_CORE'] === '1';
                    }

                    foreach ($candidate['grades'] as $test => $grade) {
                        if ($test !== 'W_EXAM_CORE') {
                            $hasFailedOneOtherExam = $hasFailedOneOtherExam || $grade !== '1';
                        }
                    }

                    $result = $passedCoreExam && $hasFailedOneOtherExam;
                }

                if ($result) {
                    $newAcc = $cAcc;
                    $newAcc[] = $candidate;
                    return $newAcc;
                }

                return $cAcc;
            }, []);

            return $candidatesArr;
        }

        if (isset($postData['recertifyTwelveMonthWindow']) && $postData['recertifyTwelveMonthWindow']) {
            $candidatesArr = array_reduce($candidatesArr, function($cAcc, $candidate) {
                $result = false;

                if (isset($candidate['grades'])) {
                    $passedCoreExam = false;
                    $hasPassedOnePracticalExam = false;

                    if (isset($candidate['grades']['W_EXAM_CORE'])) {
                        $passedCoreExam = $candidate['grades']['W_EXAM_CORE'] === '1';
                    }

                    foreach ($candidate['grades'] as $test => $grade) {
                        if (substr($test, 0, 2) === 'P_') {
                            $hasPassedOnePracticalExam = $hasPassedOnePracticalExam || $grade === '1';
                        }
                    }

                    $result = $passedCoreExam && $hasPassedOnePracticalExam;
                }

                if ($result) {
                    $newAcc = $cAcc;
                    $newAcc[] = $candidate;
                    return $newAcc;
                }

                return $cAcc;
            }, []);

            return $candidatesArr;
        }

        if (isset($postData['signedUp'])) {
            $filters = [];

            if (in_array('signedUpWrittenCore', $postData['signedUp'])) {
                $filters[] = 'W_EXAM_CORE';
            }

            if (in_array('signedUpWrittenSw', $postData['signedUp'])) {
                $filters[] = 'W_EXAM_TLL';
            }

            if (in_array('signedUpWrittenFx', $postData['signedUp'])) {
                $filters[] = 'W_EXAM_TSS';
            }

            if (in_array('signedUpPracticalSw', $postData['signedUp'])) {
                $filters[] = 'P_TELESCOPIC_TLL';
            }

            if (in_array('signedUpPracticalFx', $postData['signedUp'])) {
                $filters[] = 'P_TELESCOPIC_TSS';
            }

            $candidatesArr = array_values(array_filter($candidatesArr, function($candidate) use ($filters) {
                $result = array_reduce($filters, function($acc, $test) use ($candidate) {
                    if (isset($candidate['mergedFormSetup'])) {
                        if (isset($candidate['mergedFormSetup'][$test])) {
                            return $acc && $candidate['mergedFormSetup'][$test] === 'on';
                        }
                    }
    
                    return false;
                }, true);

                return $result;
            }));
        }

        if (isset($postData['notSignedUp'])) {
            $filters = [];

            if (in_array('notSignedUpWrittenCore', $postData['notSignedUp'])) {
                $filters[] = 'W_EXAM_CORE';
            }

            if (in_array('notSignedUpWrittenSw', $postData['notSignedUp'])) {
                $filters[] = 'W_EXAM_TLL';
            }

            if (in_array('notSignedUpWrittenFx', $postData['notSignedUp'])) {
                $filters[] = 'W_EXAM_TSS';
            }

            if (in_array('notSignedUpPracticalSw', $postData['notSignedUp'])) {
                $filters[] = 'P_TELESCOPIC_TLL';
            }

            if (in_array('notSignedUpPracticalFx', $postData['notSignedUp'])) {
                $filters[] = 'P_TELESCOPIC_TSS';
            }

            $candidatesArr = array_values(array_filter($candidatesArr, function($candidate) use ($filters) {
                $result = array_reduce($filters, function($acc, $test) use ($candidate) {
                    if ($acc) {
                        return $acc;
                    }

                    if (isset($candidate['mergedFormSetup'])) {
                        if (isset($candidate['mergedFormSetup'][$test])) {
                            return $candidate['mergedFormSetup'][$test] !== 'on';
                        }
                    }

                    return $acc;
                }, false);

                return $result;
            }));
        }

        if (isset($postData['passed'])) {
            $filters = [];

            if (in_array('passedWrittenCore', $postData['passed'])) {
                $filters[] = 'W_EXAM_CORE';
            }

            if (in_array('passedWrittenSw', $postData['passed'])) {
                $filters[] = 'W_EXAM_TLL';
            }

            if (in_array('passedWrittenFx', $postData['passed'])) {
                $filters[] = 'W_EXAM_TSS';
            }

            if (in_array('passedPracticalSw', $postData['passed'])) {
                $filters[] = 'P_TELESCOPIC_TLL';
            }

            if (in_array('passedPracticalFx', $postData['passed'])) {
                $filters[] = 'P_TELESCOPIC_TSS';
            }

            $candidatesArr = array_reduce($candidatesArr, function($cAcc, $candidate) use ($filters) {
                $result = array_reduce($filters, function($acc, $test) use ($candidate) {
                    if (isset($candidate['grades'])) {
                        if (isset($candidate['grades'][$test])) {
                            return $acc && $candidate['grades'][$test] == 1;
                        }
                    }

                    return false;
                }, true);

                if ($result) {
                    $newAcc = $cAcc;
                    $newAcc[] = $candidate;
                    return $newAcc;
                }

                return $cAcc;
            }, []);
        }

        if (isset($postData['failed'])) {
            $filters = [];

            if (in_array('failedWrittenCore', $postData['failed'])) {
                $filters[] = 'W_EXAM_CORE';
            }

            if (in_array('failedWrittenSw', $postData['failed'])) {
                $filters[] = 'W_EXAM_TLL';
            }

            if (in_array('failedWrittenFx', $postData['failed'])) {
                $filters[] = 'W_EXAM_TSS';
            }

            if (in_array('failedPracticalSw', $postData['failed'])) {
                $filters[] = 'P_TELESCOPIC_TLL';
            }

            if (in_array('failedPracticalFx', $postData['failed'])) {
                $filters[] = 'P_TELESCOPIC_TSS';
            }

            $candidatesArr = array_reduce($candidatesArr, function($cAcc, $candidate) use ($filters) {
                $result = array_reduce($filters, function($acc, $test) use ($candidate) {
                    if (isset($candidate['grades'])) {
                        if (isset($candidate['grades'][$test])) {
                            return $acc && $candidate['grades'][$test] == 0;
                        }
                    }

                    return false;
                }, true);

                if ($result) {
                    $newAcc = $cAcc;
                    $newAcc[] = $candidate;
                    return $newAcc;
                }

                return $cAcc;
            });
        }

        return $candidatesArr;
    }

    public function actionResetGrades($candidateId, $testSessionId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $candidate = Candidates::findOne($candidateId);

        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $testSession = TestSession::findOne($testSessionId);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $testSessionCounterpart = null;

        if (isset($testSession->practical_test_session_id)) {
            $testSessionCounterpart = TestSession::findOne($testSession->practical_test_session_id);
        } else {
            $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
        }

        $testSessionIds = [$testSession->id];

        if (isset($testSessionCounterpart)) {
            $testSessionIds[] = $testSessionCounterpart->id;
        }

        $candidateGradedSessions = CandidatePreviousSession::findAll([
            'candidate_id' => $candidate->id,
            'test_session_id' => $testSessionIds
        ]);

        foreach ($candidateGradedSessions as $session) {
            $session->craneStatus = json_encode([]);
            $session->save();
        }

        return [
            'status' => 'OK'
        ];
    }

    public function actionConfirmationEmailDetails($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request;

        if (!$request->isGet) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $candidate = Candidates::findOne($id);

        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $user = \Yii::$app->user->getIdentity();

        $isPracticalOnly = $candidate->applicationType->isPracticalOnly;
        $payload = [];
        $basePayload = [
            'isPracticalOnly' => $isPracticalOnly,
            'name' => $candidate->first_name . ' ' . $candidate->last_name,
            'email' => $candidate->email,
            'sender' => [
                'name' => $user->first_name . ' ' . $user->last_name
            ]
        ];

        if ($isPracticalOnly) {
            $testSession = $candidate->practicalSession->testSession;
            $testDays = $testSession->getDateRange('l, F j', true);
            $testSite = $testSession->testSite;
            $practicalTestSchedule = $candidate->practicalTestSchedule;

            $subPayload = [
                'branding' => $testSession->school,
                'hasPracticalTestSchedule' => count($practicalTestSchedule) > 0,
                'mergedFormSetup' => $candidate->mergedFormSetup
            ];

            if (isset($practicalTestSchedule[0])) {
                $subPayload['practicalTestSchedule'] = $practicalTestSchedule[0];
                $subPayload['testSchedule']['day'] = $testDays[$practicalTestSchedule[0]['day'] - 1];
                $subPayload['testSchedule']['time'] = $practicalTestSchedule[0]['time'];
            }

            $payload = array_merge($basePayload, $subPayload);
        } else {
            $writtenTestSession = $candidate->writtenTestSession->testSession;
            $testSite = $writtenTestSession->testSite;
            $classDatesStr = $writtenTestSession->getDateRange('F j - F j');
            $classDatesArr = $writtenTestSession->getDateRange('l, F j, Y', true);

            $subPayload = [
                'branding' => $writtenTestSession->school,
                'classDatesStr' => $classDatesStr,
                'classDatesArr' => $classDatesArr
            ];

            $practicalTestSession = $writtenTestSession->counterpart;
            $practicalTestSite = $practicalTestSession->testSite;

            if (isset($practicalTestSession)) {
                $subPayload['practicalTestSite'] = [
                    'name' => $practicalTestSite->name,
                    'address' => $practicalTestSite->address,
                    'city' => $practicalTestSite->city,
                    'state' => $practicalTestSite->state,
                    'zip' => $practicalTestSite->zip
                ];
            }

            $payload = array_merge($basePayload, $subPayload);
        }

        $payload['testSite'] = [
            'name' => $testSite->name,
            'address' => $testSite->address,
            'city' => $testSite->city,
            'state' => $testSite->state,
            'zip' => $testSite->zip
        ];

        return $payload;
    }
}
