<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use app\helpers\UtilityHelper;
use yii\web\Request;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$titlePage = 'Contact Search';

$this->title = $titlePage;
?>
<div class="candidates-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="display: flex; align-items: flex-end; margin-bottom: 20px;">
        <div>
            <span>Start Date</span>
            <?php
            echo DatePicker::widget([
                'name' => 'startDate',
                'value' => $startDate,
                'template' => '{input}{addon}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'orientation'=> 'bottom'
                ]
            ]);
            ?>
        </div>
        <div style="margin-left: 20px;">
            <span>End Date</span>
            <?php
            echo DatePicker::widget([
                'name' => 'endDate',
                'value' => $endDate,
                'template' => '{input}{addon}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'orientation'=> 'bottom'
                ]
            ]);
            ?>
        </div>

        <div style="margin-left: 20px;">
            <button type="button" id="filter-by-date" class="btn btn-primary">Filter by Date</button>
        </div>

        <div style="margin-left: 20px;">
            <button type="button" id="spreadsheet-dl-btn" class="btn btn-primary">Download Spreadsheet</button>
            <a href="#" id="spreadsheet-dl-link" style="display: none;">Link</a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header'=> UtilityHelper::tableSortHeader('Last Name', 'last_name'),
                'attribute'=>'last_name',
            ], [
                'header'=> UtilityHelper::tableSortHeader('First Name', 'first_name'),
                'attribute'=>'first_name',
            ], [
                'header'=> UtilityHelper::tableSortHeader('Personal Email', 'email'),
                'attribute'=>'email'
            ], [
                'header'=> UtilityHelper::tableSortHeader('Home Phone', 'phone'),
                'attribute'=>'phone'
            ], [
                'header'=> UtilityHelper::tableSortHeader('Cell Phone', 'cellNumber'),
                'attribute'=>'cellNumber'
            ], [
                'header'=> UtilityHelper::tableSortHeader('Company', 'company_name'),
                'attribute'=>'company_name'
            ], [
                'header'=> UtilityHelper::tableSortHeader('Company Email', 'contactEmail'),
                'attribute'=>'contactEmail'
            ], [
                'header'=> UtilityHelper::tableSortHeader('Company Phone', 'company_phone'),
                'attribute'=>'company_phone'
            ]
        ]
    ]); ?>

</div>

<script>
$('#main-container').removeClass('container').addClass('container-float');

var startDate = $('input[name="startDate"]');
var endDate = $('input[name="endDate"]');

$('#filter-by-date').click(function() {
    window.location.search = $.query.set('startDate', startDate.val()).set('endDate', endDate.val()).toString();
})

$('#spreadsheet-dl-btn').click(function() {
    var dlLink = $('#spreadsheet-dl-link');
    dlLink.attr('href', '/admin/contacts/download' + window.location.search);
    dlLink[0].click();
});
</script>

<style>
    .container-float {
        margin-top: 30px;
        padding: 20px;
    }

    .pop-content ul{width: 125px;}
    .candidates-index table tr th{vertical-align: middle}
</style>
