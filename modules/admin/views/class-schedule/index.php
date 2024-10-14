<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TestSessionClassScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Test Session Class Schedules';
$this->params['breadcrumbs'][] = $this->title;

function buildCustomDeleteLink($id, $el)
{
    $selectHtml = '';
    if (UtilityHelper::isSuperAdmin()) {
        return $selectHtml . '<li>' .
            '<a class="link-delete-class-async" data-id="'.$id.'" href="/admin/class-schedule/deleteasync">' .
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

<div class="test-session-class-schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Test Session Class Schedule', ['create', 'id' => md5($testSession->id)], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'testSessionId',
            'classDate',
            'startTime',
            'endTime',
            // 'date_created',

            ['label' => '',
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'action-cell'],
                        'value' => function ($model) {
                            return UtilityHelper::buildActionWrapper('/admin/class-schedule', $model->id, false,
                                extraLinks($model, $this),
                                buildCustomDeleteLink($model->id, $this), false
                            );
                        },
                    ],
        ],
    ]); ?>
</div>
