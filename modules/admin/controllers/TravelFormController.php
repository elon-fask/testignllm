<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TravelForm;
//use app\models\AppConfig;
use app\models\TravelFormFile;
use app\models\TravelFormSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * TravelFormController implements the CRUD actions for TravelForm model.
 */
class TravelFormController extends Controller
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

    public $layout = 'main';

    /**
     * Lists all TravelForm models.
     * @return mixed
     */
    public function actionIndex()
    {
//$t = Appconfig::findOne(['id'=> 7]);var_dump($t);
        $searchModel = new TravelFormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TravelForm model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TravelForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TravelForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TravelForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $redirectIndex = true)
    {
        $this->layout = 'main-react';
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();

            if ($model->load($postData) && $model->save()) {
                if (isset($_FILES['fileUpload'])) {
                    $travelFormFile = new TravelFormFile();
                    $travelFormFile->travel_form_id = $model->id;
                    $travelFormFile->filename = $_FILES['fileUpload']['name'];
                    if ($travelFormFile->save()) {
                        $s3 = new \Aws\S3\S3Client([
                            'version' => 'latest',
                            'region' => 'us-west-2'
                        ]);

                        $s3->putObject([
                            'Bucket' => getenv('S3_TRAVEL_FORM_BUCKET'),
                            'Key' => $travelFormFile->id,
                            'SourceFile' => $_FILES['fileUpload']['tmp_name'],
                            'ContentType' => $_FILES['fileUpload']['type'],
                            'ContentDisposition' => 'attachment; filename=' . $_FILES['fileUpload']['name']
                        ]);
                    }
                }

                if ($redirectIndex) {
                    return $this->redirect(['index']);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $travelForm = ArrayHelper::toArray($model, [
            'app\models\TravelForm' => [
                'id',
                'completed',
                'name',
                'one_way',
                'starting_location',
                'destination_loc',
                'destination_date',
                'destination_time',
                'return_loc',
                'return_date',
                'return_time',
                'hotel_required',
                'car_rental_required',
                'comment',
                'notes',
                'files'
            ]
        ]);

        return $this->render('update', [
            'model' => $model,
            'travelForm' => $travelForm
        ]);
    }

    /**
     * Deletes an existing TravelForm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDownloadFile($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $file = TravelFormFile::findOne($id);

        if (!isset($file)) {
            throw new NotFoundHttpException('Travel Form file attachment not found.');
        }

        $s3Client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => 'us-west-2'
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => getenv('S3_TRAVEL_FORM_BUCKET'),
            'Key' => $file->id
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        $presignedUrl = (string) $request->getUri();

        return [
            'result' => $presignedUrl,
            'status' => 'OK'
        ];
    }

    /**
     * Finds the TravelForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TravelForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TravelForm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
