<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_session_attendance".
 *
 * @property integer $id
 * @property integer $candidateId
 * @property integer $testSessionId
 * @property string $dateString
 * @property integer $status
 * @property integer $savedBy
 * @property string $date_created
 */
class CandidateSessionAttendance extends \yii\db\ActiveRecord
{
    const STATUS_NOT_SELECTED = 0;
    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_session_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidateId', 'testSessionId', 'dateString'], 'required'],
            [['candidateId', 'testSessionId', 'status', 'savedBy'], 'integer'],
            [['date_created'], 'safe'],
            [['dateString'], 'string', 'max' => 25],
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
            'testSessionId' => 'Test Session ID',
            'dateString' => 'Date String',
            'status' => 'Status',
            'savedBy' => 'Saved By',
            'date_created' => 'Date Created',
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
    
            if($this->isNewRecord){
                $this->date_created=date('Y-m-d', strtotime('now'));
                $this->savedBy = \Yii::$app->user->id;
            }
            return true;
        }else{
            return false;
        }
    }
    
    public static function getAttendanceStatus($candidateId, $testSessionId, $dateString){
        $attendance = CandidateSessionAttendance::findOne(['candidateId' => $candidateId, 'testSessionId' => $testSessionId, 'dateString' => $dateString]);
        if($attendance){
            return $attendance->status;
        }
        return self::STATUS_NOT_SELECTED;
    }
}
