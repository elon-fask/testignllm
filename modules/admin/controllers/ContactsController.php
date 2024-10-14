<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\CandidatesSearch;
use app\models\Candidates;
use app\models\CandidateSession;
use app\models\CandidatePreviousSession;
use PhpOffice\PhpSpreadsheet;

class ContactsController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'download', 'download-email', 'download-all-email'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className()
            ],
        ];
    }

    public function actionIndex($startDate = null, $endDate = null)
    {
        $request = \Yii::$app->request;
        $searchModel = new CandidatesSearch();
        $params = $request->queryParams;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'startDate' => $params['startDate'] ?? '',
            'endDate' => $params['endDate'] ?? ''
        ]);
    }

    public function actionDownload ($startDate = null, $endDate = null)
    {
        if (\Yii::$app->user->isGuest) {
            $this->redirect('/admin');
        }

        $request = \Yii::$app->request;
        $searchModel = new CandidatesSearch();
        $params = $request->queryParams;

        $dataProvider = $searchModel->search($params);

        $dataProvider->setPagination(false);
        $candidates = $dataProvider->getModels();

        $candidatesArr = ArrayHelper::toArray($candidates, [
            'app\models\Candidates' => [
                0 => 'last_name',
                1 => 'first_name',
                2 => 'email',
                3 => 'phone',
                4 => 'cellNumber',
                5 => 'company_name',
                6 => 'contactEmail',
                7 => 'company_phone'
            ]
        ]);

        $worksheetArr = array_merge([[
            'Last Name',
            'First Name',
            'Personal Email',
            'Home Phone',
            'Cell Phone',
            'Company',
            'Company Email',
            'Company Phone'
        ]], $candidatesArr);

        $filename = '/tmp/contacts-list.xlsx';
        file_exists($filename) && unlink($filename);
        $spreadsheet = new PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        $worksheet = new PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'contacts');
        $worksheet->fromArray($worksheetArr);
        $spreadsheet->addSheet($worksheet, 0);
        $spreadsheet->setActiveSheetIndex(0);

        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->getColumnDimension('A')->setAutoSize(true);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);

        $writer = new PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return \Yii::$app->response->sendFile($filename);
    }

    public function actionDownloadEmail()
    {
        return $this->render('download-email');
    }

    public function actionDownloadAllEmail($school)
    {
        if (\Yii::$app->user->isGuest) {
            $this->redirect('/admin');
        }

        $previousSessionQuery = new \yii\db\Query;

        $previousSessionEmails = $previousSessionQuery->select('candidates.email')
            ->distinct()
            ->from('candidate_previous_session')
            ->leftJoin('candidates', 'candidate_previous_session.candidate_id = candidates.id')
            ->leftJoin('test_session', 'candidate_previous_session.test_session_id = test_session.id')
            ->where(['test_session.school' => $school])
            ->all();

        $currentSessionQuery = new \yii\db\Query;

        $currentSessionEmails = $currentSessionQuery->select('candidates.email')
            ->distinct()
            ->from('candidate_session')
            ->leftJoin('candidates', 'candidate_session.candidate_id = candidates.id')
            ->leftJoin('test_session', 'candidate_session.test_session_id = test_session.id')
            ->where(['test_session.school' => $school])
            ->all();

        $emails = array_unique(array_map(function($emailArr) {
            return $emailArr['email'];
        }, $previousSessionEmails, $currentSessionEmails));

        $worksheetArr = array_map(function($email) {
            return [$email];
        }, $emails);

        $filename = '/tmp/email-list-' . strtolower($school) . '.xlsx';
        file_exists($filename) && unlink($filename);
        $spreadsheet = new PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        $worksheet = new PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'email-' . strtolower($school));
        $worksheet->fromArray($worksheetArr);
        $spreadsheet->addSheet($worksheet, 0);
        $spreadsheet->setActiveSheetIndex(0);

        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->getColumnDimension('A')->setAutoSize(true);

        $writer = new PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return \Yii::$app->response->sendFile($filename);
    }
}
