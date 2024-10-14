<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_form_file".
 *
 * @property integer $id
 * @property string $filename
 * @property string $name
 * @property string $description
 * @property integer $archived
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApplicationForm[] $applicationForms
 * @property ApplicationFormField[] $applicationFormFields
 * @property ApplicationFormTemplate[] $applicationFormTemplates
 */
class ApplicationFormFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'application_form_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'name'], 'required'],
            [['description'], 'string'],
            [['archived'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename', 'name'], 'string', 'max' => 255],
            [['filename'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'name' => 'Name',
            'description' => 'Description',
            'archived' => 'Archived',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationForms()
    {
        return $this->hasMany(ApplicationForm::className(), ['application_form_file_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormFields()
    {
        return $this->hasMany(ApplicationFormField::className(), ['application_form_file_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationFormTemplates()
    {
        return $this->hasMany(ApplicationFormTemplate::className(), ['application_form_file_id' => 'id']);
    }
}
