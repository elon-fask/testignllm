<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PromoCodesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promo Codes';
$this->params['breadcrumbs'][] = $this->title;


$searchParams = '';

$params = [];
if(isset($_GET['PromoCodesSearch'])){
    foreach($_GET['PromoCodesSearch'] as $key => $val){
        if($key != 'archived')
            $params[] = 'PromoCodesSearch['.$key.']='.$val;
    }

}
$searchParams = implode('&', $params);
$this->params['queryParams'] = $searchParams;
//var_dump($searchParams);

?>
<script>
$(function() {
    $('.show-archived').on('change', function(){
        var param = '<?php echo $searchParams?>';
        var origParam = '<?php echo $searchParams?>';
        if(param == ''){
        	param += '?';
        }else{
        	param += '&';
        }

        if($('#show-archived:checked').length == 1){
        	param += 'PromoCodesSearch[archived]=2';    
        }else{
        	param += 'PromoCodesSearch[archived]=0'; 
        }

        if(origParam != ''){
            param = '?'+param;
        }
        
        window.location.href='/admin/promo'+param;
    })
});
</script>

<div class="promo-codes-index">

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= Html::a('<i class="fa fa-plus"></i> Create Promo Codes', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div>
        <label>Filter: </label>
        <div class="btn-group" data-sort-type=''>
            &nbsp;&nbsp;&nbsp; 
            <input type='checkbox' class='show-archived' id='show-archived' <?php echo (isset($_GET['PromoCodesSearch']['archived'])) && $_GET['PromoCodesSearch']['archived'] == 2 ? 'checked' : ''?> /> Show Archived Promo Codes
          
        </div>        
    </div>
    
    <div  class="row">
        <div class="col-xs-12">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'header'=> UtilityHelper::tableSortHeader('Code', 'code'),
                    'attribute'=>'code',
                ],
                [
                    'header'=> UtilityHelper::tableSortHeader('Discount', 'discount', 'numeric'),
                    'attribute'=>'discount',
                ],
                [
                    'label' => 'Is Purchase Order',
                    'headerOptions'=>array('style'=>'width:145px'),
                    'filter' => Html::activeDropDownList($searchModel, 'isPurchaseOrder', [''=>'Show All', 0=>'No', 1=>'Yes'], ['class'=>'form-control']),
                    'value' => function($model){
                        return $model->isPurchaseOrder == 1 ? 'Yes' : 'No';
                    }
                ],
                [
                    'header'=> UtilityHelper::tableSortHeader('Assigned To Name', 'assignedToName'),
                    'attribute'=>'assignedToName',
                ],
                [   'label'=>'',
                    'format'=>'raw',
                    'headerOptions'=>['class'=>'action-cell'],
                    'value'=> function($model){return UtilityHelper::buildActionWrapper('/admin/promo', $model->id, false, null, extraLinks($model));},
                ],
            ],
        ]); ?>
        </div>
    </div>

    <form action="/admin/promo/archive" method="post" id="form-archive-promo">
        <input type='hidden' id='id' name='id' value=""/>
        <input type='hidden' id='archive' name='archive' value=""/>
    </form>
</div>

<style>
    table td:nth-child(2){width: 100px;}
</style>
<?php 
function extraLinks($model)
{
    if($model->archived == 0){
        $ret = '<li><a class="promo-archive" href="javascript: void(0);" data-next="1" data-id="'. $model->id .'">';
        $ret .= '<i class="fa fa-trash" style="width:15px"></i>Archive</a></li>';
    }else{
        $ret = '<li><a class="promo-archive" href="javascript: void(0);" data-next="0" data-id="'. $model->id .'">';
        $ret .= '<i class="fa fa-trash" style="width:15px"></i>Un-Archive</a></li>';
    }
    return $ret;
}
?>