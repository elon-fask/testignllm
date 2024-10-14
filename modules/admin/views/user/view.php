<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Website Admin';
$this->params['breadcrumbs'][] = ['label' => 'Website Admin', 'url' => ['/admin/user']];
$this->params['breadcrumbs'][] = ['label' => 'User info: ' . $model->first_name . ' ' . $model->last_name, 'url' => ''];
?>
<div class="user-view">


    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title . ': ' . $model->first_name . ' ' . $model->last_name) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-site-admin-details">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'first_name',
                    'last_name',
                    'username',
                    'homePhone',
                    'cellPhone',
                    'workPhone',
                    'city',
                    'state',
                    'zip',
                    'address1',
                ],
            ]) ?>
        </div>
    </div>
</div>
<style>
    .col-site-admin-details th {
        width: 250px;
    }
</style>