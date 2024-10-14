<?php

namespace app\modules\api\controllers;

use app\models\ApplicationType;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

class ApplicationTypeController extends ActiveController
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

    public $modelClass = 'app\models\ApplicationType';

    public function actionActive()
    {
        $applicationTypes = ApplicationType::findAll(['isArchived' => 0]);

        return $applicationTypes;
    }
}
