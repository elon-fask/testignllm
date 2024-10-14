<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Staff;
use app\models\UserRole;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use app\models\TestSite;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Staff */

$this->title = $modeldata->getFullName(false);
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['/admin/staff']];
$this->params['breadcrumbs'][] = ['label' => 'User info: ' . $modeldata->first_name . ' ' . $modeldata->last_name, 'url' => ''];
?>
<div class="staff-view">
    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1>Staff: <?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['update', 'id' => $modeldata->id], ['class' => 'btn btn-primary']) ?>
            <?php if($modeldata->active != 1){?>
            <a href="javascript: void(0);" data-staffid="<?php echo $modeldata->id?>" class="btn btn-info staff-unarchive"><i class="fa fa-trash"></i> Un-archive</a>
            <?php }else{?>
            <a href="javascript: void(0);" data-staffid="<?php echo $modeldata->id?>" class="btn btn-danger staff-archive"><i class="fa fa-trash"></i> Archive</a>
            <?php }?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-staff-details">
            <?= DetailView::widget([
                'model' => $modeldata,
                'attributes' => [
                    'first_name',
                    'last_name',
                    'workPhone',
                    'fax',
                    'email',
                    [
                        'label' => 'Staff Roles',
                        'attribute' => 'roles',
                        'value' => implode(', ', array_map(function ($role) {
                            return UserRole::ROLES_DESC[$role];
                        }, $modeldata->roles))
                    ]
                ],
            ]) ?>
        </div>
    </div>

    <style>
        .col-staff-details th {
            width: 200px;
        }
    </style>

    <div class="row">
        <div class="col-xs-12">
            <h2>Test Sessions</h2>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'Session Type',
                        'attribute' => 'session_type',
                        'filter' => Html::activeDropDownList($searchModel, 'session_type', [TestSite::TYPE_PRACTICAL => 'Practical', TestSite::TYPE_WRITTEN => 'Written'], ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->getTestSessionType();
                        },
                    ],
                    [
                        'label' => 'Test Site',
                        'attribute' => 'test_site_id',
                        'filter' => Html::activeDropDownList($searchModel, 'test_site_id', UtilityHelper::getAllTestSites(), ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->getTestSiteName();
                        },
                    ],
                    [
                        'label' => 'Enrollment Type',
                        'attribute' => 'enrollmentType',
                        'filter' => Html::activeDropDownList($searchModel, 'enrollmentType', UtilityHelper::getEnrollmentTypes(), ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->getEnrollmentTypeDescription();
                        },
                    ],
                    [
                        'label' => 'Start Date',
                        'attribute' => 'start_date',
                        'headerOptions' => ['style' => 'width:160px'],
                        'value' => function ($model) {
                            return $model->getStartDateDisplay();
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'start_date',
                            'template' => '{input}{addon}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                    ],
                    [
                        'label' => 'End Date',
                        'attribute' => 'end_date',
                        'headerOptions' => ['style' => 'width:160px'],
                        'value' => function ($model) {
                            return $model->getEndDateDisplay();
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'end_date',
                            'template' => '{input}{addon}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',

                            ]
                        ]),
                    ],
                    [
                        'label' => 'Candidates',
                        'attribute' => 'registeredCandidates',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return "<a href='/admin/candidatesession?i=" . md5($model->id) . "'>" . $model->getNumberOfRegisteredCandidates() . '</a>';
                        },
                    ],
                    ['label' => '',
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'action-cell'],
                        'value' => function ($model) {
                            return UtilityHelper::buildActionWrapper('/admin/testsession', $model->id, false);
                        },
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
<form action="/admin/staff/delete" method="post" id="form-archive-staff">
    <input type='hidden' id='staffId' name='id' value=""/>
</form>
<form action="/admin/staff/undelete" method="post" id="form-unarchive-staff">
        <input type='hidden' id='staffId' name='id' value=""/>
    </form>
