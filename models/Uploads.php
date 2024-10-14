<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uploads".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $isDeleted
 * @property integer $uploaded_by
 * @property string $date_created
 */
class Uploads extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uploads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isDeleted', 'uploaded_by'], 'integer'],
            [['uploaded_by'], 'required'],
            [['date_created'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 800]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'isDeleted' => 'Is Deleted',
            'uploaded_by' => 'Uploaded By',
            'date_created' => 'Date Created',
            'file' => 'File'
        ];
    }
    
    public function beforeSave($insert)
    {
    
        if (parent::beforeSave($insert)) {
    
            if ($this->isNewRecord) {
                $this->date_created = new \yii\db\Expression('NOW()');
            }
            return true;
        }
    
    }
}
