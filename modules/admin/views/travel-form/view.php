<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TravelForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Travel Forms', 'url' => ['/admin/travel-form']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="travel-form-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'destination_loc',
            [
                'attribute' => 'destination_date',
                'value' => function($travelForm) {
                    return date_format(date_create($travelForm->destination_date), 'm/d/Y');
                }
            ],
            'destination_time',
            [
                'attribute' => 'return_loc',
                'value' => function($travelForm) {
                    return $travelForm->one_way ? 'One Way Travel Only' : $travelForm->return_loc;
                }
            ],
            [
                'attribute' => 'return_date',
                'value' => function($travelForm) {
                    return $travelForm->one_way ? 'One Way Travel Only' : date_format(date_create($travelForm->return_date), 'm/d/Y');
                }
            ],
            [
                'attribute' => 'return_time',
                'value' => function($travelForm) {
                    return $travelForm->one_way ? 'One Way Travel Only' : $travelForm->return_time;
                }
            ],
            [
                'attribute' => 'hotel_required',
                'value' => function($travelForm) {
                    return $travelForm->hotel_required ? 'Yes' : 'No';
                }
            ],
            [
                'attribute' => 'car_rental_required',
                'value' => function($travelForm) {
                    return $travelForm->car_rental_required ? 'Yes' : 'No';
                }
            ],
            [
                'attribute' => 'comment',
                'value' => function($travelForm) {
                    return $travelForm->comment ? $travelForm->comment : '';
                }
            ]
        ],
    ]) ?>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
