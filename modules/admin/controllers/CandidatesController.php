<?php

namespace app\modules\admin\controllers;

use \Yii;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

use PhpOffice\PhpSpreadsheet;

use app\models\PromoCodes;
use app\models\User;
use app\models\Candidates;
use app\models\CandidatesSearch;
use app\models\TestSession;
use app\models\CandidateSession;
use app\models\ApplicationType;
use app\models\TestSite;
use app\models\CandidateTransactions;
use app\models\PendingTransaction;
use app\models\CandidatePreviousSession;
use app\models\CandidateNotes;
use app\models\CandidateSessionBookkeeping;

use app\controllers\RegisterController;

use app\helpers\UtilityHelper;
use app\helpers\NotificationHelper;
use app\helpers\AppFormHelper;

/**
 * CandidatesController implements the CRUD actions for Candidates model.
 */
class CandidatesController extends CController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['account-balance-json', 'transaction-remark', 'save-transaction-remark', 'remove-charge',
                            'has-current-session', 'convert-complete', 'notes-list', 'notes', 'edit-notes', 'add-notes',
                            'save-notes','delete-notes', 'files', 'save-grade-session', 'grade-session', 'download-prev',
                            'mark-app', 'pass-session','fail-session','manual-confirm','generate-certs',
                            'paymentscreen', 'refund', 'charge', 'removefromsession', 'disregard', 'viewpage',
                            'recentviewpage', 'deletesigned', 'deleteattachment', 'viewattachment','viewsigned',
                            'attachments', 'sendappform', 'account-balance', 'payment', 'index', 'generate', 'confirmationpayment',
                            'epayment', 'select', 'view', 'create', 'create-simple', 'bulk-register', 'bulk-register-legacy', 'preview-bulk-register',
                            'update', 'delete', 'update-json', 'update-grades-json', 'add-transaction-json', 'batch-update-grades-json', 'update-transaction-batch-json', 'update-transaction-json', 'delete-transaction', 'check-number', 'save-check-number', 'search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'delete-transaction'],
                ],
            ],
        ];
    }

    public function actionSaveTransactionRemark()
    {
        $resp = [];
        $id = $_POST['id'];
        $remark = $_POST['remarks'];
        $transaction = CandidateTransactions::findOne($_REQUEST['id']);
        $transaction->remarks = $remark;
        $resp['status'] = 0;
        if($transaction->save()){
            $resp['status'] = 1;
            $resp['id'] = $id;
            $resp['remark'] = $remark;
        }
        echo json_encode($resp);
        die;
    }

    public function actionSaveCheckNumber()
    {
        $resp = [];
        $id = $_POST['id'];
        $checkNumber = $_POST['check_number'];
        $transaction = CandidateTransactions::findOne($_REQUEST['id']);
        $transaction->check_number = $checkNumber;
        $resp['status'] = 0;
        if ($transaction->save()) {
            $resp['status'] = 1;
            $resp['id'] = $id;
            $resp['check_number'] = $checkNumber;
        }
        echo json_encode($resp);
        die;
    }

    public function actionTransactionRemark()
    {
        $transaction = CandidateTransactions::findOne($_REQUEST['id']);
        return $this->renderPartial('update-remark', ['transaction' => $transaction]);
    }

    public function actionCheckNumber()
    {
        $transaction = CandidateTransactions::findOne($_REQUEST['id']);
        return $this->renderPartial('update-check-number', ['transaction' => $transaction]);
    }

    public function actionConvertComplete()
    {
        $id = $_REQUEST['id'];
        $resp = [];
        $resp['status'] = 0;
        $candidate = $this->findModel($id);
        if ($candidate) {
            UtilityHelper::addCandidateInitialApplicationCharge($candidate);
            $appType = ApplicationType::findOne($candidate->application_type_id);
            $candidate->registration_step = '3';
            $candidate->isPurchaseOrder = 0;
            $candidate->referralCode = '';
            $candidate->save();

            $resp['status'] = 1;
        }
        echo json_encode($resp);
        die;
    }

    public function actionNotes()
    {
        $id = $_REQUEST['id'];
        $candidate = $this->findModel($id);
        $notes = CandidateNotes::findAll(['candidate_id' => $candidate->id]);

        if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 1){
            return $this->renderPartial('notes/_list', [
                'notes' => $notes,
                'candidateID' => $candidate->id
            ]);
        }

        return $this->render('notes/list', [
            'model' => $candidate, 'notes' => $notes, 'candidateId' => $candidate->id
        ]);
    }

    public function actionFiles($id)
    {
        $candidate = $this->findModel($id);

        $candidateArr = ArrayHelper::toArray($candidate, [
            'app\models\Candidates' => [
                'fullName',
                'photo' => 'photo_s3_key',
                'trainingSessions',
                'scoreSheetPhotos',
                'declinedTests'
            ]
        ]);

        $this->layout = 'main-new';

        return $this->render('files/index', [
            'model' => $candidate,
            'candidate' => $candidateArr,
            'candidatePhotoBaseUrl' => getenv('AWS_BASE_URL') . getenv('S3_CANDIDATE_PHOTO_BUCKET') . '/',
            'testSessionPhotoBaseUrl' => getenv('AWS_BASE_URL') . getenv('S3_TEST_SESSION_PHOTO_BUCKET') . '/',
        ]);
    }

    public function actionAddNotes()
    {
        $candidateIdMd5 = $_REQUEST['id'];
        $candidate = $this->findModel($candidateIdMd5);
        $candidateNotes = new CandidateNotes();
        $candidateNotes->candidate_id = $candidate->id;
        return $this->renderPartial('notes/addNotes', ['model' => $candidateNotes]);
    }

    public function actionEditNotes()
    {
        $noteId = $_REQUEST['id'];
        $candidateNote = CandidateNotes::findOne($noteId);
        return $this->renderPartial('notes/addNotes', ['model' => $candidateNote]);
    }

    public function actionDeleteNotes()
    {
        $id = $_REQUEST['id'];
        $candidateNote = CandidateNotes::findOne($id);
        $resp = [];
        if($candidateNote->delete()){
            $resp['status'] = 1;
        }else{
            $resp['status'] = 0;
        }
        echo json_encode($resp);
    }

    public function actionSaveNotes()
    {
        $model = new CandidateNotes();
        if(isset($_POST['CandidateNotes']['id']) && $_POST['CandidateNotes']['id'] != ''){
            $model = CandidateNotes::findOne($_POST['CandidateNotes']['id']);
        }
        $resp = [];
        $resp['status'] = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if($model->save()){
                //return $this->redirect(['view', 'id' => $model->id]);
                $resp['status'] = 1;
            }

            //return $this->redirect(['view', 'id' => $model->id]);
        }
        echo json_encode($resp);
    }

    public function actionDownloadPrev()
    {
        $x = isset($_REQUEST['x']) ? $_REQUEST['x'] : '';
        $i = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';

        if($x != '' && $i != ''){

            $x = base64_decode($x);
            if(md5($x) == $i){
                $candidateSession = CandidatePreviousSession::findOne($x);

                if($candidateSession != null){
                    $hasPreviousPdf =  $candidateSession->hasPreviousPdf();

                    if($hasPreviousPdf !== false){

                        header('Content-type: application/pdf');
                        header('Content-Disposition: inline; filename="' . basename($hasPreviousPdf) . '"');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($hasPreviousPdf));
                        header('Accept-Ranges: bytes');

                        // Render the file
                        readfile($hasPreviousPdf);

                    }
                }
            }
        }
    }

    public function actionManualConfirm()
    {
        $resp = [];
        $resp['status'] = 0;

        if(count($_POST) > 0){
            $model = $this->findModel($_POST['id']);

            $signedForms = json_decode($model->signedForms, true);
            $newKey = $_POST['formName'].'-manual-confirm';
            $signedForms[$newKey] = date('Y-m-d', strtotime('now'));
            $model->signedForms = json_encode($signedForms);

            //we add the logic of storing the signed forms if its existing
            if($model->save()){
                $resp['status'] = 1;
            }
            //var_dump($signedForms);
        }
        echo json_encode($resp);
        die;
    }

    public function actionMarkApp()
    {
        $resp = [];
        $resp['status'] = 0;

        if (count($_POST) > 0) {
            $candidate = $this->findModel($_POST['id']);
            $mark = $_POST['mark'];

            if ($candidate) {
                $candidate->save();
                $resp['status'] = 1;
            }
        }
        echo json_encode($resp);
        die;
    }

    public function actionSaveGradeSession()
    {
        $resp = [];
        $resp['status'] = 0;

        if(count($_POST) > 0){
            $candidate = Candidates::findOne($_POST['id']);
            $testSession = TestSession::findOne($_POST['testSessionId']);

            $isConfirmed = 0;

            $existingTestSession = false;
            $formName = '';
            if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
                $existingTestSession = $candidate->getPracticalSession();

                if($candidate->getSignedForm(AppFormHelper::PRACTICAL_FORM_PDF) || $candidate->isManualConfirmed(AppFormHelper::PRACTICAL_FORM_PDF)){
                    $isConfirmed = 1;
                }
                $formName = AppFormHelper::PRACTICAL_FORM_PDF;
            }else if($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
                $existingTestSession = $candidate->getWrittenTestSession();

                if($candidate->getSignedForm(AppFormHelper::WRITTEN_FORM_PDF) || $candidate->isManualConfirmed(AppFormHelper::WRITTEN_FORM_PDF)){
                    $isConfirmed = 1;
                }else if($candidate->getSignedForm(AppFormHelper::RECERTIFY_FORM_PDF) || $candidate->isManualConfirmed(AppFormHelper::RECERTIFY_FORM_PDF)){
                    $isConfirmed = 1;
                }

                if(AppFormHelper::hasRecertifyPdf($candidate->application_type_id)){
                    $formName = AppFormHelper::RECERTIFY_FORM_PDF;
                }else{
                    $formName = AppFormHelper::WRITTEN_FORM_PDF;
                }
            }

            $craneStatus = [];
            $isPass = true;
            $markedAsPassed = [];
            foreach($_POST as $key => $val){
                if(strpos($key, ':::::') !== false){
                    $craneData = explode(':::::', $key);
                    $craneInfo = [];
                    $craneInfo['key'] = $craneData[0];
                    $craneInfo['name'] = str_replace('_', ' ',$craneData[1]);
                    $craneInfo['status'] = $val == 'true' ? true : false;
                    $craneInfo['val'] = $craneData[2];

                    if($craneInfo['status'] === false){
                        $isPass = false;
                    }else{
                        $markedAsPassed[] = $craneData[0];
                    }
                    $craneStatus[] = $craneInfo;
                }
            }

            $candidatePrevSes = new CandidatePreviousSession();
            $candidatePrevSes->candidate_id = $candidate->id;
            $candidatePrevSes->test_session_id = $testSession->id;
            $candidatePrevSes->craneStatus = json_encode($craneStatus);
            $candidatePrevSes->isConfirmed = $isConfirmed;
            $candidate->save();

            $candidateSession = CandidateSession::findOne(['candidate_id' => $candidate->id, 'test_session_id' => $testSession->id]);

            if($candidateSession){
                $candidateSession->isPass = $isPass ? 1 : 2;
                $candidateSession->save();
            }

            $candidatePrevSes->save();


            $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
            if($appFormPath === false){
                UtilityHelper::generateApplicationForms($candidate->id, false);
                $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
            }
            if($appFormPath !== false){
                $mergedFile = UtilityHelper::downloadAppForm($appFormPath, $candidate);
                //we create a copy of the file
                $realCandidateBaseFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory();
                $candidateFolder = $realCandidateBaseFolder.'/previous-session/'.$candidatePrevSes->id.'/';

                UtilityHelper::createPath($candidateFolder);
                if(is_file($mergedFile))
                    copy( $mergedFile, $candidateFolder.'previous-form.pdf');

            }

            $resp['status'] = 1;
            $copyAppType = false;
            if($candidate->custom_form_setup == null || $candidate->custom_form_setup == '' || count(json_decode($candidate->custom_form_setup, true) == 0)){
                $copyAppType = true;


            }
            //add here the organize the pdf again
            if(count($markedAsPassed) > 0 ){
                $model = $candidate;
                $customFormSetup = $candidate->getCandidateFormSetup();

                $model->custom_form_setup = json_encode($customFormSetup);
                //we add the logic of storing the signed forms if its existing
                $model->save();

                UtilityHelper::generateApplicationForms($model->id, true);
            }
        }
        echo json_encode($resp);
        die;
    }

    public function actionGradeSession()
    {
        $resp = [];
        $resp['status'] = 0;

        if(count($_GET) > 0){
            $candidate = Candidates::findOne($_GET['id']);

            $craneList = $candidate->getCranes($_GET['testSessionId']);
            $craneGradeHtml = $this->renderPartial('partial/_cranes', ['craneList' => $craneList]);
            $resp['html'] = $craneGradeHtml;
        }

        echo json_encode($resp);
        die;
    }

    public function actionPassSession()
    {
        $resp = [];
        $resp['status'] = 0;

        if(count($_POST) > 0){

            $candidateSession = CandidateSession::findOne(['candidate_id' => $_POST['id'], 'test_session_id' => $_POST['testSessionId']]);

            if($candidateSession){
                $candidateSession->isPass = 1;
                $candidateSession->save();
                $resp['status'] = 1;
            }
        }
        echo json_encode($resp);
        die;
    }

    public function actionFailSession()
    {
        $resp = [];
        $resp['status'] = 0;

        if(count($_POST) > 0){
            $candidateSession = CandidateSession::findOne(['candidate_id' => $_POST['id'], 'test_session_id' => $_POST['testSessionId']]);

            if($candidateSession){
                $candidateSession->isPass = 2;
                $candidateSession->save();
                $resp['status'] = 1;
            }
        }
        echo json_encode($resp);
        die;
    }

    public function actionRemovefromsession()
    {
        $this->addNonGradedPreviousSession($_POST['id'], $_POST['sessionId'], isset($_POST['remarks']) ? $_POST['remarks'] : '');
        Candidates::cancelSession($_POST['id'], $_POST['sessionId']);
    }

    private function addNonGradedPreviousSession($candidateId, $testSessionId, $remarks)
    {
        $candidate = Candidates::findOne($candidateId);
        $testSession = TestSession::findOne($testSessionId);

        $isConfirmed = 0;

        $existingTestSession = false;
        $formName = '';
        if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
            $existingTestSession = $candidate->getPracticalSession();

            if($candidate->getSignedForm(AppFormHelper::PRACTICAL_FORM_PDF) || $candidate->isManualConfirmed(AppFormHelper::PRACTICAL_FORM_PDF)){
                $isConfirmed = 1;
            }
            $formName = AppFormHelper::PRACTICAL_FORM_PDF;
        }else if($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
            $existingTestSession = $candidate->getWrittenTestSession();

            if($candidate->getSignedForm(AppFormHelper::WRITTEN_FORM_PDF) || $candidate->isManualConfirmed(AppFormHelper::WRITTEN_FORM_PDF)){
                $isConfirmed = 1;
            }else if($candidate->getSignedForm(AppFormHelper::RECERTIFY_FORM_PDF) || $candidate->isManualConfirmed(AppFormHelper::RECERTIFY_FORM_PDF)){
                $isConfirmed = 1;
            }

            if(AppFormHelper::hasRecertifyPdf($candidate->application_type_id)){
                $formName = AppFormHelper::RECERTIFY_FORM_PDF;
            }else{
                $formName = AppFormHelper::WRITTEN_FORM_PDF;
            }
        }

        $craneStatus = [];
        $isPass = true;
        $markedAsPassed = [];
        foreach($_POST as $key => $val){
            if(strpos($key, ':::::') !== false){
                $craneData = explode(':::::', $key);
                $craneInfo = [];
                $craneInfo['key'] = $craneData[0];
                $craneInfo['name'] = str_replace('_', ' ',$craneData[1]);
                $craneInfo['status'] = $val == 'true' ? true : false;
                if($craneInfo['status'] === false){
                    $isPass = false;
                }else{
                    $markedAsPassed[] = $craneData[0];
                }
                $craneStatus[] = $craneInfo;
            }
        }

        $candidatePrevSes = new CandidatePreviousSession();
        $candidatePrevSes->candidate_id = $candidate->id;
        $candidatePrevSes->test_session_id = $testSession->id;
        $candidatePrevSes->craneStatus = json_encode($craneStatus);
        $candidatePrevSes->isConfirmed = $isConfirmed;
        $candidatePrevSes->remarks = $remarks;
        $candidatePrevSes->isGraded = 0;

        $candidatePrevSes->save();

        $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
        if($appFormPath === false){
            UtilityHelper::generateApplicationForms($candidate->id, false);
            $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
        }
        if($appFormPath !== false){
            $mergedFile = UtilityHelper::downloadAppForm($appFormPath, $candidate);
            //we create a copy of the file
            $realCandidateBaseFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory();
            $candidateFolder = $realCandidateBaseFolder.'/previous-session/'.$candidatePrevSes->id.'/';

            UtilityHelper::createPath($candidateFolder);
            if(is_file($mergedFile)){
                copy( $mergedFile, $candidateFolder.'previous-form.pdf');
            }
        }

        $resp['status'] = 1;
        $copyAppType = false;
        if($candidate->custom_form_setup == null || $candidate->custom_form_setup == '' || count(json_decode($candidate->custom_form_setup, true) == 0)){
            $copyAppType = true;
        }
        //add here the organize the pdf again
        if(count($markedAsPassed) > 0 ){
            $model = $candidate;
            $customFormSetup = $candidate->getCandidateFormSetup();

            foreach($markedAsPassed as $itemKey){
                if(isset($customFormSetup[$formName][$itemKey])){
                    unset($customFormSetup[$formName][$itemKey]);
                }
            }
            $model->custom_form_setup = json_encode($customFormSetup);

            UtilityHelper::generateApplicationForms($model->id, true);
        }
    }

    public function actionViewpage()
    {
        $page = $_REQUEST['page'];
        $items = Candidates::getIncompleteApplication(10, $page);

        return $this->renderPartial('../widgets/incomplete', ['items' => $items, 'currentPage' => $page]);
    }

    public function actionRecentviewpage()
    {
        $page = $_REQUEST['page'];
        $time = $_REQUEST['time'];
        $items = Candidates::getRecentApplication(10, $page, $time);

        return $this->renderPartial('../widgets/recent', ['items' => $items, 'currentPage' => $page]);
    }

    public function actionDisregard()
    {
        $canId = $_REQUEST['id'];
        $candidate = $this->findModel($canId);
        $candidate->disregard = 1;
        $candidate->save();
    }

    public function actionDeleteattachment()
    {
        $canId = $_REQUEST['id'];
        $candidate = $this->findModel($canId);

        $i = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';
        $pFile=  isset($_REQUEST['pFile']) ? $_REQUEST['pFile'] : 0;

        if($candidate != null){
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments/';
            $uploadPaymentDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments-payment-files/';
            $filePath = $uploadDir.base64_decode($i);
            if($pFile == 0 && is_file($filePath)){
                unlink($filePath);
            }else if(is_file($uploadPaymentDir.base64_decode($i))){
                unlink($uploadPaymentDir.base64_decode($i));
            }
        }
    }

    public function actionDeletesigned()
    {
        $canId = $_REQUEST['id'];
        $candidate = $this->findModel($canId);

        $i = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';
        $formName = isset($_REQUEST['formName']) ? $_REQUEST['formName'] : '';

        if($candidate != null){
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/signed/';
            $filePath = $uploadDir.base64_decode($i);
            if(is_file($filePath)){
                unlink($filePath);
            }

            $signedForms = json_decode($candidate->signedForms, true);
            if($signedForms == null)
                $signedForms = array();

            foreach($signedForms as $name => $val){
                if($formName == $name){
                    $signedForms[$name] = '';
                }
            }

            $candidate->signedForms = json_encode($signedForms);

            //we add the logic of storing the signed forms if its existing
            $candidate->save();

        }
        return $this->redirect(['update', 'id' => md5($candidate->id), 's' => 2]);
    }

    public function actionViewattachment()
    {
        $canId = $_REQUEST['id'];
        $candidate = $this->findModel($canId);

        $i = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';

        $pFile=  isset($_REQUEST['pFile']) ? $_REQUEST['pFile'] : 0;
        if($candidate != null){
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments/';
            $uploadPaymentDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments-payment-files/';
            $filePath = $uploadDir.base64_decode($i);
            if($pFile == 0 && is_file($filePath)){
                //Yii::app()->getRequest()->sendFile
                return \Yii::$app->getResponse()->sendFile($filePath);
            }else if(is_file($uploadPaymentDir.base64_decode($i))){
                return \Yii::$app->getResponse()->sendFile($uploadPaymentDir.base64_decode($i));
            }
        }
    }

    public function actionViewsigned()
    {
        $canId = $_REQUEST['id'];
        $candidate = $this->findModel($canId);

        $i = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';

        if($candidate != null){
            $uploadDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/signed/';
            $filePath = $uploadDir.base64_decode($i);
            if(is_file($filePath)){
                //Yii::app()->getRequest()->sendFile
                return \Yii::$app->getResponse()->sendFile($filePath);
            }
        }
    }

    public function actionAttachments()
    {
        $resp = array();
        if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
            $paymentFile = isset($_POST['paymentFile']) ? $_POST['paymentFile'] : 0;
            $showApplication = isset($_POST['showApplication']) && $_POST['showApplication'] == 1 ? true : false;
            $candidate = $this->findModel($_POST['candidateId']);

            $suffixFolder = '';

            if($paymentFile == 1){
                $suffixFolder = '-payment-files';
            }

            $uploadDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments'.$suffixFolder.'/';

            UtilityHelper::createPath($uploadDir);

            if(move_uploaded_file($_FILES['upl']['tmp_name'], $uploadDir.$_FILES['upl']['name'])){
                $resp['file'] = $_FILES['upl']['name'];
                $resp['status'] = 1;
                $resp['html'] = $this->renderPartial('file-attachments', ['candidate'=>$candidate, 'showApplication' => $showApplication, 'showPayment' => true]);
                echo json_encode($resp);
                die;
            }

        }

        $resp['status'] = 0;
        echo json_encode($resp);
        die;
    }

    /**
     * Lists all Candidates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CandidatesSearch();

        $params = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($params);
        $testSessionIdMd5Encoded = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'testSessionIdMd5Encoded' => $testSessionIdMd5Encoded,
            'showDisregard' => isset($params['showDisregard']) ? !!$params['showDisregard'] : false
        ]);
    }

    public function actionGenerate()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $candidateId = isset($_REQUEST['cId']) ? $_REQUEST['cId'] : '';
            $i = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
            if($candidateId != '' && $i == md5(base64_decode($candidateId))){
                $candidateId = base64_decode($candidateId);
                $candidate = Candidates::findOne($candidateId);
                if($candidate != null){
                    UtilityHelper::generateApplicationForms($candidateId, true);
                    return $this->redirect('/admin/candidates/view?id='.md5($candidate->id).'&s=1');
                }
            }

        }
        return $this->redirect('/admin/candidates');
    }

    public function actionAddTransactionJson($id)
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($request->isPost) {
            $post = $request->post();

            $candidate = Candidates::findOne($id);

            if (isset($candidate)) {
                $post['candidateId'] = $id;
                $transaction = new CandidateTransactions();
                $transaction->attributes = $post;

                if ($transaction->save()) {
                    return ArrayHelper::toArray($transaction, ['app\models\CandidateTransactions']);
                }

                throw new \yii\web\ServerErrorHttpException('Unable to save Candidate Transaction.');
            }

            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        throw new \yii\web\MethodNotAllowedHttpException('Invalid HTTP method.');
    }

    public function actionConfirmationpayment()
    {
        if (count($_POST) != 0) {
            $paymentSuccess = false;

            $hasSuccessResponseCode = isset($_POST['x_response_code']) && $_POST['x_response_code'] == '1';
            $hasSuccessResponseReasonCode = isset($_POST['x_response_reason_code']) && $_POST['x_response_reason_code'] == '1';

            if ($hasSuccessResponseCode && $hasSuccessResponseReasonCode) {
                $paymentSuccess = true;
                $promoCode = isset($_POST['x_promo']) ? $_POST['x_promo'] : '';
                $candidateId = base64_decode($_POST['x_cId']);
                $amount = $_POST['x_amount'];
                $candidateTransaction = new CandidateTransactions();
                $candidateTransaction->transactionId = $_POST['x_trans_id'];
                $candidateTransaction->amount = $amount;
                $candidateTransaction->paymentType = CandidateTransactions::TYPE_ELECTRONIC_PAYMENT;
                $candidateTransaction->candidateId = $candidateId;
                $candidateTransaction->remarks = isset($_POST['x_remarks']) ? $_POST['x_remarks'] : '';

                $candidateTransaction->save();
                $candidate = Candidates::findOne(['id' => $candidateId]);
                $message = false;

                if ($candidate) {
                    $message = 'Payment Successful';
                }

                return $this->render('payment', [
                    'candidate' => $candidate,
                    'message' => $message,
                    'redirectUrl' => $_POST["x_payment_success_url"]
                ]);
            }
        }

        return $this->redirect('/admin/candidates');
    }

    public function actionEpayment()
    {
        $candidates = Candidates::find()->where("md5(id) = '".$_REQUEST['id']."'")->all();
        if($candidates){
            $candidate = $candidates[0];
            $amount = $_REQUEST['amount'];
            $remarks = isset($_REQUEST['remarks']) ? $_REQUEST['remarks'] : '';

            return $this->render('e-payment-process', ['candidate' => $candidate, 'amount' =>$amount, 'remarks' => $remarks]);
        }
        return '';
    }

    public function actionPayment()
    {
        $candidateId = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        $testSessionId = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
        $candidates = Candidates::find()->where("md5(id) = '" . $candidateId . "'")->all();

        if ($candidates) {
            $candidate = $candidates[0];
            $message = false;
            if (count($_POST)) {
                $message = 'Payment Successful';
                $candidateTransaction = new CandidateTransactions();
                $candidateTransaction->amount = $_POST['paymentAmount'];
                $candidateTransaction->candidateId = $candidate->id;
                $candidateTransaction->paymentType = $_POST['type'];
                $candidateTransaction->remarks = $_POST['remarks'];
                if (isset($_POST['check_number']) && $_POST['check_number'] != '') {
                    $candidateTransaction->check_number = $_POST['check_number'];
                }
                $candidateTransaction->save();
            } elseif (isset($_REQUEST['s']) && $_REQUEST['s'] == 1) {
                $message = 'Received Payment Successful';
            }

            return $this->redirect('http://' . $_SERVER['HTTP_HOST'] . '/admin/candidates/account-balance?id=' . md5($candidate->id) . '&message=' . $message);
        }

        if ($testSessionId != '') {
            return $this->goBack('admin/candidatesession?i' . $testSessionId);
        }

        return $this->goBack('admin/candidates');
    }

    public function actionAccountBalance()
    {
        $candidateId = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        $testSessionId = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
        $candidates = Candidates::find()->where("md5(id) = '".$candidateId."'")->all();

        if ($candidates) {
            $candidate = $candidates[0];
            $message = false;

            if (isset($_REQUEST['message'])) {
                $message = $_REQUEST['message'];
            }

            $paymentsList = $candidate->getPaymentLists();

            $totalPayment = 0;
            $totalCharged = 0;
            $totalRefunded = 0;
            $totalPromo = 0;
            $totalRemovedCharge = 0;
            $totalAdjustment = 0;

            foreach($paymentsList as $transaction) {
                if ($transaction->paymentType == CandidateTransactions::TYPE_STUDENT_CHARGE) {
                    $totalCharged += $transaction->amount;
                } else if ($transaction->paymentType == CandidateTransactions::TYPE_CASH
                    || $transaction->paymentType == CandidateTransactions::TYPE_INTUIT
                    || $transaction->paymentType == CandidateTransactions::TYPE_RECEIVABLES_OTHER
                    || $transaction->paymentType == CandidateTransactions::TYPE_CHEQUE
                    || $transaction->paymentType == CandidateTransactions::TYPE_ELECTRONIC_PAYMENT
                ) {
                    $totalPayment += $transaction->amount;
                } else if ($transaction->paymentType == CandidateTransactions::TYPE_TRANSFER) {
                    $totalPayment -= $transaction->amount;
                } else if ($transaction->paymentType == CandidateTransactions::TYPE_REFUND) {
                    $totalRefunded += $transaction->amount;
                } else if ($transaction->paymentType == CandidateTransactions::TYPE_DISCOUNT) {
                    $totalRemovedCharge += $transaction->amount;
                } else if ($transaction->paymentType == CandidateTransactions::TYPE_PROMO) {
                    $totalPromo += $transaction->amount;
                } else if ($transaction->paymentType == CandidateTransactions::TYPE_ADJUSTMENT) {
                    $totalAdjustment += $transaction->amount;
                    $totalCharged -= $transaction->amount;
                }
            }

            $totalGrossPayable = $totalCharged - $totalPromo - $totalRemovedCharge;
            $totalNetPayable = $totalCharged - $totalRefunded - $totalPromo - $totalRemovedCharge;
            $totalAmountOwed = $totalGrossPayable - $totalPayment;

            $candidateArr = ArrayHelper::toArray($candidate, [
                'app\models\Candidates' => [
                    'id',
                    'idHash' => function($candidate) {
                        return md5($candidate->id);
                    },
                    'fullName',
                    'phone',
                    'poNumber' => 'purchase_order_number',
                    'transactions',
                    'pendingTransactions' => function($candidate) {
                        $ptx = $candidate->pendingTransactions;
                        return ArrayHelper::toArray($ptx, [
                            'app\models\PendingTransaction' => [
                                'id',
                                'postedBy' => function($tx) {
                                    $user = $tx->postedBy;
                                    return $user->first_name . ' ' . $user->last_name;
                                },
                                'amount',
                                'type',
                                'created_at'
                            ]
                        ]);
                    },
                    'applicationType' => 'applicationTypeDesc'
                ]
            ]);

            return $this->render(
                'account-balance',
                [
                    'candidateArr' => $candidateArr,
                    'candidate' => $candidate,
                    'message' => $message,
                    'paymentsList' => $paymentsList,
                    'totalPayment' => $totalPayment,
                    'totalCharged' => $totalCharged,
                    'totalRefunded' => $totalRefunded,
                    'totalPromo' => $totalPromo,
                    'totalRemovedCharge' => $totalRemovedCharge,
                    'totalNetPayable' => $totalNetPayable,
                    'totalAmountOwed' => $totalAmountOwed
                ]
            );
        }

        if($testSessionId != '')
            return $this->goBack('admin/candidatesession?i'.$testSessionId);
        return $this->goBack('admin/candidates');
    }

    public function actionAccountBalanceJson()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $resp = null;

        try {
            $candidateId = $_REQUEST['id'];
            $candidate = Candidates::find()->where("md5(id) = '".$candidateId."'")->all()[0];
            $resp = $candidate->getPaymentLists();
        } catch (Exception $e) {
            $resp = 'Error: ' . $e->getMessage();
        } finally {
            return $resp;
        }
    }

    public function actionPaymentscreen()
    {
        $candidateId = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        $candidates = Candidates::find()->where("md5(id) = '" . $candidateId . "'")->all();

        if ($candidates) {
            $candidate = $candidates[0];
            $message = false;
            return $this->renderPartial('paymentscreen', [
                'candidate' => $candidate,
                'message' => $message
            ]);
        }
    }

    public function actionCharge()
    {
        if (count($_POST) != 0) {
            $candidateTransaction = new CandidateTransactions();
            $candidateTransaction->load(Yii::$app->request->post());
            if ($candidateTransaction->amount > 0) {
                $candidateTransaction->save();
            }
        } else {
            $candidate = Candidates::findOne($_REQUEST['candidateId']);
            return $this->renderPartial('charge', [
                'candidate' => $candidate,
                'candidateId' => $_REQUEST['candidateId']
            ]);
        }
    }

    public function actionRefund()
    {
        if (count($_POST) != 0) {
            $candidateTransaction = new CandidateTransactions();
            $postDetails = $_POST['CandidateTransactions'];
            $candidateTransaction->amount = $postDetails['amount'];
            $candidateTransaction->candidateId = $postDetails['candidateId'];
            $candidateTransaction->paymentType = $postDetails['paymentType'];

            if (isset(postDetails['remarks'])) {
                $candidateTransaction->remarks = $postDetails['remarks'];
            }

            $candidateTransaction->save();
        } else {
            $candidate = Candidates::findOne($_REQUEST['candidateId']);
            return $this->renderPartial('refund', ['candidate' => $candidate]);
        }
    }

    public function actionRemoveCharge()
    {
        if (count($_POST) != 0) {
            $postDetails = $_POST['CandidateTransactions'];
            $candidate = Candidates::findOne($postDetails['candidateId']);
            $candidate->removeCharge($postDetails['amount'], $postDetails['remarks']);

        } else {
            return $this->renderPartial('remove-charge', ['candidateId' => $_REQUEST['candidateId']]);
        }
    }

    public function actionMakeAdjustment()
    {
        if (count($_POST) != 0) {
            $postDetails = $_POST['CandidateTransactions'];
            $candidate = Candidates::findOne($postDetails['candidateId']);
            $candidate->removeCharge($postDetails['amount'], $postDetails['remarks']);

        } else {
            return $this->renderPartial('remove-charge', ['candidateId' => $_REQUEST['candidateId']]);
        }
    }

    public function actionHasCurrentSession()
    {
        $candidateId = isset($_REQUEST['id']) ? $_REQUEST['id']: '';//candidate id
        $testSessionId = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';//test session  id
        $testSession = false;
        $candidate = false;
        $testSessions = TestSession::find()->where("md5(id) = '".$testSessionId."'")->all();
        if($testSessions){
            $testSession = $testSessions[0];
        }
        $candidates = Candidates::find()->where("md5(id) = '".$candidateId."'")->all();
        if($candidates){
            $candidate = $candidates[0];
        }
        $resp = [];
        $resp['hasCurrentSession'] = 0;

        if($candidate && $testSession){
            $matchingSession = false;
            //we check if there is a the same type session
            if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
                $matchingSession = $candidate->getPracticalSession();
            }else if($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
                $matchingSession = $candidate->getWrittenTestSession();
            }

            if($matchingSession !== false){
                $resp['hasCurrentSession'] = 1;
            }
        }

        echo json_encode($resp);
        die;
    }

    public function actionSelect()
    {
        $request = Yii::$app->request;
        $candidateId = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        $testSessionId = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
        $isRetake = isset($_REQUEST['isRetake']) ? $_REQUEST['isRetake'] : '0';

        $testSession = false;
        $candidate = false;
        $testSessions = TestSession::find()->where("md5(id) = '" . $testSessionId . "'")->all();
        if ($testSessions) {
            $testSession = $testSessions[0];
        }
        if (!$testSession) {
            $testSession = TestSession::findOne($testSessionId);
        }

        $candidates = Candidates::find()->where("md5(id) = '" . $candidateId . "'")->all();
        if ($candidates) {
            $candidate = $candidates[0];
        }
        if (!$candidate) {
            $candidate = Candidates::findOne($candidateId);
        }

        if ($request->isPost) {
            if ($testSession !== false && $candidate !== false) {
                $originalCandidateId = $candidate->id;
                $candidateToClone = false;

                $currentSession = false;
                $currentSessionId = false;
                $currentLinkedSession = false;
                $currentLinkedSessionId = false;
                $linkedSessionId = false;
                $testSessionType = $testSession->testSite->typeStr;
                if (!$candidate->hasNoSession()) {
                    if ($testSessionType === 'Written') {
                        if ($currentSession = $candidate->writtenTestSession) {
                            $currentSession = $candidate->writtenTestSession;
                            $currentSessionId = $currentSession->id;
                            $currentLinkedSession = $candidate->practicalSession;
                            $currentLinkedSessionId = $currentLinkedSession ? $currentLinkedSession->id : false;
                        }
                    } else {
                        if ($candidate->practicalSession) {
                            $currentSession = $candidate->practicalSession;
                            $currentSessionId = $currentSession->id;
                            $currentLinkedSession = $candidate->writtenTestSession;
                            $currentLinkedSessionId = $currentLinkedSession ? $currentLinkedSession->id : false;
                        }
                    }
                }

                if ($testSessionType === 'Written') {
                    $linkedSessionId = $testSession->practical_test_session_id ?? false;
                } else {
                    $linkedSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
                    if ($linkedSession) {
                        $linkedSessionId = $linkedSession->id;
                    }
                }

                if ($request->post('isRescheduleOnly')) {
                    $candidateSession = $currentSessionId ? CandidateSession::findOne($currentSessionId) : new CandidateSession();
                    if (!$currentSessionId) {
                        $candidateSession->candidate_id = $candidate->id;
                    }
                    $candidateSession->test_session_id = $testSession->id;
                    $candidateSession->save();

                    if ($request->post('transferWrittenAndPractical') && $linkedSessionId) {
                        $linkedCandidateSession = $currentLinkedSessionId ? CandidateSession::findOne($currentLinkedSessionId) : new CandidateSession();
                        if (!$currentLinkedSessionId) {
                            $linkedCandidateSession->candidate_id = $candidate->id;
                        }
                        $linkedCandidateSession->test_session_id = $linkedSessionId;
                        $linkedCandidateSession->save();
                    }
                } else {
                    $candidateToClone = new Candidates();
                    $candidateToClone->setAttributes($candidate->attributes);
                    $candidateToClone->isArchived = false;
                    unset($candidateToClone->id);
                    $candidateToClone->application_type_id = $request->post('incomingApplicationTypeId');
                    $candidateToClone->custom_form_setup = json_encode([]);

                    $candidateToClone->save();
                    $candidateSession = new CandidateSession();
                    $candidateSession->candidate_id = $candidateToClone->id;
                    $candidateSession->test_session_id = $testSession->id;
                    $candidateSession->save();

                    $prevSession = new CandidatePreviousSession();
                    $existingPrevSession = CandidatePreviousSession::findOne([
                        'candidate_id' => $candidate->id,
                        'test_session_id' => $currentSession->test_session_id
                    ]);

                    if (isset($existingPrevSession)) {
                        $prevSession->candidate_id = $candidateToClone->id;
                        $prevSession->test_session_id = $existingPrevSession->test_session_id;
                        $prevSession->isPass = $existingPrevSession->isPass;
                        $prevSession->isConfirmed = $existingPrevSession->isConfirmed;
                        $prevSession->craneStatus = $existingPrevSession->craneStatus;
                        $prevSession->isGraded = $existingPrevSession->isGraded;
                        $prevSession->remarks = $existingPrevSession->remarks;
                    } else {
                        $prevSession->candidate_id = $candidateToClone->id;
                        $prevSession->test_session_id = $currentSession->test_session_id;
                        $prevSession->isGraded = 0;
                        $prevSession->craneStatus = json_encode([]);
                    }

                    $prevSession->save();

                    if ($request->post('transferWrittenAndPractical')) {
                        $testSessionType = $testSession->testSite->typeStr;
                        $linkedSessionId = false;
                        if ($testSessionType === 'Written') {
                            $linkedSessionId = $testSession->practical_test_session_id ?? false;
                        } else {
                            $linkedSession = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
                            if ($linkedSession) {
                                $linkedSessionId = $linkedSession->id;
                            }
                        }
                        if ($linkedSessionId) {
                            $linkedSession = new CandidateSession();
                            $linkedSession->candidate_id = $candidateToClone->id;
                            $linkedSession->test_session_id = $linkedSessionId;
                            $linkedSession->save();
                        }

                        if ($currentLinkedSession) {
                            $prevSession = new CandidatePreviousSession();

                            $existingPrevSession = CandidatePreviousSession::findOne([
                                'candidate_id' => $candidate->id,
                                'test_session_id' => $currentLinkedSession->test_session_id
                            ]);

                            if (isset($existingPrevSession)) {
                                $prevSession->candidate_id = $candidateToClone->id;
                                $prevSession->test_session_id = $existingPrevSession->test_session_id;
                                $prevSession->isPass = $existingPrevSession->isPass;
                                $prevSession->isConfirmed = $existingPrevSession->isConfirmed;
                                $prevSession->craneStatus = $existingPrevSession->craneStatus;
                                $prevSession->isGraded = $existingPrevSession->isGraded;
                                $prevSession->remarks = $existingPrevSession->remarks;
                            } else {
                                $prevSession->candidate_id = $candidateToClone->id;
                                $prevSession->test_session_id = $currentLinkedSession->test_session_id;
                                $prevSession->isGraded = 0;
                                $prevSession->craneStatus = json_encode([]);
                            }

                            $prevSession->save();
                        }
                    }

                    if ($request->post('ncccoFeesOverrideCurrent') !== null) {
                        $ncccoFeesOverrideCurrent = $request->post('ncccoFeesOverrideCurrent');
                        if (isset($ncccoFeesOverrideCurrent['written'])) {
                            $candidate->written_nccco_fee_override = $ncccoFeesOverrideCurrent['written'];
                        }

                        if (isset($ncccoFeesOverrideCurrent['practical'])) {
                            $candidate->practical_nccco_fee_override = $ncccoFeesOverrideCurrent['practical'];
                        }
                    }

                    if ($request->post('ncccoFeesOverrideIncoming') !== null) {
                        $ncccoFeesOverrideIncoming = $request->post('ncccoFeesOverrideIncoming');
                        if (isset($ncccoFeesOverrideIncoming['written'])) {
                            $candidateToClone->written_nccco_fee_override = $ncccoFeesOverrideIncoming['written'];
                        }

                        if (isset($ncccoFeesOverrideIncoming['practical'])) {
                            $candidateToClone->practical_nccco_fee_override = $ncccoFeesOverrideIncoming['practical'];
                        }
                    }

                    $candidate->save();
                    $candidateToClone->save();

                    if ($request->post('incomingTransactions') !== null) {
                        $incomingTransactions = $request->post('incomingTransactions');

                        foreach ($incomingTransactions as $transactionInfo) {
                            $transaction = new CandidateTransactions();
                            $transaction->candidateId = $candidateToClone->id;
                            $transaction->paymentType = $transactionInfo['typeId'] > 40 ? 10 : $transactionInfo['typeId'];
                            $transaction->amount = $transactionInfo['amount'];
                            if (isset($transactionInfo['remarks'])) {
                                $transaction->remarks = $transactionInfo['remarks'];
                            }
                            if ($transactionInfo['typeId'] > 40) {
                                $transaction->chargeType = $transactionInfo['typeId'];
                            }
                            if ($transactionInfo['typeId'] == 50) {
                                $transaction->retest_crane_selection = 'both';
                                if ($transactionInfo['retestCraneSelection']) {
                                    $transaction->retest_crane_selection = $transactionInfo['retestCraneSelection'];
                                }
                            }
                            $transaction->save();
                        }
                    }

                    if ($request->post('currentTransactionsDiff') !== null) {
                        $currentTransactionsDiff = $request->post('currentTransactionsDiff');

                        foreach ($currentTransactionsDiff['delete'] as $transactionId) {
                            $transaction = CandidateTransactions::findOne($transactionId);
                            if (isset($transaction)) {
                                $transaction->delete();
                            }
                        }

                        foreach ($currentTransactionsDiff['update'] as $transactionInfo) {
                            $transaction = CandidateTransactions::findOne($transactionInfo['id']);
                            if (isset($transaction)) {
                                $transaction->candidateId = $candidate->id;
                                $transaction->paymentType = $transactionInfo['typeId'] > 40 ? 10 : $transactionInfo['typeId'];
                                $transaction->amount = $transactionInfo['amount'];
                                if (isset($transactionInfo['remarks'])) {
                                    $transaction->remarks = $transactionInfo['remarks'];
                                }
                                if ($transactionInfo['typeId'] > 40) {
                                    $transaction->chargeType = $transactionInfo['typeId'];
                                }
                                if ($transactionInfo['typeId'] == 50) {
                                    $transaction->retest_crane_selection = 'both';
                                    if ($transactionInfo['retestCraneSelection']) {
                                        $transaction->retest_crane_selection = $transactionInfo['retestCraneSelection'];
                                    }
                                }
                                $transaction->save();
                            }
                        }

                        foreach ($currentTransactionsDiff['create'] as $transactionInfo) {
                            $transaction = new CandidateTransactions();
                            $transaction->candidateId = $candidate->id;
                            $transaction->paymentType = $transactionInfo['typeId'] > 40 ? 10 : $transactionInfo['typeId'];
                            $transaction->amount = $transactionInfo['amount'];
                            if (isset($transactionInfo['remarks'])) {
                                $transaction->remarks = $transactionInfo['remarks'];
                            }
                            if ($transactionInfo['typeId'] > 40) {
                                $transaction->chargeType = $transactionInfo['typeId'];
                            }
                            if ($transactionInfo['typeId'] == 50) {
                                $transaction->retest_crane_selection = 'both';
                                if ($transactionInfo['retestCraneSelection']) {
                                    $transaction->retest_crane_selection = $transactionInfo['retestCraneSelection'];
                                }
                            }
                            $transaction->save();
                        }
                    }

                    UtilityHelper::generateApplicationForms($candidateToClone->id, true);

                    $candidate = $candidateToClone;
                }

                if (!!$request->post('remarks')) {
                    $note = new CandidateNotes();
                    $note->candidate_id = $candidate->id;
                    $note->user_id = \Yii::$app->user->id;
                    $note->notes = $request->post('remarks');
                    $note->save();
                }

                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'nextUrl' => '/admin/candidates/update?id=' . md5($candidate->id)
                    ];
                }
                return $this->redirect('/admin/candidates/update?id=' . md5($candidate->id));
            }
        }

        if ($testSession !== false && $candidate !== false) {
            $existingCandidateTestSession = false;
            $existingTestSession = false;
            $existingCandidateTestSessionCounterpart = false;
            $existingTestSessionCounterpart = false;

            if ($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL) {
                $existingCandidateTestSession = $candidate->getPracticalSession();
                $existingCandidateTestSessionCounterpart = $candidate->getWrittenTestSession();
            } else if ($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN) {
                $existingCandidateTestSession = $candidate->getWrittenTestSession();
                $existingCandidateTestSessionCounterpart = $candidate->getPracticalSession();
            }

            if ($existingCandidateTestSession !== false) {
                $existingTestSession = $existingCandidateTestSession->getTestSession()->all()[0];
            }

            if ($existingCandidateTestSessionCounterpart !== false) {
                $existingTestSessionCounterpart = $existingCandidateTestSessionCounterpart->testSession;
            }

            $candidateArr = ArrayHelper::toArray($candidate, [
                'app\models\Candidates' => [
                    'id',
                    'name' => function($c) {
                        return $c->first_name . ' ' . $c->last_name;
                    },
                    'transactions',
                    'applicationTypeId' => 'application_type_id'
                ]
            ]);

            $applicationTypes = ApplicationType::find()->all();

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

            $testSessionCounterpart = false;

            if ($testSession->testSite->typeStr === 'Written') {
                $testSessionCounterpart = TestSession::findOne($testSession->practical_test_session_id);
            } else {
                $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $testSession->id]);
            }

            $incomingTestSession = [
                'name' => $testSession->getFullTestSessionDescription(true),
                'id' => $testSession->id
            ];

            $payload = [
                'isRetake' => $isRetake,
                'candidate' => $candidateArr,
                'applicationTypes' => $applicationTypesArr,
                'incomingTestSession' => $incomingTestSession,
                'transferType' => $_REQUEST['transferType'] ?? '',
                'bothTestSessions' => $_REQUEST['bothTestSessions'] ?? '1'
            ];

            if ($testSessionCounterpart) {
                $incomingTestSessionCounterpart = [
                    'name' => $testSessionCounterpart->getFullTestSessionDescription(true),
                    'id' => $testSessionCounterpart->id
                ];
                $payload['incomingTestSessionCounterpart'] = $incomingTestSessionCounterpart;
            }

            if ($existingTestSession) {
                $currentTestSession = [
                    'name' => $existingTestSession->getFullTestSessionDescription(true),
                    'id' => $existingTestSession->id
                ];
                $payload['currentTestSession'] = $currentTestSession;
            }

            if ($existingTestSessionCounterpart) {
                $currentTestSessionCounterpart = [
                    'name' => $existingTestSessionCounterpart->getFullTestSessionDescription(true),
                    'id' => $existingTestSessionCounterpart->id
                ];
                $payload['currentTestSessionCounterpart'] = $currentTestSessionCounterpart;
            }

            return $this->renderPartial('select-react', $payload);
        }
    }

    private function copyCandidatePrevSession($fromCandidate, $toCandidate)
    {
        $prevs = CandidatePreviousSession::find()->where('candidate_id = '.$fromCandidate->id.' order by id asc')->all();
        foreach($prevs as $prevSession){
            unset($prevSession->id);
            $prevSession->isNewRecord = true;
            $prevSession->candidate_id = $toCandidate->id;
            $prevSession->save();
        }
    }

    /**
     * Displays a single Candidates model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $message = false;
        if(isset($_REQUEST['s']) && $_REQUEST['s'] == 1){
            $message = 'Send Email Place holders';
        }

        return $this->render('view', [
            'model' => $this->findModel($id), 'message' => $message
        ]);
    }

    /**
     * Creates a new Candidates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $candidate = new Candidates();

        $appType = false;

        if(count($_POST) != 0){
            $candidate->registration_step = 0;
        }

        if ($candidate->load(Yii::$app->request->post()) && $candidate->save()) {
            $appType = ApplicationType::findOne($candidate->application_type_id);

            if ($appType->price > 0) {
                UtilityHelper::addCandidateInitialApplicationCharge($candidate);
            }

            $customFormSetup = $candidate->getCandidateFormSetup();
            $candidate->custom_form_setup = json_encode($customFormSetup);
            $candidate->save();

            UtilityHelper::generateApplicationForms($candidate->id, true);
            return $this->redirect(['view', 'id' => md5($candidate->id)]);
        } else {
            if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
                $candidateId = $_REQUEST['id'];
                $candidates = Candidates::find()->where("md5(id) = '".$candidateId."'")->all();
                if($candidates){
                    $candidate = $candidates[0];
                    unset($candidate->id);
                    $candidate->isNewRecord = true;
                    return $this->render('create', ['model' => $candidate]);
                }
                return $this->goBack();
            }
            return $this->render('create', []);
        }
    }

    public function actionCreateSimple()
    {
        $candidate = new Candidates();

        $appType = false;
        if (count($_POST) != 0) {
            $candidate->registration_step = 0;
        }

        if ($candidate->load(Yii::$app->request->post()) && $candidate->save()) {
            $appType = ApplicationType::findOne($candidate->application_type_id);
            UtilityHelper::addCandidateInitialApplicationCharge($candidate);

            if (isset($_POST['testSessionId'])) {
                $testSessionId = $_POST['testSessionId'];
                $testSession = TestSession::findOne($testSessionId);

                if ($testSession !== false && $candidate !== false) {
                    $existingTestSession = false;

                    if ($existingTestSession === false) {
                        $existingTestSession = new CandidateSession();
                    }

                    $existingTestSession->candidate_id = $candidate->id;
                    $existingTestSession->test_session_id = $testSession->id;
                    $existingTestSession->save();
                }
            }
            UtilityHelper::generateApplicationForms($candidate->id, true);
            $resp = [];
            $resp['status'] = 1;
            echo json_encode($resp);
            die;

        } else {
            return $this->renderPartial('create-simple', []);
        }
    }

    public function actionBulkRegister()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {

            $currentDateTime = new \DateTime();

            $testSites = TestSite::find()
                ->select(['id', 'name', 'city', 'state'])
                ->where('type=2')
                ->asArray()
                ->all();

            $testSessions = TestSession::find()
                ->select(['id', 'test_site_id', 'start_date', 'end_date', 'session_number'])
                ->where(['>=', 'start_date', $currentDateTime->format('Y-m-d H:i:s')])
                // ->andWhere(['is not', 'practical_test_session_id', null])
                ->asArray()
                ->all();

            $applicationTypes = ApplicationType::find()
                ->select(['id', 'keyword', 'price', 'description'])
                ->where('isArchived=0')
                ->asArray()
                ->all();

            $promoCodes = PromoCodes::find()
                ->select(['id', 'code', 'discount', 'assignedToName'])
                ->where('archived=0')
                ->asArray()
                ->all();

            $this->layout = 'main-fullwidth';
            return $this->render('bulk-register', [
                'applicationTypes' => $applicationTypes,
                'promoCodes' => $promoCodes,
                'testSites' => $testSites,
                'testSessions' => $testSessions
            ]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $payload = $request->post();
        $resp = [];

        $discount = 0;
        $isPurchaseOrder = false;
        $promoCode = PromoCodes::findOne(['code' => $payload['promo_code']]);
        if ($promoCode != null) {
            $discount = $promoCode->discount;
            $isPurchaseOrder = $promoCode->isPurchaseOrder;
        }

        $testSession = TestSession::findOne($payload['test_session_id']);

        if (!isset($testSession)) {
            throw new \yii\web\NotFoundHttpException('Test Session not found.');
        }
        $candidates = [];

        if (isset($testSession->practical_test_session_id)) {
            $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $testSession->id . ', ' . $testSession->practical_test_session_id .')) AND isArchived = 0 ORDER BY last_name')->all();
        } else {
            $candidates = Candidates::findBySql('SELECT * FROM candidates WHERE id IN (SELECT candidate_id FROM candidate_session WHERE test_session_id IN (' . $testSession->id . ', (SELECT id FROM test_session WHERE practical_test_session_id = '. $testSession->id .'))) AND isArchived = 0 ORDER BY last_name')->all();
        }

        foreach ($payload['student_info'] as $studentInfo) {
            $isStudentInClass = array_reduce($candidates, function($acc, $candidate) use($studentInfo) {
                $studentFound = $candidate->first_name == $studentInfo['Candidates']['first_name'] && $candidate->last_name == $studentInfo['Candidates']['last_name'] && $candidate->email == $studentInfo['Candidates']['email'];
                if ($studentFound) {
                    return true;
                }
                return $acc;
            }, false);

            if (!$isStudentInClass) {
                $student = new Candidates();
                $student->load($studentInfo);
                $student->application_type_id = $payload['application_type_id'];
                $appType = ApplicationType::findOne($payload['application_type_id']);
                $student->isPurchaseOrder = $isPurchaseOrder ? 1 : 0;

                if ($promoCode != null) {
                    $student->referralCode = $promoCode->code;
                }

                if ($student->save(false)) {
                    UtilityHelper::addCandidateInitialApplicationCharge($student);

                    if ($discount != 0) {
                        $studentTransaction = new CandidateTransactions();
                        $studentTransaction->transactionId = $promoCode;
                        $studentTransaction->paymentType = CandidateTransactions::TYPE_PROMO;
                        $studentTransaction->amount = $discount;
                        $studentTransaction->candidateId = $student->id;
                        $studentTransaction->save();
                    }

                    $candidateSession = new CandidateSession();
                    $candidateSession->candidate_id = $student->id;
                    $candidateSession->test_session_id = $payload['test_session_id'];
                    $candidateSession->save();

                    $appType = ApplicationType::findOne($student->application_type_id);
                    $isRecert = $appType->isRecertify == 1 ? true : false;

                    if (!$isRecert) {
                        $testSession = TestSession::findOne($candidateSession->test_session_id);
                        if ($testSession != null && $testSession->practical_test_session_id != '') {
                            $practicalSession = TestSession::findOne($testSession->practical_test_session_id);
                            if ($practicalSession != null) {
                                $candidatePracticalSession = new CandidateSession();
                                $candidatePracticalSession->candidate_id = $student->id;
                                $candidatePracticalSession->test_session_id = $practicalSession->id;
                                $candidatePracticalSession->save();
                            }
                        }
                    }

                    UtilityHelper::generateApplicationFormsNew($student->id);

                    $sentEmail = false;

                    // if ($payload['send_notification_email']) {
                    //     NotificationHelper::notifySendUserSuccess($student->id);
                    //     $sentEmail = true;
                    // }

                    array_push($resp, [ 'data' => $studentInfo, 'status' => 'Success!', 'email_sent' => $sentEmail ]);
                } else {
                    array_push($resp, [ 'data' => $studentInfo, 'status' => 'Failure!']);
                }
            }
        }

        $zipPath = UtilityHelper::generateApplicationFormsZip($payload['test_session_id']);

        $zipUrl = str_replace(Yii::getAlias('@webroot'), '', $zipPath . '/app-forms.zip');

        $response = [
            'students' => $resp,
            'zipUrl' => $zipUrl
        ];

        return $response;
    }

    public function actionPreviewBulkRegister()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return $this->render('bulk-register');
        }

        $excelUpload = PhpSpreadsheet\IOFactory::load($_FILES[0]['tmp_name'])->getActiveSheet();
        $resp = [
            'table' => $excelUpload->toArray(null, true, true, true),
            'highestColumn' => $excelUpload->getHighestColumn()
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $resp;
    }

    public function actionBulkRegisterLegacy()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            $this->layout = 'main-react';

            return $this->render('bulk-register-legacy');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $postData = $request->post();
        $options = $postData['options'] ?? [];

        $reader = new PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $sheetNames = $spreadsheet->getSheetNames();

        if (isset($options['sheetNamesOnly']) && $options['sheetNamesOnly']) {
            return $sheetNames;
        }

        if (isset($options['worksheet'])) {
            $worksheet = $spreadsheet->getSheetByName($options['worksheet']);
            return $worksheet->toArray(null, false, false, true);
        }

        $table = [];

        foreach ($sheetNames as $sheetName) {
            $worksheet = $spreadsheet->getSheetByName($sheetName);
            $table[$sheetName] = $worksheet->toArray(null, false, false, true);
        }

        $resp = [
            'table' => $table
        ];

        return $resp;
    }

    public function actionSendappform()
    {
        $resp = array();
        $resp['status'] = 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $candidateId = $_POST['id'];
            $candidate = $this->findModel($candidateId);
            if($candidate != null){
                // NotificationHelper::notifySendUserUpdatedForm($candidate);
                $resp['status'] = 1;
            }
        }
        echo json_encode($resp);
        die;
    }

    public function actionGenerateCerts($id, $instructor, $classDates)
    {
        $candidate = Candidates::findOne($id);

        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $certFilePath =  UtilityHelper::generateCertificate($candidate->id, false, [
            'instructorName' => $instructor,
            'certDate' => $classDates
        ]);

        return \Yii::$app->response->sendFile($certFilePath);
    }

    /**
     * Updates an existing Candidates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //var_dump(Yii::$app->request->post());exit;
        if(count($_POST) && isset($_POST['reset']) && $_POST['reset'] == 1){
            $model->custom_form_setup = json_encode([]);
            $model->signedForms = json_encode([]);

            $model->save();
            $targetPath = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$model->getFolderDirectory().'/signed/';
            $uploadDirAttachment = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$model->getFolderDirectory().'/attachments/';
            UtilityHelper::createPath($uploadDirAttachment);
            if ( is_dir($targetPath) && $handle = opendir($targetPath)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && $entry != "confirmation") {
                        copy($targetPath.$entry, $uploadDirAttachment.$entry);
                        unlink($targetPath.$entry);
                    }
                }
                closedir($handle);
            }

            UtilityHelper::generateApplicationFormsNew($model->id);
            return $this->redirect(['view', 'id' => md5($model->id), 's' => 1]);
        } else if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $uploadDir = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$model->getFolderDirectory().'/signed/';

            $forms = isset($_POST['form']) ? $_POST['form'] : array();
            $customFormSetup = json_decode($model->custom_form_setup, true);;
            foreach($forms as $customForm){
                $dynamicFields = isset($_POST[$customForm]) ? $_POST[$customForm] : array();
                $customFormSetup[$customForm] = $dynamicFields;
            }

            $signedForms = json_decode($model->signedForms, true);

            foreach($_FILES as $key => $up){
                if(isset($_FILES[$key]) && $_FILES[$key]['error'] == 0){
                    UtilityHelper::createPath($uploadDir);
                    if(move_uploaded_file($_FILES[$key]['tmp_name'], $uploadDir.$_FILES[$key]['name'])){
                        $signedForms[$key] = $_FILES[$key]['name'];
                    }

                } else if($model->getSignedForm($key) !== false){
                    $signedForms[$key] = $model->getSignedForm($key);
                }
            }
            $model->custom_form_setup = json_encode($customFormSetup);
            $model->signedForms = json_encode($signedForms);
            $model->save();
            UtilityHelper::generateApplicationFormsNew($model->id);
            return $this->redirect(['view', 'id' => md5($model->id), 's' => 1]);
        } else {
            $message = false;
            if (isset($_REQUEST['s']) && $_REQUEST['s'] == 2) {
                $message = 'Signed Form Deleted Successfully';
            }

            $updatedModel = $this->findModel($id);



            return $this->render('update', [
                'model' => $updatedModel, 'message' => $message
            ]);
        }
    }

    public function actionUpdateJson($id)
    {
        $candidate = Candidates::findOne($id);
        $candidate->attributes = \Yii::$app->request->post();
        $candidate->save();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $candidate;
    }

    public function actionUpdateGradesJson($candidateId, $testSessionId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = \Yii::$app->request->post();

        $candidate = Candidates::findOne($candidateId);
        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $candidate->updateGrades($postData['grades']);

        return [
            'status' => 'OK'
        ];
    }

    public function actionBatchUpdateGradesJson()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $postData = \Yii::$app->request->post();

        $candidateIds = $postData['candidateIds'];
        $grades = $postData['grades'];

        foreach ($candidateIds as $id) {
            $candidate = Candidates::findOne($id);
            if (isset($candidate)) {
                $candidate->updateGrades($grades);
            }
        }

        return [
            'status' => 'OK'
        ];
    }

    public function actionUpdateTransactionBatchJson($candidateId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $candidate = Candidates::findOne($candidateId);
        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $requestMethod = Yii::$app->request->method;

        if ($requestMethod == 'POST') {
            $currentTransactionsDiff = \Yii::$app->request->post();

            foreach ($currentTransactionsDiff['delete'] as $transactionId) {
                $transaction = CandidateTransactions::findOne($transactionId);
                if (isset($transaction)) {
                    $transaction->delete();
                }
            }

            foreach ($currentTransactionsDiff['update'] as $transactionInfo) {
                $transaction = CandidateTransactions::findOne($transactionInfo['id']);
                if (isset($transaction)) {
                    $transaction->candidateId = $candidate->id;
                    $transaction->paymentType = $transactionInfo['typeId'] > 40 ? 10 : $transactionInfo['typeId'];
                    $transaction->amount = $transactionInfo['amount'];
                    if (isset($transactionInfo['remarks'])) {
                        $transaction->remarks = $transactionInfo['remarks'];
                    }
                    if ($transactionInfo['typeId'] > 40) {
                        $transaction->chargeType = $transactionInfo['typeId'];
                    }
                    if ($transactionInfo['typeId'] == 50) {
                        $transaction->retest_crane_selection = 'both';
                        if ($transactionInfo['retestCraneSelection']) {
                            $transaction->retest_crane_selection = $transactionInfo['retestCraneSelection'];
                        }
                    }
                    $transaction->save();
                }
            }

            foreach ($currentTransactionsDiff['create'] as $transactionInfo) {
                $transaction = new CandidateTransactions();
                $transaction->candidateId = $candidate->id;
                $transaction->paymentType = $transactionInfo['typeId'] > 40 ? 10 : $transactionInfo['typeId'];
                $transaction->amount = $transactionInfo['amount'];
                if (isset($transactionInfo['remarks'])) {
                    $transaction->remarks = $transactionInfo['remarks'];
                }
                if ($transactionInfo['typeId'] > 40) {
                    $transaction->chargeType = $transactionInfo['typeId'];
                }
                if ($transactionInfo['typeId'] == 50) {
                    $transaction->retest_crane_selection = 'both';
                    if ($transactionInfo['retestCraneSelection']) {
                        $transaction->retest_crane_selection = $transactionInfo['retestCraneSelection'];
                    }
                }
                $transaction->save();
            }

            return [
                'status' => 'OK',
                'transactions' => $candidate->transactions
            ];
        }

        throw new \yii\web\MethodNotAllowedHttpException('Invalid HTTP method.');
    }

    public function actionUpdateTransactionJson($candidateId, $transactionId = null, $pending = false)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $isPending = !!$pending;
        $candidate = Candidates::findOne($candidateId);

        if (!isset($candidate)) {
            throw new \yii\web\NotFoundHttpException('Candidate not found.');
        }

        $transaction = null;
        if (isset($transactionId)) {
            if ($isPending) {
                $transaction = PendingTransaction::findOne(['id' => $transactionId, 'candidate_id' => $candidate->id ]);
            } else {
                $transaction = CandidateTransactions::findOne(['id' => $transactionId, 'candidateId' => $candidate->id ]);
            }

            if (!isset($transaction)) {
                if ($isPending) {
                    throw new \yii\web\NotFoundHttpException('Pending Transaction not found for Candidate ' . $candidate->id . '.');
                } else {
                    throw new \yii\web\NotFoundHttpException('Transaction not found for Candidate ' . $candidate->id . '.');
                }
            }
        }

        $requestMethod = Yii::$app->request->method;

        if ($requestMethod == 'POST') {
            $postData = \Yii::$app->request->post();

            if (!isset($transaction)) {
                if ($isPending) {
                    $transaction = new PendingTransaction();
                } else {
                    $transaction = new CandidateTransactions();
                }
            }

            $transaction->attributes = $postData;
            if ($isPending) {
                $transaction->candidate_id = $candidate->id;
            } else {
                $transaction->candidateId = $candidate->id;
            }

            if (!isset($transaction->amount)) {
                $transaction->amount = CandidateTransactions::DEFAULT_CHARGES[$transaction->chargeType];
            }

            if ($transaction->save()) {
                if ($transaction->chargeType == CandidateTransactions::SUBTYPE_ADD_PRACTICE_TIME) {
                    $practiceTimeCredits = (float) number_format($transaction->amount / 125, 2);
                    if (isset($candidate->practice_time_credits)) {
                        $candidate->practice_time_credits += $practiceTimeCredits;
                        $candidate->save();
                    } else {
                        $candidate->practice_time_credits = $practiceTimeCredits;
                    }
                    $candidate->save();
                }

                return $transaction;
            }

            return [
                'status' => 'Fail'
            ];
        }

        if ($requestMethod == 'DELETE') {
            if (isset($transaction)) {
                $type = $transaction->chargeType ?? $transaction->type ?? null;

                if ($type == CandidateTransactions::SUBTYPE_ADD_PRACTICE_TIME) {
                    $practiceTimeCredits = (float) number_format($transaction->amount / 125, 2);
                    if (isset($candidate->practice_time_credits)) {
                        $candidate->practice_time_credits -= $practiceTimeCredits;
                        $candidate->save();
                    }
                }

                $transaction->delete();

                return [
                    'status' => 'OK'
                ];
            }

            throw new \yii\web\NotFoundHttpException('Transaction not found.');
        }

        throw new \yii\web\MethodNotAllowedHttpException('Invalid HTTP method.');
    }

    public function actionDeleteTransaction($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $transaction = CandidateTransactions::findOne($id);

        if (!isset($transaction)) {
            throw new \yii\web\NotFoundHttpException('Transaction not found.');
        }

        $transaction->delete();

        return [
            'status' => 'deleted',
            'id' => $id
        ];
    }

    /**
     * Deletes an existing Candidates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSearch()
    {
        return $this->render('search');
    }

    /**
     * Finds the Candidates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Candidates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
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
}
