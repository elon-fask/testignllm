<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_form".
 *
 * @property integer $id
 * @property integer $application_form_template_id
 * @property integer $application_form_file_id
 * @property integer $candidate_id
 * @property integer $test_session_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApplicationFormFile $applicationFormFile
 * @property ApplicationFormTemplate $applicationFormTemplate
 * @property Candidates $candidate
 * @property TestSession $testSession
 * @property ApplicationFormFieldValue[] $applicationFormFieldValues
 */
class ApplicationForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_form_template_id', 'application_form_file_id', 'candidate_id', 'test_session_id', 'created_at', 'updated_at'], 'required'],
            [['application_form_template_id', 'application_form_file_id', 'candidate_id', 'test_session_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['application_form_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationFormFile::className(), 'targetAttribute' => ['application_form_file_id' => 'id']],
            [['application_form_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationFormTemplate::className(), 'targetAttribute' => ['application_form_template_id' => 'id']],
            [['candidate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Candidates::className(), 'targetAttribute' => ['candidate_id' => 'id']],
            [['test_session_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestSession::className(), 'targetAttribute' => ['test_session_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_form_template_id' => 'Application Form Template ID',
            'application_form_file_id' => 'Application Form File ID',
            'candidate_id' => 'Candidate ID',
            'test_session_id' => 'Test Session ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormFile()
    {
        return $this->hasOne(ApplicationFormFile::className(), ['id' => 'application_form_file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormTemplate()
    {
        return $this->hasOne(ApplicationFormTemplate::className(), ['id' => 'application_form_template_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormFieldValues()
    {
        return $this->hasMany(ApplicationFormFieldValue::className(), ['application_form_id' => 'id']);
    }
}
