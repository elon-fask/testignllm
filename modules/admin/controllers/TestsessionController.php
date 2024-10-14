<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use PhpOffice\PhpSpreadsheet;

use app\helpers\UtilityHelper;
use app\models\ApplicationType;
use app\models\Checklist;
use app\models\ChecklistItem;
use app\models\ChecklistItemTemplate;
use app\models\PracticalTestSchedule;
use app\models\PracticalTrainingSession;
use app\models\TestSession;
use app\models\TestSessionSearch;
use app\models\CandidateSession;
use app\models\Candidates;
use app\models\CandidatePreviousSession;
use app\models\CandidateDeclineTestAttestation;
use app\models\Company;
use app\models\ChecklistTemplate;
use app\models\TestSessionPhoto;
use app\models\TestSessionReceipts;
use app\models\User;
use app\models\UserRole;
use app\models\TestSite;

/**
 * TestsessionController implements the CRUD actions for TestSession model.
 */
class TestsessionController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['zip', 'view-receipt', 'all-receipts', 'receipts','receipt-filter', 'photos',
                            'view-page-photo', 'associatedwritten', 'viewpage','index','view','create','update',
                            'delete','deleteasync', 'attachments', 'viewattachment', 'deleteattachment', 'checklists',
                            'assign-checklists', 'fulfill-checklists', 'save-checklists', 'add-practical-training-session',
                            'delete-practical-training-session', 'update-practical-training-session', 'spreadsheet', 'render-spreadsheet', 'generate-certificates', 'download-application-forms-zip', 'download-candidate-photos-zip', 'download-declined-test-attestations', 'download-candidate-decline-attestations-zip', 'update-materials-status'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'add-practical-training-session' => ['post'],
                    'update-practical-training-session' => ['post'],
                    'delete-practical-training-session' => ['post'],
                    'generate-certificates' => ['post'],
                    'render-spreadsheet' => ['get', 'post']
                ],
            ],
        ];
    }

    public function actionAssociatedwritten(){
        $resp = [];

        $resp['records'] = [];
        $testSession = TestSession::findOne($_REQUEST['id']);
        $resp['sessionNumber'] = $testSession->session_number;
        $sessions = TestSession::find()->where('practical_test_session_id = ' .$_REQUEST['id'])->all();
        foreach($sessions as $ses){
            $resp['records'][] = $ses->id;
        }
        echo json_encode($resp);
        die;
    }

    public function actionDeleteattachment(){
        $sesId = $_REQUEST['id'];
        $testSession = $this->findModelMd5($sesId);

        $i = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';
     //   var_dump($testSession);
        if($testSession != null){
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/session/'.md5($testSession->id).'/attachments/';
            $filePath = $uploadDir.base64_decode($i);


            if(is_file($filePath)){
                unlink($filePath);
            }
        }
    }

    public function actionAttachments(){
        $resp = array();
        if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

            $testSession = $this->findModel($_POST['id']);
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/session/'.md5($testSession->id).'/attachments/';

            UtilityHelper::createPath($uploadDir);

            if(move_uploaded_file($_FILES['upl']['tmp_name'], $uploadDir.$_FILES['upl']['name'])){
                $resp['file'] = $_FILES['upl']['name'];
                $resp['status'] = 1;
                $resp['html'] = $this->renderPartial('file-attachments', ['testSession'=>$testSession]);
                echo json_encode($resp);
                die;
            }

        }

        $resp['status'] = 0;
        echo json_encode($resp);
        die;
    }

    public function actionViewattachment()
    {
        $sesId = $_REQUEST['id'];
        $testSession = $this->findModelMd5($sesId);

        $i = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';

        if ($testSession != null) {
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/session/'.md5($testSession->id).'/attachments/';
            $filePath = $uploadDir.base64_decode($i);
            if (is_file($filePath)) {
                return \Yii::$app->getResponse()->sendFile($filePath);
            }
        }
    }

    /**
     * Lists all TestSession models.
     * @return mixed
     */
    public function actionIndex()
    {
        //var_dump(TestSite::find()->select(['user_id'])->where(['id' => 5])->asArray()->all());
     // var_dump(TestSession::find()->where('')->all());
      //  $tests = TestSite::find()->select(['nickname', 'id'])->all();
//        foreach ($tests as $test){
//            var_dump($test->nickname);
//        }
//      $arr = TestSession::find()->where('')->all();
//        foreach ($arr as $it){
//               $ss = TestSession::findOne( $it->id);
//                $ss->nick_id = $ss->test_site_id;
//                $ss->save();
//
//        }
        $searchModel = new TestSessionSearch();
        $md5CandidateId = isset($_REQUEST['candidateId']) ? $_REQUEST['candidateId'] : '';
        $params = Yii::$app->request->queryParams;
        if($md5CandidateId != ''){
            $candidate = $this->findCandidateModel($md5CandidateId);
            if($candidate){
                $params['exclude'] = [];
                $sessions = $candidate->getAllTestSession();
                foreach($sessions as $ses){
                    $params['exclude'][] = ($ses->test_session_id);
                }
            }
        }
        $dataProvider = $searchModel->search($params);
        $s = isset($_REQUEST['s']) ? $_REQUEST['s'] : false;

        $sesDefType = isset($_REQUEST['session_type']) ? $_REQUEST['session_type'] : '';

        $transferType = $_REQUEST['transfer_type'] ?? '';
        $singleTestSessionOnly = isset($_REQUEST['singleTestSession']) ? !!$_REQUEST['singleTestSession'] : false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            's' => $s,
            'md5CandidateId' => $md5CandidateId,
            'sesDefType' => $sesDefType,
            'transferType' => $transferType,
            'bothTestSessions' => $singleTestSessionOnly ? 0 : 1
        ]);
    }

    /**
     * Displays a single TestSession model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /*wroten from me*/
    public function actionUpdateloc(){
       if(isset($_POST['newval'])){
            $id = base64_decode($_POST['el']);
          // $row = TestSession::findOne( (int)$id)->one();
           $row = TestSession::find()->where(['id' => (int)$id])->all();
           $loc =$_POST['newval'];
           foreach ($row as $r){
               $nick_id =  $r->nick_id;
               $ss = TestSite::findOne($nick_id);
               $ss->nickname = $loc;
               $ss->save();
           }
           echo 'ok';
       }

    }
    /**
     * Creates a new TestSession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TestSession();

        if(count($_POST) > 0){
            $postData = Yii::$app->request->post();

            $tz = new \DateTimeZone('America/New_York');

            if (isset($postData['TestSession']) && isset($postData['TestSession']['testing_date'])) {
                $testingDate = \DateTime::createFromFormat('m/d/Y h:i A', $postData['TestSession']['testing_date'], $tz);
                if ($testingDate) {
                    $postData['TestSession']['testing_date'] = $testingDate->format('Y-m-d H:i:s');
                } else {
                    unset($postData['TestSession']['testing_date']);
                }
            }

            if (isset($postData['TestSession']) && isset($postData['TestSession']['registration_close_date'])) {
                $regCloseDate = \DateTime::createFromFormat('m/d/Y h:i A', $postData['TestSession']['registration_close_date'], $tz);
                if ($regCloseDate) {
                    $postData['TestSession']['registration_close_date'] = $regCloseDate->format('Y-m-d H:i:s');
                } else {
                    unset($postData['TestSession']['registration_close_date']);
                }
            }

            $model->load($postData);

            if ($model->start_date !== '') {
                $model->start_date = (UtilityHelper::dateconvert($model->start_date, 1));
            }

            if ($model->end_date !== '') {
                $model->end_date = (UtilityHelper::dateconvert($model->end_date, 1));
            }
        }

        if (count($_POST) > 0 && $model->save()) {

            //if($model->save()){
            $model->updateAssociatdSessionSchool();
            $this->doChecklistPropagate($model);
            return $this->redirect(['view', 'id' => $model->id]);
            //}
        } else {
            $users = User::findAll(['active' => 1]);

            $testCoordinatorIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::TEST_SITE_COORDINATOR])->asArray()->all()
            );

            $instructorIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::INSTRUCTOR])->asArray()->all()
            );

            $proctorIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::PROCTOR])->asArray()->all()
            );

            $writtenAdminIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::WRITTEN_ADMIN])->asArray()->all()
            );

            $practicalExaminerIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::PRACTICAL_EXAMINER])->asArray()->all()
            );

            $testCoordinators = array_reduce($users, function($acc, $user) use ($testCoordinatorIds) {
                if (in_array($user->id, $testCoordinatorIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $instructors = array_reduce($users, function($acc, $user) use ($instructorIds) {
                if (in_array($user->id, $instructorIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $proctors = array_reduce($users, function($acc, $user) use ($proctorIds) {
                if (in_array($user->id, $proctorIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $writtenAdmins = array_reduce($users, function($acc, $user) use ($writtenAdminIds) {
                if (in_array($user->id, $writtenAdminIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $practicalExaminers = array_reduce($users, function($acc, $user) use ($practicalExaminerIds) {
                if (in_array($user->id, $practicalExaminerIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $this->layout = 'main-new';

            return $this->render('create', [
                'model' => $model,
                'type' => base64_decode($_GET['type']),
                'testSiteCoordinators' => $testCoordinators,
                'instructors' => $instructors,
                'proctors' => $proctors,
                'writtenAdmins' => $writtenAdmins,
                'practicalExaminers' => $practicalExaminers
            ]);
        }
    }
    private function doChecklistPropagate($model){
        /*
        if($model->preChecklistId > 0)
            $model->doPropagateCheckList(ChecklistTemplate::TYPE_PRE);
        if($model->postChecklistId > 0)
            $model->doPropagateCheckList(ChecklistTemplate::TYPE_POST);
        */
        if($model->writtenChecklistId > 0)
            $model->doPropagateCheckList(ChecklistTemplate::TYPE_WRITTEN);
        if($model->writtenPostChecklistId > 0)
            $model->doPropagateCheckList(ChecklistTemplate::TYPE_WRITTEN_POST);
    }
    /**
     * Updates an existing TestSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (count($_POST) > 0) {
            $postData = Yii::$app->request->post();

            $tz = new \DateTimeZone($model->testSite->timeZone);

            if (isset($postData['TestSession']) && isset($postData['TestSession']['testing_date'])) {
                $testingDate = \DateTime::createFromFormat('m/d/Y h:i A', $postData['TestSession']['testing_date'], $tz);
                if ($testingDate) {
                    $postData['TestSession']['testing_date'] = $testingDate->format('Y-m-d H:i:s');
                } else {
                    unset($postData['TestSession']['testing_date']);
                }
            }

            if (isset($postData['TestSession']) && isset($postData['TestSession']['registration_close_date'])) {
                $regCloseDate = \DateTime::createFromFormat('m/d/Y h:i A', $postData['TestSession']['registration_close_date'], $tz);
                if ($regCloseDate) {
                    $postData['TestSession']['registration_close_date'] = $regCloseDate->format('Y-m-d H:i:s');
                } else {
                    unset($postData['TestSession']['registration_close_date']);
                }
            }

            $model->load($postData);

            if ($model->start_date !== '') {
                $model->start_date = (UtilityHelper::dateconvert($model->start_date, 1));
            }

            if ($model->end_date !== '') {
                $model->end_date = (UtilityHelper::dateconvert($model->end_date, 1));
            }
        }

        if (count($_POST) > 0 && $model->validate() && $model->save()) {
            $model->updateAssociatdSessionSchool();
            $this->doChecklistPropagate($model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $users = User::findAll(['active' => 1]);
            $errors = $model->errors;
            $testCoordinatorIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::TEST_SITE_COORDINATOR])->asArray()->all()
            );

            $instructorIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::INSTRUCTOR])->asArray()->all()
            );

            $proctorIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::PROCTOR])->asArray()->all()
            );

            $writtenAdminIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::WRITTEN_ADMIN])->asArray()->all()
            );

            $practicalExaminerIds = array_map(
                function ($userArr) {
                    return $userArr['user_id'];
                },
                UserRole::find()->select(['user_id'])->where(['role' => UserRole::PRACTICAL_EXAMINER])->asArray()->all()
            );

            $testCoordinators = array_reduce($users, function($acc, $user) use ($testCoordinatorIds) {
                if (in_array($user->id, $testCoordinatorIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $instructors = array_reduce($users, function($acc, $user) use ($instructorIds) {
                if (in_array($user->id, $instructorIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $proctors = array_reduce($users, function($acc, $user) use ($proctorIds) {
                if (in_array($user->id, $proctorIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $writtenAdmins = array_reduce($users, function($acc, $user) use ($writtenAdminIds) {
                if (in_array($user->id, $writtenAdminIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $practicalExaminers = array_reduce($users, function($acc, $user) use ($practicalExaminerIds) {
                if (in_array($user->id, $practicalExaminerIds)) {
                    $acc[$user->id] = $user->first_name . ' ' . $user->last_name;
                }
                return $acc;
            }, []);

            $this->layout = 'main-new';

            return $this->render('update', [
                'model' => $model,
                'type' => $model->getTestSite()->one()->type,
                'testSiteCoordinators' => $testCoordinators,
                'instructors' => $instructors,
                'errors' => $errors,
                'proctors' => $proctors,
                'writtenAdmins' => $writtenAdmins,
                'practicalExaminers' => $practicalExaminers
            ]);
        }
    }

    /**
     * Deletes an existing TestSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $candidateSessions = CandidateSession::find()->where('test_session_id = '.$id)->all();
        $params = '';
        if(count($candidateSessions) == 0){
            //ApplicationTypeFormSetup::deleteAll('application_type_id = '.$id);
            $this->findModel($id)->delete();
            $params = '?s=1';
        }else{
            $params = '?s=0';
        }

        $params1 = [];
        if(isset($_GET['TestSessionSearch'])){
            foreach($_GET['TestSessionSearch'] as $key => $val){
                $params1[] = 'TestSessionSearch['.$key.']='.$val;
            }
        }
        $searchParams = implode('&', $params1);
        if($searchParams !== false && $searchParams !== ''){
            $searchParams = '&'.$searchParams;
        }

        return $this->redirect('/admin/testsession/index'.$params.$searchParams);
    }



    /**
     * Deletes an existing TestSession model from a async call.
     * @param integer $id
     * @return int
     */
    public function actionDeleteasync()
    {
        $testSessionId = $_POST['id'];

        $candidateSessions = CandidateSession::find()->where('test_session_id = '.$testSessionId)->all();
        $candidatePrevSessions = CandidatePreviousSession::find()->where('test_session_id = '.$testSessionId)->all();

        if(count($candidateSessions) == 0 && count($candidatePrevSessions) == 0){
            $this->findModel($testSessionId)->delete();
            echo 1;
        }else if(count($candidatePrevSessions) != 0){
            echo 2;
        }else{
            echo 0;
        }
        die;
    }

    public function actionAllReceipts(){

        return $this->render('all-receipts', []);
    }

    public function actionViewReceipt(){
        $page = $_REQUEST['page'];
        $filter = [];
        if(isset($_REQUEST['filter'])){
            $filter = $_REQUEST['filter'];
        }
        $items = TestSessionReceipts::getAllReceipts($filter, 20, $page);

        return $this->renderPartial('_all_receipts', ['items' => $items, 'currentPage' => $page, 'fromDate' => isset($filter['fromDate']) ? urldecode($filter['fromDate']) : ''
            , 'toDate' => isset($filter['toDate']) ? urldecode($filter['toDate']) : '',
        ]);
    }

    public function actionReceipts(){
        $id = $_REQUEST['id'];
        $testSession = $this->findModelMd5($id);

        $allReceipts = TestSessionReceipts::find()->where('testSessionId = '.$testSession->id.' order by id desc')->all();

        return $this->render('receipts', ['allReceipts' => $allReceipts, 'testSessionId' => $testSession->id]);
    }
    public function actionReceiptFilter(){
        $id = $_REQUEST['id'];
        $sql = '';
        if(isset($_REQUEST['date']) && $_REQUEST['date'] != ''){
            $sql = "and date(date_created) = '".$_REQUEST['date']."'";
        }
        $allReceipts = TestSessionReceipts::find()->where('testSessionId = '.$id.' '.$sql.' order by id desc')->all();

        return $this->renderPartial('_receipts_archive', ['allReceipts' => $allReceipts]);
    }

    /**
     * Finds the TestSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestSession::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelMd5($id)
    {
        $session = TestSession::find()->where("md5(id) ='".$id."'")->all();
        $model = null;
        if(count($session) != 0){
            $model = $session[0];
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }

    public function actionPhotos(){
        return $this->render('session-photos', []);
    }
    public function actionViewPagePhoto(){
        $page = $_REQUEST['page'];
        $filter = [];
//         $filter['fromDate'] = date('Y-m-d', strtotime('now'));
//         $filter['toDate'] = date('Y-m-d', strtotime('-7 days'));
        if(isset($_REQUEST['filter'])){
            $filter = $_REQUEST['filter'];
        }
        $items = TestSessionPhoto::getSessionsPhotos($filter, 20, $page);

        return $this->renderPartial('photo-list', ['items' => $items, 'currentPage' => $page, 'fromDate' => isset($filter['fromDate']) ? urldecode($filter['fromDate']) : ''
            , 'toDate' => isset($filter['toDate']) ? urldecode($filter['toDate']) : '',
            'testSessionId' => isset($filter['testSessionId']) ? $filter['testSessionId'] : ''
        ]);
    }
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $testSiteId = $_REQUEST['testSiteId'];
        $items = TestSession::getSessions($testSiteId, 10, $page);

        return $this->renderPartial('../testsite/session-list', ['items' => $items, 'testSiteId' => $testSiteId, 'currentPage' => $page]);
    }

    protected function findCandidateModel($id)
    {
        $candidates = Candidates::find()->where("md5(id) ='".$id."'")->all();
        $model = null;
        if(count($candidates) != 0){
            $model = $candidates[0];
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionChecklists($id, $type)
    {
        $testSession = $this->findModel($id);

        $checklists = $testSession->getChecklists()->where(['type' => $type])->all();

        return $this->render('saved-checklists', [
            'checklists' => $checklists
        ]);
    }

    public function actionAssignChecklists()
    {
        $request = Yii::$app->request;
        $resp = [];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$request->isPost) {
            $resp = ['code' => 400, 'message' => 'Request method must be POST.'];
            Yii::$app->response->statusCode = 400;
            return $resp;
        }

        $postData = $request->post();
        $testSessionId = $postData['test-session-id'];
        $checklistIds = $postData['checklist-ids'];
        $testSession = TestSession::findOne($testSessionId);
        $checklists = ChecklistTemplate::find()
            ->where([
                'isArchived' => 0,
                'id' => $checklistIds
            ])
            ->all();

        if (!$testSession) {
            $resp = ['code' => 404, 'message' => 'Test Session not found.'];
            Yii::$app->response->statusCode = 404;
            return $resp;
        }

        if (count($checklists) !== count($checklistIds)) {
            $resp = ['code' => 404, 'message' => 'ChecklistTemplate not found.'];
            Yii::$app->response->statusCode = 404;
            return $resp;
        }

        $currentChecklists = $testSession->getChecklistTemplates()
            ->where([ 'type' => $postData['checklist-type'] ])
            ->all();

        foreach ($currentChecklists as $checklist) {
            $testSession->unlink('checklistTemplates', $checklist, true);
        }

        foreach ($checklists as $checklist) {
            $testSession->link('checklistTemplates', $checklist);
        }

        return $checklists;
    }

    public function actionFulfillChecklists()
    {
        $testSessionId = $_REQUEST['id'];
        $testSession = TestSession::findOne($testSessionId);

        if (!$testSession) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $checklists = $testSession->getChecklistTemplates()
            ->where([ 'type' => $_REQUEST['type'] ])
            ->all();
        $checklistItemsCombined = [];

        foreach ($checklists as $checklist) {
            $checklistItems = ChecklistItemTemplate::findAll([ 'checklistId' => $checklist->id ]);
            array_push($checklistItemsCombined, [
                'id' => $checklist->id,
                'name' => $checklist->name,
                'type' => $checklist->type,
                'checklistItems' => $checklistItems
            ]);
        }

        return $this->render('fulfill-checklists', [
            'testSession' => $testSession,
            'checklists' => $checklistItemsCombined
        ]);
    }

    public function actionSaveChecklists()
    {
        $request = Yii::$app->request;
        $resp = [];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$request->isPost) {
            $resp = ['code' => 400, 'message' => 'Request method must be POST.'];
            Yii::$app->response->statusCode = 400;
            return $resp;
        }

        $postData = $request->post();
        $checklistsData = $postData['checklists'];

        foreach($checklistsData as $checklistData) {
            $checklist = new Checklist();
            $checklist->test_session_id = $postData['test-session-id'];
            $checklist->template_id = $checklistData['template-id'];
            $checklist->name = $checklistData['name'];
            $checklist->type = $checklistData['type'];
            $checklist->created_at = date('Y-m-d H:i:s');
            $checklist->updated_at = date('Y-m-d H:i:s');
            $success = true;

            if ($checklist->save()) {
                foreach($checklistData['checklist-items'] as $checklistItemData) {
                    $checklistItem = new ChecklistItem();
                    $checklistItem->checklist_id = $checklist->id;
                    $checklistItem->item_type = $checklistItemData['type'];
                    $checklistItem->name = $checklistItemData['name'];
                    $checklistItem->description = $checklistItemData['description'];
                    $checklistItem->value = $checklistItemData['value'];
                    $checklistItem->failing_score = $checklistItemData['failing-score'];
                    $checklistItem->note = $checklistItemData['note'];
                    $checklistItem->created_at = date('Y-m-d H:i:s');
                    $checklistItem->updated_at = date('Y-m-d H:i:s');

                    if (!$checklistItem->save()) {
                        $success = false;
                    }
                }
            } else {
                $success = false;
            }
        }

        if ($success) {
            $resp = [
                'status' => 'OK'
            ];

            return $resp;
        } else {
            $resp = [
                'status' => 'Error saving checklists'
            ];
            Yii::$app->response->statusCode = 500;

            return $resp;
        }
    }

    public function actionAddPracticalTrainingSession()
    {
        $postData = Yii::$app->request->post();

        $resp = [];
        Yii::$app->response->format = Response::FORMAT_JSON;

        $testSession = TestSession::findOne($postData['test_session_id']);
        $student = Candidates::findOne($postData['student_id']);

        if (!$testSession) {
            $resp = ['code' => 404, 'message' => 'Test Session ID not found'];
            Yii::$app->response->statusCode = 404;
            return $resp;
        }

        if (!$student) {
            $resp = ['code' => 404, 'message' => 'Student ID not found'];
            Yii::$app->response->statusCode = 404;
            return $resp;
        }

        $practicalTrainingSession = new PracticalTrainingSession();
        $practicalTrainingSession->attributes = $postData;

        if ($practicalTrainingSession->save()) {
            $resp = [
                'status' => 'OK',
                'practicalTrainingSession' => $practicalTrainingSession
            ];
        } else {
            $resp = [
                'status' => 'Error in saving practical training session'
            ];
            Yii::$app->response->statusCode = 500;
        }

        return $resp;
    }

    public function actionUpdatePracticalTrainingSession()
    {
        $postData = Yii::$app->request->post();

        $resp = [];
        Yii::$app->response->format = Response::FORMAT_JSON;

        $practicalTrainingSession = PracticalTrainingSession::findOne($postData['id']);

        if (!$practicalTrainingSession) {
            $resp = ['code' => 404, 'message' => 'practical training session not found.'];
            Yii::$app->response->statusCode = 404;
            return $resp;
        }

        $practicalTrainingSession->attributes = $postData;

        if ($practicalTrainingSession->save()) {
            $resp = [
                'status' => 'OK',
                'practicalTrainingSession' => $practicalTrainingSession
            ];
        } else {
            $resp = [
                'status' => 'Error in saving practical training session'
            ];
            Yii::$app->response->statusCode = 500;
        }

        return $resp;
    }

    public function actionDeletePracticalTrainingSession()
    {
        $postData = Yii::$app->request->post();

        $resp = [];
        Yii::$app->response->format = Response::FORMAT_JSON;

        $practicalTrainingSession = PracticalTrainingSession::findOne($postData['id']);

        if (!$practicalTrainingSession) {
            $resp = ['code' => 404, 'message' => 'practical training session not found.'];
            Yii::$app->response->statusCode = 404;
            return $resp;
        }

        if ($practicalTrainingSession->delete()) {
            $resp = [
                'status' => 'OK'
            ];
        } else {
            $resp = [
                'code' => 500,
                'status' => 'Error in deleting practical training session'
            ];
            Yii::$app->response->statusCode = 500;
        }

        return $resp;
    }

    public function actionSpreadsheet($id = null, $startDate = null, $endDate = null, $view = 'DEFAULT', $printerFriendly = 0, $partial = 0)
    {
        $testSessionArr = $candidates = [];
        $testSessionCounterpart = $testSession = null;

        $params = \Yii::$app->request->queryParams;
        $columns = $params['c'] ?? null;
        $options = $params['o'] ?? null;

        if (isset($id)) {
            $testSession = TestSession::findOne($id);
            if (isset($testSession->practical_test_session_id)) {
                $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $testSession->id . ', ' . $testSession->practical_test_session_id .')) AND isArchived = 0 ORDER BY last_name')->all();
                $testSessionCounterpart = TestSession::findOne($testSession->practical_test_session_id);
            } else {
                $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $testSession->id . ', (SELECT id FROM test_session WHERE practical_test_session_id = '. $testSession->id .'))) AND isArchived = 0 ORDER BY last_name')->all();
                $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
            }

            $testSite = $testSession->testSite;

            $testSessionArr = ArrayHelper::toArray($testSession, [
                'app\models\TestSession' => [
                    'id',
                    'name' => 'fullTestSessionDescription',
                    'startDate' => 'start_date',
                    'endDate' => 'end_date',
                    'session_number' => 'session_number',
                    'testSiteName' => function () use ($testSite) {
                        return $testSite->name;
                    },
                    'testSiteCoordinator' => function ($testSession) {
                        if (isset($testSession->practical_test_session_id)) {
                            return $testSession->getTestCoordinatorName(false);
                        }

                        $writtenSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
                        if (isset($writtenSession)) {
                            return $writtenSession->getTestCoordinatorName(false);
                        }

                        return $testSession->getTestCoordinatorName(false);
                    },
                    'instructor' => function ($testSession) {
                        if (isset($testSession->practical_test_session_id)) {
                            return $testSession->getInstructorName(false);
                        }
                        $writtenSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
                        if (isset($writtenSession)) {
                            return $writtenSession->getInstructorName(false);
                        }

                        return $testSession->getInstructorName(false);
                    },
                    'practicalExaminer' => function ($testSession) {
                        if (!isset($testSession->practical_test_session_id)) {
                            return $testSession->getStaffName(false);
                        }
                        $practicalSession = TestSession::findOne(['id' => $testSession->practical_test_session_id]);
                        return $practicalSession->getStaffName(false);
                    },
                    'practicalTestSiteCode' => function ($testSession) {
                        if (!isset($testSession->practical_test_session_id)) {
                            return $testSession->session_number;
                        }
                        $practicalSession = TestSession::findOne(['id' => $testSession->practical_test_session_id]);
                        return $practicalSession->session_number;
                    },
                    'testSiteAddress' => function () use ($testSite) {
                        return $testSite->address . ' ' . $testSite->city . ', ' . $testSite->state;
                    },
                    'testSiteNumber' => function ($testSession) {
                        if (isset($testSession->practical_test_session_id)) {
                            return $testSession->session_number;
                        }

                        $writtenSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);

                        if (!isset($writtenSession)) {
                            return $testSession->session_number;
                        }

                        return $writtenSession->session_number;
                    },
                    'testingDate' => function ($testSession) {
                        if (isset($testSession->practical_test_session_id)) {
                            return date_format(date_create($testSession->testing_date), 'm-d-Y');
                        }

                        $writtenSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);

                        if (!isset($writtenSession)) {
                            return date_format(date_create($testSession->end_date), 'm-d-Y');
                        }

                        return date_format(date_create($writtenSession->testing_date), 'm-d-Y');
                    },
                    'ncccoTestFeesCredit' => 'nccco_test_fees_credit',
                    'practicalTestSchedule'
                ]
            ]);
        } else {
            try {
                $startDateRange = date_create($startDate);
                $endDateRange = date_create($endDate);
            } catch (Exception $e) {
                throw new yii\web\BadRequestHttpException('Invalid date format. Please use the format YYYY-MM-DD.');
            }

            $testSessions = TestSession::find()->where(['>=', 'start_date', $startDateRange->format('Y-m-d H:i:s')])->andWhere(['<=', 'end_date', $endDateRange->format('Y-m-d H:i:s')])->all();

            $testSessionIds = array_map(function($testSession) {
                return $testSession->id;
            }, $testSessions);

            $candidateSessions = CandidateSession::findAll(['test_session_id' => $testSessionIds]);

            $candidateIds = array_map(function($candidateSession) {
                return $candidateSession->candidate_id;
            }, $candidateSessions);

            $candidates = Candidates::find()->where(['id' => $candidateIds, 'isArchived' => 0])->orderBy(['last_name' => SORT_ASC])->all();
        }

        $applicationTypes = ApplicationType::find()->all();

        $candidatesArr = ArrayHelper::toArray($candidates, [
            'app\models\Candidates' => [
                'id',
                'idHash' => function ($candidate) {
                    return md5($candidate->id);
                },
                'name' => function ($candidate) {
                    return $candidate->last_name . ', ' . $candidate->first_name;
                },
                'company' => 'company_name',
                'phoneNumber' => 'phone',
                'cellNumber',
                'applicationTypeId' => 'application_type_id',
                'isPurchaseOrder',
                'customFormSetup' => 'custom_form_setup',
                'isCompanySponsored' => 'is_company_sponsored',
                'invoiceNumber' => 'invoice_number',
                'purchaseOrderNumber' => 'purchase_order_number',
                'collectPaymentOverride' => 'collect_payment_override',
                'signedWFormReceived' => 'signed_w_form_received',
                'signedPFormReceived' => 'signed_p_form_received',
                'confirmationEmailLastSent' => 'confirmation_email_last_sent',
                'appFormSentToNccco' => 'app_form_sent_to_nccco',
                'writtenNcccoFeeOverride' => 'written_nccco_fee_override',
                'practicalNcccoFeeOverride' => 'practical_nccco_fee_override',
                'transactions',
                'pendingTransactions',
                'grades' => function ($candidate) use ($testSession, $testSessionCounterpart) {
                    if (isset($testSession) && isset($testSessionCounterpart)) {
                        $grades = CandidatePreviousSession::find()->where(['candidate_id' => $candidate->id])->andWhere(['in', 'test_session_id', [$testSession->id, $testSessionCounterpart->id]])->all();
                        $gradesArr = ArrayHelper::toArray($grades, [
                            'app\models\CandidatePreviousSession' => [
                                'results' => function($grade) {
                                    return json_decode($grade->craneStatus);
                                },
                                'date_created',
                                'id',
                                'isConfirmed',
                                'isGraded',
                                'isPass',
                                'remarks',
                                'test_session_id',
                                'type' => function($grade) {
                                    return isset($grade->testSession->practical_test_session_id) ? 'written' : 'practical';
                                }
                            ]
                        ]);
                        return $gradesArr;
                    }
                    return [];
                },
                'instructorNotes' => 'instructor_notes',
                'practiceTimeCredits' => 'practice_time_credits',
                'previousGrades' => function ($candidate) use ($testSession, $testSessionCounterpart) {
                    $testSessionIds = [$testSession->id];
                    if (isset($testSessionCounterpart)) {
                        $testSessionIds[] = $testSessionCounterpart->id;
                    }

                    return $candidate->getPreviousGrades($testSessionIds);
                }
            ]
        ]);

        $applicationTypesArr = ArrayHelper::toArray($applicationTypes, [
            'app\models\ApplicationType' => [
                'id',
                'name',
                'keyword',
                'description',
                'price',
                'applicationForms' => function ($applicationType) {
                    return $applicationType->getApplicationFormSetups()->all();
                }
            ]
        ]);

        $companies = Company::find()->asArray()->all();

        $isPrinterFriendly = !!$printerFriendly;
//        echo "<pre>";
//        var_dump($partial);
        $payload = [
            'testSession' => $testSessionArr,
            'candidates' => $candidatesArr,
            'companies' => $companies,
            'applicationTypes' => $applicationTypesArr,
            'view' => $view,
            'columns' => $columns,
            'options' => $options,
            'printerFriendly' => $isPrinterFriendly,
            'partial' => !!$partial
        ];

        $this->layout = $isPrinterFriendly ? 'main-printer-friendly' : 'main-fullwidth';

        if ($partial) {
            return $this->renderAjax('spreadsheet', $payload);
        }

        return $this->render('spreadsheet', $payload);
    }

    private function addWorksheet($spreadsheet, $index, $postData)
    {
        $worksheet = new PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $postData['wsName']);
        $worksheet->fromArray($postData['data']);

        $spreadsheet->addSheet($worksheet, $index);
        $spreadsheet->setActiveSheetIndex($index);

        if (isset($postData['mergedCells'])) {
            foreach ($postData['mergedCells'] as $cellRange) {
                $spreadsheet->getActiveSheet()->mergeCells($cellRange);
            }
        }

        foreach ($postData['styles'] as $rule) {
            $spreadsheet->getActiveSheet()->getStyle($rule['range'])->applyFromArray($rule['style']);
        }

        $usedColumns = $postData['usedColumns'] ?? ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];

        $activeSheet = $spreadsheet->getActiveSheet();
        foreach ($usedColumns as $column) {
            $activeSheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    public function actionRenderSpreadsheet($multiple = 0)
    {
        $postData = Yii::$app->request->post();

        $filename = Yii::getAlias('@app/web/spreadsheet/' . $postData['filename']);
        file_exists($filename) && unlink($filename);

        $spreadsheet = new PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        if ($multiple) {
            foreach ($postData['worksheets'] as $index => $worksheetPostData) {
                $spreadsheet = $this->addWorksheet($spreadsheet, $index, $worksheetPostData);
            }
        } else {
            $spreadsheet = $this->addWorksheet($spreadsheet, 0, $postData);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => 'success',
            'link' => '/spreadsheet/' . $postData['filename']
        ];
    }

    public function actionDownloadApplicationFormsZip($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $zipPath = UtilityHelper::generateApplicationFormsZip($testSession->id);
        $zipUrl = str_replace(Yii::getAlias('@webroot'), '', $zipPath . '/app-forms.zip');

        $response = [
            'zipUrl' => $zipUrl
        ];

        return $response;
    }

    public function actionDownloadCandidateDeclineAttestationsZip($id)
    {
        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $writtenTestSessionId = null;
        $practicalTestSessionId = null;

        if (isset($testSession->practical_test_session_id)) {
            $writtenTestSessionId = $testSession->id;
            $practicalTestSessionId = $testSession->practical_test_session_id;
        } else {
            $writtenTestSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
            $writtenTestSessionId = $writtenTestSession->id;
            $practicalTestSessionId = $testSession->id;
        }

        $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $writtenTestSessionId . ', ' . $practicalTestSessionId .')) AND isArchived = 0 ORDER BY last_name')->all();

        $candidateArr = ArrayHelper::toArray($candidates, [
            'app\models\Candidates' => [
                'id',
                'name' => function ($candidate) {
                    return $candidate->last_name . ', ' . $candidate->first_name;
                },
                'declinedTests'
            ]
        ]);

        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2'
        ]);

        $zipPath = '/tmp/candidate-declined-tests-zip/' . $testSession->id;

        if (file_exists($zipPath)) {
            UtilityHelper::deleteDirectory($zipPath);
            mkdir($zipPath, 0777, true);
        } else {
            mkdir($zipPath, 0777, true);
        }

        $filesToZip = [];

        foreach ($candidateArr as $candidateInfo) {
            if (count($candidateInfo['declinedTests'] > 0)) {
                foreach($candidateInfo['declinedTests'] as $declinedTest) {
                    if (isset($declinedTest['s3_key'])) {
                        $filePath = $zipPath . '/' . $candidateInfo['name'] . '-'. $declinedTest['crane'] . '.svg';

                        $s3->getObject([
                            'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                            'Key' => $declinedTest['s3_key'],
                            'SaveAs' => $filePath
                        ]);

                        $filesToZip[] = $filePath;
                    }
                }
            }
        }

        if (count($filesToZip) === 0) {
            throw new \yii\web\ServerErrorHttpException('No Declined Test Attestations found for any Candidate in Test Session.');
        }

        $zip = UtilityHelper::create_zip($filesToZip , $zipPath . '/candidate-declined-tests.zip', true);

        if ($zip) {
            return \Yii::$app->response->sendFile($zipPath . '/candidate-declined-tests.zip');
        }

        throw new \yii\web\ServerErrorHttpException('Candidate Declined Test Attestations zip file could not be generated.');
    }

    public function actionDownloadCandidatePhotosZip($id)
    {
        $testSession = TestSession::findOne($id);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }

        $writtenTestSessionId = null;
        $practicalTestSessionId = null;

        if (isset($testSession->practical_test_session_id)) {
            $writtenTestSessionId = $testSession->id;
            $practicalTestSessionId = $testSession->practical_test_session_id;
        } else {
            $writtenTestSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
            $writtenTestSessionId = $writtenTestSession->id;
            $practicalTestSessionId = $testSession->id;
        }

        $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $writtenTestSessionId . ', ' . $practicalTestSessionId .')) AND isArchived = 0 ORDER BY last_name')->all();

        $candidateArr = ArrayHelper::toArray($candidates, [
            'app\models\Candidates' => [
                'id',
                'name' => function ($candidate) {
                    return $candidate->last_name . ', ' . $candidate->first_name;
                },
                'photo_s3_key'
            ]
        ]);

        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2'
        ]);

        $zipPath = '/tmp/candidate-photos-zip/' . $testSession->id;

        if (file_exists($zipPath)) {
            UtilityHelper::deleteDirectory($zipPath);
            mkdir($zipPath, 0777, true);
        } else {
            mkdir($zipPath, 0777, true);
        }

        $filesToZip = [];

        foreach ($candidateArr as $candidateInfo) {
            if (isset($candidateInfo['photo_s3_key'])) {
                $filePath = $zipPath . '/' . $candidateInfo['name'] . '.png';

                $s3->getObject([
                    'Bucket' => getenv('S3_CANDIDATE_PHOTO_BUCKET'),
                    'Key' => $candidateInfo['photo_s3_key'],
                    'SaveAs' => $filePath
                ]);

                $filesToZip[] = $filePath;
            }
        }

        if (count($filesToZip) === 0) {
            throw new \yii\web\ServerErrorHttpException('No Candidate Photos found for any Candidate in Test Session.');
        }

        $zip = UtilityHelper::create_zip($filesToZip , $zipPath . '/candidate-photos.zip', true);

        if ($zip) {
            return \Yii::$app->response->sendFile($zipPath . '/candidate-photos.zip');
        }

        throw new \yii\web\ServerErrorHttpException('Candidate Photos zip file could not be generated.');
    }

    public function actionDownloadDeclinedTestAttestations($id = null, $startDate = null, $endDate = null, $company = null)
    {
        $this->layout = 'main-printer-friendly';

        $declinedTests = null;

        if (isset($id)) {
            if (isset($company)) {
                $candidateSessions = CandidateSession::findAll(['test_session_id' => $id]);
                $candidateIdsSession = array_map(function($candidateSession) {
                    return $candidateSession->candidate_id;
                }, $candidateSessions);
                $candidates = Candidates::findAll([
                    'id' => $candidateIdsSession,
                    'company_name' => $company
                ]);

                $candidateIds = array_map(function($candidate) {
                    return $candidate->id;
                }, $candidates);

                $declinedTests = CandidateDeclineTestAttestation::find()->where([
                    'test_session_id' => $id,
                    'candidate_id' => $candidateIds
                ])->all();
            } else {
                $declinedTests = CandidateDeclineTestAttestation::find()->where(['test_session_id' => $id])->all();
            }
        } elseif (isset($startDate) && isset($endDate)) {
            try {
                $startDateRange = date_create($startDate);
                $endDateRange = date_create($endDate);
            } catch (Exception $e) {
                throw new yii\web\BadRequestHttpException('Invalid date format. Please use the format YYYY-MM-DD.');
            }

            $testSessions = TestSession::find()->where(['>=', 'start_date', $startDateRange->format('Y-m-d H:i:s')])->andWhere(['<=', 'end_date', $endDateRange->format('Y-m-d H:i:s')])->all();

            $testSessionIds = array_map(function($testSession) {
                return $testSession->id;
            }, $testSessions);

            if (isset($company)) {
                $candidates = Candidates::findAll(['company_name' => $company]);
                $candidateIds = array_map(function($candidate) {
                    return $candidate->id;
                }, $candidates);

                $declinedTests = CandidateDeclineTestAttestation::find()->where([
                    'test_session_id' => $testSessionIds,
                    'candidate_id' => $candidateIds
                ])->all();
            } else {
                $declinedTests = CandidateDeclineTestAttestation::find()->where([
                    'test_session_id' => $testSessionIds
                ])->all();
            }
        } else {
            throw new \yii\web\BadRequestHttpException('No test session ID or date range provided.');
        }

        $declinedTestsArr = ArrayHelper::toArray($declinedTests, [
            'app\models\CandidateDeclineTestAttestation' => [
                'id',
                'candidateName' => function ($declinedTest) {
                    $candidate = Candidates::findOne($declinedTest->candidate_id);
                    return $candidate->last_name . ', ' . $candidate->first_name;
                },
                'companyName' => function ($declinedTest) {
                    $candidate = Candidates::findOne($declinedTest->candidate_id);
                    return $candidate->company_name;
                },
                'crane' => function ($declinedTest) {
                    if ($declinedTest->crane === 'sw') {
                        return 'Swing Cab';
                    }
                    if ($declinedTest->crane === 'fx') {
                        return 'Fixed Cab';
                    }
                    return 'Unknown';
                },
                'testSession' => function ($declinedTest) {
                    $testSession = TestSession::findOne($declinedTest->test_session_id);
                    return $testSession->fullTestSessionDescription;
                },
                'signatureUrl' => function ($declinedTest) {
                    return getenv('AWS_BASE_URL') . getenv('S3_CANDIDATE_PHOTO_BUCKET') . '/' . $declinedTest->s3_key;
                },
                'createdAt' => function($declinedTest) {
                    return date('m/d/Y g:i a', strtotime($declinedTest->created_at)) . ' PST';
                }
            ]
        ]);

        return $this->render('pdf/declined-tests', [
            'declinedTests' => $declinedTestsArr
        ]);
    }

    public function actionGenerateCertificates($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $postData = Yii::$app->request->post();

        return [
            'status' => 'OK'
        ];
    }

    public function actionUpdateMaterialsStatus($id, $status, $trackingNo = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $testSession = TestSession::findOne($id);
        $testSession->materials_status = $status;
        if (isset($trackingNo)) {
            $testSession->materials_tracking_no = $trackingNo;
        }

        $testSession->save();

        return [
            'status' => 'OK'
        ];
    }
}
