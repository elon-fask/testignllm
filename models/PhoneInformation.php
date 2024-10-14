<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "phone_information".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $referral
 * @property string $referralOther
 * @property integer $userId
 * @property integer $isComplete
 * @property string $date_created
 */
class PhoneInformation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone_information';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'referral', 'userId'], 'required'],
            [['userId', 'isComplete'], 'integer'],
            [['email', 'friend_email'], 'email'],
            [['date_created', 'ad_online_info'], 'safe'],
            [['name', 'email', 'phone', 'referral'], 'string', 'max' => 250],
            [['referralOther'], 'string', 'max' => 800]
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
            'email' => 'Email',
            'phone' => 'Phone',
            'referral' => 'Referral',
            'referralOther' => 'Referral Other',
            'userId' => 'User ID',
            'isComplete' => 'Is Complete',
            'date_created' => 'Date Created',
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){            
            $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            return true;
        }else{
            return false;
        }
    }
    
    public static function getUserPhone($userId, $resultsPerPage, $page){
        $resp = array();
        $resp['list'] = PhoneInformation::find()->where('isComplete = 0 and userId = '.$userId.' order by date_created desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = PhoneInformation::find()->where('isComplete = 0 and userId = '.$userId)->count();
        return $resp;
    }
}
