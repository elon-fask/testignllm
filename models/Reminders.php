<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "reminders".
 *
 * @property integer $id
 * @property string $note
 * @property string $remindDate
 * @property integer $isComplete
 * @property string $date_created
 */
class Reminders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reminders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['remindDate'], 'required'],
            [['remindDate', 'date_created'], 'safe'],
            [['isComplete', 'userId'], 'integer'],
            [['note'], 'string', 'max' => 2500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'note' => 'Note',
            'remindDate' => 'Remind Date',
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
    
    public static function getUserReminders($userId, $resultsPerPage, $page){
        $resp = array(); 
        $resp['list'] = Reminders::find()->where('isComplete = 0 and userId = '.$userId.' order by remindDate asc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = Reminders::find()->where('isComplete = 0 and userId = '.$userId)->count();
        return $resp;             
    }
    
    public function getDeadlineColor(){
        if(date('Y-m-d', strtotime($this->remindDate)) == date('Y-m-d', strtotime('now'))){
            return 'today-deadline';
        }if(strtotime($this->remindDate) < strtotime('now')){
            return 'past-deadline';
        }else if(strtotime($this->remindDate) > strtotime('now') && strtotime($this->remindDate) < strtotime('+7 days')){
            return 'near-deadline';
        }   
        return '';
    }
}
