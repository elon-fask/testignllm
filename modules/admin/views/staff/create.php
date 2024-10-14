<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Staff */

$this->title = 'Create Staff';
$this->params['breadcrumbs'][] = ['label' => 'Staff', 'url' => ['/admin/staff']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="staff-create">
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
                'user' => $model
            ]) ?>
        </div>
    </div>
</div>
