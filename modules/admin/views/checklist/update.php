<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChecklistTemplate */

$this->title = 'Update ChecklistTemplate: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Checklists', 'url' => ['/admin/checklist']];
$this->params['breadcrumbs'][] = $model->name;

?>
<div class="checklist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
