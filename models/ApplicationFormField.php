<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_form_field".
 *
 * @property integer $id
 * @property integer $application_form_file_id
 * @property string $pdf_label
 * @property string $type
 * @property integer $archived
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApplicationFormFile $applicationFormFile
 * @property ApplicationFormFieldValue[] $applicationFormFieldValues
 * @property ApplicationFormTemplateFieldValue[] $applicationFormTemplateFieldValues
 */
class ApplicationFormField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_form_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_form_file_id', 'pdf_label', 'type', 'created_at', 'updated_at'], 'required'],
            [['application_form_file_id', 'archived'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['pdf_label', 'type'], 'string', 'max' => 255],
            [['application_form_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationFormFile::className(), 'targetAttribute' => ['application_form_file_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_form_file_id' => 'Application Form File ID',
            'pdf_label' => 'Pdf Label',
            'type' => 'Type',
            'archived' => 'Archived',
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
    public function getApplicationFormFieldValues()
    {
        return $this->hasMany(ApplicationFormFieldValue::className(), ['application_form_field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormTemplateFieldValues()
    {
        return $this->hasMany(ApplicationFormTemplateFieldValue::className(), ['application_form_field_id' => 'id']);
    }
}
