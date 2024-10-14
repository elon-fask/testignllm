<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_otp".
 *
 * @property int $id
 * @property int $user_id
 * @property string $otp_token
 * @property string $expires_at
 *
 * @property User $user
 */
class UserOtp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_otp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'otp_token', 'expires_at'], 'required'],
            [['user_id'], 'integer'],
            [['expires_at'], 'safe'],
            [['otp_token'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'otp_token' => 'Otp Token',
            'expires_at' => 'Expires At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
