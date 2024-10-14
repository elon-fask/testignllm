<?php

namespace app\modules\admin\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class UserLogController extends CController
{
   
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index',],
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

    public function actionIndex()
    {
        $apiUrl = getenv('API_HOST_INFO') . getenv('API_URL');

        return $this->render('index', [
            'apiUrl' => $apiUrl,
            'googleMapsApiKey' => getenv('GOOGLE_MAPS_API_KEY')
        ]);
    }
}
