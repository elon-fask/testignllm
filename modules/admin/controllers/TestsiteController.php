<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\TestSite;
use app\models\TestSiteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\TestSiteService;
use app\models\TestSession;

/**
 * TestsiteController implements the CRUD actions for TestSite model.
 */
class TestsiteController extends CController
{
	public function behaviors()
	{
		return [
		'access' => [
			'class' => AccessControl::className(),
			'rules' => [
				[
						'actions' => ['written','practical', 'index','view','create','update','delete'],
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
     * Lists all TestSite models.
     * @return mixed
     */
    public function actionIndex()
    {    	
        $searchModel = new TestSiteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionWritten()
    {
    	$searchModel = new TestSiteSearch();
    	$searchModel->type = TestSite::TYPE_WRITTEN;
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    	$s = isset($_REQUEST['s']) ? $_REQUEST['s'] : false;
    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	    's' => $s
    			]);
    }
    public function actionPractical()
    {
    	$searchModel = new TestSiteSearch();
    	$searchModel->type = TestSite::TYPE_PRACTICAL;
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    	$s = isset($_REQUEST['s']) ? $_REQUEST['s'] : false;
        $g = $_GET;
    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	        's' => $s,
                'qStr' => $g
    			]);
    }

    /**
     * Displays a single TestSite model.
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
     * Creates a new TestSite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new TestSite();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->propagateChecklistToSession();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            	'type' => base64_decode($_GET['type']),
            ]);
        }
    }

    /**
     * Updates an existing TestSite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	if(isset($_POST['services'])){
        		foreach($model->getTestSiteServices()->all() as $siteService){
        			$siteService->delete();
        		}
        		$services = $_POST['services'];
        		foreach($services as $serviceId){
        			$testSiteService = new TestSiteService();
        			$testSiteService->test_site_id = $model->id;
        			$testSiteService->application_type_id = $serviceId;
        			$testSiteService->save();
        		}
        	}
        	$model->propagateChecklistToSession();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            	'type' => $model->type,
            ]);
        }
    }

    /**
     * Deletes an existing TestSite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $testSessions = TestSession::find()->where('test_site_id = '.$id)->all();
        $params = '';
        if(count($testSessions) == 0){
            //ApplicationTypeFormSetup::deleteAll('application_type_id = '.$id);
            $this->findModel($id)->delete();
            $params = '?s=1';
        }else{
            $params = '?s=0';
        }
        
        $params1 = [];
        if(isset($_GET['TestSiteSearch'])){
            foreach($_GET['TestSiteSearch'] as $key => $val){
                $params1[] = 'TestSiteSearch['.$key.']='.$val;
            }
        }
        $searchParams = implode('&', $params1);
        if($searchParams !== false && $searchParams !== ''){
            $searchParams = '&'.$searchParams;
        }
//          var_dump($searchParams);
//         var_dump('/admin/testsite/written'.$params.$searchParams);
 //        die;
        //return $this->redirect(['index', ['s'=>1]]);
        if($model->type == TestSite::TYPE_WRITTEN)
            return $this->redirect('/admin/testsite/written'.$params.$searchParams);
        else 
            return $this->redirect('/admin/testsite/practical'.$params.$searchParams);
        
        //$this->findModel($id)->delete();

        //return $this->redirect(['index']);
    }

    /**
     * Finds the TestSite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestSite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestSite::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
