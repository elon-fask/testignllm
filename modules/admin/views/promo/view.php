<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PromoCodes */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Promo Codes', 'url' => ['/admin/promo']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-view">

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1>Promo Code: <?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'code',
                    [
                        'label' => 'Is Purchase Order',
                        'value' => $model->isPurchaseOrder == 1 ? 'Yes' : 'No',
                    ],
                    'discount',
                    'assignedToName',
                ],
            ]) ?>
        </div>
    </div>

</div>

<style>
    .promo-codes-view th{width: 200px;}
</style>
