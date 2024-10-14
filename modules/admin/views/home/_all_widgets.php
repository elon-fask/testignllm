<?php
use app\models\Reminders;
use app\models\Messages;
use app\models\Candidates;
use app\models\TestSiteChecklistItemDiscrepancy;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
?>
<br />

<div class="row dashboard-widgets">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

        <div class="widget widget-item-wrapper widget-applications">
            <h3>Recent Applications</h3>
            <?php
            $app = Candidates::getRecentApplication(10, 1);
            ?>
            <div class="widget-item"  data-modaltitle="Recent Applications">
                <div class="widget-item-content">
                    <a href="#" class="wic-link">
                        <span class="widget-item-number"><?php echo $app['count']?></span>
                        <span class="widget-item-label">Last 24 hours</span>
                    </a>
                </div>

                <div class="widget-item-modal-content-wrapper">
                    <div class="widget-item-modal-content">
                        <?php echo $this->render('../widgets/recent', ['items' => Candidates::getRecentApplication(10, 1)]);?>
                    </div>
                </div>

            </div>

            <ul class="widget-item-links">
                <li><a href="#" class="quick-preview">View List <i class="fa fa-list"></i></a></li>
                <li><a href="/admin/candidates">Go to Applications List <i class="fa fa-external-link"></i></a></li>
            </ul>
        </div>
    </div>


    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

        <div class="widget widget-item-wrapper widget-incomplete">
            <h3>Incomplete Applications</h3>
            <?php
            $incomplete = Candidates::getIncompleteApplication(10, 1)
            ?>
            <div class="widget-item" data-modaltitle="Incomplete Applications">

                <div class="widget-item-content">
                    <a href="#" class="wic-link">
                        <span class="widget-item-number"><?php echo $incomplete['count']?></span>
                        <span class="widget-item-label">Applications</span>
                    </a>
                </div>

                <div class="widget-item-modal-content-wrapper">
                    <div class="widget-item-modal-content">
                        <?php echo $this->render('../widgets/incomplete', ['items' => Candidates::getIncompleteApplication(10, 1)]);?>
                    </div>
                </div>

            </div>

            <ul class="widget-item-links">
                <li><a href="#" class="quick-preview">View List <i class="fa fa-list"></i></a></li>
            </ul>

        </div>
    </div>
</div>
