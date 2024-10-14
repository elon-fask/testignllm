<?php 
use app\models\TestSessionPhoto;
use yii\bootstrap\Html;
$titlePage = 'Session Photos';

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
                    class="form-control session-photo-date-picker" value='<?php echo date('m/d/Y', strtotime('-7 days'))?>' >
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
                <label class="control-label col-xs-4">Test Sessions</label>
                <div class="col-xs-12 col-md-5">
                    <select class="form-control" name='testSessionId'>
                        <option value="">Select Session</option>
                    </select>
                </div>
            </div>
        <div class="form-group">
                <div class=" col-xs-12 col-md-offset-4 col-md-5">
                    <input type="button" class="btn btn-primary btn-filter-photos" value="Filter Photos"/>
                </div>
            </div>

	</form>
    </div>

    <div  class="row">
        
        <div class="col-xs-12">
           <div class="session-photos-panel-body">
           <?php 
            $filter = [];
            $filter['fromDate'] = date('m/d/Y', strtotime('-7 days'));
            $filter['toDate'] = date('m/d/Y', strtotime('now'));
            
            ?>
                 <?= $this->render('photo-list', [
                    'items' => TestSessionPhoto::getSessionsPhotos($filter, 20, 1),
                     'fromDate' => $filter['fromDate'],
                     'toDate' => $filter['toDate'],
                     'testSessionId' => '',
                ]) ?>
           </div>
        </div>
    </div>
</div>
