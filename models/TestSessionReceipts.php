<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_session_receipts".
 *
 * @property integer $id
 * @property integer $testSessionId
 * @property string $filename
 * @property string $vendorName
 * @property double $amount
 * @property string $description
 * @property integer $savedBy
 * @property string $date_created
 */
class TestSessionReceipts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_session_receipts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testSessionId', 'filename', 'vendorName', 'amount', 'description'], 'required'],
            [['testSessionId', 'savedBy'], 'integer'],
            [['amount'], 'number'],
            [['date_created'], 'safe'],
            [['filename', 'description'], 'string', 'max' => 2500],
            [['vendorName'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'testSessionId' => 'Test Session ID',
            'filename' => 'Filename',
            'vendorName' => 'Vendor Name',
            'amount' => 'Amount',
            'description' => 'Description',
            'savedBy' => 'Saved By',
            'date_created' => 'Date Created',
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->isNewRecord){
                $this->savedBy = \Yii::$app->user->id;
                $this->date_created=date('Y-m-d H:i:s', strtotime('now'));
            }
            return true;
        }else{
            return false;
        }
    }
    
    public function getPhoto(){
        //$testSession = TestSession::findOne($this->testSessionId);
        $suffix = '?t='.strtotime("now");
        $path =  '/images/session/'.md5($this->testSessionId).'/receipts/'.$this->filename;
        if(is_file( realpath(Yii::$app->basePath) .'/web'.$path)){
            return $path.$suffix;
        }
        return '';
    }
    
    public static function getAllReceipts($filter, $resultsPerPage, $page){
        $resp = array();
        $sql = '';
        //var_dump($filter);
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
    
        $resp['list'] = TestSessionReceipts::find()->where($sql.' order by id desc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = TestSessionReceipts::find()->where($sql)->count();
        return $resp;
    }
}
