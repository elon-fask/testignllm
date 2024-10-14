<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\CandidateTransactions;
use app\models\Company;
use app\models\CompanyTransaction;
use app\models\CompanyTransactionCandidateTransaction;
use app\models\TestSite;
use app\helpers\QuickBooksOnlineHelper;

class CompanyController extends CController
{
    public function actionIndex()
    {
        $companies = Company::find()->asArray()->all();

        return $this->render('index', [
            'companies' => $companies
        ]);
    }

    public function actionTransaction()
    {
        $companies = Company::find()->asArray()->all();
        $transactions = CompanyTransaction::find()->asArray()->all();
        $testSites = TestSite::find()->asArray()->all();

        $apiUrl = getenv('API_HOST_INFO') . getenv('API_URL');

        return $this->render('transaction', [
            'companies' => $companies,
            'transactions' => $transactions,
            'testSites' => $testSites,
            'apiUrl' => $apiUrl
        ]);
    }

    public function actionAdd($returnAll = false)
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException('Only POST requests are allowed.');
        }

        $postData = $request->post();

        if (!isset($postData['name'])) {
            throw new \yii\web\BadRequestHttpException('Missing name field in request.');
        }

        $company = new Company();
        $company->name = $postData['name'];
        $company->email = $postData['email'] ?? null;
        $company->phone = $postData['phone'] ?? null;

        if (!$company->save()) {
            throw new \yii\web\ServerErrorHttpException('Could not save company.');
        }

        if ($returnAll) {
            $allCompanies = Company::find()->all();

            return $allCompanies;
        }

        return $company;
    }

    public function actionUpdate()
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException('Only POST requests are allowed.');
        }

        $postData = $request->post();

        $company = Company::findOne($postData['id']);

        if (!isset($company)) {
            throw new \yii\web\NotFoundHttpException('Company not found.');
        }

        $company->attributes = $postData;

        $company->save();

        return $company;
    }

    public function actionUpsertBatch()
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException('Only POST requests are allowed.');
        }

        $companiesData = $request->post('companies');

        if (!isset($companiesData)) {
            throw new \yii\web\BadRequestHttpException('Missing companies field in request.');
        }

        $errors = [];

        foreach ($companiesData as $companyData) {
            $company = null;

            if (isset($companyData['id'])) {
                $company = Company::findOne(['qbo_id' => $companyData['id']]);
            }

            if (isset($company)) {
                $company->name = $companyData['name'];
                $company->email = $companyData['email'];
                $company->phone = $companyData['phone'];
                if (!$company->save()) {
                    $errors[] = $company->errors;
                }
            } else {
                $company = new Company();
                $company->qbo_id = $companyData['id'] ?? null;
                $company->name = $companyData['name'];
                $company->email = $companyData['email'];
                $company->phone = $companyData['phone'];
                if (!$company->save()) {
                    $errors[] = $company->errors;
                }
            }
        }

        $companies = Company::find()->all();

        return [
            'companies' => $companies,
            'errors' => $errors
        ];
    }

    public function actionQboImport()
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $query = 'SELECT Id, FullyQualifiedName, PrimaryEmailAddr, PrimaryPhone FROM Customer';
        $queryResult = QuickBooksOnlineHelper::query($query);

        $result = \yii\helpers\ArrayHelper::toArray($queryResult, [
            'QuickBooksOnline\API\Data\IPPCustomer' => [
                'id' => 'Id',
                'name' => 'FullyQualifiedName',
                'email' => function ($customer) {
                    $email = $customer->PrimaryEmailAddr;
                    if (isset($email)) {
                        return $email->Address;
                    }
                    return null;
                },
                'phone' => function ($customer) {
                    $phone = $customer->PrimaryPhone;
                    if (isset($phone)) {
                        return $phone->FreeFormNumber;
                    }
                    return null;
                }
            ]
        ]);

        return $result;
    }

    public function actionQboPayments()
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $query = 'SELECT Id, TotalAmt, CustomerRef, PaymentMethodRef, MetaData.CreateTime, MetaData.LastUpdatedTime FROM Payment';
        $queryResult = QuickBooksOnlineHelper::query($query);

        $result = \yii\helpers\ArrayHelper::toArray($queryResult, [
            'QuickBooksOnline\API\Data\IPPPayment' => [
                'id' => 'Id',
                'totalAmt' => 'TotalAmt',
                'qboCustomerId' => 'CustomerRef',
                'qboCustomer' => function($payment) {
                    $query = "SELECT Id, FullyQualifiedName, CompanyName FROM Customer WHERE Id = '" . $payment->CustomerRef . "'";
                    $queryResult = QuickBooksOnlineHelper::query($query);
                    return [
                        'id' => $queryResult[0]->Id,
                        'name' => $queryResult[0]->FullyQualifiedName,
                        'companyName' => $queryResult[0]->CompanyName
                    ];
                },
                'createdAt' => 'MetaData.CreateTime',
                'updatedAt' => 'MetaData.LastUpdatedTime'
            ]
        ]);

        return $result;
    }

    public function actionAddTransaction()
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException('Only POST requests are allowed.');
        }

        $postData = $request->post();
        $user = \Yii::$app->user->identity;

        $requiredFields = ['company_id', 'type', 'amount'];

        foreach ($requiredFields as $field) {
            if (!isset($postData[$field])) {
                throw new \yii\web\BadRequestHttpException('Missing ' . $field . ' field in request.');
            }
        }

        $errors = [];

        $company = Company::findOne($postData['company_id']);
        if (!isset($company)) {
            throw new \yii\web\NotFoundHttpException('Company not found.');
        }

        $companyTx = new CompanyTransaction();
        $companyTx->company_id = $postData['company_id'];
        $companyTx->type = $postData['type'];
        $companyTx->amount = $postData['amount'];
        $companyTx->posted_by = $user->id;

        if (isset($postData['check_number'])) {
            $companyTx->check_number = $postData['check_number'];
        }

        if (!$companyTx->save()) {
            throw new \yii\web\ServerErrorHttpException('Could not save Company Transaction.');
        }

        if (isset($postData['candidate_transactions'])) {
            foreach ($postData['candidate_transactions'] as $cTxData) {
                $candidateTx = new CandidateTransactions();
                $candidateTx->candidateId = $cTxData['candidate_id'];
                $candidateTx->amount = $cTxData['amount'];
                $candidateTx->paymentType = CompanyTransaction::CANDIDATE_PAYMENT_TYPE_MAPPING[$postData['type']];

                if (isset($postData['check_number'])) {
                    $candidateTx->check_number = $postData['check_number'];
                }

                if (!$candidateTx->save()) {
                    $errors[] = $candidateTx->errors;
                    break;
                }

                $candidateTxLink = new CompanyTransactionCandidateTransaction();
                $candidateTxLink->company_transaction_id = $companyTx->id;
                $candidateTxLink->candidate_transaction_id = $candidateTx->id;

                if (!$candidateTxLink->save()) {
                    $errors[] = $candidateTxLink->errors;
                }
            }
        }

        $companyArr = $companyTx->toArray();
        $companyArr['candidate_transactions'] = $companyTx->candidateTransactions;

        return $companyArr;
    }
}
