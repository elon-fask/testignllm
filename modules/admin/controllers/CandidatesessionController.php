<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\CandidateSession;
use app\models\CandidateSessionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\TestSession;
use app\models\Candidates;
use app\models\ApplicationType;
use yii\filters\AccessControl;
use app\models\CandidateTestSessionClassSchedule;
use app\models\TestSessionClassSchedule;

/**
 * CandidatesessionController implements the CRUD actions for CandidateSession model.
 */
class CandidatesessionController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'class'],
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
    
    public function actionClass(){
        $testSession = TestSession::findOne(base64_decode($_REQUEST['sId']));
        $candidate = Candidates::findOne(base64_decode($_REQUEST['i']));
        $testSessionClassSchedules = TestSessionClassSchedule::findAll(['testSessionId' => $testSession->id]);
       
        if(count($_POST) > 0){
            $resp = [];
            $resp['status'] = 0;
            $model = new CandidateTestSessionClassSchedule();
            
            if(isset($_POST['CandidateTestSessionClassSchedule']['id']) && $_POST['CandidateTestSessionClassSchedule']['id'] != ''){
                 $model = CandidateTestSessionClassSchedule::findOne($_POST['CandidateTestSessionClassSchedule']['id']);
            }
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
               $resp['status'] = 1;
            }
            
            echo json_encode($resp);
            die;
        }
        

        
        $studentClass = new CandidateTestSessionClassSchedule();
        $studentClass->candidateId = $candidate->id;
        $sortedList = [];
        foreach($testSessionClassSchedules as $sched){
            $candidateClass = CandidateTestSessionClassSchedule::findOne(['candidateId' => $candidate->id, 'testSessionClassScheduleId' => $sched->id]);
            if($candidateClass){
                $studentClass = $candidateClass;
            }
            $sortedList[] = $sched;
        }
        ksort($sortedList);
        
        usort($sortedList, function($a, $b)
        {
            $dateTime1 = \DateTime::createFromFormat('M d, Y', $a->classDate);
            $dateTime2 = \DateTime::createFromFormat('M d, Y', $b->classDate);
            
            return $dateTime1 > $dateTime2;
        });
        //'testSession' => $testSession,  
        return $this->renderPartial('class', ['model' => $studentClass,'candidate' => $candidate, 'availableSchedules' => $sortedList]);
    }
    
    /**
     * Lists all CandidateSession models.
     * @return mixed
     */
    
    public function actionIndex()
    {
        $searchModel = new CandidateSessionSearch();

        $id = $_REQUEST['i'];
        $testSession = false;
        $testSessions = TestSession::find()->where("md5(id) = '".$id."'")->all();
        $totalCandidates = 0;
        if ($testSessions) {
            $testSession = $testSessions[0];
            $searchModel->test_session_id = $testSession->id;
            $candidateSessions = CandidateSession::findAll(['test_session_id' => $testSession->id]);
            $totalCandidates = count($candidateSessions);
        }

        $queryParams = Yii::$app->request->queryParams;
      
        for($x = 0 ; $x < 4 ; $x++){
        	if(isset($queryParams['sort'.$x]) && $queryParams['sort'.$x] != ''){
        		$q = explode('-', $queryParams['sort'.$x]);
        		if($q[0] == 'certification'){
        			$queryParams['sort'.$x] = 'application_type.name '.$q[1];
        		}else if($q[0] == 'firstName'){
        			$queryParams['sort'.$x] = 'first_name '.$q[1];
        		}else if($q[0] == 'lastName'){
        			$queryParams['sort'.$x] = 'last_name '.$q[1];
        		}
        		
        	}
        }

        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'testSession' => $testSession,
            'totalCandidates' => $totalCandidates
        ]);
    }

    /**
     * Displays a single CandidateSession model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    
    /**
     * Updates an existing CandidateSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $candidate = false;
        if(count($_POST) != 0){
            $candidate = Candidates::findOne($model->candidate_id);
            $candidate->requestAda = 0; //we set to default blank
        }
        if ($candidate !== false && $candidate->load(Yii::$app->request->post()) && $candidate->save()) {
            return $this->redirect(['view', 'id' => md5($model->id)]);
        } else {
            
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CandidateSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $candidateSession = $this->findModel($id);

        Candidates::cancelSession($candidateSession->candidate_id, $candidateSession->test_session_id);
        
        return $this->redirect(['index', 'i' => $_REQUEST['i']]);
    }

    /**
     * Finds the CandidateSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CandidateSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $candidates = CandidateSession::find()->where("md5(id) ='".$id."'")->all();
        $model = null;
        if(count($candidates) != 0){
            $model = $candidates[0];
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
