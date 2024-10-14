<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\TestSite;
use dosamigos\datepicker\DatePicker;
use app\models\TestSession;
use app\models\Candidates;
use app\models\ChecklistTemplate;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TestSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$titlePage = 'Test Session Receipts';

$this->title = $titlePage;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

$allAvailDates = [];
foreach($allReceipts as $receipt){
    $dateCreated = date('M d, Y', strtotime($receipt->date_created));
    if(!isset($allAvailDates[$dateCreated])){
        $allAvailDates[$dateCreated] = date('Y-m-d', strtotime($receipt->date_created));;
    }
}
?>

<div class="test-session-index">
    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <select data-id='<?php echo $testSessionId?>' class='form-control pull-right receipt-filter' name='date' style='width: 150px'>
                <option value=''>Show All</option>
                <?php foreach($allAvailDates as $key => $date){?>
                <option value='<?php echo $date?>'><?php echo $key?></option>
                <?php }?>
            </select>
            <label class='pull-right' style='margin-right: 10px; margin-top: 5px'>Show Receipts: </label>
        </div>
        
    </div>
    <div class='row receipt-archive'>
        <?= $this->render('_receipts_archive', [
        'allReceipts' => $allReceipts,
    ]) ?>
    </div>
</div>
