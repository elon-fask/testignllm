<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApplicationType */

$this->title = 'Update Application Type: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Application Types', 'url' => ['/admin/application']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="application-type-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>