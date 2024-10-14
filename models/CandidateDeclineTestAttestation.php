<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_decline_test_attestation".
 *
 * @property int $id
 * @property int $candidate_id
 * @property int $test_session_id
 * @property string $crane
 * @property string $s3_key
 * @property string $created_at
 *
 * @property Candidates $candidate
 * @property TestSession $testSession
 */
class CandidateDeclineTestAttestation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_decline_test_attestation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['candidate_id', 'test_session_id', 'crane', 's3_key'], 'required'],
            [['candidate_id', 'test_session_id'], 'integer'],
            [['candidate_id', 'test_session_id', 'crane', 's3_key', 'created_at'], 'safe'],
            [['crane', 's3_key'], 'string', 'max' => 255],
            ['crane', 'in', 'range' => ['fx', 'sw']],
            [['candidate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidates::className(), 'targetAttribute' => ['candidate_id' => 'id']],
            [['test_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestSession::className(), 'targetAttribute' => ['test_session_id' => 'id']]
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
            'crane' => 'Crane',
            's3_key' => 'S3 Key',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandidate()
    {
        return $this->hasOne(Candidates::className(), ['id' => 'candidate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSession()
    {
        return $this->hasOne(TestSession::className(), ['id' => 'test_session_id']);
    }
}
