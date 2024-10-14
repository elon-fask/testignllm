<?php


namespace app\controllers;

use app\models\TestSession;
use app\models\TestSite;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\helpers\NotificationHelper;
use app\models\UserResetPassword;
use app\helpers\UtilityHelper;
use app\models\CandidateTransactions;
use app\models\Candidates;
use app\models\ApplicationType;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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

    public function actionIndex()
    {
        $_SESSION['branding'] = UtilityHelper::getSubdomain();
        if(UtilityHelper::getCurrentBranding() != '')
            return $this->render('//home/index', ['uniqueCode' => '']);
        else
            return $this->redirect('/admin');
    }

    public function actionReact()
    {
        $this->layout = 'main-new';

        $timeNow = date('Y-m-d', strtotime('now'));
        $testSites = [];

        if (isset($_GET['id'])) {
            $testSites[] = TestSite::findOne([
                'type' => TestSite::TYPE_WRITTEN,
                'uniqueCode' => $_GET['id'],
                'scheduleType' => TestSite::SCHEDULE_TYPE_OPENED
            ]);
        } else {
            $testSessions = TestSession::find()->where([
                '>', 'registration_close_date', $timeNow
            ])->orderBy('start_date asc')->all();

            $testSiteIDs = [];
            foreach ($testSessions as $testSession) {
                $testSiteIDs[] = $testSession->test_site_id;
            }
            $testSites = TestSite::find()->where(['in', 'id', $testSiteIDs])->all();
        }

        $testSiteArr = ArrayHelper::toArray($testSites, ['app\models\TestSite' => [
            'id',
            'city',
            'state',
            'name',
            'testSessions' => function($testSite) {
                $timeNow = date('Y-m-d', strtotime('now'));
                $testSessions = $testSite->getTestSessions()->where([
                    '>', 'registration_close_date', $timeNow
                ])->orderBy('start_date asc')->all();

                return ArrayHelper::toArray($testSessions, ['app\models\TestSession' => [
                    'id',
                    'startDate' => 'start_date',
                    'endDate' => 'end_date'
                ]]);
            }
        ]]);

        return $this->render('/home/index-react', [testSites => $testSiteArr]);
    }

    public function actionCheckPassword($password)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $applicationType = ApplicationType::findOne(['keyword' => $password]);

        if ($applicationType) {
            return [
                'password' => $password,
                'valid' => true
            ];
        }

        Yii::$app->response->statusCode = 404;
        return [
            'error' => "No Application Type found for given password $password."
        ];
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
                $userReset = UserResetPassword::find()->where("user_id = ".$users[0]->id." and date_requested = '".date('Y-m-d', strtotime('now'))."'")->all();
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
        if (count($_POST) != 0) {
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
                    'model' => $model, 'error2' => "Account is not activated", 'inactive' => true, 'id' => $model->getUser()->id
                ]);
            }
        } else {
            if (\Yii::$app->request->isPost) {
                return $this->render('login', [
                    'model' => $model, 'error' => "Invalid Login Credentials"
                ]);
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionConfirmationpayment()
    {
        if (count($_POST) != 0) {
            $paymentSuccess = false;

            $hasSuccessResponseCode = isset($_POST['x_response_code']) && $_POST['x_response_code'] == '1';
            $hasSuccessResponseReasonCode = isset($_POST['x_response_reason_code']) && $_POST['x_response_reason_code'] == '1';

            if ($hasSuccessResponseCode && $hasSuccessResponseReasonCode) {
                $paymentSuccess = true;
                $promoCode = isset($_POST['x_promo']) ? $_POST['x_promo'] : '';
                $candidateId = base64_decode($_POST['x_cId']);
                $amount = $_POST['x_amount'];
                $candidateTransaction = new CandidateTransactions();
                $candidateTransaction->transactionId = $_POST['x_trans_id'];
                $candidateTransaction->amount = $amount;
                $candidateTransaction->paymentType = CandidateTransactions::TYPE_ELECTRONIC_PAYMENT;
                $candidateTransaction->candidateId = $candidateId;
                $candidateTransaction->remarks = isset($_POST['x_remarks']) ? $_POST['x_remarks'] : '';
                $candidateTransaction->save();

                $candidate = Candidates::findOne(['id'=>$candidateId]);
                $message = false;

                if ($candidate) {
                    $message = 'Payment Successful';
                }

                return $this->render('payment', [
                    'candidate' => $candidate,
                    'message' => $message,
                    'redirectUrl' => $_POST["x_payment_success_url"]
                ]);
            }
        }
        return $this->redirect('/admin/candidates');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->render('//home/index');
    }

    public function actionCsvContact()
    {
        $allCandidates = Candidates::find()->where("email != '' order by id asc")->all();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=contacts.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array('First Name', 'Last Name', 'Email', 'Phone #'));
        foreach($allCandidates as $candidate){
            $firstName = $candidate->first_name;
            $lastName  = $candidate->last_name;
            $email = $candidate->email;
            $phone = $candidate->phone;

            fputcsv($output, [$firstName, $lastName, $email, $phone]);

        }
        die;
    }

    public function actionCsvCompany()
    {
        $allCandidates = Candidates::find()->where("company_name != '' order by id asc")->all();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=company.csv');

        $output = fopen('php://output', 'w');

        fputcsv($output, array('Company Name',  'Phone #', 'Street Address', 'City', 'Postal Code'));
        foreach($allCandidates as $candidate){
            $companyName = $candidate->company_name;
            $companyPhone  = $candidate->company_phone;
            $companyAddress = $candidate->company_address;
            $city = $candidate->company_city;
            $zip = $candidate->company_zip;
            fputcsv($output, [$companyName, $companyPhone, $companyAddress, $city, $zip]);
        }
        die;
    }
}
