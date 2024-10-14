<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_oauth2_token".
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $scope
 * @property string $realm_id
 * @property string $access_token
 * @property string $expires_at
 * @property string $refresh_token
 * @property string $token_type
 * @property string $created_at
 *
 * @property User $user
 */
class UserOauth2Token extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_oauth2_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'provider', 'scope', 'access_token', 'access_token_expires_at'], 'required'],
            [['user_id'], 'integer'],
            [['user_id', 'provider', 'scope', 'realm_id', 'access_token', 'access_token_expires_at', 'refresh_token', 'refresh_token_expires_at', 'token_type'], 'safe'],
            [['provider', 'scope', 'realm_id', 'token_type'], 'string', 'max' => 255],
            [['access_token', 'refresh_token'], 'string', 'max' => 2000],
            ['provider', 'in', 'range' => [
                'QUICKBOOKS_ONLINE'
            ]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => new \yii\db\Expression('NOW()')
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
            'user_id' => 'User ID',
            'provider' => 'Provider',
            'scope' => 'Scope',
            'realm_id' => 'Realm ID',
            'access_token' => 'Access Token',
            'access_token_expires_at' => 'Access Token Expires At',
            'refresh_token' => 'Refresh Token',
            'refresh_token_expires_at' => 'Refresh Token Expires At',
            'token_type' => 'Token Type',
            'created_at' => 'Created At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'user_id',
            'provider',
            'scope',
            'access_token_expires_at',
            'refresh_token_expires_at'
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
