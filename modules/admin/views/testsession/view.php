<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\TestSite;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TestSession */

$sessionType = $model->getTestSessionType();
$type = $model->getTestSessionTypeId();


$this->title = $sessionType . ' Test Session #' . $model->session_number;
$this->params['breadcrumbs'][] = ['label' => 'Test Sessions', 'url' => ['/admin/testsession']];
$this->params['breadcrumbs'][] = $this->title;

$attributes = [
    'school',
    [
        'attribute' => 'test_site_id',
        'value' => $model->testSite->getTestSiteName(),
    ], [
        'attribute' => 'session_number',
        'value' => $model->session_number
    ], [
        'attribute' => 'enrollmentType',
        'value' => $model->enrollmentType == TestSite::ENROLLMENT_TYPE_PRIVATE ? 'Private Enrollment' : 'Public Enrollment'
    ], [
        'attribute' => 'numOfCandidates',
        'value' => $model->numOfCandidates
    ], [
        'attribute' => 'nccco_fee_notes',
        'label' => 'NCCCO Fee Notes',
        'value' => $model->nccco_fee_notes
    ]
];

if ($model->testSite->type === 2) {
    $attributes[] = [
        'attribute' => 'instructor_id',
        'label' => 'Instructor',
        'value' => $model->instructorName
    ];
} else {
    $attributes[] = [
        'attribute' => 'staff_id',
        'value' => $model->staffName
    ];
    $attributes[] = [
        'attribute' => 'proctor_id',
        'value' => $model->proctorName
    ];
}

$attributes[] = [
    'attribute' => 'test_coordinator_id',
    'value' => $model->getTestCoordinatorName()
];

$attributes[] = [
    'attribute' => 'start_date',
    'value' => UtilityHelper::dateconvert($model->start_date, 2)
];

$attributes[] = [
    'attribute' => 'end_date',
    'value' => UtilityHelper::dateconvert($model->end_date, 2)
];
?>

<div class="test-session-view">
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode('View ' . $this->title) ?></h1>
        </div>
        <?php if (UtilityHelper::isSuperAdmin()) { ?>
            <div class="col-xs-12 col-md-4" style="margin-top: 20px; line-height: 39px; text-align: right">
                <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        <?php } ?>
    </div>

    <div  class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
            ]) ?>
        </div>

        <div class="col-xs-12">
            <h3>File Attachments</h3>
            <div class="row">
                <div class="form-group file-attachments">
                    <?= $this->render('file-attachments', ['testSession'=>$model]) ?>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <form id="upload" method="post" action="/admin/testsession/attachments" enctype="multipart/form-data">
                <div id="drop">
                    Drop Here
                    <a href="#">Browse</a>
                    <input type="file" name="upl" multiple />
                    <input type="hidden" name="id" value="<?= ($model->id) ?>" />
                </div>
            </form>
        </div>
    </div>
</div>
