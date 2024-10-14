<?php
namespace app\helpers;

use yii;
use app\models\UserResetPassword;
use app\models\Candidates;
use app\models\CandidateSession;
use app\models\PendingTransaction;
use app\models\AppConfig;
use yii\base\Exception;
use app\models\User;
use app\models\TestSite;
use app\models\ChecklistItemTemplate;
use app\models\Messages;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
use app\models\ChecklistTemplate;

class NotificationHelper
{
    public static function getContextUrl()
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }

        return $protocol . "://" . $_SERVER['HTTP_HOST'] ;
    }

    public static function notifyConfirmationEmail($candidate, $details = null)
    {
        $template = 'candidate-confirmation-email';

        $emailAddress = $details['email'];
        $emailAddress = YII_ENV_DEV ? \Yii::$app->params['adminNotificationEmailDebug'] : $emailAddress;

        \Yii::$app->mailer->htmlLayout = 'layouts/new';
        $message = \Yii::$app->mailer->compose($template, $details)
        ->setTo($emailAddress)
        ->setFrom(\Yii::$app->params['adminEmail'])
        ->setSubject($details['subject']);

        $message->send();
    }

    static public function notifyUpcomingClassReport($lateFeeTestSessions, $regCloseTestSessions)
    {
        $params = [
            'lateFeeTestSessions' => $lateFeeTestSessions,
            'regCloseTestSessions' => $regCloseTestSessions
        ];

        $recipientListConfig = AppConfig::findOne(['code' => AppConfig::UPCOMING_CLASS_REPORT_EMAIL_RECIPIENT]);
        $recipientList = explode(', ', $recipientListConfig->val);

        \Yii::$app->mailer->htmlLayout = 'layouts/new';
        $message = \Yii::$app->mailer->compose('notification-upcoming-class-report', $params)
        ->setTo($recipientList)
        ->setFrom(\Yii::$app->params['adminEmail'])
        ->setSubject('Upcoming Class Report');

        $message->send();
    }

    static public function notifyPendingTransactionReceipt($pendingTx) {
        if (!isset($pendingTx)) {
            return false;
        }

        $candidate = Candidates::findOne($pendingTx->candidate_id);
        $paymentMethod = PendingTransaction::TX_NAME_MAPPING[$pendingTx->type];

        $params = [
            'pendingTx' => $pendingTx,
            'paymentMethod' => $paymentMethod,
            'candidate' => $candidate
        ];

        $emailAddress = YII_ENV_DEV ? \Yii::$app->params['adminNotificationEmailDebug'] : $candidate->email;

        \Yii::$app->mailer->htmlLayout = 'layouts/new-ccs-receipt';
        $message = \Yii::$app->mailer->compose('payment-receipt', $params)
        ->setTo($emailAddress)
        ->setFrom(\Yii::$app->params['adminEmail'])
        ->setSubject('California Crane School Receipt');

        if ($message->send()) {
            return true;
        }

        return false;
    }

    static public function notifySendUserSuccess($candidateId){
        // $resp = UtilityHelper::generateApplicationForms($candidateId, true);

        self::notifyAdminSendUserSuccess($candidateId);

        $params = [ 'school' => 'ACS' ];
        $candidate = Candidates::findOne(['id' => $candidateId]);
        $writtenTestSession = $candidate->writtenTestSession;
        if ($writtenTestSession) {
            $params['school'] = $writtenTestSession->testSession->school;
        } else {
            $practicalTestSession = $candidate->practicalSession;
            if ($practicalTestSession) {
                $params['school'] = $practicalTestSession->testSession->school;
            }
        }

        $params['name'] = $candidate->getFullName();
        $params['downloadUrl'] = '';//self::getContextUrl() . '/register/form?cId=' . base64_encode($candidateId) . '&i=' . md5($candidateId);

        $message = \Yii::$app->mailer->compose('candidate-register-success', $params)
        ->setTo($candidate->email)
        ->setFrom(Yii::$app->params['adminEmail'])
        ->setSubject('Successful Registration');

        // $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidateId);

        // if ($appFormPath !== false) {
        //     $message->attach($appFormPath);
        // }

        $email = $message->send();
        if ($email) {
            return true;
        }
        return false;
    }
    
    static public function notifyAdminSendUserSuccess($candidateId)
    {
        // $resp = UtilityHelper::generateApplicationForms($candidateId, true);

        $params = [];
        $candidate = Candidates::findOne(['id' => $candidateId]);
        $session = TestSession::findOne(['id' => CandidateSession::find()->select(['test_session_id'])->where(['candidate_id' => $candidate->id])->asArray()->one()]);

        $params['candidate'] = $candidate;
        $params['link'] = self::getContextUrl() . '/admin/candidates/update?id=' . md5($candidateId);

        $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];
        $recipients = null;

        if (YII_ENV_DEV) {
            $recipients = [Yii::$app->params['adminNotificationEmailDebug']];
        } else {
            $config = null;
            if ($session->school == 'ACS') {
                $config = AppConfig::findOne([
                    'code' => AppConfig::NEW_CANDIDATES_ACS_EMAIL_RECIPIENT
                ]);
            } else {
                $config = AppConfig::findOne([
                    'code' => AppConfig::NEW_CANDIDATES_CCS_EMAIL_RECIPIENT
                ]);
            }
            $recipients = isset($config) ? preg_split('/\s*,\s*/', $config->val) : [];
        }

        $params['classStats'] = $session->classStats;

        $subjectStr = 'Notification: Successful Registration of Candidate';
        $warningStr = '';

        $limitedRegularSeats = $params['classStats']['totalRegular'] > 32;
        $limitedSwSeats = $params['classStats']['sw'] > 17;
        $limitedFxSeats = $params['classStats']['fx'] > 17;

        if ($limitedRegularSeats || $limitedSwSeats || $limitedFxSeats) {
            $warningStr = '| Limited Seats: | ';

            if ($limitedRegularSeats) {
                $seatsLeft = 35 - $params['classStats']['totalRegular'];
                $seatsLeft = $seatsLeft < 0 ? 0 : $seatsLeft;
                $warningStr = $warningStr . $seatsLeft . ' Reg | ';
            }

            if ($limitedSwSeats) {
                $seatsLeft = 20 - $params['classStats']['sw'];
                $seatsLeft = $seatsLeft < 0 ? 0 : $seatsLeft;
                $warningStr = $warningStr . $seatsLeft . ' SW | ';
            }

            if ($limitedSwSeats) {
                $seatsLeft = 20 - $params['classStats']['fx'];
                $seatsLeft = $seatsLeft < 0 ? 0 : $seatsLeft;
                $warningStr = $warningStr . $seatsLeft . ' FX | ';
            }
        }

        \Yii::$app->mailer->htmlLayout = 'layouts/new';
        $message = \Yii::$app->mailer->compose('notification-admin-candidate-register-success', $params)
        ->setTo($recipients)
        ->setFrom(Yii::$app->params['adminEmail'])
        ->setSubject($subjectStr . $warningStr);

        $email = $message->send();
        if ($email) {
            return true;
        }
        return false;
    }

    static public function notifyAdminAboutUnfinishedRegistration($candidates)
    {
        $params = [];
        $params['candidates'] = $candidates;

        $appConfig = AppConfig::findOne([
            'code' => AppConfig::UNFINISHED_REGISTRATION_EMAIL_RECIPIENT
        ]);

        $recipients = [];
        if ($appConfig->val != '') {
            $recipients = preg_split('/\s*,\s*/', $appConfig->val);
        }

        $email = \Yii::$app->mailer->compose('unfinished-registration',$params)
        ->setTo($recipients)
        ->setFrom(Yii::$app->params['adminEmail'])
        ->setSubject('Notification: Unfinished Registration')
        ->send();
        if ($email) {
            return true;
        }
        return false;
    }

    static public function notifySendUserUpdatedForm($candidate){        
        $params = array();
        
        $params['name'] = $candidate->getFullName();
        $params['downloadUrl'] = '';// self::getContextUrl().'/register/form?cId='.base64_encode($candidate->id).'&i='.md5($candidate->id);
        
        
        $message = \Yii::$app->mailer->compose('candidate-app-form-update',$params)
        ->setTo($candidate->email)
        ->setFrom(Yii::$app->params['adminEmail'])
        ->setSubject('Registration Application Form Updated');
        
        // $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
        
        // if($appFormPath !== false){
        //     $mergedFile = UtilityHelper::downloadAppForm($appFormPath, $candidate);
        //     $message->attach($mergedFile);
        // }
        try{
            $email = $message->send();
            if($email){
                return true;
            }
        }catch (Exception $e){
            
        }
        return false;
    }
    
    static public function send_sms( $sid, $token, $to, $from, $body ) {
        // resource url & authentication
        $uri = 'https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/SMS/Messages';
        $auth = $sid . ':' . $token;
    
        // post string (phone number format= +15554443333 ), case matters
        $fields =
        '&To=' .  urlencode( $to ) .
        '&From=' . urlencode( $from ) .
        '&Body=' . urlencode( $body );
    
        // start cURL
        $res = curl_init();
         
        // set cURL options
        curl_setopt( $res, CURLOPT_URL, $uri );
        curl_setopt( $res, CURLOPT_POST, 3 ); // number of fields
        curl_setopt( $res, CURLOPT_POSTFIELDS, $fields );
        curl_setopt( $res, CURLOPT_USERPWD, $auth ); // authenticate
        curl_setopt( $res, CURLOPT_RETURNTRANSFER, true ); // don't echo
         
        // send cURL
        $result = curl_exec( $res );
        //var_dump($result);
        return $result;
    }
    static public function notifyForDiscrepancy($userId, $discrepancyList){
        
        $testSiteId = $discrepancyList[0]->testSiteId;
        $checkListItemId = $discrepancyList[0]->checklistItemId;
        $dateCreated = date('m-d-Y', strtotime($discrepancyList[0]->date_created));
        $user = User::findOne($userId);
       
        if($user && $user->active == 1){
            $testSite = TestSite::findOne($testSiteId);
            if($testSite){
                $checklistItem = ChecklistItemTemplate::findOne($checkListItemId);
                if($checklistItem){
                    
                    $params = [];
                    $params['testSiteName'] = $testSite->getTestSiteName();
                    $params['checklistName'] = $checklistItem->name;
                    $params['dateCreated'] = $dateCreated;
                    $params['discrepancyList'] = $discrepancyList;
                    
                    $siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
                    
                    //$body = $params['testSiteName'] . ' has failed '.$params['checklistName']. ' since '.$params['dateCreated'];
                    $body = 'Test Site: '.$params['testSiteName'] . ' has failed ChecklistTemplate Items, '.$siteUrl.'/resolve?id='.$testSite->id;
                    if($user->cellPhone != ''){
                        //notify SMS
                        $cellPhone = str_replace(array("+1", "(", ")", " ", "-", "+"), "", $user->cellPhone);
                        try{
                            $sid =  \Yii::$app->params['twilio.sid'] ; // "ACcb406d1ac7721f12fa4958ab18803345"; // Your Account SID from www.twilio.com/user/account
                            $token = \Yii::$app->params['twilio.token']; //  "c8508c7fdc8b72ad51e2b89cc5351655"; // Your Auth Token from www.twilio.com/user/account
                            
                            //$client = new \Twilio\Rest\Client($sid, $token);
                            /*
                            $message = $client->account->messages->create(
                              '15005550006', // From a valid Twilio number
                              '13057090915', // Text this number
                              array(
                                'Body' => $body
                              )
                            );
                            */
                            
                            $from = \Yii::$app->params['twilio.phone'] ; //'15005550006';
                            $to = $cellPhone ;//'13057090915';
                            self::send_sms($sid, $token, $to, $from, $body );                            
                                echo 'SMS Sent: '.$cellPhone;
                        }catch (Exception $e){
                            var_dump($e->getMessage( ));
                        }
                        
                    }
                    $subject = 'Notification: Failed ChecklistTemplate Item';
                    if($user->email != ''){
                        //notfy email

                        $emailSender = \Yii::$app->mailer->compose('failed-checklist',$params);
                      
                        $emailSent = \Yii::$app->mailer->compose('failed-checklist',$params)
                        ->setTo($user->email)
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setSubject($subject)
                        ->send();
                        
                        if($emailSent){
                           // return true;
                            echo 'Email Sent: '.$user->email;
                        }
                    }
                    
                    $messageBody = $body;
                    $messageBody = \Yii::$app->mailer->render('failed-checklist',$params);
                    
                    
                    $newMessage = new Messages();
                    $newMessage->sender_id = 0;
                    $newMessage->receiver_id = $userId;
                    $newMessage->subject = $subject;
                    $newMessage->body = $messageBody;
                    $newMessage->save(false);
                }
            }    
        }
    }
    
    static public function notifyForCalendarChecklistFailed($userId, $testSiteId, $data){
        $user = User::findOne($userId);
         
        if($user && $user->active == 1){
            $testSite = TestSite::findOne($testSiteId);
            if($testSite){
                //$testSession, $checklistItem, $isWritten
                
                $params = [];
                $params['testSiteName'] = $testSite->getTestSiteName();
                $params['failedItems'] = $data;
                $isWritten = false;
                if($testSite->type == TestSite::TYPE_WRITTEN){
                    $isWritten = true;
                }
                $siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
                    
                $body = 'Test Site: '.$params['testSiteName'] . ' has failed Practical Calendar ChecklistTemplate, '.$siteUrl.'/admin/calendar';
                    
                
                if($isWritten){
                    $body = 'Test Site: '.$params['testSiteName'] . ' has failed Written Calendar ChecklistTemplate, '.$siteUrl.'/admin/calendar';
                }
                
                if($user->cellPhone != ''){
                    //notify SMS
                    $cellPhone = str_replace(array("+1", "(", ")", " ", "-", "+"), "", $user->cellPhone);
                    try{
                        $sid =  \Yii::$app->params['twilio.sid'] ; // "ACcb406d1ac7721f12fa4958ab18803345"; // Your Account SID from www.twilio.com/user/account
                        $token = \Yii::$app->params['twilio.token']; //  "c8508c7fdc8b72ad51e2b89cc5351655"; // Your Auth Token from www.twilio.com/user/account
                
                
                        $from = \Yii::$app->params['twilio.phone'] ; //'15005550006';
                        $to = $cellPhone ;//'13057090915';
                        self::send_sms($sid, $token, $to, $from, $body );
                        echo 'SMS Sent: '.$cellPhone;
                    }catch (Exception $e){
                        var_dump($e->getMessage( ));
                    }
                
                }
                $subject = 'Notification: Failed Practical Calendar ChecklistTemplate Item';
                $params['type'] = 'Practical';
                if($isWritten){
                    $subject = 'Notification: Failed Written Calendar ChecklistTemplate Item';
                    $params['type'] = 'Written';
                }
                
                if($user->email != ''){
                    //notfy email
                    $emailSent = \Yii::$app->mailer->compose('failed-calendar-checklist',$params)
                    ->setTo($user->email)
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setSubject($subject)
                    ->send();
                
                    if($emailSent){
                        // return true;
                        echo 'Email Sent: '.$user->email;
                    }
                }
                $messageBody = \Yii::$app->mailer->render('failed-calendar-checklist',$params);
                $newMessage = new Messages();
                $newMessage->sender_id = 0;
                $newMessage->receiver_id = $userId;
                $newMessage->subject = $subject;
                $newMessage->body = $messageBody;
                $newMessage->save(false);
                
            }
            
            
        }
    }
    static public function notifyAdminForWrittenChecklist($testSessionId){
        $testSession = TestSession::findOne($testSessionId);
        if($testSession){
            $testSessionChecklistItems = TestSessionChecklistItems::findAll(['isFailed' => 1, 'testSessionId' => $testSessionId]);
            $siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
            $siteUrl .= '/admin';
            $isWritten = true;
            $subject = 'Notification: Written ChecklistTemplate Failed Warning';
            if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
                $isWritten = false;
                $subject = 'Notification: Practical ChecklistTemplate Failed Warning';
            }
           
            $failedItems = [];
            $failedType = [];
            foreach($testSessionChecklistItems as $item){
                $checklistItem = ChecklistItemTemplate::findOne($item->checkListItemId);
                //we check if failed
                $prefix = '';
                if($isWritten == false){
                    $prefix = '&craneId='.$item->craneId;
                }
                if($checklistItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
                    //if($item->status == ChecklistItemTemplate::STATUS_FAIL){
                        $failedItems[] = ['item'=>$item, 'checkListItem' => $checklistItem ,'resolve' => $siteUrl.'/checklist/session?type='.$item->type.$prefix.'&id='.md5($testSessionId)];
                        if(!isset($failedType[$item->type])){
                            $failedType[$item->type] = $item->type;
                        }
                    //}
                }else if($checklistItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION && $checklistItem->failingScore != null){
                    //if($item->status != null && intval($item->status) <= intval($checklistItem->failingScore)){
                        $failedItems[] = ['item'=>$item, 'checkListItem' => $checklistItem ,'resolve' => $siteUrl.'/checklist/session?type='.$item->type.$prefix.'&id='.md5($testSessionId)];
                        if(!isset($failedType[$item->type])){
                            $failedType[$item->type] = $item->type;
                        }
                    //}
                }else if($checklistItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS){
                    //if($item->status != null && intval($item->status) <= intval($checklistItem->failingScore)){
                        $failedItems[] = ['item'=>$item, 'checkListItem' => $checklistItem ,'resolve' => $siteUrl.'/checklist/session?type='.$item->type.$prefix.'&id='.md5($testSessionId)];
                        if(!isset($failedType[$item->type])){
                            $failedType[$item->type] = $item->type;
                        }
                    //}
                }
                
            }
            
            
            
            if(count($failedItems) > 0){
                
                $testSite = TestSite::findOne($testSession->test_site_id);
                 
                $params = [];
                $params['testSiteName'] = $testSite->getTestSiteName();
                $params['failedTypes'] = $failedType;
                $params['failedItems'] = $failedItems;
                $params['sessionName'] = $testSession->getPartialTestSessionDescription();
                
                $emailSent = \Yii::$app->mailer->compose('failed-generic-checklist',$params)
                ->setTo(Yii::$app->params['failed.checklist.email.notification'])
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subject)
                ->send();
                
                if($emailSent){
                    // return true;
                    echo 'Email Sent:';
                }
            }
            
        }
    }
    static public function notifyForWrittenChecklist($userId, $testSession, $allChecklistItems){
        $user = User::findOne($userId);
         
        if($user && $user->active == 1){
            $checklistReminder = '';
            foreach($allChecklistItems as $item){
                $checklistReminder .= $item->name.' ('.$item->val.')
';
            }
            $params = [];
            $params['testSessionName'] = $testSession->getFullTestSessionDescription();
            $params['checklistReminder'] = $checklistReminder;
            $params['checklistItems'] = $allChecklistItems;
            //$params['dateCreated'] = $dateCreated;
            
            $body = $params['testSessionName'] .' - Written Session ChecklistTemplate: '.$checklistReminder;
            $messageBody = 'Written Session ChecklistTemplate: '.$params['testSessionName'] .'<br /><br />';
            foreach($allChecklistItems as $item){
                $messageBody .= 'Name: '.$item->name.' - Quantity : '.$item->val.'<br />';
            }
            if($user->cellPhone != ''){
                //notify SMS
                $cellPhone = str_replace(array("+1", "(", ")", " ", "-", "+"), "", $user->cellPhone);
                try{
                    $sid =  \Yii::$app->params['twilio.sid'] ; // "ACcb406d1ac7721f12fa4958ab18803345"; // Your Account SID from www.twilio.com/user/account
                    $token = \Yii::$app->params['twilio.token']; //  "c8508c7fdc8b72ad51e2b89cc5351655"; // Your Auth Token from www.twilio.com/user/account
    
    
                    $from = \Yii::$app->params['twilio.phone'] ; //'15005550006';
                    $to = $cellPhone ;//'13057090915';
                    self::send_sms($sid, $token, $to, $from, $body );
                    echo 'SMS Sent: '.$cellPhone;
                }catch (Exception $e){
                    var_dump($e->getMessage( ));
                }
    
            }
            $subject = 'Notification: Written Session ChecklistTemplate';
            if($user->email != ''){
                //notfy email
                $emailSent = \Yii::$app->mailer->compose('written-checklist',$params)
                ->setTo($user->email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subject)
                ->send();
    
                if($emailSent){
                    // return true;
                    echo 'Email Sent: '.$user->email;
                }
            }
    
            $newMessage = new Messages();
            $newMessage->sender_id = 0;
            $newMessage->receiver_id = $userId;
            $newMessage->subject = $subject;
            $newMessage->body = $messageBody;
            $newMessage->save(false);
    
    
        }
    }
}
