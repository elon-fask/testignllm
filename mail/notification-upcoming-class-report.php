<?php
?>

<div>
<p>Dear Admin,</p>
<p>Please review the following classes:</p>
<br />

<h4>Classes Starting in Two Weeks:</h4>
<?php foreach($lateFeeTestSessions as $session) { ?>
<?php $classStats = $session->classStats; ?>
<div>
<p style="font-weight: bold;">Class Stats - <?= $session->fullTestSessionDescription ?></p>
<table>
<tbody>
    <tr>
        <td>Regular Students</td>
        <td><?= $classStats['totalRegular'] ?> / 35</td>
    </tr>
    <tr>
        <td>Swing Cab Examinees</td>
        <td><?= $classStats['sw'] ?> / 20</td>
    </tr>
    <tr>
        <td>Fixed Cab Examinees</td>
        <td><?= $classStats['fx'] ?> / 20</td>
    </tr>
</tbody>
</table>
</div>
<br />
<?php } ?>

<h4>Classes Past Registration Deadline:</h4>
<?php foreach($regCloseTestSessions as $session) { ?>
<?php $classStats = $session->classStats; ?>
<div>
<p  style="font-weight: bold;">Class Stats - <?= $session->fullTestSessionDescription ?></p>
<table>
<tbody>
    <tr>
        <td>Regular Students</td>
        <td><?= $classStats['totalRegular'] ?> / 35</td>
    </tr>
    <tr>
        <td>Swing Cab Examinees</td>
        <td><?= $classStats['sw'] ?> / 20</td>
    </tr>
    <tr>
        <td>Fixed Cab Examinees</td>
        <td><?= $classStats['fx'] ?> / 20</td>
    </tr>
</tbody>
</table>
</div>
<br />
<?php } ?>
</div>
