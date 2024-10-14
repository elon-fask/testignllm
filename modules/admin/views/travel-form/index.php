<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TravelFormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Travel Forms';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="travel-form-index">

    <h1 style="margin-top: 60px"><?= Html::encode($this->title) ?></h1>

    <p>
        <a class="btn btn-success" href="/travel-form">Create Travel Form</a>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header'=> UtilityHelper::tableSortHeader('Name', 'name'),
                'attribute' => 'name'
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Starting Airport Location', 'starting_location'),
                'attribute' => 'starting_location'
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Destination Location', 'destination_loc'),
                'attribute' => 'destination_loc',
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Destination Date', 'destination_date'),
                'attribute' => 'destination_date',
                'value' => function($travelForm) {
                    return date_format(date_create($travelForm->destination_date), 'm/d/Y');
                }
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Destination Time', 'destination_time'),
                'attribute' => 'destination_time'
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Return Location', 'return_loc'),
                'attribute' => 'return_loc',
                'value' => function($travelForm) {
                    return $travelForm->one_way ? 'One Way Travel Only' : $travelForm->return_loc;
                }
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Return Date', 'return_date'),
                'attribute' => 'return_date',
                'value' => function($travelForm) {
                    return $travelForm->one_way ? 'One Way Travel Only' : date_format(date_create($travelForm->return_date), 'm/d/Y');
                }
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Return Time', 'return_time'),
                'attribute' => 'return_time',
                'value' => function($travelForm) {
                    return $travelForm->one_way ? 'One Way Travel Only' : $travelForm->return_time;
                }
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Hotel Required', 'hotel_required'),
                'attribute' => 'hotel_required',
                'value' => function($travelForm) {
                    return $travelForm->hotel_required ? 'Yes' : 'No';
                }
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Car Rental Required', 'car_rental_required'),
                'attribute' => 'car_rental_required',
                'value' => function($travelForm) {
                    return $travelForm->car_rental_required ? 'Yes' : 'No';
                }
            ],
            [
                'header' => UtilityHelper::tableSortHeader('Completed', 'completed'),
                'attribute' => 'completed',
                'format' => 'raw',
                'value' => function($travelForm) {
                    $completedStr = '<button type="button" class="btn btn-success mark-complete" data-value="0" data-id="' . $travelForm->id . '">Yes</button>';
                    $notCompletedStr = '<button type="button" class="btn btn-danger mark-complete" data-value="1" data-id="' . $travelForm->id . '">No</button>';

                    return $travelForm->completed ? $completedStr : $notCompletedStr;
                }
            ],
            [
                'attribute' => 'comment',
                'value' => function($travelForm) {
                    return $travelForm->comment ? $travelForm->comment : '';
                },
                'contentOptions' => [
                    'class' => 'read-more',
                    'style' => 'max-width: 500px;'
                ]
            ],
            [
                'attribute' => 'notes',
                'value' => function($travelForm) {
                    return $travelForm->notes ? $travelForm->notes : '';
                },
                'contentOptions' => [
                    'style' => 'max-width: 500px;'
                ]
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<div style="display: none">
<form id="update-completed" method="POST">
<input name="TravelForm[completed]" />
</form>
</div>

<script>
$('#main-container').removeClass('container');
$('#main-container').addClass('container-fluid');

$('.mark-complete').click(function(event) {
    var travelFormId = event.target.dataset.id;
    var complete = event.target.dataset.value;

    var confirmed = confirm('Are you sure you wish to mark Travel Form as ' + (complete === '1' ? 'completed?' : 'not completed?'));

    if (confirmed) {
        var form = $('#update-completed');
        form.attr('action', '/admin/travel-form/update?id=' + travelFormId + '&redirectIndex=1');
        form.children('input[name="TravelForm[completed]"]').val(complete === '1' ? 1 : 0);
        form.submit();
    }
});

var expandedText = {};

$('.read-more').each(function(i, el) {
    var textId = el.parentElement.dataset.key;
    expandedText[textId] = el.innerText;

    if (el.innerText.length > 255) {
        el.innerHTML = '<div>' + el.innerText.substring(0, 256) + '...&nbsp;<button class="btn btn-info btn-read-more" data-key="'+ textId +'">Read More</button></div>';
    }
});

$('.btn-read-more').click(function(event) {
    var textId = event.target.dataset.key;
    var text = expandedText[textId];
    event.target.parentElement.parentElement.innerText = text;
});
</script>
