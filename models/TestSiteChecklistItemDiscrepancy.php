<?php

namespace app\models;

use Yii;
use app\helpers\NotificationHelper;

/**
 * This is the model class for table "test_site_checklist_item_discrepancy".
 *
 * @property integer $id
 * @property integer $testSiteId
 * @property integer $checklistItemId
 * @property integer $isCleared
 * @property integer $cleared_by
 * @property string $date_created
 *
 * @property ChecklistItemTemplate $checklistItem
 * @property User $clearedBy
 * @property TestSite $testSite
 */
class TestSiteChecklistItemDiscrepancy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_site_checklist_item_discrepancy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testSiteId', 'checklistItemId'], 'required'],
            [['testSiteId', 'checklistItemId', 'isCleared', 'cleared_by'], 'integer'],
            [['date_created', 'testSessionId', 'notes'], 'safe'],
            [['checklistItemId'], 'exist', 'skipOnError' => true, 'targetClass' => ChecklistItemTemplate::className(), 'targetAttribute' => ['checklistItemId' => 'id']],
            [['cleared_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cleared_by' => 'id']],
            [['testSiteId'], 'exist', 'skipOnError' => true, 'targetClass' => TestSite::className(), 'targetAttribute' => ['testSiteId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testSiteId' => 'Test Site ID',
            'checklistItemId' => 'ChecklistTemplate Item ID',
            'isCleared' => 'Is Cleared',
            'cleared_by' => 'Cleared By',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklistItem()
    {
        return $this->hasOne(ChecklistItemTemplate::className(), ['id' => 'checklistItemId']);
    }

    public function getType(){
        $checklistItem = ChecklistItemTemplate::findOne($this->checklistItemId);
        $checklist = ChecklistTemplate::findOne($checklistItem->checklistId);
        return $checklist->type;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClearedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'cleared_by']);
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSite()
    {
        return $this->hasOne(TestSite::className(), ['id' => 'testSiteId']);
    }
    public function getName(){
        $checkListItem = ChecklistItemTemplate::findOne($this->checklistItemId);
        if($checkListItem){
            return $checkListItem->name;
        }
        return '';
    }
    public function getSiteName(){
        $testSite = TestSite::findOne($this->testSiteId);
        if($testSite){
            return $testSite->getTestSiteName();
        }
        return 'N/A';
    }
    public static function addDiscrepancy($testSessionId, $checkListItemId, $notes = ''){
        $testSiteChecklistItemDiscrepancy = new TestSiteChecklistItemDiscrepancy();
        $testSession = TestSession::findOne($testSessionId);
        if($testSession){
            $hasTestSiteDiscrepancy = TestSiteChecklistItemDiscrepancy::findOne(['testSiteId' => $testSession->test_site_id, 'checklistItemId' => $checkListItemId, 'isCleared' => 0]);
            if($hasTestSiteDiscrepancy){
                ;
                //var_dump('has discrepancy');
            }else{
                $testSiteChecklistItemDiscrepancy->testSiteId = $testSession->test_site_id;
                $testSiteChecklistItemDiscrepancy->checklistItemId = $checkListItemId;
                $testSiteChecklistItemDiscrepancy->notes = $notes;
                $testSiteChecklistItemDiscrepancy->testSessionId = $testSessionId;
                $testSiteChecklistItemDiscrepancy->save();
                //var_dump($testSiteChecklistItemDiscrepancy->errors);
                
                //var_dump($testSiteChecklistItemDiscrepancy->errors);
            }
        }
    }
    
    public function clearDiscrepancy(){
        //we need to add a note for all the checklist item under this test site
        $user = User::findOne(\Yii::$app->user->id);
        $allSessions = TestSession::findAll(['test_site_id' => $this->testSiteId]);
        foreach($allSessions as $testSession){
            $testSessionChecklistItems = TestSessionChecklistItems::findAll(['testSessionId'=> $testSession->id, 'checkListItemId'=>$this->checklistItemId]);
            foreach($testSessionChecklistItems as $testSessionChecklistItem){
                if(strtotime($this->date_created) < strtotime($testSessionChecklistItem->date_created)){
                    $testSessionCheckListNote = new TestSessionChecklistNotes();
                    $testSessionCheckListNote->testSessionChecklistItemId = $testSessionChecklistItem->id;
                    $testSessionCheckListNote->note = 'Cleared by : '.$user->getFullName();
                    $testSessionCheckListNote->created_by = $user->id;
                    $testSessionCheckListNote->save();
                }
            }
        }
        $this->isCleared = 1;
        $this->cleared_by = $user->id;
        return $this->save();
    }
    
    public static function getAllDiscrepancy($resultsPerPage, $page){
        $resp = array();
        $resp['list'] = TestSiteChecklistItemDiscrepancy::find()->where('isCleared = 0 order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = TestSiteChecklistItemDiscrepancy::find()->where('isCleared = 0')->count();
        return $resp;
    }
}
