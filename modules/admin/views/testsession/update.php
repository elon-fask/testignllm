<?php

use yii\helpers\Html;
use app\models\TestSite;
use app\helpers\UtilityHelper;
use app\assets\AppAssetExtra;

AppAssetExtra::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\TestSession */
$sessionType = $type == TestSite::TYPE_WRITTEN ? 'Written' : 'Practical';

$this->title = 'Update '.$sessionType.' Test Session: ' . ' ';
$this->title = 'Edit '.$sessionType.' Test Session';
$this->params['breadcrumbs'][] = ['label' => 'Test Sessions', 'url' => ['/admin/testsession']];
$this->params['breadcrumbs'][] = ['label' =>  $sessionType . ' Test Session #'.$model->session_number, 'url' => ['/admin/testsession/view?id='.$model->id]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="test-session-update">
    <h1><?= Html::encode($this->title . ' #' . $model->session_number) ?></h1>

<?php
    echo $this->render('_form', [
        'model' => $model,
        'errors' => $errors,
        'type' => $type,
        'testSiteCoordinators' => $testSiteCoordinators,
        'instructors' => $instructors,
        'proctors' => $proctors,
        'writtenAdmins' => $writtenAdmins,
        'practicalExaminers' => $practicalExaminers
    ]);
?>
</div>
