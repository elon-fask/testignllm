<?php
namespace app\commands;

use yii\console\Controller;
use app\models\Manufacturer;
use app\models\ManufacturerModel;
use app\models\Candidates;
use app\helpers\NotificationHelper;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
use app\models\TestSite;
use app\models\User;
use app\models\ChecklistTemplate;
use app\models\ChecklistItemTemplate;
use app\models\TestSessionReceipts;

class NotificationController extends Controller
{

    public function actionRegistration(){

        $sql = "select * from candidates where disregard = 0 and 
    	    registration_step in (1,2) and '".date('Y-m-d', strtotime('-1 day'))."' < date_created and date_created <= '".date('Y-m-d', strtotime('now'))."'";
        $command = \Yii::$app->db->createCommand($sql);
        $candidates = $command->queryAll();

        var_dump(NotificationHelper::notifyAdminAboutUnfinishedRegistration($candidates));
    }

    private static function getDaysUntil($dateStr)
    {
        $tz = 'America/New_York';

        $dateToBeTested = new \DateTime($dateStr, new \DateTimeZone($tz));
        $currentDate = new \DateTime('now', new \DateTimeZone($tz));
        $interval = $currentDate->diff($dateToBeTested);

        if ($interval->invert) {
            return $interval->days * -1;
        }

        return $interval->days;
    }

    public function actionTestSessionReport()
    {

        $testSessions = TestSession::find()->where(['>', 'start_date', date('Y-m-d')])->all();

        $lateFeeTestSessions = array_filter($testSessions, function($testSession) {
            if (isset($testSession->testing_date)) {
                $daysUntilTestingDate = self::getDaysUntil($testSession->testing_date);
                return $daysUntilTestingDate === 13;
            }
            return false;
        });

        $regCloseTestSessions = array_filter($testSessions, function($testSession) {
            if (isset($testSession->registration_close_date)) {
                $daysUntilTestingDate = self::getDaysUntil($testSession->registration_close_date);
                return $daysUntilTestingDate === 0;
            }
            return false;
        });

        if (count($lateFeeTestSessions) > 0 || count($regCloseTestSessions) > 0) {
            NotificationHelper::notifyUpcomingClassReport($lateFeeTestSessions, $regCloseTestSessions);
        }

        return 0;
    }

    public function actionDiscrepancyNotify(){
        $discrepancyList = TestSiteChecklistItemDiscrepancy::findAll(['isCleared' => 0]);
        $testSiteDiscrepancies = [];
        foreach($discrepancyList as $testSiteChecklistItemDiscrepancy){
            $testSiteId = $testSiteChecklistItemDiscrepancy->testSiteId;
            $checklistItemId = $testSiteChecklistItemDiscrepancy->checklistItemId;
            if(!isset($testSiteDiscrepancies[$testSiteId])){
                $testSiteDiscrepancies[$testSiteId] = [];
            }
            $testSiteDiscrepancies[$testSiteId][] = $testSiteChecklistItemDiscrepancy;
        }
        foreach($testSiteDiscrepancies as $testSiteId => $discrepancyList){
            //we notify the site manager
            $testSite = TestSite::findOne($testSiteId);
            if($testSite){
                NotificationHelper::notifyForDiscrepancy($testSite->siteManagerId, $discrepancyList);
            }
            $siteAdmins = User::findAll(['role' => User::ROLE_ADMIN, 'active' => 1]);
            foreach($siteAdmins as $admin){
                NotificationHelper::notifyForDiscrepancy($admin->id, $discrepancyList);
            }
            //$checklistItem = ChecklistItemTemplate::findOne($checklistItemId);
            //var_dump($checklistItem->name);
        }
    }
    public function actionCalendarChecklistNotify(){
        $testSiteFailedItems = [];
        $siteAdmins = User::findAll(['role' => User::ROLE_ADMIN, 'active' => 1]);
        $practicalChecklist = ChecklistTemplate::findOne(['type' => ChecklistTemplate::TYPE_PRACTICAL, 'name' => 'Practical ChecklistTemplate']);
        $allItems = ChecklistItemTemplate::findAll(['checklistId' => $practicalChecklist->id, 'isArchived' => 0]);
        $testSessions = TestSession::findBySql('select * from test_session where date(start_date) BETWEEN date(now()) and  date(DATE_ADD(NOW(), INTERVAL 7 DAY)) ')->all();
        foreach($testSessions as $testSession){
            if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
                foreach($allItems as $item){
                    if($testSession->isChecklistItemChecked($item->id) === false){
                        //we send notification

                        $testSiteId = $testSession->test_site_id;
                        if(!isset($testSiteFailedItems[$testSiteId])){
                            $testSiteFailedItems[$testSiteId] = [];
                            $testSiteFailedItems[$testSiteId]['practical-sessions'] = [];
                            $testSiteFailedItems[$testSiteId]['written-sessions'] = [];
                        }

                        if(!isset($testSiteFailedItems[$testSiteId]['practical-sessions'][$testSession->id])){
                            $testSiteFailedItems[$testSiteId]['practical-sessions'][$testSession->id] = [];
                        }
                        $testSiteFailedItems[$testSiteId]['practical-sessions'][$testSession->id][] = $item;
                    }
                }
            }
        }

        $practicalChecklist = ChecklistTemplate::findOne(['type' => ChecklistTemplate::TYPE_WRITTEN_CALENDAR_CHECKLIST, 'name' => 'Written Calendar ChecklistTemplate']);
        $allItems = ChecklistItemTemplate::findAll(['checklistId' => $practicalChecklist->id, 'isArchived' => 0]);
        $testSessions = TestSession::findBySql('select *  from test_session where date(start_date) BETWEEN date(now()) and  date(DATE_ADD(NOW(), INTERVAL 7 DAY)) ')->all();
        foreach($testSessions as $testSession){
            if($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
                foreach($allItems as $item){
                    if($testSession->isChecklistItemChecked($item->id) === false){
                        //we send notification

                        $testSiteId = $testSession->test_site_id;
                        if(!isset($testSiteFailedItems[$testSiteId])){
                            $testSiteFailedItems[$testSiteId] = [];
                            $testSiteFailedItems[$testSiteId]['practical-sessions'] = [];
                            $testSiteFailedItems[$testSiteId]['written-sessions'] = [];
                        }

                        if(!isset($testSiteFailedItems[$testSiteId]['written-sessions'][$testSession->id])){
                            $testSiteFailedItems[$testSiteId]['written-sessions'][$testSession->id] = [];
                        }
                        $testSiteFailedItems[$testSiteId]['written-sessions'][$testSession->id][] = $item;

                    }
                }
            }
        }

        foreach($testSiteFailedItems as $testSiteId => $data){
            //$data['written-sessions']
            //$data['practical-sessions']
            foreach($siteAdmins as $admin){
                NotificationHelper::notifyForCalendarChecklistFailed($admin->id, $testSiteId, $data);
            }
        }
        //var_dump($testSiteDiscrepancies);
    }

    public function actionWrittenChecklistNotify(){

        $testSessions = TestSession::findBySql('select *  from test_session where date(start_date) = date(now()) and writtenChecklistId is not null')->all();
        foreach($testSessions as $testSession){
            if($testSession->instructor_id > 0){
                $allItems = ChecklistItemTemplate::findAll(['checklistId' => $testSession->writtenChecklistId, 'isArchived' => 0]);
                NotificationHelper::notifyForWrittenChecklist($testSession->instructor_id, $testSession, $allItems);
            }
        }
    }

    public function actionSendAdminReceipt($receiptId){
        //we email
        $receipt = TestSessionReceipts::findOne($receiptId);
        $params = [];
        $params['testSession'] = TestSession::findOne($receipt->testSessionId);
        $params['receipt'] = $receipt;
        $message = \Yii::$app->mailer->compose('new-receipt',$params)
            ->setTo(\Yii::$app->params['receipt.email.notification'])
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setSubject('New Session Receipt');

        $path =  '/images/session/'.md5($receipt->testSessionId).'/receipts/'.$receipt->filename;
        if(is_file( realpath(\Yii::$app->basePath) .'/web'.$path)){
            $message->attach( realpath(\Yii::$app->basePath) .'/web'.$path);
        }


        $emailSent = $message->send();

        var_dump($emailSent);
    }

    public function actionMonthlyReceipts(){
        //true || 
        if(date('j') == 1){
            //we get the previous whole month
            $month_ini = new \DateTime("first day of last month");
            $month_end = new \DateTime("last day of last month");


            $start = $month_ini->format('Y-m-d'); // 2012-02-01
            $end = $month_end->format('Y-m-d'); // 2012-02-29

            //$start = '2016-09-01';
            //$end = '2016-09-30';

            $allReceipts = TestSessionReceipts::find()->where("date(date_created) >= '".$start."' and date(date_created) <= '".$end."' order by testSessionId asc")->all();
            $params = [];
            $params['allReceipts'] = $allReceipts;
            $message = \Yii::$app->mailer->compose('all-receipts',$params)
                ->setTo(\Yii::$app->params['receipt.email.notification'])
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setSubject($month_end->format('F').' Month Receipt');


            $emailSent = $message->send();

            var_dump($emailSent);
        }
    }

    public function actionChecklistNotify($testSessionId){

        NotificationHelper::notifyAdminForWrittenChecklist($testSessionId);

    }
}
