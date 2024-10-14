<?php
$totalCount = count($ongoingSessions) + count($upcomingSessions);
?>

<?php if ($totalCount == 0) { ?>
    <h2>No Ongoing / Upcoming Sessions</h2>
<?php } else { ?>
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th>Session Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ongoingSessions as $testSession) { ?>
            <tr data-id="<?= $testSession->id ?>">
                <td><?= $testSession->getPartialTestSessionDescription() ?></td>
                <td>Ongoing</td>
            </tr>
            <?php }?>
            <?php foreach ($upcomingSessions as $testSession) { ?>
            <tr data-id="<?= $testSession->id ?>">
                <td><?= $testSession->getPartialTestSessionDescription() ?></td>
                <td>Upcoming</td>
            </tr>
            <?php }?>
        </tbody>
    </table>
<?php } ?>

<div>
To view other sessions, <a href="/admin/calendar">click here to go to Calendar Page</a>.
</div>
