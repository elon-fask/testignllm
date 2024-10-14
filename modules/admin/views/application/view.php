<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ApplicationType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Application Types', 'url' => ['/admin/application']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Archive', ['archive', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to archive this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name',
            'keyword',
            'description',
            'price',
            'iaiFee',
            'lateFee',
            //'date_created',
            //'date_updated',
        ],
    ]) ?>

</div>
