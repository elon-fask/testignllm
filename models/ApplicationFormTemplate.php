<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_form_template".
 *
 * @property integer $id
 * @property integer $application_type_id
 * @property integer $application_form_file_id
 * @property integer $archived
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApplicationForm[] $applicationForms
 * @property ApplicationFormFile $applicationFormFile
 * @property ApplicationType $applicationType
 * @property ApplicationFormTemplateFieldValue[] $applicationFormTemplateFieldValues
 */
class ApplicationFormTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_form_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_type_id', 'application_form_file_id', 'created_at', 'updated_at'], 'required'],
            [['application_type_id', 'application_form_file_id', 'archived'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['application_form_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationFormFile::className(), 'targetAttribute' => ['application_form_file_id' => 'id']],
            [['application_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationType::className(), 'targetAttribute' => ['application_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_type_id' => 'Application Type ID',
            'application_form_file_id' => 'Application Form File ID',
            'archived' => 'Archived',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationForms()
    {
        return $this->hasMany(ApplicationForm::className(), ['application_form_template_id' => 'id']);
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
    public function getApplicationType()
    {
        return $this->hasOne(ApplicationType::className(), ['id' => 'application_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormTemplateFieldValues()
    {
        return $this->hasMany(ApplicationFormTemplateFieldValue::className(), ['application_form_template_id' => 'id']);
    }
}
