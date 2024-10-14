<?php

namespace app\modules\api\controllers;

use app\models\User;
use app\models\Candidates;
use app\models\TestSite;
use app\models\TestSession;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

class LegacyController extends ActiveController
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

    public function actionUpload()
    {
        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $postData = $request->post();
        $testSessionInfo = $postData['testSessionInfo'];
        $regularCandidateRoster = $postData['regularCandidateRoster'];

        $result = [
            'postData' => $postData
        ];

        if (isset($testSessionInfo)) {
            $result['testSessionInfo'] = $this->processTestSessionInfo($testSessionInfo);

            if (isset($regularCandidateRoster) && isset($testSessionInfo['testDate'])) {
                $result['regularCandidateRoster'] = $this->processRegularCandidateRoster($regularCandidateRoster, $testSessionInfo['testDate']);
            }
        }

        return $result;
    }

    private function processRegularCandidateRoster($regularCandidateRoster, $testDate)
    {
        $regularCandidates = [];

        foreach ($regularCandidateRoster as $cInfo) {
            $candidate = new Candidates();
            $candidate->date_created = $testDate;
            $candidate->last_name = $cInfo['lastName'];
            $candidate->first_name = $cInfo['firstName'];
            $candidate->company_name = $cInfo['company'];

            if ($cInfo['type'] === 'NEW') {
                $candidate->application_type_id = 1;
            } elseif ($cInfo['type'] === 'RECERT') {
                $candidate->application_type_id = 2;
            } elseif ($cInfo['type'] === 'RETEST') {
                $candidate->application_type_id = 5;
            } else {
                $candidate->application_type_id = 1;
            }

            $candidate->phone = $cInfo['phone'] ?? '(000)000-0000';
            $candidate->cellNumber = $cInfo['cell'];
            $candidate->faxNumber = $cInfo['fax'];
            $candidate->company_phone = $cInfo['companyPhone'];
            $candidate->email = $cInfo['email'] ?? 'admin@tabletbasedtesting.com';
            $candidate->contact_person = $cInfo['contactName'];
            $candidate->instructor_notes = $cInfo['notes'];
            $candidate->isPurchaseOrder = 0;
            $candidate->save(false);

            $regularCandidates[] = $candidate;
        }

        return $regularCandidates;
    }

    private function processTestSessionInfo($testSessionInfo)
    {
        $testSiteCoordinator = null;
        $practicalExaminer = null;
        $proctor = null;
        $instructor = null;

        if (isset($testSessionInfo['testSiteCoordinator'])) {
            $testSiteCoordinator = User::findOrCreateUserFromNameAndRole($testSessionInfo['testSiteCoordinator'], 'TEST_SITE_COORDINATOR');
        }

        if (isset($testSessionInfo['practicalExaminer'])) {
            $practicalExaminer = User::findOrCreateUserFromNameAndRole($testSessionInfo['practicalExaminer'], 'PRACTICAL_EXAMINER');
        }

        if (isset($testSessionInfo['proctor'])) {
            $proctor = User::findOrCreateUserFromNameAndRole($testSessionInfo['proctor'], 'PROCTOR');
        }

        if (isset($testSessionInfo['instructor'])) {
            $instructor = User::findOrCreateUserFromNameAndRole($testSessionInfo['instructor'], 'INSTRUCTOR');
        }

        $writtenTestSite = null;
        $practicalTestSite = null;

        if (isset($testSessionInfo['testSiteName']) && isset($testSessionInfo['testSiteAddress'])) {
            $writtenTestSite = TestSite::findOrCreateFromNameAndAddress(TestSite::TYPE_WRITTEN, $testSessionInfo['testSiteName'], $testSessionInfo['testSiteAddress']);
        }

        if (isset($testSessionInfo['testSiteNumber']) && isset($testSessionInfo['testSiteName']) && isset($testSessionInfo['testSiteAddress']) && isset($testSessionInfo['practicalTestSiteCode'])) {
            $practicalTestSite = TestSite::findOrCreateFromNameAndAddress(TestSite::TYPE_PRACTICAL, $testSessionInfo['testSiteName'], $testSessionInfo['testSiteAddress'], $testSessionInfo['practicalTestSiteCode']);
        }

        try {
            $practicalTestSession = new TestSession();
            $practicalTestSession->test_site_id = $practicalTestSite->id;
            $practicalTestSession->enrollmentType = 2;
            $practicalTestSession->session_number = $practicalTestSite->siteNumber;
            $practicalTestSession->start_date = $testSessionInfo['testDate'];
            $practicalTestSession->end_date = $testSessionInfo['testDate'];
            $practicalTestSession->school = TestSession::SCHOOL_CCS;
            $practicalTestSession->test_coordinator_id = $testSiteCoordinator->id;
            $practicalTestSession->proctor_id = isset($proctor) ? $proctor->id : null;
            $practicalTestSession->staff_id = isset($practicalExaminer) ? $practicalExaminer->id : null;
            $practicalTestSession->save(false);

            $writtenTestSession = new TestSession();
            $writtenTestSession->test_site_id = $writtenTestSite->id;
            $writtenTestSession->enrollmentType = 2;
            $writtenTestSession->session_number = $testSessionInfo['testSiteNumber'];
            $writtenTestSession->practical_test_session_id = $practicalTestSession->id;
            $writtenTestSession->start_date = $testSessionInfo['testDate'];
            $writtenTestSession->end_date = $testSessionInfo['testDate'];
            $writtenTestSession->testing_date = $testSessionInfo['testDate'];
            $writtenTestSession->registration_close_date = $testSessionInfo['testDate'];
            $writtenTestSession->school = TestSession::SCHOOL_CCS;
            $writtenTestSession->test_coordinator_id = $testSiteCoordinator->id;
            $writtenTestSession->instructor_id = isset($instructor) ? $instructor->id : null;
            $writtenTestSession->save(false);
        } catch (Exception $e) {
        }

        return [
            'testSiteCoordinator' => $testSiteCoordinator,
            'practicalExaminer' => $practicalExaminer,
            'proctor' => $proctor,
            'instructor' => $instructor,
            'writtenTestSite' => $writtenTestSite,
            'practicalTestSite' => $practicalTestSite,
            'writtenTestSession' => $writtenTestSession,
            'practicalTestSession' => $practicalTestSession
        ];
    }
}
