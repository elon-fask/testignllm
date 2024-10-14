<?php

namespace app\modules\admin\controllers;

use Yii;
//use yii\db\Command;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\ChecklistTemplate;
use app\models\TestSite;
use app\models\TestSession;

class CalendarController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'events', 'session-data-json'],
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
        return $this->render('index', []);
    }

    public function actionEvents()
    {
//\Yii::$app->db->createCommand()->update('candidate_session',['test_session_id' => 757],'id  = 15816')
//->execute();
//\Yii::$app->db->createCommand()->delete('test_session' ,'id  = 757')->execute();
        $resp = [];
$arr = [];
        $sessions = TestSession::find()->where("start_date >= '".$_REQUEST['start']."' and end_date <= '".$_REQUEST['end']."' ")->orderBy('start_date')->all();
  
      foreach($sessions as $session){
            $start = '';
            $end = '';
            if($session->start_date != ''){
                $start = date('Y-m-d', strtotime($session->start_date));
            }
            if($session->end_date != ''){
                $end = date('Y-m-d', strtotime($session->end_date));
            }

            if($start != $end){
                $end = date('Y-m-d 23:00:00', strtotime($session->end_date));
            }

            $start = date('Y-m-d 06:00:00', strtotime($session->start_date));
$t = explode(',',$session->getFullTestSessionDescription());
/*
if(strpos(trim($t[0]),' ')){
	$t[0] = explode(' ',trim($t[0]))[1];
}*/
if(!in_array(strtotime($start).strtotime($end).trim($t[0]),$arr)){


            $resp[] = [
                'title' => $session->getFullTestSessionDescription(),
                'start' => $start, 'end' => $end,
                'id' => $session->id,
                'url' => '/admin/testsession/view?id='.$session->id,
                'allDay' => true,
                'editable' => false,
                'className' => strtolower($session->getTestSessionType()). ' '.'event-id-'.$session->id
            ];
        }
//$arr[] = strtotime($start).strtotime($end).trim($t[0]);
}
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $resp;
    }

    public function actionSessionDataJson(){
        $id = $_REQUEST['id'];
        $testSession = TestSession::findOne(['id' => $id]);
        $testSite = TestSite::findOne($testSession->test_site_id);

        $resp = [
            'testSessionInfo' => [
                'id' => $testSession->id,
                'idHash' => md5($testSession->id),
                'testSite' => $testSite->name,
                'address' => $testSite->getCompleteTestSiteLocation(),
                'date' => $testSession->getDateInfo(),
                'staff' => $testSession->getStaffName(),
                'testSiteCoordinator' => $testSession->getTestCoordinatorName(),
                'totalSeats' => $testSession->numOfCandidates,
                'totalSeatsTaken' => $testSession->getNumberOfRegisteredCandidates(),
                'sessionType' => $testSession->getTestSessionType(),
                'classStats' => $testSession->classStats
            ]
        ];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $resp;
    }
}
