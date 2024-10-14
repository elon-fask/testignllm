<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_reset_password".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip_address
 * @property string $date_requested
 * @property string $date_created
 *
 * @property User $user
 */
class UserResetPassword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_reset_password';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ip_address'], 'required'],
            [['user_id'], 'integer'],
            [['date_created'], 'safe'],
            [['ip_address', 'date_requested'], 'string', 'max' => 250]
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
            'ip_address' => 'Ip Address',
            'date_requested' => 'Date Requested',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function beforeSave($insert)
    {
    
        if (parent::beforeSave($insert)) {
    
            if ($this->isNewRecord) {
                $this->date_created = new \yii\db\Expression('NOW()');
                $this->date_requested = date('Y-m-d', strtotime('now'));
            }
            return true;
        }
    
    }
}
