<?php

use app\assets\AppAsset;

AppAsset::register($this);
?>

<div style="display: flex; justify-content: center">
    <table class="table-bordered">
        <thead>
            <tr><th>Name</th><th>Company</th><th>Practical Exam</th><th>Class</th><th>Date Signed</td><th>Signature</th></tr>
        </thead>
        <tbody>
        <?php foreach ($declinedTests as $declinedTest) { ?>
            <tr>
                <td><?= $declinedTest['candidateName'] ?></td>
                <td><?= $declinedTest['companyName'] ?></td>
                <td><?= $declinedTest['crane'] ?></td>
                <td><?= $declinedTest['testSession'] ?></td>
                <td><?= $declinedTest['createdAt'] ?></td>
                <td><img src="<?= $declinedTest['signatureUrl'] ?>" alt="Signature" /></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<style>
td, th {
    padding: 8px;
}
</style>
