<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\TestSite;
use app\models\TestSession;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TestSite */
$testSiteType =  $model->type == TestSite::TYPE_PRACTICAL ? 'Practical' : 'Written';
$this->title = '('.$model->siteNumber.') '.$model->name . ' - '.$model->getTestSiteLocation();
if($model->type == TestSite::TYPE_WRITTEN){
    $this->title = $model->name . ' - '.$model->getTestSiteLocation();    
}

$this->params['breadcrumbs'][] = ['label' => $testSiteType.' Test Sites', 'url' => ['/admin/testsite/'.strtolower($testSiteType)]];
$this->params['breadcrumbs'][] = ['label' =>$this->title, 'url' => ''];


$createURLType = ($testSiteType == 'Practical') ? base64_encode(TestSite::TYPE_PRACTICAL) : base64_encode(TestSite::TYPE_WRITTEN);
?>


<div class="test-site-view">

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
          <?php if(UtilityHelper::isSuperAdmin()){?>
            <?= Html::a('<i class="fa fa-plus"></i> Create Session', ['/admin/testsession/create', 'type' => $createURLType, 'siteId' => $model->id ], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger link-delete',
                'data'=>[
                    'confirmtitle'=>'Delete Test Site',
                    'confirmcontent'=>'Are you sure you want to delete this Test Site?'
                ]
            ]) ?>
            <?php }?>
        </div>
    </div>

    <div  class="row">
        <div class="col-xs-6">
            <h2> Site Information</h2>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',
                    [
                        'attribute' => 'type',
                        'value' => $model->type == TestSite::TYPE_PRACTICAL ? 'Practical' : 'Written'
                    ],
                    [
                        'attribute' => 'enrollmentType',
                        'value' => $model->enrollmentType == TestSite::ENROLLMENT_TYPE_PRIVATE ? 'Private Enrollment' : 'Public Enrollment'
                    ],
                    [
                        'attribute' => 'scheduleType',
                        'value' => $model->scheduleType == TestSite::SCHEDULE_TYPE_CLOSED ? 'Closed for Schedule' : 'Opened for Schedule'
                    ],
                    'name',
                    'address',
                    'city',
                    'state',
                    'zip',
                    'siteNumber',
                    'phone',
                    'fax',
                    'email:email',
                    'remark',
                    //'date_created',
                    //'date_updated',
                ],
            ]) ?>
        </div>
        <div class="col-xs-6">
           <h2>Test Sessions</h2>
<!--            <p style="line-height: 34px;">&nbsp;</p>-->
           <div class="session-panel-body">
                 <?= $this->render('session-list', [
                    'items' => TestSession::getSessions($model->id, 10, 1),
                     'testSiteId' => $model->id
                ]) ?>
           </div>
        </div>
    </div>
</div>
