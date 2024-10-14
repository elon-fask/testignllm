<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_session_photos".
 *
 * @property integer $id
 * @property integer $testSessionId
 * @property string $filename
 * @property integer $savedBy
 * @property string $date_created
 */
class TestSessionPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_session_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['test_session_id', 's3_key'], 'required'],
            [['test_session_id', 'uploaded_by'], 'integer'],
            [['date_created'], 'safe'],
            [['s3_key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_session_id' => 'Test Session ID',
            's3_key' => 'AWS S3 Key',
            'uploaded_by' => 'Uploader',
            'date_created' => 'Date Created',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->uploaded_by = \Yii::$app->user->id;
                $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function getPhoto()
    {
        $suffix = '?t='.strtotime("now");
        $path =  '/images/session/'.md5($this->testSessionId).'/'.$this->filename;
        if (is_file( realpath(Yii::$app->basePath) .'/web'.$path)) {
            return $path.$suffix;
        }
        return '';
    }
    
    public static function getSessionsPhotos($filter, $resultsPerPage, $page)
    {
        $resp = array();
        $sql = '';
        if(isset($filter['fromDate']) && $filter['fromDate'] != ''){
            $dateTime = date_create_from_format('m/d/Y', urldecode($filter['fromDate']));
            $sql .= "date(date_created)  >= '".date('Y-m-d', $dateTime->getTimestamp())."'" ;
        }
        if(isset($filter['toDate']) && $filter['toDate'] != ''){
            if($sql != '')
                $sql .= ' and ';
            $dateTime = date_create_from_format('m/d/Y', urldecode($filter['toDate']));
            $sql .= "date(date_created)  <= '".date('Y-m-d', $dateTime->getTimestamp())."'" ;
        }
        if(isset($filter['testSessionId']) && $filter['testSessionId'] != ''){
            if($sql != '')
                $sql .= ' and ';
            $sql .= 'testSessionId = '.$filter['testSessionId'] ;
        }
      
        $resp['list'] = TestSessionPhoto::find()->where($sql.' order by id asc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = TestSessionPhoto::find()->where($sql)->count();
        return $resp;
    }
}
