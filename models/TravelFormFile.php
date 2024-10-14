<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "travel_form_file".
 *
 * @property int $id
 * @property int $travel_form_id
 * @property string $filename
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TravelForm $travelForm
 */
class TravelFormFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'travel_form_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['travel_form_id', 'filename'], 'required'],
            [['travel_form_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            [['travel_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => TravelForm::className(), 'targetAttribute' => ['travel_form_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'travel_form_id' => 'Travel Form ID',
            'filename' => 'Filename',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTravelForm()
    {
        return $this->hasOne(TravelForm::className(), ['id' => 'travel_form_id']);
    }
}
