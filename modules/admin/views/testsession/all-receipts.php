<?php 
use app\models\TestSessionReceipts;
use yii\bootstrap\Html;
$titlePage = 'Receipts';

$this->title = $titlePage;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<div class="test-site-view">
    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <form id="report-form" action='/admin/reports/generate' class="form-horizontal" target='_blank' method='POST'>
            <div class="form-group">
                <label class="control-label col-xs-4">Start Date</label>&nbsp;&nbsp;&nbsp;
                <div class="col-xs-12 col-md-5">
                    <input type="text" style="width: 100px; " 
                    placeholder="Select Date" name="filter[fromDate]" 
                    class="form-control session-photo-date-picker" value='<?php echo date('m/d/Y', strtotime('-1 month'))?>' >
                </div>
            </div>
		      <div class="form-group">
                <label class="control-label col-xs-4">End Date</label>&nbsp;&nbsp;&nbsp;
                <div class="col-xs-12 col-md-5">
                    <input type="text" style="width: 100px; " 
                    placeholder="Select Date" name="filter[toDate]"
                    class="form-control session-photo-date-picker" value='<?php echo date('m/d/Y', strtotime('now'))?>' >
                </div>
            </div>        
        <div class="form-group">
                <div class=" col-xs-12 col-md-offset-4 col-md-5">
                    <input type="button" class="btn btn-primary btn-show-receipts" value="Filter Receipts"/>
                </div>
            </div>

	</form>
    </div>

    <div  class="row">
        
        <div class="col-xs-12">
           <div class="receipts-panel-body">
           <?php 
            $filter = [];
            $filter['fromDate'] = date('m/d/Y', strtotime('-1 month'));
            $filter['toDate'] = date('m/d/Y', strtotime('now'));
            
            ?>
                 <?= $this->render('_all_receipts', [
                    'items' => TestSessionReceipts::getAllReceipts($filter, 20, 1),
                     'fromDate' => $filter['fromDate'],
                     'toDate' => $filter['toDate']
                ]) ?>
           </div>
        </div>
    </div>
</div>