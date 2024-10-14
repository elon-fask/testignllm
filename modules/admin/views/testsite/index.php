<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\TestSite;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TestSiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$type = $searchModel->type == TestSite::TYPE_WRITTEN ? 'Written' : 'Practical';
$this->title = $type.' Test Sites';
$this->params['breadcrumbs'][] = ['label' => $this->title];
$this->params['type'] = $searchModel->type;


    
    $searchParams = '';
    $params = [];
    if(isset($_GET['TestSiteSearch'])){
        foreach($_GET['TestSiteSearch'] as $key => $val){
            //foreach($val as $key1 => $val1){
                $params[] = 'TestSiteSearch['.$key.']='.$val;
           // }
        }
    }
    $searchParams = implode('&', $params);
    $this->params['queryParams'] = $searchParams;


function buildCustomDeleteLink($siteId, $siteName, $searchParams=false){
    if($searchParams !== false && $searchParams !== ''){
        $searchParams = '&'.$searchParams;
    }
    if(UtilityHelper::isSuperAdmin()){
    return '<li>'.
            '<a class="link-delete" href="/admin/testsite/delete?id='.$siteId.$searchParams.'" data-sitename="'.$siteName.'" data-confirmtitle="Delete Test Site" data-confirmcontent="Are you sure you want to delete this Test Site?">'.
            '<i class="fa fa-trash" style="width:15px"></i>'.
            '<span style="font-size: 14px;"> Delete</span></a>'.
            '</li>';
    }
    return '';
}
?>


<div class="test-site-index">

    <?php if(isset($s) && $s == '0'  && $s !== false){?>
	<div class="alert alert-danger">Unable to delete test site, since there are test sessions scheduled</div>
	<?php }else if(isset($s) && $s == '1' && $s !== false){?>
	<div class="alert alert-success">Delete Successful</div>
	<?php }?>
	
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
    		[
	    		'attribute' => 'siteNumber',
	    		'visible' => $this->params['type'] == TestSite::TYPE_PRACTICAL,
                'header'=> UtilityHelper::tableSortHeader('Site Number', 'siteNumber'),
                'headerOptions'=>['style'=>'width:120px;']
    		],
            [
            'label' => 'Enrollment Type',
//            'attribute' => 'enrollmentType',
            'headerOptions'=>['style'=>'width:175px;'],
            'filter' => Html::activeDropDownList($searchModel, 'enrollmentType', UtilityHelper::getEnrollmentTypesShort(),['class'=>'form-control','prompt' => 'Enrollment Type']),
            'value' => function ($model) {
                return $model->getEnrollmentTypeDescription();
            },
            ],
            [
            'label' => 'Schedule Type',
//            'attribute' => 'scheduleType',
            'headerOptions'=>['style'=>'width:150px;'],
            'filter' => Html::activeDropDownList($searchModel, 'scheduleType', UtilityHelper::getScheduleTypesShort(),['class'=>'form-control','prompt' => 'Schedule Type']),
            'value' => function ($model) {
                return $model->getScheduleTypeDescription();
            },
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Name', 'name'),
                'attribute'=>'name',
            ],

            [
                'header'=> UtilityHelper::tableSortHeader('Address', 'address'),
                'attribute'=>'address',
            ],

            [   'label'=>'',
                'format'=>'raw',
                'headerOptions'=>['class'=>'action-cell'],
                'value'=> function($model){ return UtilityHelper::buildActionWrapper('/admin/testsite',$model->id, false, null, buildCustomDeleteLink($model->id, $model->name, $this->params['queryParams']) );},
            ],
        ],
    ]); ?>
</div>
<style>
    .test-site-index table  tr  td:nth-child(1){
        width: 100px;
    }
    .test-site-index table  tr  td:nth-child(2), .test-site-index table  tr  td:nth-child(3){
        width: 175px;
    }
    .test-site-index table  tr  th{
        vertical-align: middle;
    }

</style>
