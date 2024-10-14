<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TestSessionClassSchedule;
use app\models\TestSessionClassScheduleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\TestSession;
use app\models\CandidateTestSessionClassSchedule;

/**
 * ClassScheduleController implements the CRUD actions for TestSessionClassSchedule model.
 */
class ClassScheduleController extends CController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TestSessionClassSchedule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestSessionClassScheduleSearch();
        $testSession = $this->findTestSessionModelMd5($_REQUEST['id']);
        $params = Yii::$app->request->queryParams;
        $searchModel->testSessionId = $testSession->id;
        unset($params['id']);
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'testSession' => $testSession
        ]);
    }

    /**
     * Displays a single TestSessionClassSchedule model.
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
     * Creates a new TestSessionClassSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TestSessionClassSchedule();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => md5($model->testSessionId)]);
        } else {
            if(isset($_REQUEST['id'])){
                $testSession = $this->findTestSessionModelMd5($_REQUEST['id']);
                $model->testSessionId = $testSession->id;
            }
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TestSessionClassSchedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => md5($model->testSessionId)]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TestSessionClassSchedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteasync()
    {
        $id = $_POST['id'];

        $testSessionClassSchedule = $this->findModel($id);
        
        $candidateSchedules = CandidateTestSessionClassSchedule::findAll(['testSessionClassScheduleId' => $testSessionClassSchedule->id]);
        foreach($candidateSchedules as $sched){
            $sched->delete();
        }
        
        $testSessionClassSchedule->delete();
         echo 1;
        die;
    }

    /**
     * Finds the TestSessionClassSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestSessionClassSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestSessionClassSchedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findTestSessionModelMd5($id)
    {
        $session = TestSession::find()->where("md5(id) ='".$id."'")->all();
        $model = null;
        if(count($session) != 0){
            $model = $session[0];
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    
    }
}
