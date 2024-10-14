<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PhoneInformationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Phone Informations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-information-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'email:email',
            'phone',
            'referral',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'headerOptions'=>['class'=>'action-cell'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return '<a href="#" class="phone-info-view" data-id="' . $model->id . '"><i class="fa fa-eye"></i> Details</a>';
                    },
                ]
            ]
        ],
    ]); ?>
</div>
