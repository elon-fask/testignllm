<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UploadsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uploads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uploads-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Add Uploads', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'description',
            //'isDeleted',
            //'uploaded_by',
            // 'date_created',

            ['class' => 'yii\grid\ActionColumn',
                          'template'=>'{view} {update}',
                            'buttons'=>[
                              'view' => function ($url, $model) {     
                                return Html::a('<span class="fa fa-eye"></span>', '/admin/uploads/view-file?id='.base64_encode($model->id), [
                                        'title' => Yii::t('yii', 'View'),
                                ]);                                
            
                              },
                              'update' => function ($url, $model) {
                                  return Html::a('<span class="fa fa-pencil"></span>', $url, [
                                      'title' => Yii::t('yii', 'Edit'),
                                  ]);
                              
                              }
                          ]    
            ]
        ],
    ]); ?>

</div>
