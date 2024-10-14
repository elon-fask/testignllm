<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_test_session_class_schedule".
 *
 * @property integer $id
 * @property integer $candidateId
 * @property integer $testSessionClassScheduleId
 * @property string $date_created
 */
class CandidateTestSessionClassSchedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_test_session_class_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidateId', 'testSessionClassScheduleId'], 'required'],
            [['candidateId', 'testSessionClassScheduleId'], 'integer'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidateId' => 'Candidate ID',
            'testSessionClassScheduleId' => 'Test Session Class Schedule ID',
            'date_created' => 'Date Created',
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
}
