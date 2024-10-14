<?php
use yii\helpers\Html;
use app\models\TestSite;
use app\models\Staff;
use app\helpers\UtilityHelper;
?>

<div class="container-fluid container-footer-wraper footer hidden-xs hidden-sm">
    <div class="container container-footer-inner">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 col-sm-6 col-md-2">
                    <ul>
                        <li><h4><a href="/admin/home">Dashboard</a></h4></li>
<!--                        <li class="hidden-xs hidden-sm"><a href="#">Logout</a></li>-->
                        <li><h4><a href="/admin/messaging">Inbox</a></h4></li>
                        <li><h4><a href="/admin/calendar">Calendar</a></h4></li>
                    </ul>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <ul>
                        <li><h4><a href="#">Sites &amp Sessions</a></h4></li>
                        <li class="hidden-xs hidden-sm"><a href="#">Manage Sessions</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsession">Written &amp; Practical Sessions List</a></li>
                        <?php if(UtilityHelper::isSuperAdmin()){?>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsession/create?type=<?php echo base64_encode(TestSite::TYPE_WRITTEN)?>">Add New Written Session</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsession/create?type=<?php echo base64_encode(TestSite::TYPE_PRACTICAL)?>">Add New Practical Session</a></li>
                        <?php }?>
                        <li class="hidden-xs hidden-sm"><a href="#">Manage Sites</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsite/written">Written Sites List</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsite/practical">Practical Sites List</a></li>
                        <?php if(UtilityHelper::isSuperAdmin()){?>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsite/create?type=<?php echo base64_encode(TestSite::TYPE_WRITTEN)?>">Add New Written Site</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/testsite/create?type=<?php echo base64_encode(TestSite::TYPE_PRACTICAL)?>">Add New Practical Site</a></li>
                        <?php }?>
                    </ul>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <ul>
                        <li><h4><a href="#">Applications</a></h4></li>
                        <li class="hidden-xs hidden-sm"><a href="#">Manage Students</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/candidates/create">Manually Enter New Application</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/candidates">Search for Existing Application</a></li>
                        <?php if(UtilityHelper::isSuperAdmin()){?>
                        <li class="hidden-xs hidden-sm"><a href="#">Manage Applications</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/application">Application Program Type</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/application/create">Create New Application Type</a></li>
                        <?php }?>
                    </ul>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <ul>
                    <?php if(UtilityHelper::isSuperAdmin()){?>
                        <li><h4><a href="#">Staff</a></h4></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/staff">Staff List</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/user">Website Admin</a></li>
                    <?php }?>
                    <?php if(UtilityHelper::isSuperAdmin()){?>
                        <li><h4><a href="#">Promos</a></h4></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/promo">Promo List</a></li>
                        <li class="hidden-xs hidden-sm"><a href="/admin/promo/create">Create Promo</a></li>
                        <?php }?>
                    </ul>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
                    <ul>
<!--                        <li><a href="#"><h4>Resources</h4></a></li>-->
<!--                        <li><h4><a href="#">Reports</a></h4></li>-->
                        <li><h4><a href="/admin/default/logout" class="logout">Logout</a></h4></li>
                    </ul>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-2">
<!--                    <div class="footer-logo">-->
                        <img src="/images/ccslogo-s.png" class="img-responsive"/>

<!--                    </div>-->
                </div>

            </div>
        </div>
    </div>

    <div class="copy">
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <span>&copy; craneadmin.com <?= date("Y") ?></span><span style="margin-left: 2rem;">version 20190732.1</span>
                </div>
            </div>
        </div>
    </div>
</div>
