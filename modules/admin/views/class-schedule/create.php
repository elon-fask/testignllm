<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TestSessionClassSchedule */

$this->title = 'Create Session Class Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Test Session Class Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-session-class-schedule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
