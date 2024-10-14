<?php
use yii\helpers\Url;

$tz = 'America/Los_Angeles';
$ts = time();
$dt = new \DateTime('now', new \DateTimeZone($tz));
$dt->setTimestamp($ts);
$dateTimeStr = $dt->format('F j, Y');
$year = $dt->format('Y');

$hasCompleteAddress = $candidate->address && $candidate->city && $candidate->state && $candidate->zip;
$lineItems = $pendingTx->lineItems;
?>

<style type="text/css">
p {
    margin-top: 0;
    margin-bottom: 0;
}
.table th, .table td {
    border: 2px solid #3B5E91;
    margin: 0px;
}
.table th {
    background-color: #E4EAF4;
    padding: 20px;
}
.table td {

}
.table.text-center th, .table.text-center td {
    text-align: center;
}
</style>

<div>
    <table style="width: 100%;">
    <tbody>
    <tr>
    <td>
        <div>
            <h2>California Crane School, Inc.</h2>
            <p>111 Bank Street #254, Grass Valley, CA 95945</p>
            <p>Phone (888) 967-7277 Fax (888) 701-7277</p>
        </div>
    </td>
    <td>
        <div style="text-align: right;">
            <p>RECEIPT# <?= $year . '-' . str_pad($pendingTx->id, 8, "0", STR_PAD_LEFT) ?></p>
            <p>DATE: <?= $dateTimeStr ?></p>
        </div>
    </td>
    </tr>
    </tbody>
    </table>
    <br />
    <h2><?= $candidate->getFullName() ?></h2>
    <?php if ($hasCompleteAddress) { ?>
    <p><?= $candidate->address ?></p>
    <p><?= $candidate->city . ', ' . $candidate->state . ' ' . $candidate->zip ?></p>
    <?php } ?>
    <br />
    <table>
    <tr>
    <td style="text-align: center">
    <table class="table text-center" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 20px;">PAYMENT METHOD</th>
            <?php if ($pendingTx->type === 2 && isset($pendingTx->check_number)) { ?>
            <th style="padding: 20px;">CHECK NO.</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 8px;"><?= $paymentMethod ?></td>
            <?php if ($pendingTx->type === 2 && isset($pendingTx->check_number)) { ?>
            <td style="padding: 8px;"><?= $pendingTx->check_number ?></td>
            <?php } ?>
        </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </table>
    <br />
    <table class="table" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 8px;">QTY</th>
            <th style="padding: 8px;">DESCRIPTION</th>
            <th style="padding: 8px;">UNIT PRICE</th>
            <th style="padding: 8px;">DISCOUNT</th>
            <th style="padding: 8px;">LINE TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($lineItems) > 0) { ?>
        <?php foreach($lineItems as $lineItem) { ?>
        <tr>
            <td style="padding: 8px;">1</td>
            <td style="padding: 8px;"><?= $lineItem->description ?></td>
            <td style="padding: 8px;">$<?= $lineItem->amount ?></td>
            <td style="padding: 8px;"></td>
            <td style="padding: 8px;">$<?= $lineItem->amount ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
            <td style="padding: 8px;">1</td>
            <td style="padding: 8px;">Class Charges</td>
            <td style="padding: 8px;">$<?= $pendingTx->amount ?></td>
            <td style="padding: 8px;"></td>
            <td style="padding: 8px;">$<?= $pendingTx->amount ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <?php if (count($lineItems) > 0) { ?>
        <tr>
        <td colspan="3" style="border: none;"></td>
        <td style="padding: 8px;">TOTAL</td>
        <td style="padding: 8px;">$<?= $pendingTx->lineItemsTotal ?></td>
        </tr>
        <?php } else { ?>
        <tr>
        <td colspan="3" style="border: none;"></td>
        <td style="padding: 8px;">TOTAL</td>
        <td style="padding: 8px;">$<?= $pendingTx->amount ?></td>
        </tr>
        <?php } ?>
    </tfoot>
    </table>
    <br />
    <div style="text-align: center;">
        <p>THANK YOU FOR YOUR BUSINESS</p>
    </div>
</div>
