<?php

use yii\helpers\Html;
use app\models\TestSite;

/* @var $this yii\web\View */
/* @var $model app\models\TestSite */

$testSiteType =  $type == TestSite::TYPE_PRACTICAL ? 'Practical' : 'Written';
//$this->title = 'Update '.$testSiteType.' Test Site: ' . ' '.'('.$model->siteNumber.') '.$model->name . ' - '.$model->getTestSiteLocation();
$this->title = '('.$model->siteNumber.') '.$model->name . ' - '.$model->getTestSiteLocation();

if($type == TestSite::TYPE_WRITTEN){
    $this->title = 'Update '.$testSiteType.' Test Site: ' . ' '.$model->name . ' - '.$model->getTestSiteLocation();
}
$this->params['breadcrumbs'][] = ['label' => $testSiteType.' Test Sites', 'url' => ['/admin/testsite/'.strtolower($testSiteType)]];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['/admin/testsite/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Edit Site Info', 'url' => ''];

?>
<div class="test-site-update">

    <h1><?= Html::encode('Edit Test Site: '.$this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'type' => $type
    ]) ?>

</div>
