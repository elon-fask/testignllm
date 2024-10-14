<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\helpers\UtilityHelper;
use app\models\TestSite;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
use app\models\PendingTransaction;
use app\commands\NotificationController;
use app\helpers\NotificationHelper;
use app\models\ChecklistItemTemplate;
use app\models\ChecklistTemplate;
use app\models\User;
use app\models\UserRole;


class HomeController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['staff', 'index', 'view','create','update','delete', 'policies', 'test', 'index-test'],
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

    public function actionStaff(){
        return $this->render('staff-dashboard', []);
    }

    public function actionIndex()
    {
        $ongoingClasses = TestSession::getOngoingSessions();
        $ongoingClassesArr = ArrayHelper::toArray($ongoingClasses, [
            'app\models\TestSession' => [
                'id',
                'name' => 'partialTestSessionDescription',
                'location' => function ($testSession) {
                    $testSite = $testSession->testSite;
                    return $testSite->city . ', ' . $testSite->state;
                },
                'numCandidates' => function ($testSession) {
                    $candidates = $testSession->candidates;
                    return count($candidates);
                },
                'staff' => function ($testSession) {
                    return [
                        'instructor' => $testSession->getInstructorName(false),
                        'testSiteCoordinator' => $testSession->getTestCoordinatorName(false),
                        'practicalExaminer' => $testSession->getStaffName(false),
                        'proctor' => $testSession->getProctorName(false)
                    ];
                },
                'pendingTransactions' => function ($testSession) {
                    $candidates = $testSession->getCandidates()->select('id')->asArray()->all();
                    $candidateIds = array_map(function ($candidate) {
                        return $candidate['id'];
                    }, $candidates);

                    $pendingTransactions = PendingTransaction::findAll(['candidate_id' => $candidateIds]);

                    return count($pendingTransactions);
                }
            ]
        ]);

        $upcomingClasses = TestSession::getUpcomingSessions(30);
        $upcomingClassesArr = ArrayHelper::toArray($upcomingClasses, [
            'app\models\TestSession' => [
                'id',
                'name' => 'partialTestSessionDescription',
                'location' => function ($testSession) {
                    $testSite = $testSession->testSite;
                    return $testSite->city . ', ' . $testSite->state;
                },
                'numCandidates' => function ($testSession) {
                    $candidates = $testSession->candidates;
                    return count($candidates);
                },
                'staff' => function ($testSession) {
                    return [
                        'instructor' => $testSession->getInstructorName(false),
                        'testSiteCoordinator' => $testSession->getTestCoordinatorName(false),
                        'practicalExaminer' => $testSession->getStaffName(false),
                        'proctor' => $testSession->getProctorName(false)
                    ];
                },
                'materialsStatus' => 'materials_status',
                'materialsTrackingNo' => 'materials_tracking_no'
            ]
        ]);

        return $this->render('index', [
            'ongoingClasses' => $ongoingClassesArr,
            'upcomingClasses' => $upcomingClassesArr
        ]);
    }


    public function actionPolicies()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $roles = $user->roles;
        $isAdmin = in_array(UserRole::SUPER_ADMIN, $roles);

        if (!$isAdmin) {
            return $this->redirect('/admin/home');
        }

        return $this->redirect(\Yii::$app->params['wp.url']);
    }
}
