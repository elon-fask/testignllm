<?php

namespace app\modules\api\controllers;

use app\models\CompanyTransaction;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

class CompanyTransactionController extends ActiveController
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

    public $modelClass = 'app\models\CompanyTransaction';

    public function actionDetails($id) {
        $companyTx = CompanyTransaction::findOne($id);

        $result = ArrayHelper::toArray($companyTx, [
            'app\models\CompanyTransaction' => [
                'id',
                'companyName' => function($tx) {
                    return $tx->company->name;
                },
                'amount',
                'type',
                'transactionId' => 'transaction_id',
                'authCode' => 'auth_code',
                'checkNumber' => 'check_number',
                'postedBy' => function($tx) {
                    $user = $tx->postedBy;
                    return $user->first_name . ' ' . $user->last_name;
                },
                'lastUpdated' => 'updated_at'
            ]
        ]);

        return $result;
    }

    public function actionCandidateTransactions($id) {
        $companyTx = CompanyTransaction::findOne($id);

        $transactions = $companyTx->candidateTransactions;

        $result = ArrayHelper::toArray($transactions, [
            'app\models\CandidateTransactions' => [
                'id',
                'candidate' => function($tx) {
                    $candidate = $tx->candidate;
                    return [
                        'id' => $candidate->id,
                        'name' => $candidate->last_name . ', ' . $candidate->first_name,
                        'hash' => md5($candidate->id)
                    ];
                },
                'amount'
            ]
        ]);

        return $result;
    }
}
