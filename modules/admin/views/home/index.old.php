<?php
use app\models\Reminders;
use app\models\Messages;
use app\models\Candidates;
use app\models\PhoneInformation;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\models\TestSession;
$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row" style="padding-top: 15px;">
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">Inbox</div>
            <div class="panel-body inbox-panel-body">
                <?php echo $this->render('../widgets/inbox', ['items' => Messages::getUserMessage(\Yii::$app->user->id, 10, 1, true)]);?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Incomplete Application</div>
            <div class="panel-body incomplete-panel-body">
                <?php echo $this->render('../widgets/incomplete', ['items' => Candidates::getIncompleteApplication(10, 1)]);?>
            </div>
        </div>
    </div>

    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">Ongoing / Upcoming Sessions</div>
            <div class="panel-body sessions-panel-body">
                <?php echo $this->render('../widgets/ongoing-upcoming-sessions', ['ongoingSessions' => TestSession::getOngoingSessions(), 'upcomingSessions' => TestSession::getUpcomingSessions(10)]);?>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Reminders</div>
            <div class="panel-body reminder-panel-body">
                <?php echo $this->render('../widgets/reminders', ['reminders' => Reminders::getUserReminders(\Yii::$app->user->id, 10, 1)]);?>
            </div>
        </div>
    </div>
    
    <div class="col-xs-6">
        <div class="panel panel-default">
            <div class="panel-heading">Discrepancy</div>
            <div class="panel-body discrepancy-panel-body">
                <?php echo $this->render('../widgets/discrepancy', ['discrepancyList' => TestSiteChecklistItemDiscrepancy::getAllDiscrepancy(10, 1)]);?>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading" style='height: 60px;'>
                <div class="col-xs-5" style="line-height: 34px;">
                    Recent Application
                </div>
                <div class="col-xs-offset-3 col-xs-4">
                    <select class="form-control" id="recent-application-time">
                        <option value="1">Last 24 hours</option>
                        <option value="2">Last 2 days</option>
                        <option value="7">Last 7 days</option>
                        <option value="30">Last 30 days</option>
                    </select>
                </div>
            </div>
            <div class="panel-body recent-panel-body">
                <?php echo $this->render('../widgets/recent', ['items' => Candidates::getRecentApplication(10, 1)]);?>
            </div>
        </div>

    </div>



</div>
