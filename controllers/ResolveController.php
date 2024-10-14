<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\helpers\NotificationHelper;
use app\models\UserResetPassword;
use app\models\ApplicationType;
use app\models\TestSession;
use app\models\TestSite;
use app\models\Candidates;
use app\models\CandidateSession;
use app\helpers\UtilityHelper;
use app\models\PromoCodes;
use app\models\CandidateTransactions;
use mikehaertl\pdftk\Pdf;
use app\helpers\AppFormHelper;
use yii\filters\AccessControl;
class ResolveController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'calendar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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

    public function actionIndex($id)
    {
        return $this->redirect('/admin/reports/discrepancy?id='.$id);
    }

    public function actionCalendar($id)
    {
        return $this->redirect('/admin/calendar/?id='.$id);
    }
}
