<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_session_ratings".
 *
 * @property integer $id
 * @property integer $candidateId
 * @property integer $testSessionId
 * @property string $checkin
 * @property string $checkout
 * @property integer $rating
 * @property string $date_created
 */
class CandidateTrainingSession extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_training_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id', 'test_session_id'], 'required'],
            [['candidate_id', 'test_session_id', 'grade'], 'integer'],
            [['attestation_s3_key'], 'string'],
            ['type', 'in', 'range' => ['20_MIN', '15_MIN', '5_MIN', 'PAID_PRACTICE', 'NA']],
            ['grade', 'in', 'range' => [1, 2, 3]],
            [['candidate_id', 'test_session_id', 'grade', 'attestation_s3_key', 'start_time', 'end_time', 'date_created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'candidate_id' => 'Candidate ID',
            'test_session_id' => 'Test Session ID',
            'grade' => 'Grade',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'attestation_s3_key' => 'Attestation S3 Key',
            'date_created' => 'Date Created',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'candidate_id',
            'test_session_id',
            'type',
            'grade',
            'start_time',
            'end_time',
            'attestation_s3_key',
            'date_created',
            'trainingPhotos'
        ];
    }

    public function getTrainingPhotos()
    {
        return $this->hasMany(CandidateTrainingPhoto::className(), ['training_session_id' => 'id']);
    }
}
