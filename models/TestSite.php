<?php

namespace app\models;

use Yii;
use app\helpers\UtilityHelper;

/**
 * This is the model class for table "test_site".
 *
 * @property integer $id
 * @property integer $type
 * @property string $enrollmentType
 * @property string $scheduleType
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $siteNumber
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $remark
 * @property string $date_created
 * @property string $date_updated
 *
 * @property TestSiteService[] $testSiteServices
 */
class TestSite extends \yii\db\ActiveRecord
{
	const TYPE_PRACTICAL = 1;
	const TYPE_WRITTEN = 2;
	
	const ENROLLMENT_TYPE_PUBLIC = 1;
	const ENROLLMENT_TYPE_PRIVATE = 2;
	
	const SCHEDULE_TYPE_CLOSED = 1;
	const SCHEDULE_TYPE_OPENED = 2;
	
	public $enrollmentTypeDisplay = 'aa';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_site';
    }
    
    public static function getAllTestSite($type){
        $testSites = TestSite::findAll(['type' => $type]);
        $resp = [];
        foreach($testSites as $site){
            $resp[$site->id] = $site->getTestSiteName();
        }
        return $resp;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'enrollmentType', 'scheduleType', 'address','nickname', 'city', 'state', 'zip', 'name'], 'required'],
            [['type'], 'integer'],
            [['siteNumber'], 'unique'],
            ['siteNumber', 'required', 'when' => function($model) {
                if($this->type == TestSite::TYPE_PRACTICAL){
                        return  true;
                    }
                    return false;
            }],
// Temporarily make Site Manager field optional, review on next discussion w/ stakeholder (Ticket #34)
//            ['siteManagerId', 'required', 'when' => function($model) {
//                if($this->type == TestSite::TYPE_PRACTICAL){
//                    return  true;
//                }
//                return false;
//            }],
            [['email'], 'email'],
            [[ 'preChecklistId', 'postChecklistId','writtenPostChecklistId','writtenChecklistId', 'date_created', 'date_updated', 'siteNumber', 'uniqueCode', 'siteManagerId'], 'safe'],
            [['enrollmentType', 'scheduleType', 'city', 'state', 'zip', 'siteNumber', 'phone', 'fax', 'email'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 250],
            [['nickname'], 'string', 'max' => 250],
            [['remark'], 'string', 'max' => 2500]
        ];
    }
	public function enrollmentTypeDisplay(){
		return 'sss';
	}
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'enrollmentType' => 'Enrollment Type',
            'scheduleType' => 'Schedule Type',
            'address' => 'Address',
            'nickname' => 'Nickname',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'siteNumber' => 'Site Number',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'email' => 'Email',
            'remark' => 'Remark',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'name' => 'Name',
            'preChecklistId' => 'Pre ChecklistTemplate',
            'postChecklistId' => 'Post ChecklistTemplate'
        ];
    }
    public function getEnrollmentTypeDescription(){
        foreach(UtilityHelper::getEnrollmentTypes() as $key => $type){
            if($this->enrollmentType == $key){
                return $type;
            }
        }
        return '';
    }
    public function getScheduleTypeDescription(){
        foreach(UtilityHelper::getScheduleTypes() as $key => $type){
            if($this->scheduleType == $key){
                return $type;
            }
        }
        return '';
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSiteServices()
    {
        return $this->hasMany(TestSiteService::className(), ['test_site_id' => 'id']);
    }

    public function getTestSessions()
    {
        return $this->hasMany(TestSession::className(), ['test_site_id' => 'id']);
    }
    
    public function getTestSiteName(){
       if($this->type == self::TYPE_PRACTICAL)
    	   return $this->name.' - '.$this->city.', '.$this->state.' : '.$this->siteNumber;
        else{
            return $this->name.' - '.$this->city.', '.$this->state;
        }
    }

    public function getTypeStr()
    {
        return $this->type == self::TYPE_PRACTICAL ? 'Practical' : 'Written';
    }



    public function getLocStr()
    {
        return $this->city.', '.$this->state;
    }

    public function getTestSiteLocation($data = null){
        /*wroten from me*/
        if(!empty($data)){
            $test = $this->nickname;
            return $test;
        }else{
            return $this->city.', '.$this->state;
        }

    }

    public function getCompleteTestSiteLocation(){
        $location = '';
        if($this->address != ''){
            $location .= $this->address;
        }
        if($this->city != ''){
            if($location != ''){
                $location .= ', ';
            }
            $location .= $this->city;
        }
        if($this->state != ''){
            if($location != ''){
                $location .= ', ';
            }
            $location .= $this->state;
        }
        if($this->zip != ''){
            if($location != ''){
                $location .= ', ';
            }
            $location .= $this->zip;
        }
        return $location;
    }
    
    public function getTestSiteLocationForRegistration(){
        return $this->city.', '.$this->state." - ".$this->name;
    }
    
    public function hasAppServiceType($appTypeId){
    	$testSiteService = TestSiteService::find()->where('test_site_id = '.$this->id.' and application_type_id = '.$appTypeId)->all();
    	
    	if(count($testSiteService) > 0)
    		return true;
    	return false;
    }
    
    public function getAvailableCranes(){
        return Cranes::findAll(['testSiteId' => $this->id, 'isDeleted' => 0]);
    }

    public function getTimeZone()
    {
        $tzMappings = [
            'CA' => 'America/New_York',
            'ID' => 'America/New_York',
            'TX' => 'America/New_York',
            'HI' => 'America/New_York',
            'FL' => 'America/New_York'
        ];

        if (array_key_exists($this->state, $tzMappings)) {
            return $tzMappings[$this->state];
        }

        return 'America/New_York';
    }
    
    public function propagateChecklistToSession(){
        if($this->type == self::TYPE_PRACTICAL){
            ;
            /*
            if($this->preChecklistId > 0){
                $testSessionList = TestSession::findAll(['test_site_id' => $this->id]);
                foreach($testSessionList as $testSession){
                    if($testSession->preChecklistId > 0){
                        continue;
                    }else{
                        //we need to assign it
                        $originalCheckListId = $testSession->preChecklistId;
                        $testSession->preChecklistId = $this->preChecklistId;
                        $testSession->save(false, ['preChecklistId']);
                        //var_dump($testSession->errors);
                        $testSession->doPropagateCheckList(ChecklistTemplate::TYPE_PRE);
                    }
                }
            }
            
            if($this->postChecklistId > 0){
                $testSessionList = TestSession::findAll(['test_site_id' => $this->id]);
                foreach($testSessionList as $testSession){
                    if($testSession->postChecklistId > 0){
                        continue;
                    }else{
                        //we need to assign it
                        $originalCheckListId = $testSession->postChecklistId;
                        $testSession->postChecklistId = $this->postChecklistId;
                        $testSession->save();
                        $testSession->doPropagateCheckList(ChecklistTemplate::TYPE_POST);
                    }
                }
            }
            */
        }else{
            if($this->writtenChecklistId > 0){
                $testSessionList = TestSession::findAll(['test_site_id' => $this->id]);
                foreach($testSessionList as $testSession){
                    if($testSession->writtenChecklistId > 0){
                        continue;
                    }else{
                        //we need to assign it
                        $originalCheckListId = $testSession->writtenChecklistId;
                        $testSession->writtenChecklistId = $this->writtenChecklistId;
                        $testSession->save();
                        //$testSession->doPropagateCheckList(ChecklistTemplate::TYPE_WRITTEN);
                    }
                }
            }
            if($this->writtenPostChecklistId > 0){
                $testSessionList = TestSession::findAll(['test_site_id' => $this->id]);
                foreach($testSessionList as $testSession){
                    if($testSession->writtenPostChecklistId > 0){
                        continue;
                    }else{
                        //we need to assign it
                        $originalCheckListId = $testSession->writtenPostChecklistId;
                        $testSession->writtenPostChecklistId = $this->writtenPostChecklistId;
                        $testSession->save();
                        //$testSession->doPropagateCheckList(ChecklistTemplate::TYPE_WRITTEN);
                    }
                }
            }
        }
    }

    public static function findOrCreateFromNameAndAddress($type, $name, $address = null, $siteCode = null) {
        if ($type === self::TYPE_WRITTEN) {
            $writtenTestSite = TestSite::findOne(['name' => $name]);

            if (!isset($writtenTestSite) && isset($address)) {
                try {
                    $addressArr = explode(', ', $address);
                    $zipState = explode(' ', $address[2]);

                    $writtenTestSite = new TestSite();
                    $writtenTestSite->type = $type;
                    $writtenTestSite->enrollmentType = self::ENROLLMENT_TYPE_PRIVATE;
                    $writtenTestSite->scheduleType = self::SCHEDULE_TYPE_CLOSED;
                    $writtenTestSite->address = $addressArr[0];
                    $writtenTestSite->city = $addressArr[1];
                    $writtenTestSite->state = $zipState[0];
                    $writtenTestSite->zip = $zipState[1];
                    $writtenTestSite->save(false);
                } catch (Exception $e) {
                    return null;
                }
            }

            return $writtenTestSite;
        }

        $practicalTestSite = null;

        if (isset($siteCode)) {
            $practicalTestSite = TestSite::findOne(['siteNumber' => $siteCode]);
        }

        if (!isset($practicalTestSite) && isset($siteCode) && isset($name) && isset($address)) {
            try {
                $addressArr = explode(', ', $address);
                $zipState = explode(' ', $address[2]);

                $practicalTestSite = new TestSite();
                $practicalTestSite->siteNumber = $siteCode;
                $practicalTestSite->type = $type;
                $practicalTestSite->enrollmentType = self::ENROLLMENT_TYPE_PRIVATE;
                $practicalTestSite->scheduleType = self::SCHEDULE_TYPE_CLOSED;
                $practicalTestSite->address = $addressArr[0];
                $practicalTestSite->city = $addressArr[1];
                $practicalTestSite->state = $zipState[0];
                $practicalTestSite->zip = $zipState[1];
                $practicalTestSite->save(false);
            } catch (Exception $e) {
                return null;
            }
        }

        return $practicalTestSite;
    }
}
