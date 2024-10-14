<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\ApplicationType;
use app\models\ApplicationTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\ViewContextInterface;
use app\models\ApplicationTypeFormSetup;
use app\models\Candidates;
use yii\base\Application;

/**
 * ApplicationController implements the CRUD actions for ApplicationType model.
 */
class ApplicationController extends CController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['check', 'wizard', 'index','view','create','update', 'archive', 'unarchive',
                            'delete', 'written', 'practical', 'recertify'],
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
     * Lists all ApplicationType models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new ApplicationTypeSearch();
        $params = Yii::$app->request->queryParams;
        if(!isset($params['ApplicationTypeSearch']['app_type'])){
            $params['ApplicationTypeSearch']['app_type'] = ApplicationType::TYPE_PUBLIC;
        }

        $dataProvider = $searchModel->search($params);
        $s = isset($_REQUEST['s']) ? $_REQUEST['s'] : false;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            's' => $s,
            'appTypeFilter' => $params['ApplicationTypeSearch']['app_type']
        ]);
    }

    /**
     * Displays a single ApplicationType model.
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
     * Creates a new ApplicationType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ApplicationType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);

            $forms = isset($_POST['form']) ? $_POST['form'] : array();
            //we delete all app forms first
            ApplicationTypeFormSetup::deleteAll(['application_type_id' => $model->id]);
            //then we save
            foreach($forms as $customForm){
                //$dynamicFieldsFormName = str_replace(".pdf", "", $customForm);
                $appFormType = new ApplicationTypeFormSetup();
                $appFormType->form_name = $customForm;
                //we iterate all the checked fields

                $dynamicFields = isset($_POST[$customForm]) ? $_POST[$customForm] : array();
                $appFormType->form_setup = json_encode($dynamicFields);
                $appFormType->application_type_id = $model->id;
                $appFormType->save();
            }

            return $this->redirect(['update', 'id' => ($model->id)]);
        } else {
            $model->cross_out_cc_fields = 1;

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionWizard(){
        $model = new ApplicationType();
        return $this->render('wizard', [
            'model' => $model,
        ]);
    }

    public function actionCheck(){

        $forms = isset($_POST['form']) ? $_POST['form'] : array();
        //then we save
        $forChecking = [];
        foreach($forms as $customForm){
            //$dynamicFieldsFormName = str_replace(".pdf", "", $customForm);
            $appFormType = new ApplicationTypeFormSetup();
            $appFormType->form_name = $customForm;
            //we iterate all the checked fields

            $dynamicFields = isset($_POST[$customForm]) ? $_POST[$customForm] : array();
            $appFormType->form_setup = json_encode($dynamicFields);

            if(isset($dynamicFields['W_TOTAL_DUE'])){
                unset($dynamicFields['W_TOTAL_DUE']);
            }
            $forChecking[$customForm] = json_encode($dynamicFields);
        }
        $matched = [];

        $appTypesList = ApplicationType::find()->all();
        foreach($appTypesList as $appType){
//             if($appType->id != 1)
//                 continue;
            $appTypesFormSetups = ApplicationTypeFormSetup::findAll(['application_type_id' => $appType->id]);
            $appTypeFormMap = [];
            foreach($appTypesFormSetups as $setups){
                $appTypeFormMap[$setups->form_name] = $setups->form_setup;
            }

            if(count($forChecking) == count($appTypeFormMap)){
                foreach($forChecking as $formName => $customSetup){
                    /*
                    if(isset($appTypeFormMap[$formName]) && $appTypeFormMap[$formName] == $customSetup){
                        unset($appTypeFormMap[$formName]);
                    }
                    */
                    if(isset($appTypeFormMap[$formName])){
                        $mapToCompare = json_decode($appTypeFormMap[$formName], true);

                        $originalMap = json_decode($customSetup, true);

                        // we dont calculate the amount so we only base on checkboxes
                        if(isset($mapToCompare['W_TOTAL_DUE'])){
                            unset($mapToCompare['W_TOTAL_DUE']);
                        }

                        $intersectingData = array_intersect_assoc($originalMap, $mapToCompare);

                        if(json_encode($intersectingData) == $customSetup){
                            unset($appTypeFormMap[$formName]);
                        }
                    }
                }
                if(count($appTypeFormMap) == 0){
                    $matched[] = $appType;
                }


            }
        }

        return $this->renderPartial('check', [
            'list' => $matched,
        ]);

    }
    /**
     * Updates an existing ApplicationType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);

            //var_dump($_POST);
            $forms = isset($_POST['form']) ? $_POST['form'] : array();
            //we delete all app forms first
            //ApplicationTypeFormSetup::find()->where('application_type_id = '.$model->id)->
            ApplicationTypeFormSetup::deleteAll(['application_type_id' => $model->id]);
            //then we save
            foreach($forms as $customForm){
                //$dynamicFieldsFormName = str_replace(".pdf", "", $customForm);
                $appFormType = new ApplicationTypeFormSetup();
                $appFormType->form_name = $customForm;
                //we iterate all the checked fields

                $dynamicFields = isset($_POST[$customForm]) ? $_POST[$customForm] : array();
                $appFormType->form_setup = json_encode($dynamicFields);
                $appFormType->application_type_id = $model->id;
                $appFormType->save();
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ApplicationType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $candidates = Candidates::find()->where('application_type_id = '.$id)->all();
        $params = '';
        if(count($candidates) == 0){
            ApplicationTypeFormSetup::deleteAll('application_type_id = '.$id);
            $this->findModel($id)->delete();
            $params = '?s=0';
        }else{
            $params = '?s=1&apptypeid=' . $id;
        }
        //return $this->redirect(['index', ['s'=>1]]);
        return $this->redirect('/admin/application/index'.$params);
    }

    public function actionArchive($id)
    {
        $this->findModel($id)->archive();
        return $this->redirect('/admin/application/index');
    }

    public function actionUnarchive($id)
    {
        $this->findModel($id)->unarchive();
        return $this->redirect('/admin/application/index');
    }

    /**
     * Finds the ApplicationType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ApplicationType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApplicationType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionWritten()
    {
        return $this->render('writtenapplication', [
        ]);
    }

    public function actionPractical()
    {
        return $this->render('practicalapplication', [
        ]);
    }

    public function actionRecertify()
    {
        return $this->render('recertifylapplication', [
        ]);
    }

    public static function generateApplicationForms($candidateId, $isGenerateNewPdf){

        $candidate = Candidates::findOne($candidateId);

        if($candidate){

            $appType = ApplicationType::findOne($candidate->application_type_id);
            $appTypeForms = ApplicationTypeFormSetup::findAll(['application_type_id' => $candidate->application_type_id]);

            $writtenTestSession = false;
            $practicalTestSession = false;
            $candidateSessions = $candidate->getAllTestSession();
            foreach($candidateSessions as $testSession){
                if($testSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
                    $writtenTestSession = TestSession::findOne($testSession->test_session_id);
                }else if($testSession->getTestSessionTypeId() == TestSite::TYPE_PRACTICAL){
                    $practicalTestSession = TestSession::findOne($testSession->test_session_id);
                }
            }

            $originalAppForms = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/app-forms.zip';
            $hasOriginalForms = false;
            if(is_file($originalAppForms)){
                $hasOriginalForms = true;
            }

            $fileFormDirectory = 'original';
            $zipFileName = 'app-forms.zip';
            if($hasOriginalForms){
                $fileFormDirectory = 'modified';
                $zipFileName = 'app-forms-latest.zip';
            }
            //first we create the candidate folder
            $candidateFolder = realpath(\Yii::$app->basePath) . '/web/app-forms/'.$candidate->getFolderDirectory().'/'.$fileFormDirectory.'/';
            $files_to_zip = array();
            self::createPath($candidateFolder);
            //no we need to generate the files
            //step 1: we copy the introduction page for now
            $school = $candidate->getSchool();
            $confirmationPdf = $candidateFolder.$school.'-confirmation-page.pdf';
            copy( realpath(\Yii::$app->basePath) . '/web/forms/confirmation/'.$school.'-confirmation-page.pdf', $confirmationPdf);
            $files_to_zip[] = $confirmationPdf;
            //step 2: we create the needed information
            $params = self::generateBasicCandidateSessionInfo($candidate, $writtenTestSession, $practicalTestSession);
            //step 3: we generate the dynamic application forms

            $customForms = json_decode($candidate->custom_form_setup, true);

            $isWrittenRetake = false;

            if($candidate->isRetake == 1 && $candidate->retakeType == TestSite::TYPE_WRITTEN){
                $isWrittenRetake = true;
            }

            foreach($appTypeForms as $appForm){

                $formNamePdf = $appForm->form_name;

                if($candidate->hasPreviousWrittenSession()  && $candidate->getWrittenTestSession() === false
                    && ($formNamePdf == AppFormHelper::WRITTEN_FORM_PDF || $formNamePdf == AppFormHelper::RECERTIFY_FORM_PDF)){
                    continue;
                }
                if($candidate->hasPreviousPracticalSession() && $candidate->getPracticalSession() === false &&
                    $formNamePdf == AppFormHelper::PRACTICAL_FORM_PDF){
                    continue;
                }

                if ($isWrittenRetake && $formNamePdf == AppFormHelper::PRACTICAL_FORM_PDF) {
                    continue;
                }

                if ($formNamePdf == AppFormHelper::WRITTEN_FORM_PDF && (!$appType->cross_out_cc_fields || $candidate->hasPreviousSessions() || $isWrittenRetake)) {
                    $formNamePdf = $formNamePdf . '-credit-card';
                } else if ($formNamePdf == AppFormHelper::RECERTIFY_FORM_PDF && (!$appType->cross_out_cc_fields || $candidate->hasPreviousSessions() || $isWrittenRetake)) {
                    $formNamePdf = $formNamePdf . '-credit-card';
                }

                $pdfFormPath = realpath(\Yii::$app->basePath) . '/web/forms/'.$formNamePdf . '.pdf';

                $targetCandidatePdfFile = $candidateFolder.$appForm->form_name.'.pdf';
                if ($isGenerateNewPdf && is_file($targetCandidatePdfFile)) {
                    unlink($targetCandidatePdfFile);
                }

                if ($isGenerateNewPdf && is_file($pdfFormPath)) {

                    $pdf = new Pdf($pdfFormPath);

                    $formName = $appForm->form_name;
                    if ($candidate->custom_form_setup != null && isset($customForms[$formName])) {
                        $customFormData = $customForms[$formName];
                        $appForm->form_setup = json_encode($customFormData);
                    }

                    $dynaForms = json_decode($appForm->form_setup, true);

                    foreach ($dynaForms as $key => $val) {
                        if($val == 'on'){
                            $dynaForms[$key] = 'Yes';
                            if($key == 'W_EXAM_TLL_LINK-BELT'){
                                //workaround
                                $dynaForms['W_EXAM_CORE_LINK-BELT'] = 'Yes';
                            }
                        }
                    }

                    $mergedParams = array_merge($params, $dynaForms);

                    $pdf->fillForm($mergedParams)->saveAs($targetCandidatePdfFile);
                    $files_to_zip[] = $targetCandidatePdfFile;
                }else{
                    //we jsut zip the folder
                    $files_to_zip[] = $targetCandidatePdfFile;
                }
            }
            //if true, good; if false, zip creation failed
            $result = self::create_zip($files_to_zip,$candidateFolder.'../'.$zipFileName, true);
            if($result){
                return true;
            }

        }
        return false;
    }
}
