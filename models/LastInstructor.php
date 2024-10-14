<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "last_instructor".
 *
 * @property integer $id
 * @property string $instructor
 * @property string $date_created
 */
class LastInstructor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'last_instructor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instructor'], 'required'],
            [['date_created'], 'safe'],
            [['instructor'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'instructor' => 'Instructor',
            'date_created' => 'Date Created',
        ];
    }
}
