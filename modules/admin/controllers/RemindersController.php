<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Reminders;
use app\models\RemindersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\helpers\UtilityHelper;

/**
 * RemindersController implements the CRUD actions for Reminders model.
 */
class RemindersController extends CController
{
public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'viewpage', 'markcomplete'],
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

    /**
     * Lists all Reminders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RemindersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reminders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionViewpage(){
        $page = $_REQUEST['page'];
        $userId = $_REQUEST['userId'];
        $reminderInfo = Reminders::getUserReminders($userId, 10, $page);
        
        return $this->renderPartial('../widgets/reminders', ['reminders' => $reminderInfo, 'currentPage' => $page]);
    }
    public function actionMarkcomplete(){
        $id = $_REQUEST['id'];
        $reminder = Reminders::findOne($id);
        $reminder->isComplete = 1;
        $reminder->save();
    }
    /**
     * Creates a new Reminders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reminders();
        if(count($_POST) > 0){
            $model->load(Yii::$app->request->post());
            if($model->remindDate !== ''){
                $model->remindDate = (UtilityHelper::dateconvert($model->remindDate,1));
            }
        }
        if (count($_POST) > 0 && $model->save()) {
            /*
            if($model->save()){
                //return $this->redirect(['view', 'id' => $model->id]);
            }
            */
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reminders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(count($_POST) > 0){
            $model->load(Yii::$app->request->post());
            if($model->remindDate !== ''){
                $model->remindDate = (UtilityHelper::dateconvert($model->remindDate,1));
            }
        }
        if (count($_POST) > 0 && $model->save()) {
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Reminders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reminders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reminders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reminders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
