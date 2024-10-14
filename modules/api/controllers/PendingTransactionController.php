<?php

namespace app\modules\api\controllers;

use app\models\PendingTransaction;
use app\models\PendingTransactionLineItem;
use app\helpers\NotificationHelper;
use yii\rest\ActiveController;

class PendingTransactionController extends ActiveController
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

    public $modelClass = 'app\models\PendingTransaction';

    public function actionNew()
    {
        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $postData = $request->post();

        $pendingTx = new PendingTransaction();
        $pendingTx->candidate_id = $postData['candidate_id'];
        $pendingTx->posted_by = $postData['posted_by'];
        $pendingTx->amount = $postData['amount'];
        $pendingTx->type = $postData['type'];

        if ($pendingTx->type == 2 && isset($postData['checkNumber'])) {
            $pendingTx->check_number = $postData['checkNumber'];
        }

        if (!$pendingTx->save()) {
            throw new \yii\web\ServerErrorHttpException('Pending Transaction could not be saved.');
        }

        if (isset($postData['lineItems'])) {
            foreach($postData['lineItems'] as $lineItem) {
                $txLineItem = new PendingTransactionLineItem();
                $txLineItem->tx_id = $pendingTx->id;
                $txLineItem->description = $lineItem['description'];
                $txLineItem->amount = $lineItem['amount'];
                $txLineItem->save();
            }
        }

        if (in_array($pendingTx->type, PendingTransaction::PAYMENT_TYPES)) {
            NotificationHelper::notifyPendingTransactionReceipt($pendingTx);
        }

        return $pendingTx;
    }
}
