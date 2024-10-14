<?php

namespace app\models;

use Yii;
use app\helpers\UtilityHelper;

/**
 * This is the model class for table "test_session".
 *
 * @property integer $id
 * @property integer $test_site_id
 * @property string $enrollmentType
 * @property string $start_date
 * @property string $end_date
 * @property string $date_created
 * @property string $date_updated
 *
 * @property TestSite $testSite
 */
class TestSession extends \yii\db\ActiveRecord
{
    public $session_type;
    const SCHOOL_ACS = 'ACS';
    const SCHOOL_CCS = 'CCS';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['test_site_id', 'enrollmentType', 'start_date', 'end_date', 'school', 'test_coordinator_id'], 'required'],
            [['test_site_id', 'numOfCandidates','nick_id', 'practical_test_session_id', 'extra_days'], 'integer'],
            [['nccco_fee_notes'], 'string'],
            [['numOfCandidates'], 'integer', 'min'=>1],
            ['testing_date', 'required', 'when' => function ($model) {
                $testSite = TestSite::findOne($this->test_site_id);
                $isWritten = false;
                if ($testSite) {
                    if($testSite->type == TestSite::TYPE_WRITTEN){
                        $isWritten = true;
                    }
                }
                return $isWritten;
            }],
            ['nccco_test_fees_credit', 'number'],
            [['preChecklistId', 'postChecklistId','writtenPostChecklistId','writtenChecklistId', 'start_date', 'end_date', 'date_created', 'date_updated', 'session_type', 'session_number', 'staff_id','instructor_id', 'practical_test_session_id', 'testing_date', 'registration_close_date'], 'safe'],
            [['enrollmentType'], 'string', 'max' => 50],
            [
                ['proctor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['proctor_id' => 'id']
            ]
        ];
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if(!$this->id) return true;
        $sql = 'SELECT max(day) as maxDay FROM practical_test_schedule where test_session_id = '.$this->id;
        $maxDay = \Yii::$app->db->createCommand($sql)->queryOne();
        $maxDay = intval($maxDay['maxDay']);
        $day_diff = date_diff(date_create($this->end_date), date_create($this->start_date))->format('%d');
        if($maxDay == 0) return true;
        if( $day_diff + 1  >= $maxDay ) {
            return true;
        }
        $this->addError('date', "The test session has a schedule out of the date");

        return false ;
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['session_type']);
    }

    public function extraFields()
    {
        return ['candidates'];
    }

    public function checkDate($attribute) {
        $day_diff = date_diff(date_create($this->end_date), date_create($this->start_date))->format('%d');
        if($day_diff - 3 < $this->extra_days) {
            $this->addError('date', 'The test session has a schedule out of the date');
            return false;
        }
        return true;

    }

    public function getFolderDirectory(){
        return md5($this->id);
    }

    public function greaterThanZero($attribute,$params)
    {
        if ($this->$attribute<=0)
            $this->addError($attribute, '# of Candidates has to be greater than 0');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $prefix = 'Test Site Coordinator';
        if($this->session_type == TestSite::TYPE_WRITTEN){
            $prefix = 'Written Test Site Coordinator';
        }else if($this->session_type == TestSite::TYPE_PRACTICAL){
            $prefix = 'Practical Test Site Coordinator';
        }

        return [
            'id' => 'ID',
            'numOfCandidates' => 'Maximum Enrollment',
            'test_site_id' => 'Test Site ID',
            'nick_id' => 'Nickname',
            'enrollmentType' => 'Enrollment Type',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
            'session_type' => 'Session Type',
            'staff_id' => 'Practical Examiner',
            'proctor_id' => 'Proctor',
            'practical_test_session_id' => 'Practical Session',
            'registration_close_date' => 'Registration Close Date',
            'test_coordinator_id' => $prefix
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSite()
    {
        return $this->hasOne(TestSite::className(), ['id' => 'test_site_id']);
    }

    public function getCounterpart()
    {
        $testSessionCounterpart = null;

        if (isset($this->practical_test_session_id)) {
            $testSessionCounterpart = TestSession::findOne($this->practical_test_session_id);
        } else {
            $testSessionCounterpart = TestSession::findOne(['practical_test_session_id' => $this->id]);
        }

        return $testSessionCounterpart;
    }

    public function getCombinedIds()
    {
        $testSessionIds = [$this->id];
        $testSessionCounterpart = $this->counterpart;

        if (isset($testSessionCounterpart)) {
            $testSessionIds[] = $testSessionCounterpart->id;
        }

        return $testSessionIds;
    }

    public function getTestSessionCandidates()
    {
        return $this->hasMany(CandidateSession::className(), ['id' => 'test_session_id']);
    }

    public function getCandidates()
    {
        return $this
            ->hasMany(Candidates::className(), ['id' => 'candidate_id'])
            ->viaTable('candidate_session', ['test_session_id' => 'combinedIds']);
    }

    public function getChecklistTemplates()
    {
        return $this
            ->hasMany(ChecklistTemplate::className(), ['id' => 'checklist_id'])
            ->viaTable('test_session_checklist_template', ['test_session_id' => 'id']);
    }

    public function getChecklists()
    {
        return $this->hasMany(Checklist::className(), ['test_session_id' => 'id']);
    }

    public function getPracticalTrainingSessions()
    {
        return $this->hasMany(PracticalTrainingSession::className(), ['test_session_id' => 'id']);
    }

    public function getActiveRosters()
    {
        return $this->getRegisteredCandidates();
    }

    public function getDateRange($format = 'm/d/Y', $asArray = false)
    {
        $startDate = new \DateTimeImmutable($this->start_date);
        $endDate = new \DateTimeImmutable($this->end_date);

        if ($format === 'F j - F j') {
            $startDateMonth = date_format($startDate, 'm');
            $endDateMonth = date_format($endDate, 'm');

            $startDateStr = date_format($startDate, 'F j');
            $endDateStrFormat = $startDateMonth === $endDateMonth ? 'j' : 'F j';
            $endDateStr = date_format($endDate, $endDateStrFormat);

            return "$startDateStr - $endDateStr";
        }

        if ($asArray) {
            $numClassDays = (int) date_diff($endDate, $startDate)->format('%a');
            $result = [];

            for ($i = 0; $i <= $numClassDays; $i++) {
                $dateStr = date_format($startDate->modify("+${i} day"), $format);
                $result[] = $dateStr;
            }

            return $result;
        }

        return date_format($startDate, $format) . ' - ' . date_format($endDate, $format);
    }

    public function getDateInfo()
    {
        return UtilityHelper::jb_verbose_date_range(strtotime($this->start_date), strtotime($this->end_date));
    }

    public function getTestSiteName($data = null)
    {
        /*wroten from me*/
        if(!empty($data)){
            $testSite = TestSite::findOne($this->nick_id);
            if($testSite){
                return $testSite->getTestSiteLocation('nick_id');
            }
        }else{
            $testSite = TestSite::findOne($this->test_site_id);
            if($testSite){
                return $testSite->getTestSiteLocation();
            }
        }

        return "";
    }

    public function getTestSiteNameOnly()
    {
        $testSite = TestSite::findOne($this->test_site_id);
        if($testSite){
            return $testSite->name;
        }
        return "";
    }

    public function getTestSiteNumber()
    {
        $testSite = TestSite::findOne($this->test_site_id);
        if($testSite){
            return $testSite->siteNumber;
        }
        return "-";
    }

    public function getTestSiteAddress()
    {
        $testSite = TestSite::findOne($this->test_site_id);
        if($testSite){
            return $testSite->getCompleteTestSiteLocation();
        }
        return "-";
    }

    public function getFullTestSessionDescription($includeType = false)
    {
        $result = $this->getTestSiteName() . ': ' . $this->session_number . ' - ' . $this->getDateInfo();

        if ($includeType) {
            $isWritten = $this->getTestSessionTypeId() == TestSite::TYPE_WRITTEN;
            if ($isWritten) {
                $result .= ' (Written)';
            } else {
                $result .= ' (Practical)';
            }
        }

        return $result;
    }
    public function canAddDays() {
        $cities = array('west sacramento', 'desoto','la mirada', 'humble');
        return in_array(strtolower($this->getTestSiteName()),$cities) && strpos('PE', $this->session_number) != false;
    }
    public function getPartialTestSessionDescription()
    {
        return $this->session_number.' - '.$this->getDateInfo();
    }

    public function getIsLateFeeApplicable()
    {
        $tz = $this->testSite->timeZone;

        $testingDateStr = $this->testing_date;

        $testingDate = new \DateTime($testingDateStr, new \DateTimeZone($tz));
        $currentDate = new \DateTime('now', new \DateTimeZone($tz));

        $interval = $testingDate->diff($currentDate);

        if ($interval->days === 12 && $currentDate->format('A') === 'PM' && (int)$currentDate->format('H') >= 2) {
            return true;
        }

        return $interval->days < 12;
    }

    public function getTestSessionPhotos()
    {
        return $this->hasMany(TestSessionPhoto::className(), ['test_session_id' => 'id']);
    }

    public function isSessionPassTodayDate()
    {
        $tz = $this->testSite->timeZone;
        $endDateStr = $this->end_date;

        $endDate = new \DateTime($endDateStr, new \DateTimeZone($tz));
        $currentDate = new \DateTime('now', new \DateTimeZone($tz));

        return $endDate < $currentDate;
    }

    public function isSessionCloseRegistrationAlready()
    {
        if ($this->registration_close_date != null && $this->registration_close_date != '') {
            $closeDateStr = $this->registration_close_date;
            $tz = $this->testSite->timeZone;
            $closeDate = new \DateTime($closeDateStr, new \DateTimeZone($tz));
            $currentDate = new \DateTime('now', new \DateTimeZone($tz));
            $interval = $closeDate->getTimestamp() - $currentDate->getTimestamp();

            if ($interval < -1) {
                return true;
            }
        }

        return false;
    }

    public function getEnrollmentTypeDescription()
    {
        foreach(UtilityHelper::getEnrollmentTypes() as $key => $type){
            if($this->enrollmentType == $key){
                return $type;
            }
        }
        return '';
    }

    public function getTestSessionType()
    {
        $testSite = TestSite::findOne($this->test_site_id);
        if($testSite){
            if($testSite->type == TestSite::TYPE_PRACTICAL){
                return "Practical";
            }else if($testSite->type == TestSite::TYPE_WRITTEN){
                return "Written";
            }
        }
        return "";
    }

    public function getTestSessionTypeId()
    {
        $testSite = TestSite::findOne($this->test_site_id);
        if($testSite){
            return $testSite->type;
        }
        return "";
    }

    public function getRegisteredCandidates()
    {
        $candidatesSession = CandidateSession::find()->where('test_session_id = '.$this->id)->all();
        return $candidatesSession;
    }

    public function getNumberOfRegisteredCandidates()
    {
        return count($this->getRegisteredCandidates());
    }

    public function getAvailableSlots()
    {
        $remainingSlots = $this->numOfCandidates - count($this->getRegisteredCandidates());
        if($remainingSlots < 0){
            $remainingSlots = 0;
        }
        return $remainingSlots;
    }

    public function getStartDateDisplay()
    {
        if($this->start_date != ''){
            return date('m-d-Y', strtotime($this->start_date));
        }
        return '';
    }

    public function getEndDateDisplay()
    {
        if($this->end_date != ''){
            return date('m-d-Y', strtotime($this->end_date));
        }
        return '';
    }

    public function showEnrollmentStatusText()
    {
        $remaining = $this->getAvailableSlots();
        if($remaining > 0){
            return '('.$remaining.' slots remaining)';
        }
        return '(No Slots Available - Enrollment Closed)';
    }

    public function getStaffName($showRole = true)
    {
        if($this->staff_id != ''){
            $staff = User::findOne($this->staff_id);
            if($staff){
                return $staff->getFullName($showRole);
            }
        }
        return "-";
    }

    public function getProctorName($showRole = true)
    {
        if ($this->proctor_id != '') {
            $proctor = User::findOne($this->proctor_id);
            if ($proctor) {
                return $proctor->getFullName($showRole);
            }
        }
        return "-";
    }

    public static function getOngoingSessions()
    {
        $sessions = TestSession::find()->where("date(start_date) <= date(now()) and date(now()) <= date(end_date) order by start_date asc")->all();
        return $sessions;
    }

    public static function getUpcomingSessions($upcomingDaysAhead = 10)
    {
        $sessions = TestSession::find()->where("date(start_date) <= date(DATE_ADD(now(),INTERVAL ".$upcomingDaysAhead." DAY)) and date(start_date) > date(now())  order by start_date asc")->limit(5)->all();
        return $sessions;
    }

    public static function getStaffOngoingSessions($userId)
    {
        $dateToday = new \DateTimeImmutable('now', new \DateTimeZone('America/Los_Angeles'));
        $dateTodayStr = $dateToday->format('Y-m-d H:i:s');
        $dateInterval = new \DateInterval('P30D');
        $endDate = $dateToday->sub($dateInterval);
        $endDateTimeStr = $endDate->format('Y-m-d H:i:s');

        $sessions = TestSession::find()
            ->where(['or',
                ['staff_id' => $userId],
                ['instructor_id' => $userId],
                ['proctor_id' => $userId],
                ['test_coordinator_id' => $userId]
            ])
            ->andWhere(['between', 'start_date', $endDateTimeStr, $dateTodayStr])
            ->orderBy(['start_date' => SORT_DESC])
            ->all();
        return $sessions;
    }

    public static function getStaffUpcomingSessions($upcomingDaysAhead = 30, $userId)
    {
        $startDate = new \DateTimeImmutable('now', new \DateTimeZone('America/Los_Angeles'));
        $startDateTimeStr = $startDate->format('Y-m-d H:i:s');
        $dateInterval = new \DateInterval("P${upcomingDaysAhead}D");
        $endDate = $startDate->add($dateInterval);
        $endDateTimeStr = $endDate->format('Y-m-d H:i:s');

        $sessions = TestSession::find()
            ->where(['or',
                ['staff_id' => $userId],
                ['instructor_id' => $userId],
                ['proctor_id' => $userId],
                ['test_coordinator_id' => $userId]
            ])
            ->andWhere(['between', 'start_date', $startDateTimeStr, $endDateTimeStr])
            ->orderBy(['start_date' => SORT_ASC])
            ->all();
        return $sessions;
    }

    public static function getEnrolledInOngoingSessions()
    {
        $sessions = TestSession::find()->where("date(start_date) <= date(now()) and date(now()) <= date(end_date) order by start_date asc")->all();
        $total = 0;
        foreach($sessions as $session){
            $total += $session->getNumberOfRegisteredCandidates();
        }
        return $total;

    }

    public static function getEnrolledInOngoingSessionsTestSites()
    {
        $sessions = TestSession::find()->where("date(start_date) <= date(now()) and date(now()) <= date(end_date) order by start_date asc")->all();
        $total = 0;
        $testSiteList = [];
        foreach($sessions as $session){
            $total += $session->getNumberOfRegisteredCandidates();
            if(!isset($testSiteList[$session->test_site_id])){
                $testSiteList[$session->test_site_id] = 0;
            }
            $testSiteList[$session->test_site_id] += $session->getNumberOfRegisteredCandidates();
        }
        return $testSiteList;

    }

    public static function getEnrolledInUpcomingSessionsTestSites($upcomingDaysAhead = 10)
    {
        $allTestSites = TestSite::find()->where('')->all();
        $total = 0;
        $testSiteList = [];
        foreach($allTestSites as $testSite){
            $session = TestSession::find()->where("test_site_id = ".$testSite->id." and date(start_date) <= date(DATE_ADD(now(),INTERVAL ".$upcomingDaysAhead." DAY)) and date(start_date) > date(now())  order by start_date asc")->limit(1)->one();
            if($session){
                $total += $session->getNumberOfRegisteredCandidates();

                if(!isset($testSiteList[$session->test_site_id])){
                    $testSiteList[$session->test_site_id] = 0;
                }
                $testSiteList[$session->test_site_id] += $session->getNumberOfRegisteredCandidates();
            }
        }
        return $testSiteList;
    }

    public static function getEnrolledInUpcomingSessions($upcomingDaysAhead = 10)
    {
        $allTestSites = TestSite::find()->where('')->all();
        $total = 0;
        foreach($allTestSites as $testSite){
            $session = TestSession::find()->where("test_site_id = ".$testSite->id." and date(start_date) <= date(DATE_ADD(now(),INTERVAL ".$upcomingDaysAhead." DAY)) and date(start_date) > date(now())  order by start_date asc")->limit(1)->one();
            if($session)
                $total += $session->getNumberOfRegisteredCandidates();
        }
        return $total;
    }

    public static function getSessions($testSiteId, $resultsPerPage, $page)
    {
        $resp = array();
        $resp['list'] = TestSession::find()->where('test_site_id = '.$testSiteId.' order by start_date asc limit '.$resultsPerPage.' offset '.(($page-1)*$resultsPerPage))->all();
        $resp['count'] = TestSession::find()->where('test_site_id = '.$testSiteId)->count();
        return $resp;
    }

    public function updateAssociatdSessionSchool()
    {
        if($this->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
            $sessions = TestSession::find()->where('practical_test_session_id = ' .$this->id)->all();
            foreach($sessions as $ses){
                $ses->school = $this->school;
                $ses->save();
            }
        }else if($this->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
            if($this->practical_test_session_id != null){
                $testSession = TestSession::findOne($this->practical_test_session_id );
                if($testSession != null){
                    $testSession->school = $this->school;
                    $testSession->save();
                }

                $sessions = TestSession::find()->where('practical_test_session_id = ' .$this->practical_test_session_id . ' and id != '.$this->id)->all();

                foreach($sessions as $ses){
                    $ses->practical_test_session_id = null;
                    $ses->save();
                }
            }
        }
    }

    public function getInstructorName($showRole = true)
    {
        $staff = User::findOne($this->instructor_id);
        if($staff != null){
            return $staff->getFullName($showRole);
        }
        return '-';
    }

    public function getTestCoordinatorName($showRole = true)
    {
        $staff = User::findOne($this->test_coordinator_id);
        if($staff != null){
            return $staff->getFullName($showRole);
        }
        return '-';
    }

    public function getChecklistItem($type)
    {
        $testSessionCheckListItems = TestSessionChecklistItems::findAll(['testSessionId' => $this->id, 'type' => $type]);
        return $testSessionCheckListItems;
    }

    public function doPropagateCheckList($type)
    {
        $testSessionCheckListItems = $this->getChecklistItem($type);
        $checkListId = false;
        if($testSessionCheckListItems && count($testSessionCheckListItems) > 0){
            $checkListSessionItem = $testSessionCheckListItems[0];
            $checkListItem = ChecklistItemTemplate::findOne($checkListSessionItem->checkListItemId);
            if($checkListItem){
                $checkListId = $checkListItem->checklistId;
            }
        }
        $currentCheckListIdForTheCurrentType = false;
        if($type == ChecklistTemplate::TYPE_PRE && $this->preChecklistId > 0){
            $currentCheckListIdForTheCurrentType = $this->preChecklistId;
        }else if($type == ChecklistTemplate::TYPE_POST  && $this->postChecklistId > 0){
            $currentCheckListIdForTheCurrentType = $this->postChecklistId;
        }else if($type == ChecklistTemplate::TYPE_WRITTEN  && $this->writtenChecklistId > 0){
            $currentCheckListIdForTheCurrentType = $this->writtenChecklistId;
        }else if($type == ChecklistTemplate::TYPE_WRITTEN_POST  && $this->writtenPostChecklistId > 0){
            $currentCheckListIdForTheCurrentType = $this->writtenPostChecklistId;
        }
        if($checkListId === false || $currentCheckListIdForTheCurrentType != $checkListId){
            TestSessionChecklistItems::deleteAll(['testSessionId' => $this->id, 'type' => $type]);
            $checklistItems = ChecklistItemTemplate::findAll(['checklistId' => $currentCheckListIdForTheCurrentType, 'isArchived' => 0]);
            foreach($checklistItems as $checkListItem){
                $testSessionCheckListItem = new TestSessionChecklistItems();
                $testSessionCheckListItem->testSessionId = $this->id;
                $testSessionCheckListItem->type = $type;
                $testSessionCheckListItem->checkListItemId = $checkListItem->id;
                if($checkListItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
                    if($checkListItem->status == ChecklistItemTemplate::STATUS_FAIL){
                        $testSessionCheckListItem->status = $checkListItem->status;
                    }else{
                        $testSessionCheckListItem->status = ChecklistItemTemplate::STATUS_NOT_CHECKED;
                    }
                }
                $testSessionCheckListItem->save();
            }
        }

    }

    public function isChecklistItemChecked($checklistItemId)
    {
        $testSessionChecklistItem = TestSessionChecklistItems::findOne(['checkListItemId' => $checklistItemId, 'testSessionId' => $this->id, 'status' => ChecklistItemTemplate::STATUS_PASSED]);
        if($testSessionChecklistItem){
            return true;
        }
        return false;
    }

    public function getChecklistItemValue($checklistItemId)
    {
        $testSessionChecklistItem = TestSessionChecklistItems::findOne(['checkListItemId' => $checklistItemId, 'testSessionId' => $this->id]);
        if($testSessionChecklistItem){
            return $testSessionChecklistItem->status;
        }
        return 0;
    }

    public function hasIncompleteCalendarChecklist()
    {

        $testSessionTypeId = $this->getTestSessionTypeId();
        $checkListType = false;
        $calendarChecklist = false;
        if($testSessionTypeId == TestSite::TYPE_WRITTEN){
            $calendarChecklist = ChecklistTemplate::findOne(['type' => ChecklistTemplate::TYPE_WRITTEN_CALENDAR_CHECKLIST, 'name' => 'Written Calendar ChecklistTemplate']);
        }else if($testSessionTypeId == TestSite::TYPE_PRACTICAL){
            $calendarChecklist = ChecklistTemplate::findOne(['type' => ChecklistTemplate::TYPE_PRACTICAL,  'name' => 'Practical ChecklistTemplate']);
        }

        if($calendarChecklist !== false){
            $allItems = ChecklistItemTemplate::findAll(['checklistId' => $calendarChecklist->id, 'isArchived' => 0]);
            $testSession = TestSession::findBySql('select *  from test_session where id = '.$this->id.' and date(start_date) BETWEEN date(now()) and  date(DATE_ADD(NOW(), INTERVAL 7 DAY)) ')->one();
            if($testSession){
                foreach($allItems as $item){
                    if($testSession->isChecklistItemChecked($item->id) === false){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function hasIncompleteChecklist()
    {
        $testSession = TestSession::findBySql('select *  from test_session where id = '.$this->id.' and date(start_date) BETWEEN date(now()) and  date(DATE_ADD(NOW(), INTERVAL 7 DAY)) ')->one();
        if($testSession){
            $checkListItems = TestSessionChecklistItems::findAll(['testSessionId' => $this->id, 'type' => ChecklistTemplate::TYPE_PRE]);

            foreach ($checkListItems as $checkListItem) {
                if($checkListItem->status != ChecklistItemTemplate::STATUS_PASSED){
                    return true;
                }
            }

            $checkListItems = TestSessionChecklistItems::findAll(['testSessionId' => $this->id, 'type' => ChecklistTemplate::TYPE_POST]);

            foreach ($checkListItems as $checkListItem) {
                if($checkListItem->status != ChecklistItemTemplate::STATUS_PASSED){
                    return true;
                }
            }
        }
        return false;
    }

    public function getStartEndDatesForClass()
    {
        $availDates = [];

        $endDate = date('M d, Y', strtotime($this->end_date));
        $addDay = 0;
        do{
            $dateFormatted = date('M d, Y', strtotime('+ '.$addDay.' day', strtotime($this->start_date)));
            $dateFormattedKey = $dateFormatted; //date('Y-m-d', strtotime('+ '.$addDay.' day', strtotime($this->start_date)));


            $availDates[$dateFormattedKey] = $dateFormatted;
            $addDay++;
            if($dateFormatted == $endDate)
                break;
        }while(true);

        return ($availDates);
    }

    public function getAllCranesOfTestSite()
    {
        $testSite = TestSite::findOne($this->test_site_id);
        return $testSite->getAvailableCranes();
    }

    public static function getAllClasses($upcomingDaysAhead = 30)
    {
        $startDate = new \DateTimeImmutable('now', new \DateTimeZone('America/Los_Angeles'));
        $startDateTimeStr = $startDate->format('Y-m-d H:i:s');
        $dateInterval = new \DateInterval('P' . $upcomingDaysAhead . 'D');

        $endDate = $startDate->add($dateInterval);
        $endDateTimeStr = $endDate->format('Y-m-d H:i:s');

        $testSessions = TestSession::find()
            ->where(['between', 'end_date', $startDateTimeStr, $endDateTimeStr])
            ->orderBy(['start_date' => SORT_ASC])
            ->all();

        return $testSessions;
    }

    public static function getAllPreviousClasses($previousDays = 30)
    {
        $startDate = new \DateTimeImmutable('now', new \DateTimeZone('America/Los_Angeles'));
        $startDateTimeStr = $startDate->format('Y-m-d H:i:s');
        $dateInterval = new \DateInterval('P' . $previousDays . 'D');

        $endDate = $startDate->sub($dateInterval);
        $endDateTimeStr = $endDate->format('Y-m-d H:i:s');

        $testSessions = TestSession::find()
            ->where(['between', 'end_date', $endDateTimeStr, $startDateTimeStr])
            ->orderBy(['start_date' => SORT_DESC])
            ->all();

        return $testSessions;
    }

    public function getPracticalTestSchedule()
    {
        $candidateIds = array_map(function ($candidate) {
            return $candidate->id;
        }, $this->getCandidates()->select('id')->all());

        return PracticalTestSchedule::findAll([
            'test_session_id' => $this->combinedIds,
            'type' => 'TEST',
            'candidate_id' => $candidateIds
        ]);
    }

    public function checkIfClassFull ($cranes) {
        $craneCounts = $this->classStats;

        if ($craneCounts['totalRegular'] > 34) {
            return true;
        }

        if ($cranes === 'sw') {
            return $craneCounts['sw'] > 19;
        }

        if ($cranes === 'fx') {
            return $craneCounts['fx'] > 19;
        }

        if ($cranes === 'both') {
            return $craneCounts['sw'] > 19 || $craneCounts['fx'] > 19;
        }

        return false;
    }

    public function getClassStats()
    {
        $candidateSessions = CandidateSession::findAll(['test_session_id' => $this->combinedIds]);
        $candidateIds = array_reduce($candidateSessions, function ($acc, $session) {
            $acc[] = $session->candidate_id;
            return $acc;
        }, []);

        $candidates = Candidates::findAll(['id' => $candidateIds, 'isArchived' => 0]);

        $writtenTests = [
            'W_EXAM_CORE',
            'W_EXAM_LBC',
            'W_EXAM_LBT',
            'W_EXAM_BTF',
            'W_EXAM_TOWER',
            'W_EXAM_OVERHEAD',
            'W_EXAM_TLL',
            'W_EXAM_TSS',
            'W_EXAM_ADD_LBC',
            'W_EXAM_ADD_LBT',
            'W_EXAM_ADD_TLL',
            'W_EXAM_ADD_TSS',
            'W_EXAM_ADD_BTF',
            'W_EXAM_ADD_TOWER',
            'W_EXAM_ADD_OVERHEAD'
        ];

        $craneCounts = array_reduce($candidates, function ($acc, $candidate) use($writtenTests) {
            $result = $acc;
            $mergedFormSetup = $candidate->mergedFormSetup;
            $hasWrittenTest = false;

            foreach ($mergedFormSetup as $key => $value) {
                $testEnabled = array_reduce($writtenTests, function($acc, $writtenTest) use ($mergedFormSetup) {
                    if (isset($mergedFormSetup[$writtenTest]) && $mergedFormSetup[$writtenTest] === 'on') {
                        return true;
                    }
                    return $acc;
                }, false);

                if ($testEnabled) {
                    $hasWrittenTest = true;
                }
            }

            $hasPracticalSwing = isset($mergedFormSetup['P_TELESCOPIC_TLL']) && $mergedFormSetup['P_TELESCOPIC_TLL'] === 'on';
            $hasPracticalFixed = isset($mergedFormSetup['P_TELESCOPIC_TSS']) && $mergedFormSetup['P_TELESCOPIC_TSS'] === 'on';

            if ($hasWrittenTest) {
                if ($hasPracticalSwing || $hasPracticalFixed) {
                    if ($hasPracticalSwing) {
                        $result['sw'] = $result['sw'] + 1;
                    }

                    if ($hasPracticalFixed) {
                        $result['fx'] = $result['fx'] + 1;
                    }

                    $result['totalRegular'] = $result['totalRegular'] + 1;
                } else {
                    $result['writtenOnly'] = $result['writtenOnly'] + 1;
                }
            } elseif ($hasPracticalSwing || $hasPracticalFixed) {
                $result['practicalOnly'] = $result['practicalOnly'] + 1;
            } else {
                $result['testOnly'] = $result['testOnly'] + 1;
            }

            $result['totalCandidates'] = $result['totalCandidates'] + 1;

            return $result;
        }, [
            'totalCandidates' => 0,
            'totalRegular' => 0,
            'sw' => 0,
            'fx' => 0,
            'writtenOnly' => 0,
            'practicalOnly' => 0,
            'testOnly' => 0
        ]);

        return $craneCounts;
    }

    public function getGradeStats()
    {
        $testSessionIds = $this->combinedIds;
        $candidateRawGrades = CandidatePreviousSession::find()->select(['craneStatus'])->where(['test_session_id' => $testSessionIds])->asArray()->all();
        $candidateGrades = array_map(function($rawGrade) {
            $gradeArr = json_decode($rawGrade['craneStatus'], true);
            return array_reduce($gradeArr, function($acc, $test) {
                $newAcc = $acc;
                $newAcc[$test['key']] = $test['val'];

                return $newAcc;
            }, []);
        }, $candidateRawGrades);

        $gradesByExam = [
            'W_EXAM_CORE' => [
                'total' => 0,
                'pass' => 0,
                'fail' => 0
            ],
            'W_EXAM_TLL' => [
                'total' => 0,
                'pass' => 0,
                'fail' => 0
            ],
            'W_EXAM_TSS' => [
                'total' => 0,
                'pass' => 0,
                'fail' => 0
            ],
            'P_TELESCOPIC_TLL' => [
                'total' => 0,
                'pass' => 0,
                'fail' => 0,
                'decline' => 0
            ],
            'P_TELESCOPIC_TSS' => [
                'total' => 0,
                'pass' => 0,
                'fail' => 0,
                'decline' => 0
            ]
        ];

        foreach($candidateGrades as $candidateGrade) {
            foreach($candidateGrade as $test => $grade) {
                if (array_key_exists($test, $gradesByExam)) {
                    $gradesByExam[$test]['total'] += 1;
                    if ($grade === '1') {
                        $gradesByExam[$test]['pass'] += 1;
                    } elseif ($grade === '2' && substr($test, 0, 2) === 'P_') {
                        $gradesByExam[$test]['decline'] += 1;
                    } else {
                        $gradesByExam[$test]['fail'] += 1;
                    }
                }
            }
        }

        return $gradesByExam;
    }
}
