<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\ChecklistTemplate;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChecklistTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Checklists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checklist-index">

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= Html::a('<i class="fa fa-plus"></i> Create CheckList', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [           
            'name',
            [
            'label' => 'Type',
            'filter' => Html::activeDropDownList($searchModel, 'type', ChecklistTemplate::getTypes(),['class'=>'form-control','prompt' => 'All']),
            'value' => function ($model) {
                return $model->getTypeDescription();
            },
            ],
            [
            'label' => 'Is Archived',
            'filter' => Html::activeDropDownList($searchModel, 'isArchived', [0 => 'Un-archived', 1 => 'Archived'],['class'=>'form-control','prompt' => 'All']),
            'value' => function ($model) {
                return $model->isArchived == 1 ? 'Archived' : 'Un-archived';
            },
            ],

            ['label' => '',
                'format' => 'raw',
                'headerOptions' => ['class' => 'action-cell'],
                'value' => function ($model) {
                    return UtilityHelper::buildActionWrapper('/admin/checklist', $model->id, false, null, ($model->isArchived == 1) ? extraLinksUnArchive($model) : extraLinks($model), false);
                },
            ],
        ],
    ]); ?>
</div>
<form action="/admin/checklist/delete" method="post" id="form-archive-checklist">
    <input type='hidden' id='checklistId' name='id' value=""/>
</form>

 <form action="/admin/checklist/undelete" method="post" id="form-unarchive-checklist">
    <input type='hidden' id='checklistId' name='id' value=""/>
</form>


<?php
function extraLinks($model)
{
    $ret = '<li><a class="checklist-archive" href="javascript: void(0);" data-id="'. $model->id .'">';
    $ret .= '<i class="fa fa-trash" style="width:15px"></i>Archive</a></li>';

    return $ret;
}
function extraLinksUnArchive($model)
{
    $ret = '<li><a class="checklist-unarchive" href="javascript: void(0);" data-id="'. $model->id .'">';
    $ret .= '<i class="fa fa-trash" style="width:15px"></i>Un-archive</a></li>';

    return $ret;
}
?>