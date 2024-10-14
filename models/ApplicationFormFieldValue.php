<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_form_field_value".
 *
 * @property integer $id
 * @property integer $application_form_id
 * @property integer $application_form_field_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApplicationFormField $applicationFormField
 * @property ApplicationForm $applicationForm
 */
class ApplicationFormFieldValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_form_field_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_form_id', 'application_form_field_id', 'value', 'created_at', 'updated_at'], 'required'],
            [['application_form_id', 'application_form_field_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['application_form_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationFormField::className(), 'targetAttribute' => ['application_form_field_id' => 'id']],
            [['application_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApplicationForm::className(), 'targetAttribute' => ['application_form_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_form_id' => 'Application Form ID',
            'application_form_field_id' => 'Application Form Field ID',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormField()
    {
        return $this->hasOne(ApplicationFormField::className(), ['id' => 'application_form_field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationForm()
    {
        return $this->hasOne(ApplicationForm::className(), ['id' => 'application_form_id']);
    }
}
