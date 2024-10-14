<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_items".
 *
 * @property integer $id
 * @property integer $checklistId
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $isArchived
 * @property string $date_created
 *
 * @property ChecklistTemplate $checklist
 * @property TestSessionChecklistItems[] $testSessionChecklistItems
 */
class ChecklistItemTemplate extends \yii\db\ActiveRecord
{
    const STATUS_NOT_CHECKED = 0;
    const STATUS_PASSED = 1;
    const STATUS_FAIL = 2;
    const STATUS_NA = 3;
    
    const TYPE_PASS_FAIL = 1;
    const TYPE_NUMBER = 2;
    const TYPE_RATE_CONDITION = 3;
    const TYPE_RATE_FULLNESS = 4;
    
    const CONDITION_0 = 0;
    const CONDITION_1 = 1;
    const CONDITION_2 = 2;
    const CONDITION_3 = 3;
    const CONDITION_4 = 4;
    
    const FULLNESS_0 = 0;
    const FULLNESS_1 = 1;
    const FULLNESS_2 = 2;
    const FULLNESS_3 = 3;
    const FULLNESS_4 = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checklist_item_template';
    }
    
    public static function getStatuses(){
        return [
            self::STATUS_NOT_CHECKED => 'Not Checked',
            self::STATUS_PASSED => 'Passed',
            self::STATUS_FAIL => 'Fail',
            self::STATUS_NA => 'N/A',
        ];
    }
    public static function getItemTypes(){
        return [
            self::TYPE_PASS_FAIL => 'Pass / Fail',
            self::TYPE_NUMBER => 'Number',
            self::TYPE_RATE_CONDITION => 'Rate Condition',
            self::TYPE_RATE_FULLNESS => 'Rate Fullness'
        ];
    }
    
    public static function getAvailableRateConditionValues(){
        return [
            self::CONDITION_0 => '0',
            self::CONDITION_1 => '1',
            self::CONDITION_2 => '2',
            self::CONDITION_3 => '3',
            self::CONDITION_4 => '4'
        ];
    }
    
    public static function getAvailableRateFullValues(){
        return [
            self::FULLNESS_0 => '0',
            self::FULLNESS_1 => '1/4',
            self::FULLNESS_2 => '1/2',
            self::FULLNESS_3 => '3/4',
            self::FULLNESS_4 => '1',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checklistId', 'name'], 'required'],
            [['checklistId', 'status', 'isArchived', 'val'], 'integer'],
            [['description'], 'string'],
            [['date_created', 'val', 'itemType', 'failingScore'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['checklistId'], 'exist', 'skipOnError' => true, 'targetClass' => ChecklistTemplate::className(), 'targetAttribute' => ['checklistId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'checklistId' => 'ChecklistTemplate ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'isArchived' => 'Is Archived',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklistTemplate()
    {
        return $this->hasOne(ChecklistTemplate::className(), ['id' => 'checklistId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSessionChecklistItems()
    {
        return $this->hasMany(TestSessionChecklistItems::className(), ['checkListItemId' => 'id']);
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
}
