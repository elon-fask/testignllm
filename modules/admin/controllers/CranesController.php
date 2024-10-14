<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Cranes;
use app\models\CranesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\UtilityHelper;

/**
 * CranesController implements the CRUD actions for Cranes model.
 */
class CranesController extends CController
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
     * Lists all Cranes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CranesSearch();
        $searchModel->isDeleted = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionDeleteFile(){
        $crane = Cranes::findOne($_REQUEST['id']);
        $type = $_REQUEST['type'];
        $crane->$type = 0;
        $fileName = $type.'Filename';
        $crane->$fileName = '';
        $crane->save();
        $resp = [];
        $resp['status'] = 1;
        echo json_encode($resp);
        die;
    }

    /**
     * Displays a single Cranes model.
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
     * Creates a new Cranes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cranes();
        $params = [];
        if(count($_POST) > 0){
            $params = Yii::$app->request->post();
            foreach(Cranes::getFilesForUpload() as $field){
                unset($params['Cranes'][$field]);
            }
        }
        if (count($_POST) > 0 && $model->load($params) && $model->save()) {
            
            foreach(Cranes::getFilesForUpload() as $field){
                if(isset($_FILES['Cranes']['name'][$field]) && $_FILES['Cranes']['error'][$field] == 0){
                     
                    $uploadDir = realpath(\Yii::$app->basePath) . '/web/cranes/'.md5($model->id).'/';
                     
                    UtilityHelper::createPath($uploadDir);
                    $finalFile = $uploadDir.$field.'-'.$_FILES['Cranes']['name'][$field];
                     
                    if(move_uploaded_file($_FILES['Cranes']['tmp_name'][$field], $finalFile)){
                        $model->$field = 1;
                        $fieldName = $field.'Filename';
                        $model->$fieldName = basename($finalFile);
                    }
            
                }
            }
            $model->save();
            
            return $this->redirect('/admin/cranes');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cranes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $params = [];
        if(count($_POST) > 0){
            $params = Yii::$app->request->post();
            foreach(Cranes::getFilesForUpload() as $field){
                unset($params['Cranes'][$field]);
            }
        }
        if (count($_POST) > 0 && $model->load($params) && $model->save()) {
            foreach(Cranes::getFilesForUpload() as $field){
                if(isset($_FILES['Cranes']['name'][$field]) && $_FILES['Cranes']['error'][$field] == 0){
                   
                    $uploadDir = realpath(\Yii::$app->basePath) . '/web/cranes/'.md5($model->id).'/';
                     
                    UtilityHelper::createPath($uploadDir);
                    $finalFile = $uploadDir.$field.'-'.$_FILES['Cranes']['name'][$field];
                     
                    if(move_uploaded_file($_FILES['Cranes']['tmp_name'][$field], $finalFile)){
                        $model->$field = 1;
                        $fieldName = $field.'Filename';
                        $model->$fieldName = basename($finalFile);
                    }
                
                }
            }
            $model->save();

           return $this->redirect('/admin/cranes');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionDeleteasync()
    {
        $id = $_POST['id'];
    
        $crane = $this->findModel($id);
    
        $crane->isDeleted = 1;
        $crane->save();
    
        echo 1;
        die;
    }
    

    /**
     * Deletes an existing Cranes model.
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
     * Finds the Cranes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cranes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cranes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
