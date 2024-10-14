<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Resources;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResourcesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Resources';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="resources-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Resources', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

           // 'id',
           // 'type',
            [
            'label' => 'Type',
            'attribute' => 'type',
            'filter' => Html::activeDropDownList($searchModel, 'type', Resources::getTypes(),['class'=>'form-control','prompt' => 'Select Category']),
            'value' => function ($model) {
                return $model->getTypeDescription();
            },
            ],
            'name',
            'notes',
           // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
