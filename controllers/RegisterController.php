<?php

namespace app\controllers;

use Yii;
use yii\db\Command;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\helpers\NotificationHelper;
use app\helpers\PipeDriveHelper;
use app\models\UserResetPassword;
use app\models\ApplicationType;
use app\models\ApplicationTypeFormSetup;
use app\models\TestSession;
use app\models\TestSite;
use app\models\Candidates;
use app\models\CandidateSession;
use app\helpers\UtilityHelper;
use app\models\PromoCodes;
use app\models\CandidateTransactions;
use mikehaertl\pdftk\Pdf;
use app\helpers\AppFormHelper;

class RegisterController extends Controller
{
    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get','post'],
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ["*"],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionPromo()
    {
        $promoCode = PromoCodes::findOne(['archived' => 0, 'code' => $_REQUEST['code']]);
        $resp = array();
        $resp['status'] = 0;

        $finalAmountToPay = $_REQUEST['amount'];
        $api_login_id = Yii::$app->params['authorize.net.login.id'];
        $transaction_key = Yii::$app->params['authorize.net.transaction.key'];

        if ($_REQUEST['school'] == TestSession::SCHOOL_ACS) {
            $api_login_id = Yii::$app->params['authorize.net.login.id.acs'];
            $transaction_key = Yii::$app->params['authorize.net.transaction.key.acs'];
        }

        if ($promoCode != null) {
            $resp['status'] = 1;
            $resp['discount'] = $promoCode->discount;
            $resp['isPurchaseOrder'] = $promoCode->isPurchaseOrder;

            $totalPrice = 0;
            if ($_REQUEST['type'] == 'deposit') {
                $totalPrice = (float)($_REQUEST['deposit']);
            } else {
                $totalPrice = (float)($finalAmountToPay - $promoCode->discount);
            }

            $amount = number_format($totalPrice, 2, '.', '');
            $date = date_create();
            $fp_sequence = date_format($date, 'YmdHis');
            $fp_timestamp = time();
            $fingerprint = \AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $fp_timestamp);

            $resp['amount'] = $amount;
            $resp['isFullDiscount'] = $amount == 0 ? 1 : 0;
            $resp['fingerprint'] = $fingerprint;
            $resp['sequence'] = $fp_sequence;
            $resp['timestamp'] = $fp_timestamp;
        } else {
            $totalPrice = (float)($finalAmountToPay);
            $amount = number_format($totalPrice, 2, '.', '');
            $date = date_create();
            $fp_sequence = date_format($date, 'YmdHis');
            $fp_timestamp = time();
            $fingerprint = \AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $fp_timestamp);

            $resp['amount'] = $amount;
            $resp['fingerprint'] = $fingerprint;
            $resp['sequence'] = $fp_sequence;
            $resp['timestamp'] = $fp_timestamp;
        }
        echo json_encode($resp);
        die;
    }

    public function actionDeposit()
    {
        $resp = array();
        $resp['status'] = 0;

        $promoCode = PromoCodes::findOne(['code'=>$_REQUEST['code']]);
        $resp = array();
        $resp['status'] = 0;
        $discount = 0;
        if ($promoCode != null) {
            $discount = $promoCode->discount;
        }

        $resp['status'] = 1;
        $finalAmountToPay = $_REQUEST['amount'];

        if ($_REQUEST['type'] == 'deposit') {
            $finalAmountToPay = (float)($_REQUEST['amount']);
        } else {
            $finalAmountToPay = (float)($finalAmountToPay - $discount);
        }

        $api_login_id = Yii::$app->params['authorize.net.login.id'];
        $transaction_key = Yii::$app->params['authorize.net.transaction.key'];

        if ($_REQUEST['school'] == TestSession::SCHOOL_ACS) {
            $api_login_id = Yii::$app->params['authorize.net.login.id.acs'];
            $transaction_key = Yii::$app->params['authorize.net.transaction.key.acs'];
        }

        $amount = number_format((float)($finalAmountToPay), 2, '.', '');
        $date = date_create();
        $fp_sequence = date_format($date, 'YmdHis');
        $fp_timestamp =time();
        $fingerprint = \AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $fp_timestamp);

        $resp['amount'] = $amount;
        $resp['fingerprint'] = $fingerprint;
        $resp['sequence'] = $fp_sequence;
        $resp['timestamp'] = $fp_timestamp;

        echo json_encode($resp);
        die;
    }

    public function actionForm()
    {
        $cId = isset($_REQUEST['cId']) ? $_REQUEST['cId'] : '';
        $i = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
        if ($cId != '' && $i != '') {
            $cId = base64_decode($cId);
            if (md5($cId) == $i) {
                $candidate = Candidates::findOne($cId);
                if ($candidate != null) {
                    $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
                    if ($appFormPath === false) {
                        UtilityHelper::generateApplicationForms($candidate->id, false);
                        $appFormPath = UtilityHelper::getOriginalAppFormsByCandidateId($candidate->id);
                    }
                    if ($appFormPath !== false) {
                        $mergedFile = UtilityHelper::downloadAppForm($appFormPath, $candidate);
                        header('Content-type: application/pdf');
                        header('Content-Disposition: inline; filename="' . basename($mergedFile) . '"');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($mergedFile));
                        header('Accept-Ranges: bytes');
                        readfile($mergedFile);
                    }
                }
            }
        }
        return $this->render('form-error', ['message' => 'Invalid File Location']);
    }

    public function actionIndex()
    {

        $_SESSION['branding'] = UtilityHelper::getSubdomain();
        if (count($_POST) != 0) {
            $keyword = $_POST['keyword'];
            $referralCode = $_POST['referralCode'];
            $uniqueCode = $_POST['uniqueCode'];
            $days = '';
            if(strpos($keyword,'-3') or strpos($keyword,'-5')){
                $explkey = explode('-',$keyword);
                $keyword = $_POST['keyword'];
                $days = $explkey[1];
            }else{
                $arr3 = ['certify','rcw','rcwo','retest','class-f','private','rcw1','rcw2','rcwo1','rcwo2','retests','certifys','certifyf','class','penon-fixed','retestf',
                    'class-s','lbc','lbt','rcwlattice','rcwf','rcws','rcwof','rcwos','preadds','Comp','addf','preaddf','adds','rcwlc','rcws-late','lbcrecert','certall','certlate',
                    'rcwlate','rcwf-late','certifys-late','certf-late','reschtest-s','certifys-hi','retest-fpe2','classnt-f','retestfs','classnt','certify+2','rcwretest-f',
                    'certs-late','core','cert-pe-sw','craneguys','rcwo-s','ContraCert','rancho-recert','rcwlbt','craneguys-rcw','craneguyscl', 'certifyf+2','certifys+2',
                    'retest-withpe','class-c','class-cf','rbrothers','class-cs','core-peswing','bart','retestf-pes','certify+1','Apcertify','Apcertifys','Apcertifyf',
                    'Aprcw','Aprcws','Aprcwf','Aprcwo','Aprcwos','Aprcwof','sw-peboth+2','penon+2','csl','practice1','practice2','practice3','penon-both+2','peretstf+S',
                    'retestf-pes+1','retestcf-peb','class-sf','rcwretest-c','aaa','certify+pef','coreswfx','certifysp','retestc+pef','core+swpe','class1pe','wlattice','rcwlat',
                    'latticelbt','addswing','walkthrough','enonboth-guided','certifyrs'];
                $arr5 = ['certify5'];
                $arr1 = ['rigsig'];

                if(in_array($keyword,$arr3)){
                    $days = 3;
                }else if(in_array($keyword,$arr5)){
                    $days = 5;
                }else if(in_array($keyword,$arr1)){
                    $keyword = $arr1[0];
                    $days = null;
                }else{
                    $keyword = $_POST['keyword'];
                    $days = null;
                }
            }

            $appType = ApplicationType::findOne(['keyword' => $keyword, 'isArchived' => 0]);

            if ($appType != null) {
                return $this->render('choose-location', [
                    'candidateId' => '',
                    'appType' => $appType,
                    'referralCode' => $referralCode,
                    'uniqueCode' => $uniqueCode,
                    'days' => $days
                ]);
            } else {
                return $this->render('//home/index', [
                    'message' => 'Keyword is not valid',
                    'referralCode' => $referralCode,
                    'uniqueCode' => $uniqueCode
                ]);
            }
        }

        $referralCode = isset($_REQUEST['referralCode']) ? $_REQUEST['referralCode'] : '' ;

        $uniqueCode = isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ;

        if (UtilityHelper::getCurrentBranding() != '') {
            return $this->render('//home/index', ['message' => false, 'referralCode' => $referralCode, 'uniqueCode' => $uniqueCode]);
        } else {
            return $this->render('//home/home', []);
        }
    }

    public function actionSessions()
    {
        $testSiteId = $_GET['testSiteId'];
        $appTypeId = $_GET['appTypeId'];
        $referralCode = isset($_GET['referralCode']) ? $_GET['referralCode'] : '';
        $uniqueCode = isset($_GET['uniqueCode']) ? $_GET['uniqueCode'] : '';
        $appType = ApplicationType::findOne(base64_decode($_GET['appTypeId']));
        $testSessions = TestSession::find()->where(['test_site_id' => $testSiteId])->orderBy('start_date asc')->all();

        $cranes = $appType->cranes;

        $openTestSessions = array_filter($testSessions, function ($testSession) use ($cranes) {
            if ($testSession->isSessionPassTodayDate() || $testSession->isSessionCloseRegistrationAlready()) {
                return false;
            }
            return true;
        });
        return $this->renderPartial('sessions', [
            'candidateId' => $_GET['candidateId'],
            'referralCode' => $referralCode,
            'appTypeId' => $appTypeId,
            'appTypeIsPracticalOnly' => false,//$appType->isPracticalOnly,
            'testSiteId' => $testSiteId,
            'uniqueCode' => $uniqueCode,
            'sessions' => $openTestSessions
        ]);
    }

    private function processUserRegistration($candidateId, $testSessionId, $promoCode, $transactionId, $amount, $poNumber = false, $authCode = null)
    {
        $discount = 0;
        $isPurchaseOrder = 0;
        $finalPromoCode = false;
        if ($promoCode != '') {
            $promoCodes = PromoCodes::findOne(['code' => $promoCode]);
            if ($promoCodes != null) {
                $discount = $promoCodes->discount;
                $finalPromoCode = $promoCode;
                $isPurchaseOrder = $promoCodes->isPurchaseOrder;
            }
        }

        $candidateSession = new CandidateSession();
        $candidateSession->candidate_id = $candidateId;
        $candidateSession->test_session_id = $testSessionId;
        $candidateSession->save();

        $candidate = Candidates::findOne(['id'=>$candidateSession->candidate_id]);
        $testSession = TestSession::findOne($candidateSession->test_session_id);
        $appType = isset($candidate) ? ApplicationType::findOne($candidate->application_type_id) : null;

        $lateFeeApplicable = $testSession->isLateFeeApplicable;// && !$appType->isPracticalOnly;
        $lateFee = $lateFeeApplicable ? 50 : 0;

        $isRecert = $appType->isRecertify == 1 ? true : false;

        if ($candidate) {

            $customFormSetup = $candidate->getCandidateFormSetup();
            $candidate->custom_form_setup = json_encode($customFormSetup);

            if ($appType && $appType->price > 0) {
                UtilityHelper::addCandidateInitialApplicationCharge($candidate);
                if ($discount != 0) {
                    $candidateTransaction = new CandidateTransactions();
                    $candidateTransaction->transactionId = $promoCode;
                    $candidateTransaction->paymentType = CandidateTransactions::TYPE_PROMO;
                    $candidateTransaction->amount = $discount;
                    $candidateTransaction->candidateId = $candidateSession->candidate_id;
                    $candidateTransaction->save();
                }

                if ($lateFeeApplicable) {
                    $candidateTransaction = new CandidateTransactions();
                    $candidateTransaction->amount = $lateFee;
                    $candidateTransaction->paymentType = CandidateTransactions::TYPE_STUDENT_CHARGE;
                    $candidateTransaction->chargeType = CandidateTransactions::SUBTYPE_LATE_FEE;
                    $candidateTransaction->candidateId = $candidateSession->candidate_id;
                    $candidateTransaction->save();

                    $writtenComponent = $isRecert ? 'iai-blank-recert-with-1000-hours-application' : 'iai-blank-written-test-site-application-new-candidate';

                    $writtenAppForm = $appType->getApplicationFormSetups()->where(['form_name' => $writtenComponent])->one();
                    if (isset($writtenAppForm)) {
                        $customAppForm = [];
                        $customAppForm[$writtenComponent] = json_decode($writtenAppForm->form_setup, true);
                        $customAppForm[$writtenComponent]['W_FEE_LATE'] = 'on';
                        $customAppForm[$writtenComponent]['W_TOTAL_DUE'] = ApplicationTypeFormSetup::getFormTotal($customAppForm, $writtenComponent);
                        $candidate->custom_form_setup = json_encode($customAppForm);
                         $custom_form_setup =json_encode($customAppForm);

                   }
                }

                if ($transactionId !== false) {
                    $candidateTransaction = new CandidateTransactions();
                    $candidateTransaction->transactionId = $transactionId;
                    $candidateTransaction->auth_code = $authCode;
                    $candidateTransaction->amount = $amount;
                    $candidateTransaction->paymentType = CandidateTransactions::TYPE_ELECTRONIC_PAYMENT;
                    $candidateTransaction->candidateId = $candidateSession->candidate_id;
                    $candidateTransaction->save();
                }
            }

            if ($appType && $appType->name === 'Test') {
                $candidate->written_nccco_fee_override = 0;
                $candidate->practical_nccco_fee_override = 0;
                $written_nccco_fee_override = 0;
                $practical_nccco_fee_override = 0;
            }

            if ($isRecert == false) {
                if ($testSession != null && $testSession->practical_test_session_id != '') {
                    $practicalSession = TestSession::findOne($testSession->practical_test_session_id);
                    if ($practicalSession != null) {
                        $candidatePracticalSession = new CandidateSession();
                        $candidatePracticalSession->candidate_id = $candidateId;
                        $candidatePracticalSession->test_session_id = $practicalSession->id;
                        $candidatePracticalSession->save();
                    }
                }
            }
            $registration_step = 3;
            $isPurchaseOrderr  = $isPurchaseOrder;
            $candidate->registration_step = '3';
            $candidate->isPurchaseOrder = $isPurchaseOrder;
            if ($finalPromoCode !== false) {
                $candidate->referralCode = $finalPromoCode;
                $referralCode =  $finalPromoCode;
            } else {
                $referralCode = '';
                $candidate->referralCode = '';
            }

            if ($poNumber) {
                $candidate->purchase_order_number = $poNumber;
                $purchase_order_number = $poNumber;
            }
            $candidate->save();
        }
        
        $maxTimes = 3;
        $times = 0;
        while ($times < $maxTimes) {
            try {
                PipeDriveHelper::postDeal($candidate);
                $times = $maxTimes;
            } catch (\Exception $e) {
                $times++;
            }
        }
    }
    public function actionSendcall(){
        if(isset($_POST['date'])){
            $data = [];
            $data['name'] = $_POST['nameuser'];
            $data['phone'] = $_POST['phoneuser'];
            $data['date'] = $_POST['date'];
            $data['time'] = $_POST['time'];
            $data['email'] = $_POST['emailuser'];
            $deal = PipeDriveHelper::callDeal($data);
            echo $deal;
        }

    }
    public function actionConfirmation()
    {
        if (count($_POST) != 0) {
            $paymentSuccess = false;
            if (isset($_POST['x_response_code']) && isset($_POST['x_response_reason_code']) && $_POST['x_response_code'] == '1' && $_POST['x_response_reason_code'] == '1') {
                $paymentSuccess = true;
                $promoCode = isset($_POST['x_promo']) ? $_POST['x_promo'] : '';
                $candidateId = base64_decode($_POST['x_cId']);
                $poNumber = isset($_POST['x_poNumber']) && $_POST['x_poNumber'] !== '' ? $_POST['x_poNumber'] : false;
                $testSessionId = base64_decode($_POST['x_sesId']);
                $this->processUserRegistration($candidateId, $testSessionId, $promoCode, $_POST['x_trans_id'], $_POST['x_amount'], $poNumber, $_POST['x_auth_code']);

                return $this->render('redirect', ['redirectUrl' => $_POST['x_thankyou_url']]);
            } else {
                $additionalMsg = '';
                if (isset($_POST['x_response_reason_text'])) {
                    $additionalMsg = $_POST['x_response_reason_text'];
                }
                if (isset($_POST['x_profile_url']) && $_POST['x_profile_url'] != '') {
                    \Yii::$app->getSession()->setFlash('error', 'Payment Failed, Please try again: ' . $additionalMsg);
                    return $this->redirect($_POST['x_profile_url']);
                }
                return $this->render('//home/index', ['message' => 'Payment Failed, Please try again: ' . $additionalMsg, 'uniqueCode' => '']);
            }
        }
        return $this->render('//home/index', ['uniqueCode' => '']);
    }

    // TODO streamline registration process - make API
    public function actionFree()
    {
        if (count($_POST) != 0) {
            $cId = base64_decode($_POST['cId']);
            $poNumber = isset($_POST['poNumber']) && $_POST['poNumber'] !== '' ? $_POST['poNumber'] : false;
            $sessionId = ($_POST['sesId']);
            $appTypeId = ($_POST['appTypeId']);
            $d = ($_POST['d']);

            $applicationType = ApplicationType::findOne(base64_decode($appTypeId));

            if (!$applicationType) {
                return $this->render('partials/payment', ['message' =>'Invalid Application Type, please try again', 'id' => $cId,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
            }

            if ($applicationType->price > 0) {
                return $this->render('partials/payment', ['message' =>'Application type is not free, please use process payment workflow to register.', 'id' => $cId,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
            }

            $testSessionId = base64_decode($_POST['sesId']);

            $this->processUserRegistration($cId, $testSessionId, '', false, 0, $poNumber);
            $this->redirect(\Yii::$app->urlManager->createUrl("/register/thankyou?cId=" . base64_encode($cId)));
        }
        return $this->render('//home/index');
    }

    public function actionPasscode()
    {
        if (count($_POST) != 0) {
            $promoCode = $_POST['promoCode'];
            $poNumber = isset($_POST['poNumber']) && $_POST['poNumber'] !== '' ? $_POST['poNumber'] : false;
            $cId = base64_decode($_POST['cId']);
            $sessionId = ($_POST['sesId']);
            $appTypeId = ($_POST['appTypeId']);
            $isFullDiscount = $_POST['isFullDiscount'];
            $d = ($_POST['d']);
            $isPurchaseOrder = false;
            if ($promoCode != '') {
                $promoCodes = PromoCodes::findOne(['code' => $promoCode]);
                if ($promoCodes != null) {
                    $isPurchaseOrder = $promoCodes->isPurchaseOrder == 1 ? true : false;
                }
            }

            if ($isPurchaseOrder || $isFullDiscount == 1) {
                $paymentSuccess = true;

                $candidateId = $cId;
                $testSessionId = base64_decode($_POST['sesId']);
                $promoCode = ($_POST['promoCode']);
                $this->processUserRegistration($candidateId, $testSessionId, $promoCode, false, 0, $poNumber, null);
                $this->redirect(\Yii::$app->urlManager->createUrl("/register/thankyou?cId=" . base64_encode($candidateId)));
            } else {
               return $this->render('partials/payment', ['message' =>'Invalid Passcode, please try again', 'id' => $cId,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
            }
        }
        return $this->render('//home/index');
    }

    public function actionThankyou()
    {
        $cId = $_REQUEST['cId'];
        $candidate = Candidates::findOne(base64_decode($cId));

        if ($candidate->applicationType->keyword === 'test') {
            $testSession = $candidate->writtenTestSession->testSession;
            $testCoordinator = $testSession->getTestCoordinatorName(false);
            $testSiteNumber = $testSession->testSiteNumber;
            $testSite = $testSession->testSite;
            $testingDate = date_format(date_create($testSession->testing_date), 'm/d/Y');

            return $this->render('confirmation-test-only', [
                'testCoordinator' => $testCoordinator,
                'testSiteNumber' => $testSiteNumber,
                'testSite' => $testSite,
                'testingDate' => $testingDate,
                'cId' => $cId,
                'decodeCId' => base64_decode($cId)]
            );
        }

        return $this->render('confirmation', ['cId'=>$cId, 'decodeCId' => base64_decode($cId)]);
    }

    public function actionBack()
    {
        $sessionId = $_REQUEST['sesId'];
        $appTypeId = $_REQUEST['appTypeId'];
        $referralCode = isset($_REQUEST['referralCode']) ? $_REQUEST['referralCode'] : '';
        $d = $_REQUEST['d'];
        $resp = array();
        $resp['status'] = 0;
        if ($d == base64_encode(date('Ymd', strtotime('now')))) {
            if (count($_GET) != 0) {
                $model = Candidates::findOne(['id'=>$_GET['candidateId']]);
                    if ($_GET['step'] == 1) {
                        $resp['status'] = 1;
                        $model->ssn = $model->getSsn();
                        $resp['html'] =  $this->renderAjax('partials/info', ['referralCode'=> $referralCode, 'model' => $model, 'id' => $model->id,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
                    } else if ($_GET['step'] == 1.1) {
                        $resp['status'] = 1;
                        $resp['html'] =  $this->renderAjax('partials/survey', ['model' => $model, 'id' => $model->id,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
                    } else if ($_GET['step'] == 2) {
                        $resp['status'] = 1;
                        $model->ssn = $model->getSsn();
                        $resp['html'] =  $this->renderAjax('partials/more-info', ['model' => $model, 'id' => $model->id,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
                    } else if ($_GET['step'] == 0) {
                        $resp['status'] = 1;
                        $appTypeId = $_GET['appTypeId'];
                        $referralCode = isset($_GET['referralCode']) ? $_GET['referralCode'] : '';
                        $uniqueCode = isset($_GET['uniqueCode']) ? $_GET['uniqueCode'] : '';
                        $sesID = base64_decode($sessionId);
                        $sess = TestSession::findOne($sesID);
                        $testSiteId = ($sess->test_site_id);
                        $appType = ApplicationType::findOne(base64_decode($appTypeId));
                        $resp['html'] =  $this->renderPartial('choose-location',
                            ['appType' => $appType,
                                'referralCode' => $referralCode,
                                'uniqueCode' => $uniqueCode,
                                'testSiteId' => $testSiteId,
                                'candidateId' => $model != null ? $model->id : ''
                            ]);
                    }
                echo json_encode($resp);
                die;
            }
            return $this->render('partials/info', [
                'referralCode' => $referralCode,
                'model' => new Candidates(),
                'sesId'=>$sessionId,
                'appTypeId' => $appTypeId,
                'd' =>$d]);
        }
    }

    public function actionInfo()
    {
        $sessionId = $_REQUEST['sesId'];
        $candidateId = $_REQUEST['candidateId'];
        $appTypeId = $_REQUEST['appTypeId'];
        $referralCode = isset($_REQUEST['referralCode']) ? $_REQUEST['referralCode'] : '';
        $uniqueCode = isset($_REQUEST['uniqueCode']) ? $_REQUEST['uniqueCode'] : '';
        $d = $_REQUEST['d'];
        $resp = array();
        $resp['status'] = 0;
        if ($d == base64_encode(date('Ymd', strtotime('now')))) {
            if (count($_POST) != 0) {
                $model = new Candidates();
                if ($_POST['candidateId'] != '') {
                    $model = Candidates::findOne(['id'=>$_POST['candidateId']]);
                }

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    if ($_POST['step'] == 1) {
                        if ($model->survey == 'Ad (Online)') {
                            $model->friend_email = '';
                            $model->surveyOther = '';
                        } else if($model->survey == 'Heard from a friend') {
                            $model->ad_online_info = '';
                            $model->surveyOther = '';
                        } else if($model->survey == 'Other') {
                            $model->ad_online_info = '';
                            $model->friend_email = '';
                        } else if($model->survey == 'Flyer') {
                            $model->ad_online_info = '';
                            $model->friend_email = '';
                            $model->surveyOther = '';
                        }
                        $resp['status'] = 1;
                        $model->ssn1 = 'XXXX';
                        $model->ssn2 = 'XX';
                        $model->ssn3 = $model->ssn;
                        $model->save();
                        $resp['xx']=$model->errors;

                        $appType = ApplicationType::findOne(base64_decode($_GET['appTypeId']));
                        $testSession = TestSession::findOne(base64_decode($sessionId));

                        $resp['html'] = $this->renderAjax('partials/payment', [
                            'nonAjax' => false,
                            'model' => $model,
                            'id' => $model->id,
                            'sesId' => $sessionId,
                            'appTypeId' => $appTypeId,
                            'appType' => $appType,
                            'appTypeIsPracticalOnly' => $appType->isPracticalOnly,
                            'testSession' => $testSession,
                            'd' =>$d,
                            'uniqueCode' => $uniqueCode
                        ]);
                    }
                } else {
                    $resp['html'] = $this->renderAjax('partials/info', [
                        'model' => $model, 'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d, 'referralCode'=>$referralCode
                    ]);
                }

                echo json_encode($resp);
                die;
            }

            $model = new Candidates();
            if (isset($_REQUEST['candidateId']) && $_REQUEST['candidateId'] != '') {
                $cands = Candidates::find()->where("md5(id) = '".$_REQUEST['candidateId']."'")->all();
                if (count($cands) != 0) {
                    $model = $cands[0];
                }
            }
            if (isset($_REQUEST['paymentStep']) && $_REQUEST['paymentStep'] == 1) {
                return $this->render('partials/payment',
                            ['nonAjax' => true, 'model' => $model, 'id' => $model->id,'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d, 'uniqueCode' => $uniqueCode]);
            }
            return $this->render('partials/info', ['model' => $model,'referralCode'=>$referralCode, 'sesId'=>$sessionId, 'appTypeId' => $appTypeId, 'd' =>$d]);
        }
        $this->render('//home/index', ['message' => 'Invalid Session, Please try again']);
    }

    public function actionReport()
    {
        $this->layout = "login";
        $userPasswordResetId = $_GET['id'];
        $key = $_GET['key'];
        $users = User::find()->where("md5(id) = '".$_GET['key']."'")->all();
        $hasError = false;
        if (count($users) == 0) {
            $hasError = 'Account is not valid';
        } else {
            $userResetPassword = UserResetPassword::find()->where("md5(id) = '".$userPasswordResetId."'")->all();
            NotificationHelper::notifyAdminUnauthorizePasswordReset($users[0], $userResetPassword[0]);
        }
        return $this->render('login', ['message' => 'Unauthorized password reset has been reported to the admin, thank you!']);
    }

    public function actionForget()
    {
        if (count($_POST) != 0) {
            $resp = array();
            $users = User::find()->where("username in ('".$_POST['username']."')")->all();
            if (count($users) != 0) {
                $userReset = UserResetPassword::find()->where("user_id = ".$users[0]->id." and date_requested = '" . date('Y-m-d', strtotime('now'))."'")->all();
                if (count($userReset) >= 3) {
                    NotificationHelper::notifyAdminOFMoreThan3Reset($users[0], $userReset);
                    return $this->render('forget', ['error' => 'Password can only be reset 3 times in a day']);
                } else {
                    $userPassword = new UserResetPassword();
                    $userPassword->ip_address = $_SERVER['REMOTE_ADDR'];
                    $userPassword->user_id = $users[0]->id;
                    $userPassword->save();
                    NotificationHelper::sendPasswordRecovery($users[0], $userPassword->id);
                    $resp['status'] = 1;
                    return $this->render('forget', ['message' => 'Recovery Email Sent']);
                }
            } else {
                $resp['status'] = 0;
                return $this->render('forget', ['error' => 'Account is invalid']);
            }
        }
        return $this->render('forget');
    }

    public function actionReset()
    {
        if(count($_POST) != 0){
            $password = $_POST['password'];
            $key = $_POST['key'];
            if ($_POST['password'] != $_POST['confirmPassword']) {
                return $this->render('reset', ['key'=>$key, 'error' => 'Password does not match']);
            }

            $users = User::find()->where("md5(id) = '".$key."'")->all();
            if (count($users) == 0) {
                $hasError = 'User is not existing';
                return $this->render('reset', ['key'=>$key, 'error' => $hasError]);
            } else {
                $user = $users[0];
                $user->password = $_POST['password'];
                $user->save('false', ['password']);

                $loginForm = new LoginForm();
                $resp = $loginForm->simulateLogin($user);
                if ($resp !== false) {
                    $this->redirect(\Yii::$app->urlManager->createUrl("/home"));
                } else {
                    $this->redirect(\Yii::$app->urlManager->createUrl("/site"));
                }
            }
        } else {
            $users = User::find()->where("md5(id) = '".$_GET['key']."'")->all();
            $hasError = false;
            if (count($users) == 0) {
                $hasError = 'Account is not valid';
            }
            return $this->render('reset', ['key'=>$_GET['key'], 'error' => $hasError]);
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->redirect(\Yii::$app->urlManager->createUrl("/classes"));
        }

        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();
        if ($model->login()) {
            if ($model->getUser()->active == 1) {
                $this->redirect(\Yii::$app->urlManager->createUrl("/home"));
            } else {
                Yii::$app->user->logout();
                return $this->render('login', [
                    'model' => $model,
                    'error2' => 'Account is not activated',
                    'inactive' => true,
                    'id' => $model->getUser()->id
                    ]);
            }
        } else {
            if (\Yii::$app->request->isPost) {
                return $this->render('login', [
                    'model' => $model,
                    'error' => "Invalid Login Credentials"
                ]);
            } else {
                return $this->render('login', [
                    'model' => $model
                    ]);
            }
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->render('//home/index');
    }
}
