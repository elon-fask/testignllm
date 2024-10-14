<?php

namespace  app\modules\admin\controllers;

use Yii;
use app\models\Uploads;
use app\models\UploadsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\helpers\UtilityHelper;
use yii\web\Response;

/**
 * UploadsController implements the CRUD actions for Uploads model.
 */
class UploadsController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view', 'view-file','create','update','delete'],
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
     * Lists all Uploads models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UploadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Uploads model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    
    public function actionViewFile(){
        
        $id = $_REQUEST['id'];
        if($id != ''){
            $id = base64_decode($id);
            $model = $this->findModel($id);
            
            if($model){
                $filePath = realpath(\Yii::$app->basePath) . '/web/application-files/'.$model->name;
               
                if(is_file($filePath)){
                    // Render the file
                    return \Yii::$app->getResponse()->sendFile($filePath);
                    
                }
            }
        }
        echo('File Not Found');
        die;
    }
    /**
     * Creates a new Uploads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Uploads();

        if ($model->load(Yii::$app->request->post())) {
            
             if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
                $description = isset($_POST['Uploads']['description']) ? $_POST['Uploads']['description'] : '';
               
                $uploadDir = realpath(\Yii::$app->basePath) . '/web/application-files/';
               
                UtilityHelper::createPath($uploadDir);
               
                if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir.$_FILES['file']['name'])){
                    $model->name = $_FILES['file']['name'];
                    $model->description = $description;
                    $model->uploaded_by = \Yii::$app->user->id;
                    if($model->save()){
                        return $this->redirect(['index', '']);
                    }
                }
            }else{
                \Yii::$app->getSession()->setFlash('error', 'File Upload Failed, please try again');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Uploads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $description = isset($_POST['Uploads']['description']) ? $_POST['Uploads']['description'] : '';
            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
                $uploadDir = realpath(\Yii::$app->basePath) . '/web/application-files/';
               
                UtilityHelper::createPath($uploadDir);
               
                if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir.$_FILES['file']['name'])){
                    $model->name = $_FILES['file']['name'];
                    $model->description = $description;
                    $model->uploaded_by = \Yii::$app->user->id;
                    if($model->save()){
                        return $this->redirect(['index', '']);
                    }
                }
            }else{
                
                $model->description = $description;
                $model->uploaded_by = \Yii::$app->user->id;
                if($model->save()){
                    return $this->redirect(['index', '']);
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Uploads model.
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
     * Finds the Uploads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Uploads the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Uploads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
