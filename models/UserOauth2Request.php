<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_oauth2_request".
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $state
 * @property string $prev_route
 * @property string $created_at
 *
 * @property User $user
 */
class UserOauth2Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_oauth2_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'provider', 'state'], 'required'],
            [['user_id'], 'integer'],
            [['user_id', 'provider', 'state', 'prev_route'], 'safe'],
            [['provider', 'state', 'prev_route'], 'string', 'max' => 255],
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
            'state' => 'State',
            'prev_route' => 'Prev Route',
            'created_at' => 'Created At',
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
