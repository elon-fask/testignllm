<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Edit Website Admin';
$this->params['breadcrumbs'][] = ['label' => 'Website Admin', 'url' => ['/admin/user']];
$this->params['breadcrumbs'][] = ['label' => $model->first_name . ' ' . $model->last_name, 'url' => ['/admin/user/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Edit User', 'url' => ['/admin/user', ['id']]];
?>
<div class="user-update">

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?php // = Html::a('<i class="fa fa-times"></i> Cancel', ['/admin/user', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
