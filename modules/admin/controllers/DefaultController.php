<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\User;
use app\models\UserOtp;
use app\models\UserRole;
use app\helpers\NotificationHelper;
use app\models\UserResetPassword;
use app\helpers\UtilityHelper;

class DefaultController extends Controller
{
    public function beforeAction($event)
    {
        $this->layout = "/../../modules/admin/views/layouts/login";
        return parent::beforeAction($event);
    }

    public function actionIndex()
    {
        return $this->actionLogin();
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
            $this->redirect(\Yii::$app->urlManager->createUrl('/admin/home'));
        }

        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();
        if ($model->login()) {
            $user = $model->getUser();

            if ($user->active == 1) {
                if (UtilityHelper::isSuperAdmin()) {
                    return $this->goBack('/admin/home');
                } elseif (in_array(UserRole::TRAVEL_COORDINATOR, $user->roles)) {
                    return $this->goBack('/admin/home');
                }

                $otp = new UserOtp();
                $otp->user_id = $user->id;
                $otp->otp_token = Yii::$app->getSecurity()->generateRandomString();

                $tz = 'America/Los_Angeles';
                $ts = time() + 3600;
                $dt = new \DateTime('now', new \DateTimeZone($tz));
                $dt->setTimestamp($ts);
                $dateTimeStr = $dt->format('Y-m-d H:i:s');

                $otp->expires_at = $dateTimeStr;
                if ($otp->save()) {
                    return $this->redirect(\Yii::$app->urlManager->createUrl('/cranetrx/#/?user=' . $user->username . '&otp=' . htmlspecialchars($otp->otp_token, ENT_SUBSTITUTE)));
                }

                return $this->redirect(\Yii::$app->urlManager->createUrl('/cranetrx'));
             } else {
                 Yii::$app->user->logout();
                 return $this->render('login', [
                     'model' => $model,
                     'error2' => "Account is not activated",
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
        return $this->render('login');
    }
}
