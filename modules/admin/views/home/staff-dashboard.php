<?php
use app\models\TestSession;
use yii\bootstrap\Html;
$this->title = 'Assigned Classes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class=''>
    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class='pull-right'>
            <a href='javascript: void(0);' onclick="javascript: Roster.addReceiptPhoto();" class='btn btn-info'>Add Receipt</a>
        </div>
    </div>
    <div class="">
        <?php echo $this->render('../widgets/ongoing-upcoming-sessions', ['ongoingSessions' => TestSession::getStaffOngoingSessions(\Yii::$app->user->id), 'upcomingSessions' => TestSession::getStaffUpcomingSessions(30, \Yii::$app->user->id) ]);?>
    </div>
</div>

<div class="modal fade" id="view-photo-popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-body-content" style="text-align: center;"></div>
               
            </div>
            <div class="modal-footer" style='text-align: center'>
                <span data-dismiss="modal"><i class="fa fa-3x fa fa-close" style="color:#bbb;"></i></span>
              </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
