<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_session_checklist_items".
 *
 * @property integer $id
 * @property integer $testSessionId
 * @property integer $checkListItemId
 * @property integer $status
 * @property integer $type
 * @property string $date_created
 *
 * @property ChecklistItemTemplate $checkListItem
 * @property TestSession $testSession
 * @property TestSessionChecklistNotes[] $testSessionChecklistNotes
 */
class TestSessionChecklistItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_session_checklist_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testSessionId', 'type'], 'required'],
            [['testSessionId', 'checkListItemId', 'status', 'type'], 'integer'],
            [['isFailed', 'date_created', 'craneId'], 'safe'],
            [['checkListItemId'], 'exist', 'skipOnError' => true, 'targetClass' => ChecklistItemTemplate::className(), 'targetAttribute' => ['checkListItemId' => 'id']],
            [['testSessionId'], 'exist', 'skipOnError' => true, 'targetClass' => TestSession::className(), 'targetAttribute' => ['testSessionId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testSessionId' => 'Test Session ID',
            'checkListItemId' => 'Check List Item ID',
            'status' => 'Status',
            'type' => 'Type',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckListItem()
    {
        return $this->hasOne(ChecklistItemTemplate::className(), ['id' => 'checkListItemId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSession()
    {
        return $this->hasOne(TestSession::className(), ['id' => 'testSessionId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSessionChecklistNotes()
    {
        return $this->hasMany(TestSessionChecklistNotes::className(), ['testSessionChecklistItemId' => 'id']);
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            
            $checklistItem = ChecklistItemTemplate::findOne($this->checkListItemId);
            //we check if failed
            
            if($checklistItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
                if($this->status == ChecklistItemTemplate::STATUS_FAIL){
                    $this->isFailed = 1;
                    
                }else{
                    $this->isFailed = 0;
                }
            }else if($checklistItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION && $checklistItem->failingScore != null){
                if($this->status != null && intval($this->status) <= intval($checklistItem->failingScore)){
                    $this->isFailed = 1;
                }else{
                    $this->isFailed = 0;
                }
            }else if($checklistItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS){
                if($this->status != null && intval($this->status) <= intval($checklistItem->failingScore)){
                    $this->isFailed = 1;
                }else{
                    $this->isFailed = 0;
                }
            }
            
            return true;
        }else{
            return false;
        }
    }
    
    public static function getFailedChecklistItems($resultsPerPage, $page){
        $resp = array();
        $resp['list'] = TestSessionChecklistItems::find()->where('isFailed = 1  order by date_created desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = TestSessionChecklistItems::find()->where('isFailed = 1')->count();
        return $resp;
    }
    
    public function displayStatus(){
        $checklistItem = ChecklistItemTemplate::findOne($this->checkListItemId);
            //we check if failed
        
        if($checklistItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
            if($this->status == ChecklistItemTemplate::STATUS_FAIL){
                return 'Failed';
                
            }
        }else if($checklistItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION){
            return isset(ChecklistItemTemplate::getAvailableRateConditionValues()[$this->status]) ? ChecklistItemTemplate::getAvailableRateConditionValues()[$this->status] : '-';
        }else if($checklistItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS){
            return isset(ChecklistItemTemplate::getAvailableRateFullValues()[$this->status]) ? ChecklistItemTemplate::getAvailableRateFullValues()[$this->status] : '-';
        }
        
        return '-';   
    }
}
