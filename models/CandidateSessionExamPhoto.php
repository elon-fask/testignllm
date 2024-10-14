<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_session_exam_photo".
 *
 * @property integer $id
 * @property integer $candidateId
 * @property integer $testSessionId
 * @property integer $isDeleted
 * @property integer $uploadedBy
 * @property string $date_created
 */
class CandidateSessionExamPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_session_exam_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidateId', 'testSessionId', 's3_key'], 'required'],
            [['s3_key'], 'string'],
            [['candidateId', 'testSessionId', 'isDeleted', 'uploadedBy'], 'integer'],
            [['s3_key', 'date_created'], 'safe'],
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
            'isDeleted' => 'Is Deleted',
            'uploadedBy' => 'Uploaded By',
            'date_created' => 'Date Created',
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->uploadedBy = \Yii::$app->user->id;

                $tz = 'America/Los_Angeles';
                $ts = time();
                $dt = new \DateTime('now', new \DateTimeZone($tz));
                $dt->setTimestamp($ts);
                $dateTimeStr = $dt->format('Y-m-d H:i:s');

                $this->date_created = $dateTimeStr;
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function getPhoto($noSuffix = false){
        $suffix = '?t='.strtotime("now");
        $path =  '/images/candidates/'.md5($this->candidateId).'/'.md5($this->testSessionId).'/'.md5($this->id);
        if(is_file( realpath(Yii::$app->basePath) .'/web'.$path)){
            if($noSuffix){
                return $path;
            }
            return $path.$suffix;
        }
        return '';
    }
}
