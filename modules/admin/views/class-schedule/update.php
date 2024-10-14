<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TestSessionClassSchedule */

$this->title = 'Update Session Class Schedule: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Test Session Class Schedules', 'url' => ['/admin/class-schedule/?id='.md5($model->testSessionId)]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="test-session-class-schedule-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
