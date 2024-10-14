<?php

use app\helpers\UtilityHelper;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Website Admin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= Html::a('<i class="fa fa-plus"></i> Create User', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-staff-details">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'header' => UtilityHelper::tableSortHeader('First Name', 'first_name'),
                        'attribute' => 'first_name',
                    ],
                    [
                        'header' => UtilityHelper::tableSortHeader('Last Name', 'last_name'),
                        'attribute' => 'last_name',
                    ],
                    [
                        'header' => UtilityHelper::tableSortHeader('Email Address (Username)', 'username'),
                        'attribute' => 'username',
                    ],
                    [
                    'label' => 'Is Active',
                    'filter' => Html::activeDropDownList($searchModel, 'active', [0 => 'No', 1 => 'Yes', '' => 'All'], ['class' => 'form-control', 'value' => 0, 'style' => 'width:100px;']),
                    'value' => function ($model) {
                        return $model->active == 1 ? 'Yes' : 'No';
                    },
                    ],
                    ['label' => '',
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'action-cell'],
                        'value' => function ($model) {
                            return UtilityHelper::buildActionWrapper('/admin/user', $model->id, false, null, ($model->active != 1) ? extraLinksUnArchive($model) : extraLinks($model));
                        },
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

 <form action="/admin/user/delete" method="post" id="form-archive-id">
    <input type='hidden' id='id' name='id' value=""/>
</form>

 <form action="/admin/user/undelete" method="post" id="form-unarchive-user">
    <input type='hidden' id='id' name='id' value=""/>
</form>
<?php
function extraLinks($model)
{
    $ret = '<li><a class="user-archive" href="javascript: void(0);" data-id="'. $model->id .'">';
    $ret .= '<i class="fa fa-trash" style="width:15px"></i>Archive</a></li>';

    return $ret;
}
function extraLinksUnArchive($model)
{
    $ret = '<li><a class="user-unarchive" href="javascript: void(0);" data-id="'. $model->id .'">';
    $ret .= '<i class="fa fa-trash" style="width:15px"></i>Un-archive</a></li>';

    return $ret;
}
?>
