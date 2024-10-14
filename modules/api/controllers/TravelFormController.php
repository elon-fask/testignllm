<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use app\models\TravelForm;
use app\models\AppConfig;

class TravelFormController extends ActiveController
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

    public $modelClass = 'app\models\TravelForm';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);

        return $actions;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
    }

    public function actionCreate()
    {
        $model = new TravelForm();

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));

            $recipientListConfig = AppConfig::findOne(['code' => AppConfig::TRAVEL_FORM_EMAIL_RECIPIENT]);
            $recipientList = explode(', ', $recipientListConfig->val);

            \Yii::$app->mailer->htmlLayout = 'layouts/new';
            $message = \Yii::$app->mailer->compose('new-travel-form', ['travelForm' => $model])
                ->setTo($recipientList)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject('New Crane School Travel Form sent by ' . $model->name);
            $message->send();

        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
