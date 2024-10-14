<?php
use yii\helpers\Html;

$this->title = 'Edit Staff';
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['/admin/staff']];
$this->params['breadcrumbs'][] = [
    'label' => $model->first_name . ' ' . $model->last_name,
    'url' => ['/admin/staff/view', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = ['label' => 'Edit Staff', 'url' => ''];
?>

<div class="staff-update">
    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= $this->render('_form', [
                'model' => $model,
                'user' => $user
            ]) ?>
        </div>
    </div>
</div>
