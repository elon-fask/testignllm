<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\ApplicationType;
use app\models\ApplicationTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\ViewContextInterface;
use app\models\ApplicationTypeFormSetup;
use app\models\Candidates;
use app\models\AppConfig;
use app\helpers\PipeDriveHelper;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class SettingsController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'test'],
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
        ];
    }

    /**
     * Lists all ApplicationType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $message = false;
        if (count($_POST) != 0) {
            $appConfigs = $_POST['AppConfig'];
            foreach ($appConfigs as $code => $val) {
                $appConfig = AppConfig::findOne(['code' => $code]);
                if ($appConfig != null) {
                    $appConfig->val = $val;
                    $appConfig->save();
                }
            }
            $message = 'Application Settings Saved Successfully';
        }

        $appConfigs = AppConfig::find()->where([
            'code' => [
                'UNFINISHED_REGISTRATION_EMAIL_RECIPIENT',
                'NEW_CANDIDATES_CCS_EMAIL_RECIPIENT',
                'NEW_CANDIDATES_ACS_EMAIL_RECIPIENT',
                'TRAVEL_FORM_EMAIL_RECIPIENT',
                'UPCOMING_CLASS_REPORT_EMAIL_RECIPIENT',
                'PIPEDRIVE_API_KEY'
            ]
        ])->orderBy([
            'sort_order' => SORT_ASC
        ])->all();

        $pipedriveStages = PipeDriveHelper::getStages() ?? [];
        $pipedriveInitialStageConfig = AppConfig::findOne(['code' => 'PIPEDRIVE_INITIAL_STAGE']);
        $pipedriveInitialStage = $pipedriveInitialStageConfig ? $pipedriveInitialStageConfig->val : false;

        return $this->render('index', [
            'message' => $message,
            'appConfigs' => $appConfigs,
            'pipedriveStages' => $pipedriveStages,
            'pipedriveInitialStage' => $pipedriveInitialStage
        ]);
    }

    public function actionTest($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $candidate = Candidates::findOne($id);

        if (isset($candidate)) {
            return PipeDriveHelper::postDeal($candidate);
        }

        return [
            'result' => 'OK'
        ];
    }
}
