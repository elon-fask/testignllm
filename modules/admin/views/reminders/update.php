<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Reminders */

$this->title = 'Update Reminders: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Reminders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reminders-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
