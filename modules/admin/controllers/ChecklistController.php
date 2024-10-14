<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\ChecklistTemplate;
use app\models\ChecklistTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ChecklistItemTemplate;
use yii\filters\AccessControl;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
use app\models\TestSessionChecklistNotes;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\helpers\NotificationHelper;
use app\models\TestSite;
use app\models\User;
use app\models\Cranes;
use app\helpers\UtilityHelper;

/**
 * ChecklistController implements the CRUD actions for ChecklistTemplate model.
 */
class ChecklistController extends CController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['viewfailedpage', 'roster-session', 'index', 'view', 'create', 'update', 'delete', 'undelete', 'session', 'add-note', 'save-note', 'view-note', 'clear-item', 'viewpage', 'send-notification', 'update-session-item'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'undelete' => ['post'],
                ],
            ],
        ];
    }

    public function actionViewfailedpage(){
        $page = $_REQUEST['page'];
        $items = TestSessionChecklistItems::getFailedChecklistItems(10, $page);
    
        return $this->renderPartial('../widgets/failed-checklist', ['items' => $items, 'currentPage' => $page]);
    }
    
    public function actionRosterSession(){
        $testSessionId = $_REQUEST['id'];
        $testSession = TestSession::findOne($testSessionId);
        return $this->renderPartial('roster-session', ['testSession' => $testSession]);
    }
    /**
     * Lists all ChecklistTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChecklistTemplateSearch();
        $params = Yii::$app->request->queryParams;
        if(!isset($params['ChecklistTemplateSearch']['isArchived'])){
            $params['ChecklistTemplateSearch']['isArchived'] = 0;
        }
        
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChecklistTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ChecklistTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChecklistTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            //var_dump($_POST);
            //we save the checklist item
            $this->doSave($_POST, $model);
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            if(isset($_REQUEST['type']) && $_REQUEST['type'] != ''){
                $model->type = $_REQUEST['type'];
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    private function doSave($postParams, $checkList){
        foreach($postParams['itemId'] as $index => $checkListItemId){
            $checkListItem = new ChecklistItemTemplate();
            $isNew = true;
            if($checkListItemId != ''){
                $checkListItem = ChecklistItemTemplate::findOne($checkListItemId);
                $isNew = false;
            }
            if($postParams['isArchived'][$index] == 1 && $isNew === true){
                continue;
            }
            
            //we save it
            $checkListItem->isArchived = intval($postParams['isArchived'][$index]);
            
            $checkListItem->name = $postParams['itemName'][$index];
            $checkListItem->description = $postParams['itemDescription'][$index];
            $checkListItem->checklistId = $checkList->id;
            /*
            if($checkList->type == ChecklistTemplate::TYPE_WRITTEN){
                $checkListItem->val = intval($postParams['val'][$index]);
            }else{
                $checkListItem->status = intval($postParams['itemStatus'][$index]);
            }
            */
            $checkListItem->itemType = $postParams['itemType'][$index];
            if($checkListItem->itemType == ChecklistItemTemplate::TYPE_NUMBER){
                $checkListItem->val = intval($postParams['val'][$index]);
            }else if($checkListItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
                $checkListItem->status = intval($postParams['itemStatus'][$index]);
            }
            //for the failing
            if($checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION && isset($postParams['failingScoreCondition'][$index])){
                $checkListItem->failingScore = intval($postParams['failingScoreCondition'][$index]);
            }else if($checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS && isset($postParams['failingScoreFullness'][$index])){
                $checkListItem->failingScore = intval($postParams['failingScoreFullness'][$index]);
            }else{
                $checkListItem->failingScore = 0;
            }
            $checkListItem->save();
            //var_dump($checkListItem->errors);
        }
    }

    /**
     * Updates an existing ChecklistTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->doSave($_POST, $model);
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

     public function actionDelete()
    {
        $id = $_POST['id'];
        $model = $this->findModel($id);//->delete();
        $model->isArchived = 1;
        $model->save();

        return $this->redirect(['/admin/checklist/index']);
    }
    
    public function actionUndelete()
    {
        $id = $_POST['id'];
        $model = $this->findModel($id);//->delete();
        $model->isArchived = 0;
        $model->save();
    
        return $this->redirect(['/admin/checklist/index']);
    }

    public function actionSession(){
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $message = false;
        $failedUpdates = [];
        $craneId = false;
        if(count($_POST) != 0){
            
            $itemIds = $_POST['itemId'];
            //$itemStatus = $_POST['itemStatus'];
            $testSessionId = 0;
            foreach($itemIds as $index => $itemId){
                $checkListItem = TestSessionChecklistItems::findOne($itemId);
                $testSessionId = $checkListItem->testSessionId;
                $item = ChecklistItemTemplate::findOne($checkListItem->checkListItemId);
                if($item->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
                    if($_POST['itemStatus'.$itemId] == ChecklistItemTemplate::STATUS_FAIL && $checkListItem->status != ChecklistItemTemplate::STATUS_FAIL){
                        //do the notifications here
                        $testSessionChecklistNote = TestSessionChecklistNotes::find()->where('testSessionChecklistItemId = '.$itemId.' order by id desc')->limit(1)->one();
                        $lastNote = 'N/A';
                        if($testSessionChecklistNote){
                            $lastNote = $testSessionChecklistNote->note;
                        }
                        TestSiteChecklistItemDiscrepancy::addDiscrepancy($checkListItem->testSessionId, $checkListItem->checkListItemId, $lastNote);
                        $failedUpdates[] = $checkListItem->checkListItemId;
                    }
                    $checkListItem->status = $_POST['itemStatus'.$itemId];
                }else if($item->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION ||
                    $item->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS){
                    if($_POST['itemStatus'.$itemId] != ''){
                        $checkListItem->status = $_POST['itemStatus'.$itemId];
                    }
                }
                
                
                $checkListItem->save();
//                var_dump($checkListItem->errors);
            }
            UtilityHelper::runCommand("notification/checklist-notify", $testSessionId);
            $message = 'Saved Successfully';
        }
        
        $testSession = $this->findSessionModelMd5($id);
        if($type == ChecklistTemplate::TYPE_PRE || $type == ChecklistTemplate::TYPE_POST){
            $craneId = $_REQUEST['craneId'];
            $crane = Cranes::findOne($craneId);
            $checkListId = false;
            if($type == ChecklistTemplate::TYPE_PRE){
                $checkListId = $crane->preChecklistId;
            }else if($type == ChecklistTemplate::TYPE_POST){
                $checkListId = $crane->postChecklistId;
            }
            $checkListItems = ChecklistItemTemplate::findAll(['checklistId' => $checkListId, 'isArchived' => 0]);
            
         
        }else if($type == ChecklistTemplate::TYPE_WRITTEN){
             $checkListItems = ChecklistItemTemplate::findAll(['checklistId' => $testSession->writtenChecklistId, 'isArchived' => 0]);
             
        }else if($type == ChecklistTemplate::TYPE_WRITTEN_POST){
             $checkListItems = ChecklistItemTemplate::findAll(['checklistId' => $testSession->writtenPostChecklistId, 'isArchived' => 0]);
             
        }
        
        return $this->render('session', ['craneId' => $craneId, 'testSession' => $testSession, 'checkListItems' => $checkListItems, 'type' => $type, 'message' => $message, 'failed' => $failedUpdates]);
    }
    
    public function actionSendNotificationOld(){
        var_dump($_REQUEST);
        $failedChecklistItemId = explode(',', $_REQUEST['failed']);
        $testSessionId = $_REQUEST['id'];
        $testSession = TestSession::findOne($testSessionId);
        if($testSession){
            foreach($failedChecklistItemId as $checkListItemId){
                $testSiteChecklistItemDiscrepancy = TestSiteChecklistItemDiscrepancy::findOne(['testSiteId' => $testSession->test_site_id, 'checklistItemId' => $checkListItemId, 'isCleared' => 0]);
                $testSite = TestSite::findOne($testSession->test_site_id);
                if($testSite){
                    NotificationHelper::notifyForDiscrepancy($testSite->siteManagerId, [$testSiteChecklistItemDiscrepancy]);
                }
                //we get all the Website Admin
                $siteAdmins = User::findAll(['role' => User::ROLE_ADMIN, 'active' => 1]);
                foreach($siteAdmins as $admin){
                    NotificationHelper::notifyForDiscrepancy($admin->id, [$testSiteChecklistItemDiscrepancy]);
                }
            }
        }
    }
    public function actionSendNotification(){
        die;
        $failedChecklistItemId = explode(',', $_REQUEST['failed']);
        $testSessionId = $_REQUEST['id'];
        $testSiteId = $_REQUEST['testSiteId'];
        /*
        $testSession = TestSession::findOne($testSessionId);
        $testSiteDiscrepancies = [];
        if($testSession){
            foreach($failedChecklistItemId as $checkListItemId){
                $testSiteChecklistItemDiscrepancy = TestSiteChecklistItemDiscrepancy::findOne(['testSiteId' => $testSession->test_site_id, 'checklistItemId' => $checkListItemId, 'isCleared' => 0]);
//                 $testSite = TestSite::findOne($testSession->test_site_id);
//                 if($testSite){
//                     NotificationHelper::notifyForDiscrepancy($testSite->siteManagerId, [$testSiteChecklistItemDiscrepancy]);
//                 }
//                 //we get all the Website Admin
//                 $siteAdmins = User::findAll(['role' => User::ROLE_ADMIN, 'active' => 1]);
//                 foreach($siteAdmins as $admin){
//                     NotificationHelper::notifyForDiscrepancy($admin->id, [$testSiteChecklistItemDiscrepancy]);
//                 }
            }
        }
        */
        $discrepancyList = TestSiteChecklistItemDiscrepancy::findAll(['isCleared' => 0, 'testSiteId' => $testSiteId]);
        
        foreach($discrepancyList as $testSiteChecklistItemDiscrepancy){
            $testSiteId = $testSiteChecklistItemDiscrepancy->testSiteId;
            $checklistItemId = $testSiteChecklistItemDiscrepancy->checklistItemId;
            if(!isset($testSiteDiscrepancies[$testSiteId])){
                $testSiteDiscrepancies[$testSiteId] = [];
            }
            $testSiteDiscrepancies[$testSiteId][] = $testSiteChecklistItemDiscrepancy;
        }
        foreach($testSiteDiscrepancies as $testSiteId => $discrepancyList){
            //we notify the site manager
            $testSite = TestSite::findOne($testSiteId);
            if($testSite){
                NotificationHelper::notifyForDiscrepancy($testSite->siteManagerId, $discrepancyList);
            }
            $siteAdmins = User::findAll(['role' => User::ROLE_ADMIN, 'active' => 1]);
            foreach($siteAdmins as $admin){
                NotificationHelper::notifyForDiscrepancy($admin->id, $discrepancyList);
            }
            //$checklistItem = ChecklistItemTemplate::findOne($checklistItemId);
            //var_dump($checklistItem->name);
        }
    }
    public function actionAddNote(){
        $model = new TestSessionChecklistNotes();
        $model->testSessionChecklistItemId = $_GET['id'];
        return $this->renderPartial('add-note', ['model' => $model]);
    } 
    
    public function actionSaveNote()
    {
        $model = new TestSessionChecklistNotes();
        $resp = [];
        $resp['status'] = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $resp['status'] = 1;            
        } 
        echo json_encode($resp);
        die;
    }
    
    public function actionViewNote()
    {
        $id = $_GET['id'];
        $allNotes = TestSessionChecklistNotes::find()->where('testSessionChecklistItemId = ' .$id.' order by id desc')->all();
        return $this->renderPartial('view-note', ['notes' => $allNotes, 'cheklistId'=>$id]);
    }
    
    public function actionClearItem()
    {
        $id = $_POST['id'];
        $resp = [];
        $discrepancy = TestSiteChecklistItemDiscrepancy::findOne($id);
        if($discrepancy->clearDiscrepancy()){
            $resp['status'] = 1;
        }else{
            $resp['status'] = 0;
        }
        
        echo json_encode($resp);
        die;
    }
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $discrepancyList = TestSiteChecklistItemDiscrepancy::getAllDiscrepancy(10, $page);
    
        return $this->renderPartial('../widgets/discrepancy', ['discrepancyList' => $discrepancyList, 'currentPage' => $page]);
    }
    
    public function actionUpdateSessionItem(){
        $resp = [];
        $resp['status'] = 0;
        $itemId = $_REQUEST['id'];
        $sessionId = $_REQUEST['sessionId'];
        $status = $_REQUEST['status'];
        /*
        $isChecked = $_REQUEST['isChecked'];
        $status = ChecklistItemTemplate::STATUS_FAIL;
        if($isChecked == 1){
            $status = ChecklistItemTemplate::STATUS_PASSED;
        }
        */
        $checklistItem = TestSessionChecklistItems::findOne(['testSessionId' => $sessionId, 'checkListItemId' => $itemId]);
        if($checklistItem == null || $checklistItem === false){
            $checklistItem = new TestSessionChecklistItems();
            $checklistItem->testSessionId = $sessionId;
            $checklistItem->checkListItemId = $itemId;
            $checkItem = ChecklistItemTemplate::findOne($itemId);
            $checklist = ChecklistTemplate::findOne($checkItem->checklistId);
            $checklistItem->type = $checklist->type;
        }
        
        $checklistItem->status = $status;
        if($checklistItem->save()){
            $resp['status'] = 1;
        }
        echo json_encode($resp);
        die;
    }
    protected function findSessionModelMd5($id)
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
    /**
     * Finds the ChecklistTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChecklistTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChecklistTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
