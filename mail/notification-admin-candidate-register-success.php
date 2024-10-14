<?php
use app\models\CandidateTransactions;

$authorizenetTx = $candidate->getTransactions()->where([
    'paymentType' => CandidateTransactions::TYPE_ELECTRONIC_PAYMENT
])->one();

$transactionTotals = $candidate->transactionTotals;

?>

<div>
<p>Dear Admin,</p>
<h4>Candidate: <a href="<?= $link?>"><?= $candidate->getFullName()?></a> has successfully registered.</h2>
<br />

<div>
    <h4>Class Stats</h4>
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

<div>
    <h4>Details</h3>
    <table class="table table-striped table-condensed">
        <tbody>
            <tr>
                <td>Details</td>
                <td><a href="<?= $link?>">Click Here</a></td>
            </tr>
            <tr>
                <td>Session Name</td>
                <td><?= $candidate->getWrittenTestSession() !== false ? $candidate->getWrittenTestSession()->getFullTestSessionDescription() : 'N/A'?></td>
            </tr>
            <tr>
                <td>Candidate Name</td>
                <td><?= $candidate->getFullName()?></td>
            </tr>
            <tr>
                <td>Application</td>
                <td><a href="<?= $link?>">Link</a></td>
            </tr>
            <tr>
                <td>Date</td>
                <td><?= date('Y-m-d', strtotime($candidate->date_created))?></td>
            </tr>
            <tr>
                <td>Type of Application</td>
                <td><?= $candidate->getApplicationTypeDesc()?></td>
            </tr>
            <tr>
                <td>Code Word Used</td>
                <td><?= $candidate->getApplicationTypeKeyword()?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?= $candidate->email?></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><?= $candidate->phone?></td>
            </tr>
            <tr>
                <td>Mobile Phone</td>
                <td><?= $candidate->cellNumber != '' ? $candidate->cellNumber : 'N/A'?></td>
            </tr>
            <tr>
                <td>Company Phone</td>
                <td><?= $candidate->company_phone != '' ? $candidate->company_phone : 'N/A'?></td>
            </tr>
            <tr>
                <td>Amount Paid/Total (Owed)</td>
                <td>
                <span class='amount-paid' style='color: green'>$<?= number_format($transactionTotals['totalPayment'], 2, ".", ',')?></span>/
                <span class='amount-total' style='color: #000000'>$<?= number_format($transactionTotals['totalNetPayable'], 2, ".", ',')?></span>
                <span class='amount-owed' style='color: red'>($<?= number_format($transactionTotals['totalAmountOwed'], 2, ".", ',')?>)</span>
                </td>
            </tr>
            <?php if (isset($authorizenetTx)) { ?>
            <tr>
                <td>Authorize.Net Transaction ID</td>
                <td><?= $authorizenetTx->transactionId ?></td>
            </tr>
            <tr>
                <td>Authorize.Net Authorization Code</td>
                <td><?= $authorizenetTx->auth_code ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
