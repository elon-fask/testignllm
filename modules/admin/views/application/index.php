<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\ApplicationType;
use app\models\Candidates;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Application Types';
$this->params['breadcrumbs'][] = ['label' => 'Application Types', 'url' => ['index']];


$searchParams = '';

$params = [];
if(isset($_GET['ApplicationTypeSearch'])){
    foreach($_GET['ApplicationTypeSearch'] as $key => $val){
        if($key != 'app_type')
            $params[] = 'ApplicationTypeSearch['.$key.']='.$val;
    }

}
$searchParams = implode('&', $params);
$this->params['queryParams'] = $searchParams;
//var_dump($searchParams);

function buildActionWrapper($linkId, $isArchived = false){
    $archiveAction = $isArchived ? "unarchive" : "archive";
    $archiveText = $isArchived ? "Un-archive" : "Archive";
    $actionWrapper =
        <<<HTML
                <a href="#" class="show-action"><i class="fa fa-cogs"></i> Actions</a>
        <div class="pop-content" style="display: none">
        <ul style="list-style-type: none; margin: 0; padding: 0;">
            <li><a href="/admin/application/view?id=$linkId"><i class="fa fa-eye" style="width:15px"></i><span style="font-size: 14px;"> View</span></a></li>
            <li><a href="/admin/application/update?id=$linkId"><i class="fa fa-pencil" style="width:15px"></i><span style="font-size: 14px;"> Edit</span></a></li>
            <li><a href="/admin/application/$archiveAction?id=$linkId" class="link-$archiveAction"><i class="fa fa-archive" style="width:15px"></i><span style="font-size: 14px;"> $archiveText</span></a></li>
            <li><a href="/admin/application/delete?id=$linkId" class="link-delete"><i class="fa fa-trash" style="width:15px"></i><span style="font-size: 14px;"> Delete</span></a></li>
        </ul>
HTML;

    return $actionWrapper;
}

?>
<script>
    $(function() {
        $('.public-private').on('change', function(){
            var param = '<?php echo $searchParams?>';
            var origParam = '<?php echo $searchParams?>';
            if(param == ''){
                param += '?';
            }else{
                param += '&';
            }

            if($('#private:checked').length == 1 && $('#public:checked').length == 1){
                param += 'ApplicationTypeSearch[app_type]=3';
            }else if($('#private:checked').length == 1 ){
                param += 'ApplicationTypeSearch[app_type]=2';
            }else if($('#public:checked').length == 1 ){
                param += 'ApplicationTypeSearch[app_type]=1';
            }else{
                param += 'ApplicationTypeSearch[app_type]=0';
            }

            if($('#archived:checked').length == 1) {
                param += '&showArchived=1';
            } else {
                param += '&showArchived=0';
            }

            if(origParam != ''){
                param = '?'+param;
            }

            window.location.href='/admin/application'+param;
        })
    });
</script>
<div class="application-type-index">
    <?php if(isset($s) && $s == '1'){
    $appTypeId = Yii::$app->getRequest()->getQueryParam('apptypeid');
    $candidates = Candidates::find()->where('application_type_id = '. $appTypeId)->all();
    ?>
        <div class="alert alert-danger">
            <p>Cannot delete Application Type. The following applications are using this Application Type:</p>
            <ul>
                <?php foreach ($candidates as $candidate) { ?>
                    <li>
                        <a href="<?= '/admin/candidates/view?id=' . md5($candidate->id) ?>">
                            <?= $candidate->first_name . $candidate->last_name ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    <?php } else if(isset($s) && $s == '0' && $s !== false){ ?>
        <div class="alert alert-success">Delete Successful</div>
    <?php } ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Application Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <div>
        <label>Filter: </label>
        <div class="btn-group" data-sort-type=''>
            &nbsp;&nbsp;&nbsp;
            <input type='checkbox' class='public-private' id='public' <?php echo isset($_GET['ApplicationTypeSearch']['app_type']) && ($_GET['ApplicationTypeSearch']['app_type'] == ApplicationType::TYPE_PUBLIC || $_GET['ApplicationTypeSearch']['app_type'] == 3) ? 'checked' : $appTypeFilter == ApplicationType::TYPE_PUBLIC ? 'checked' : ''?>/> Show Public
            &nbsp;&nbsp;&nbsp;
            <input type='checkbox' class='public-private' id='private' <?php echo isset($_GET['ApplicationTypeSearch']['app_type']) && ($_GET['ApplicationTypeSearch']['app_type'] == ApplicationType::TYPE_PRIVATE || $_GET['ApplicationTypeSearch']['app_type'] == 3) ? 'checked' : ''?>/> Show Private

            <input type='checkbox' class='public-private' id='archived' <?= isset($_GET['showArchived']) && ($_GET['showArchived']) ? 'checked' : '' ?>/> Show Archived

        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header'=> UtilityHelper::tableSortHeader('Name', 'name'),
                'attribute'=>'name',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Keyword', 'keyword'),
                'attribute'=>'keyword',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Description', 'description'),
                'attribute'=>'description',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Price', 'price', 'numeric'),
                'attribute'=>'price',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('NCCCO Testing Services Fee', 'iaiFee', 'numeric'),
                'attribute'=>'iaiFee',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Late Fee', 'lateFee', 'numeric'),
                'attribute'=>'lateFee',
            ],
            [   'label'=>'',
                'format'=>'raw',
                'headerOptions'=>['class'=>'action-cell'],
                'value'=> function($model) {return buildActionWrapper($model->id, $model->isArchived);},
            ],
        ],
    ]); ?>

</div>
<style>
    .application-type-index .popover-content ul > li:first-child{display: none;}
    .application-type-index table td:nth-child(4),
    .application-type-index table td:nth-child(5),
    .application-type-index table td:nth-child(6)
    {
        width: 100px;
    }
    /*.application-type-index table td:nth-child(7){*/
    /*width: 135px;*/
    /*}*/
</style>
