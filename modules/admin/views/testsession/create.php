<?php

use yii\helpers\Html;
use app\models\TestSite;
use app\assets\AppAssetExtra;

AppAssetExtra::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\TestSession */

$sessionType = $type == TestSite::TYPE_WRITTEN ? 'Written' : 'Practical';

$this->title = 'Create '.$sessionType.' Test Session';
$this->params['breadcrumbs'][] = ['label' => 'Test Sessions', 'url' => ['/admin/testsession/']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ''];
?>
<div class="test-session-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
        'testSiteCoordinators' => $testSiteCoordinators,
        'instructors' => $instructors,
        'proctors' => $proctors,
        'writtenAdmins' => $writtenAdmins,
        'practicalExaminers' => $practicalExaminers
    ]) ?>

</div>
