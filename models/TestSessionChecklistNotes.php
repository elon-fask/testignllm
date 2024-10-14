<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_session_checklist_notes".
 *
 * @property integer $id
 * @property integer $testSessionChecklistItemId
 * @property string $note
 * @property integer $created_by
 * @property string $date_created
 *
 * @property User $createdBy
 * @property TestSessionChecklistItems $testSessionChecklistItem
 */
class TestSessionChecklistNotes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_session_checklist_notes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testSessionChecklistItemId', 'created_by'], 'required'],
            [['testSessionChecklistItemId', 'created_by'], 'integer'],
            [['note'], 'string'],
            [['date_created'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['testSessionChecklistItemId'], 'exist', 'skipOnError' => true, 'targetClass' => TestSessionChecklistItems::className(), 'targetAttribute' => ['testSessionChecklistItemId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testSessionChecklistItemId' => 'Test Session ChecklistTemplate Item ID',
            'note' => 'Note',
            'created_by' => 'Created By',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSessionChecklistItem()
    {
        return $this->hasOne(TestSessionChecklistItems::className(), ['id' => 'testSessionChecklistItemId']);
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
    
    public function getFullName(){
        $user = User::findOne($this->created_by);
        if($user){
            return $user->getFullName();
        }
        return 'N/A';
    }
}
