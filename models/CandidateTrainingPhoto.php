<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate_training_photo".
 *
 * @property int $id
 * @property int $training_session_id
 * @property string $s3_key
 * @property int $uploaded_by
 * @property string $created_at
 *
 * @property CandidateTrainingSession $trainingSession
 * @property User $uploadedBy
 */
class CandidateTrainingPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidate_training_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['training_session_id', 's3_key'], 'required'],
            [['training_session_id', 'uploaded_by'], 'integer'],
            [['created_at'], 'safe'],
            [['s3_key'], 'string', 'max' => 255],
            [['training_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => CandidateTrainingSession::className(), 'targetAttribute' => ['training_session_id' => 'id']],
            [['uploaded_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uploaded_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'training_session_id' => 'Training Session ID',
            's3_key' => 'S3 Key',
            'uploaded_by' => 'Uploaded By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrainingSession()
    {
        return $this->hasOne(CandidateTrainingSession::className(), ['id' => 'training_session_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'uploaded_by']);
    }
}
