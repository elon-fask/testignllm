
<?php
use yii\helpers\Html;
use app\models\TestSite;
use app\models\Staff;
use app\models\ChecklistTemplate;
use app\models\User;
use app\models\UserRole;
use app\helpers\UtilityHelper;
?>
<?php if(!\Yii::$app->user->isGuest){?>


    <?php
    $currentPage = \Yii::$app->controller->getRoute();

    $user = User::findOne(\Yii::$app->user->id);
    $roles = $user->roles;
    $isAdmin = in_array(UserRole::SUPER_ADMIN, $roles);
    $isWrittenAdmin = in_array(UserRole::WRITTEN_ADMIN, $roles);
    ?>

    <input type="hidden" name="messaging-url" id="messaging-url" value="/admin/messaging/"/>
    <input type="hidden" name="current-user-key" id="current-user-key" value="<?= base64_encode(\Yii::$app->user->id) ?>"/>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-site" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php echo Html::a('CraneAdmin', ['/admin'], ['class'=>'navbar-brand']); ?>
            </div>

            <?php if ($isAdmin || $isWrittenAdmin) { ?>
                <div class="collapse navbar-collapse" id="navbar-site">
                <ul class="nav navbar-nav">
                    <li role="separator" class="divider"></li>
                    <li class="dropdown<?php echo in_array($currentPage, ['admin/home/index']) ? ' active' : '';?>">
                        <a href="/admin/home">Dashboard</a>
                    </li>
                    <?php
                    $activeRoutes = [
                        'admin/testsession',
                        'admin/testsession/view',
                        'admin/testsession/update',
                        'admin/candidatesession',
                        'admin/testsite/practical',
                        'admin/testsite/create',
                        'admin/testsession/create',
                        'admin/testsite/written',
                        'admin/testsite/create',
                        'admin/testsession/create',
                        'admin/checklist/index',
                        'admin/checklist/create',
                    ];
                    ?>
                    <li class="dropdown<?php echo in_array($currentPage, $activeRoutes) ? ' active' : '';?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="hidden-sm">Manage </span>Sessions<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><h4>Manage Sessions</h4></li>
                            <li><a href="/admin/testsession">Written &amp; Practical Sessions List</a></li>
                            <?php if(UtilityHelper::isSuperAdmin()){?>
                                <li><a href="/admin/testsession/create?type=<?php echo base64_encode(TestSite::TYPE_WRITTEN)?>">Add New Written Session</a></li>
                                <li><a href="/admin/testsession/create?type=<?php echo base64_encode(TestSite::TYPE_PRACTICAL)?>">Add New Practical Session</a></li>
                                <li><a href="/admin/testsession/photos">Session Photos</a></li>
                                
                            <?php }?>
                            <li role="separator" class="divider"></li>
                            <li><h4>Manage Sites</h4></li>
                            <li><a href="/admin/testsite/written">Written Sites List</a></li>
                            <li><a href="/admin/testsite/practical">Practical Sites List</a></li>
                            <?php if(UtilityHelper::isSuperAdmin()){?>
                                <li><a href="/admin/testsite/create?type=<?php echo base64_encode(TestSite::TYPE_WRITTEN)?>">Add New Written Site</a></li>
                                <li><a href="/admin/testsite/create?type=<?php echo base64_encode(TestSite::TYPE_PRACTICAL)?>">Add New Practical Site</a></li>
                            <?php } ?>
                        </ul>
                    </li>

                    <?php /* CALENDAR */ ?>
                    <?php
                    $activeRoutes = [
                        'admin/calendar',
                        'admin/calendar/index'
                    ];
                    ?>
                    <li class="dropdown<?php echo in_array($currentPage, $activeRoutes) ? ' active' : '';?>">
                        <a href="/admin/calendar">Calendar</a>
                    </li>

                    <?php /* APPLICATIONS  */ ?>
                    <?php
                    $activeRoutes = [
                        'admin/candidates/create',
                        'admin/candidates/index',
                        'admin/candidates/view',
                        'admin/candidates/payment',
                        'admin/candidates/update',
                        'admin/application/index',
                        'admin/application/create',
                        'admin/application/update'
                    ];
                    ?>
                    <li class="dropdown<?php echo in_array($currentPage, $activeRoutes) ? ' active' : '';?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Applications<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><h4>Manage Students</h4></li>
                            <li><a href="/admin/candidates/create">Manually Enter New Application</a></li>
                            <li><a href="/admin/candidates/bulk-register">Bulk Register Student Applications</a></li>
                            <li><a href="/admin/candidates">Search for Existing Application</a></li>
                            <li><a href="/admin/candidates/search">Advanced Candidate Search</a></li>

                            <?php if ($isAdmin) {?>
                                <li role="separator" class="divider"></li>
                                <li><h4>Manage Applications</h4></li>
                                <li><a href="/admin/application">Application Program Type</a></li>
                                <li><a href="/admin/application/wizard">Application Program Wizard</a></li>
                                <li><a href="/admin/application/create">Create New Application Type</a></li>
                            <?php }?>
                        </ul>
                    </li>


                    <?php /* Info  */ ?>
                    <?php
                    $activeRoutes = [
                        'admin/cranes/index',
                        'admin/cranes/create',
                        'admin/cranes/update',
                        'admin/promo/index',
                        'admin/promo/view',
                        'admin/promo/create',
                        'admin/promo/update',
                        'admin/phone',
                        'admin/reports/index',
                        'admin/reports',
                        'admin/staff/index',
                        'admin/staff/view',
                        'admin/staff/create',
                        'admin/staff/update',
                        'admin/user/index',
                        'admin/user/create',
                        'admin/user/update',
                        'admin/user/view',
                        'admin/settings/index'
                    ];
                    ?>
                    <li class="dropdown<?php echo in_array($currentPage, $activeRoutes) ? ' active' : '';?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Info<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><h4>Companies</h4></li>
                            <li><a href="/admin/company">Companies</a></li>
                            <li><a href="/admin/company/transaction">Company Transactions</a></li>
                            <li role="separator" class="divider"></li>
                            <li><h4>Cranes</h4></li>
                            <li><a href="/admin/cranes">Crane List</a></li>
                            <li role="separator" class="divider"></li>
                            <li><h4>Reports</h4></li>
                            <li><a href="/admin/reports">Reports Generation</a></li>
                            <li><a href="/admin/reports/custom">Custom Report Generator</a></li>
                            <li role="separator" class="divider"></li>
                            <li><h4>Promos</h4></li>
                            <li><a href="/admin/promo">Promo List</a></li>
                            <li><a href="/admin/promo/create">Create Promo</a></li>
                            <li role="separator" class="divider"></li>
                            <li><h4>Travel Forms</h4></li>
                            <li><a href="/admin/travel-form">Travel Forms</a></li>
                            <li role="separator" class="divider"></li>
                            <?php if ($isAdmin) { ?>
                            <li><h4>Users</h4></li>
                            <li><a href="/admin/staff">User List</a></li>
                            <li role="separator" class="divider"></li>
                            <?php } ?>
                            <li><h4>Contacts</h4></li>
                            <li><a href="/admin/contacts">Student/Company Contact Search</a></li>
                            <li><a href="/admin/contacts/download-email">Download Candidate Email Addresses</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/admin/testsession/all-receipts">Receipts</a></li>
                            <?php if ($isAdmin) { ?>
                            <li><a target='_blank' href="/admin/home/policies">Policies & Procedures</a></li>
                            <?php } ?>
                            <li><a href="/admin/settings">Settings</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/admin/default/logout" class="logout">Logout</a></li>
                        </ul>
                    </li>

                    <?php
                    $activeRoutes = [
                        'admin/user-log'
                    ];
                    ?>
                    <li class="dropdown<?php echo in_array($currentPage, $activeRoutes) ? ' active' : '';?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Logs<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/user-log">User Activity Logs</a></li>
                        </ul>
                    </li>

                    <li class="dropdown visible-sm visible-xs">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tools<span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-tools-sm">
                            <li class="phone-info"><a href="#" class="add-phone" title="Add Phone Info" data-toggle="tooltip" data-placement="top"><i class="fa fa-phone"></i></a></li>
                            <li class="note"><a href="#" class="add-reminder" title="Add Reminder" data-toggle="tooltip" data-placement="top"><i class="fa fa-file-text-o"></i></a></li>
                            <li class="mail-notice"><a href="/admin/messaging" title="Inbox"  data-placement="top"><div class="info-badge-wrapper"><span class="inbox-badge">&nbsp;</span></div><i class="fa fa-envelope-o"></i></a></li>
                            <li class="logout"><a class="logout" href="/admin/default/logout" title="Logout"  data-toggle="tooltip" data-placement="top" ><i class="fa fa-power-off"></i></a></li>
                        </ul>
                    </li>

                </ul>

                <ul class="nav navbar-nav navbar-right hidden-sm hidden-xs">
                    <li class="phone-info"><a href="#" class="add-phone" title="Add Phone Info" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-phone"></i></a></li>
                    <li class="note"><a href="#" class="add-reminder" title="Add Reminder" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-file-text-o"></i></a></li>
                    <li class="mail-notice"><a href="/admin/messaging" title="Inbox"  data-placement="bottom"><div class="info-badge-wrapper"><span class="inbox-badge">&nbsp;</span></div><i class="fa fa-envelope-o"></i></a></li>
                    <li class="logout"><a class="logout" href="/admin/default/logout" title="Logout"  data-toggle="tooltip" data-placement="bottom" ><i class="fa fa-power-off"></i></a></li>
                </ul>
            <?php } elseif (in_array(UserRole::TRAVEL_COORDINATOR, $roles)) { ?>
                <div class="collapse navbar-collapse" id="navbar-site">
                    <ul class="nav navbar-nav">
                    <?php /* Info  */ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Info<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><h4>Travel Forms</h4></li>
                            <li><a href="/admin/travel-form">Travel Forms</a></li>
                            <li role="separator" class="divider"></li>
                        </ul>
                    </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right hidden-sm hidden-xs">
                    <li class="phone-info"><a href="#" class="add-phone" title="Add Phone Info" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-phone"></i></a></li>
                    <li class="note"><a href="#" class="add-reminder" title="Add Reminder" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-file-text-o"></i></a></li>
                    <li class="mail-notice"><a href="/admin/messaging" title="Inbox"  data-placement="bottom"><div class="info-badge-wrapper"><span class="inbox-badge">&nbsp;</span></div><i class="fa fa-envelope-o"></i></a></li>
                    <li class="logout"><a class="logout" href="/admin/default/logout" title="Logout"  data-toggle="tooltip" data-placement="bottom" ><i class="fa fa-power-off"></i></a></li>
                </ul>
                </div>
            <?php } ?>
        </div><!-- /.container-fluid -->
    </nav>
<?php }?>

<style>
@media (max-width: 767px) {
    .dropdown-tools-sm li{
        float: left;

    }
}
@media (min-width: 767px) and (max-width: 992px) {
    .info-badge-wrapper{
        border-color: #333;
        color: #fff;
    }
}

</style>
