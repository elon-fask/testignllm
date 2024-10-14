<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $isArchived
 * @property string $date_created
 *
 * @property ChecklistItemTemplate[] $checklistItems
 */
class ChecklistTemplate extends \yii\db\ActiveRecord
{
    const TYPE_PRE = 1;
    const TYPE_POST = 2;
    const TYPE_PRACTICAL = 3;
    const TYPE_WRITTEN = 4; // pre
    const TYPE_WRITTEN_POST = 6;
    const TYPE_WRITTEN_CALENDAR_CHECKLIST = 5;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checklist_template';
    }

    public static function getAllChecklists($type){
        $lists = ChecklistTemplate::findAll(['type' => $type]);
        $items = [];
        foreach($lists as $checkList){
            $items[$checkList->id] = $checkList->getCheckListNameDisplay();
        }
        return $items;
    }
    public static function getTypes(){
        return [
            self::TYPE_PRE => 'Pre Checklist',
            self::TYPE_POST => 'Post Checklist',
            self::TYPE_WRITTEN => 'Pre Written Checklist',
            self::TYPE_WRITTEN_POST => 'Post Written Checklist'
        ];
    }
    public function getTypeDescription(){
        return self::getTypes()[$this->type];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'isArchived'], 'integer'],
            [['date_created'], 'safe'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'isArchived' => 'Is Archived',
            'date_created' => 'Date Created',
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChecklistItemTemplates()
    {
        return $this->hasMany(ChecklistItemTemplate::className(), ['checklistId' => 'id']);
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
    
    public function getCheckListNameDisplay(){
        if($this->isArchived == 1){
            return $this->name.' - (Archived)';
        }
        return $this->name;
    }
}
