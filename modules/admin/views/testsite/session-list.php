<?php
use app\helpers\UtilityHelper;

$list = $items['list'];
$totalCount = $items['count'];
?>
<?php if ($totalCount == 0) { ?>
    <h2>No Sessions</h2>
<?php } else { ?>
    <table class="table table-striped table-bordered table-session-list">
        <thead>
            <tr style="background: #f5f5f5">
                <th>Enrollment Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Candidates</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <tr style="display: none">
            <td colspan="5"></td>
        </tr>
        <?php foreach ($list as $session) { ?>
            <tr class="session-info" data-sessionid="<?php echo $session->id?>">
                <td><?php echo $session->getEnrollmentTypeDescription() ?></td>
                <td><?php echo $session->getStartDateDisplay() ?></td>
                <td><?php echo $session->getEndDateDisplay() ?></td>
                <td>
                    <a href='/admin/candidatesession?i=<?php echo md5($session->id) ?>'><?php echo $session->getNumberOfRegisteredCandidates() ?></a>
                </td>
                <td><?php
                    if ($session->getNumberOfRegisteredCandidates() > 0 ){
                        $customDelete =  false;
                    }else{
                        $customDelete = '<li><a href="/admin/testsession/deleteasync" data-id="'. $session->id .'" class="link-delete-session-async"><i class="fa fa-trash" style="width:15px;"></i> Delete</a></li>';
                    }

                    echo UtilityHelper::buildActionWrapper('/admin/testsession',
                        $session->id,
                        false,
                            [[
                                'label' => 'View Roster',
                                'url' => '/admin/candidatesession?i=' . md5($session->id),
                                'ico' => 'fa-list',
                            ]],
                        $customDelete
                        );
                    ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php } ?>
<div class="session-pagination" data-test-site-id='<?php echo $testSiteId ?>' data-total-pages="<?php echo ceil($totalCount / 10) ?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1 ?>">

</div>
<style>
    .popover {
        width: 130px;
    }
</style>
