<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_session_class_schedule".
 *
 * @property integer $id
 * @property integer $testSessionId
 * @property string $classDate
 * @property string $startTime
 * @property string $endTime
 * @property string $date_created
 */
class TestSessionClassSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_session_class_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testSessionId', 'classDate', 'startTime', 'endTime'], 'required'],
            [['testSessionId'], 'integer'],
            [['date_created'], 'safe'],
            [['classDate', 'startTime', 'endTime'], 'string', 'max' => 20],
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
    
            if($this->isNewRecord){
                $this->date_created=date('Y-m-d', strtotime('now'));
            }
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testSessionId' => 'Test Session ID',
            'classDate' => 'Class Date',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
            'date_created' => 'Date Created',
        ];
    }
    
    public function showInfo(){
        return $this->classDate.' : '.$this->startTime.' to '.$this->endTime;
    }
}
