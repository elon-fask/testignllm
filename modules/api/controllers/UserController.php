<?php

namespace app\modules\api\controllers;

use app\models\User;
use app\models\TestSession;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

class UserController extends ActiveController
{
    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => [getenv('CRANETRX_URL')],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
        ];
    }

    public $modelClass = 'app\models\User';

    public function actionLogin()
    {
        $request = \Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();

            $user = User::findOne(['username' => $postData['username']]);

            if (isset($user)) {
                if (isset($postData['password'])) {
                    if (!$user->validatePassword($postData['password'])) {
                        throw new \yii\web\BadRequestHttpException('Incorrect password.');
                    }
                }

                if (isset($postData['otp'])) {
                    $otpToken = $user->validateOtp($postData['otp']);
                    if (isset($otpToken)) {
                        $otpToken->delete();
                    } else {
                        throw new \yii\web\BadRequestHttpException('Invalid OTP token.');
                    }
                }

                $resp = ArrayHelper::toArray($user, [
                    'app\models\User' => [
                        'id',
                        'firstName' => 'first_name',
                        'lastName' => 'last_name',
                        'username',
                        'role',
                        'ongoingClasses' => function($user) {
                            return TestSession::getStaffOngoingSessions($user->id);
                        },
                        'upcomingClasses' => function($user) {
                            return TestSession::getStaffUpcomingSessions(30, $user->id);
                        },
                        'otherClasses' => function() {
                            return TestSession::getAllClasses();
                        }
                    ]
                ]);

                return $resp;
            }

            throw new \yii\web\NotFoundHttpException('Username not found.');
        }

        throw new \yii\web\MethodNotAllowedHttpException();
    }

    public function actionClasses($id)
    {
        $user = User::findOne($id);

        if (!isset($user)) {
            throw new \yii\web\BadRequestHttpException('Invalid user.');
        }

        $resp = ArrayHelper::toArray($user, [
            'app\models\User' => [
                'ongoingClasses' => function($user) {
                    return TestSession::getStaffOngoingSessions($user->id);
                },
                'upcomingClasses' => function($user) {
                    return TestSession::getStaffUpcomingSessions(30, $user->id);
                }
            ]
        ]);

        return $resp;
    }
}
