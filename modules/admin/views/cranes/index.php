<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\Cranes;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CranesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cranes';
$this->params['breadcrumbs'][] = $this->title;

function buildCustomDeleteLink($id, $el)
{
    $selectHtml = '';
    if(UtilityHelper::isSuperAdmin()){



        return $selectHtml . '<li>' .
            '<a class="link-delete-cranes-async" data-id="'.$id.'" href="/admin/cranes/deleteasync">' .
            '<i class="fa fa-trash" style="width:15px"></i>' .
            '<span style="font-size: 14px;"> Delete</span></a>' .
            '</li>';
    }
    return $selectHtml;
}

function extraLinks($model, $el)
{
     

    return '';
}
?>
<div class="cranes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cranes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'model',
            
            [
            'label' => 'Manufacturer',
            'attribute' => 'manufacturer',
            'filter' => Html::activeDropDownList($searchModel, 'manufacturer', Cranes::getAvailableManufacturer(), ['class' => 'form-control', 'prompt' => 'Show All']),
            'value' => function ($model) {
                return $model->manufacturer;
            },
            ],
            
            'unitNum',
            'serialNum',
            // 'cad',
            // 'weightCerts',
            // 'loadChart',
            // 'manual',
            // 'certificate',
            // 'certificateExpirateDate',
            // 'companyOwner',
            // 'preChecklistId',
            // 'postChecklistId',
            // 'date_created',
            // 'isDeleted',

            ['label' => '',
            'format' => 'raw',
            'headerOptions' => ['class' => 'action-cell'],
            'value' => function ($model) {
                return UtilityHelper::buildActionWrapper('/admin/cranes', $model->id, false,
                    extraLinks($model, $this),
                    buildCustomDeleteLink($model->id, $this), false
                );
            },
            ],
        ],
    ]); ?>
</div>
