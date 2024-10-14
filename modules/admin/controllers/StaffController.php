<?php

namespace app\modules\admin\controllers;
use Yii;
use app\models\Staff;
use app\models\StaffSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\TestSessionSearch;
use app\models\TestSession;
use app\models\TestSessionPhoto;
use app\models\TestSessionReceipts;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\models\TestSite;
use app\models\UserSearch;
use app\models\User;
use app\models\UserRole;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'undelete', 'sessions', 'merge'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'undelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $roles = $user->roles;
        $isAdmin = in_array(UserRole::SUPER_ADMIN, $roles);

        if (!$isAdmin) {
            return $this->redirect('/admin/home');
        }

        $searchModel = new UserSearch();
        $params = Yii::$app->request->queryParams;

        if (!isset($params['UserSearch']['active'])) {
            $params['UserSearch']['active'] = 1;
        }

        $dataProvider = $searchModel->search($params);

        $users = User::find()->select([
            'id',
            'active',
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'username',
            'email',
            'role',
            'phone' => 'workPhone',
            'created_date' => 'date_created'
        ]);

        if (isset($params['merge_primary'])) {
            $users = $users->andWhere(['<>', 'id', $params['merge_primary']]);
        }

        $users = $users->asArray()->all();

        $payload = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'users' => $users
        ];

        if (isset($params['merge']) && isset($params['merge_primary'])) {
            $payload['merge'] = true;
            $payload['mergePrimaryId'] = (int) $params['merge_primary'];
            $payload['mergeUserPrimary'] = User::findOne($params['merge_primary']);
        } else {
            $payload['merge'] = false;
        }

        return $this->render('index', $payload);
    }

    public function actionSessions($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $testSessions = TestSession::find()->where('test_coordinator_id = ' . $id . ' or staff_id = ' . $id . ' or instructor_id = ' . $id . ' order by start_date asc')->all();

        $r =[];
        if ($testSessions) {
            foreach ($testSessions as $ses) {
                if (strtotime($ses->start_date) > time()) {
                    array_push($r, $ses->getFullTestSessionDescription());
                }
            }
        }

        return $r;
    }

    /**
     * Displays a single Staff model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    
    	$searchModel = new TestSessionSearch();
    	
    	$staff = User::findOne($id);
    	if($staff->staffType == User::TYPE_INSTRUCTOR){
    	    $searchModel->instructor_id = $id;
    	}else if($staff->staffType == User::TYPE_TEST_COORDINATOR){
    	    $searchModel->test_coordinator_id = $id;
    	}else{
    	   $searchModel->staff_id = $id;
    	}
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    	
    	return $this->render('view', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'modeldata' => $this->findModel($id),
    			]);
    }

    /**
     * Creates a new Staff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new User();

        if ($request->isPost) {
            $postData = \Yii::$app->request->post();

            if ($model->load($postData) && $model->save()) {
                if (isset($postData['User']['roles']) && is_array($postData['User']['roles'])) {
                    foreach($postData['User']['roles'] as $role) {
                        $userRole = new UserRole();
                        $userRole->user_id = $model->id;
                        $userRole->role = $role;
                        $userRole->save();
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        } 
    }

    /**
     * Updates an existing Staff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $savingType = true;

        if (isset($_POST['saveType']) && $_POST['saveType'] == 'account') {
            $savingType = false;
        }

        if ($request->isPost) {
            $postData = $request->post();

            if (isset($postData['User']['roles'])) {
                $currentRoles = $model->roles;

                foreach($postData['User']['roles'] as $role) {
                    if (!in_array($role, $currentRoles)) {
                        $userRole = new UserRole();
                        $userRole->user_id = $model->id;
                        $userRole->role = $role;
                        $userRole->save();
                    }
                }

                foreach($currentRoles as $currentRole) {
                    if (!in_array($currentRole, $postData['User']['roles'])) {
                        $currentUserRole = UserRole::findOne(['user_id' => $model->id, 'role' => $currentRole]);
                        if (isset($currentUserRole)) {
                            $currentUserRole->delete();
                        }
                    }
                }
            }

            $model->load($request->post());
            $model->save($savingType);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $userArr = \yii\helpers\ArrayHelper::toArray($model, [
                'app\models\User' => [
                    'id',
                    'roles',
                    'linkedAccounts'
                ]
            ]);

            return $this->render('update', [
                'model' => $model,
                'user' => $userArr
            ]);
        }
    }

    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = $_POST['id'];
        $model = $this->findModel($id);//->delete();
        $model->active = 0;
        $model->save(false);

        return $this->redirect(['/admin/staff/index']);
    }
    
    public function actionUndelete()
    {
        $id = $_POST['id'];
        $model = $this->findModel($id);//->delete();
        $model->active = 1;
        $model->save(false);
    
        return $this->redirect(['/admin/staff/index']);
    }

    public function actionMerge($primary, $secondary) {
        $primaryUser = User::findOne($primary);
        $secondaryUser = User::findOne($secondary);

        if (!isset($primaryUser) || !isset($secondaryUser)) {
            throw new NotFoundHttpException('User not found.');
        }

        $request = Yii::$app->request;

        if ($request->isPost) {
            $postData = $request->post();

            $primaryUser->last_name = $postData['lastName'];
            $primaryUser->first_name = $postData['firstName'];
            $primaryUser->email = $postData['email'];
            $primaryUser->homePhone = $postData['homePhone'];
            $primaryUser->cellPhone = $postData['cellPhone'];
            $primaryUser->workPhone = $postData['workPhone'];
            $primaryUser->fax = $postData['fax'];
            $primaryUser->username = $postData['username'];
            $primaryUser->password = \Yii::$app->getSecurity()->generatePasswordHash($postData['password']);

            if (!$primaryUser->validate()) {
                throw new NotFoundHttpException('Invalid input.');
            }

            TestSession::updateAll(['staff_id' => $primaryUser->id], 'staff_id = ' . $secondaryUser->id);
            TestSession::updateAll(['instructor_id' => $primaryUser->id], 'instructor_id = ' . $secondaryUser->id);
            TestSession::updateAll(['test_coordinator_id' => $primaryUser->id], 'test_coordinator_id = ' . $secondaryUser->id);
            TestSessionPhoto::updateAll(['uploaded_by' => $primaryUser->id], 'uploaded_by = ' . $secondaryUser->id);
            TestSessionReceipts::updateAll(['savedBy' => $primaryUser->id], 'savedBy = ' . $secondaryUser->id);
            TestSite::updateAll(['siteManagerId' => $primaryUser->id], 'siteManagerId = ' . $secondaryUser->id);
            TestSiteChecklistItemDiscrepancy::updateAll(['cleared_by' => $primaryUser->id], 'cleared_by = ' . $secondaryUser->id);

            if ($secondaryUser->delete()) {
                if ($primaryUser->save()) {
                    if (isset($postData['roles'])) {
                        $primaryUser->updateRoles($postData['roles']);
                    }

                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['status' => 'OK'];
                }
            }

            return $this->redirect('/admin/staff');
        }

        $fields = ['id', 'last_name', 'first_name', 'email', 'homePhone', 'cellPhone', 'workPhone', 'fax', 'username', 'staffType'];

        return $this->render('merge', [
            'primaryUser' => $primaryUser->toArray($fields, ['roles']),
            'secondaryUser' => $secondaryUser->toArray($fields, ['roles'])
        ]);
    }

    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
