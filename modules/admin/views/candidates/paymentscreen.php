<?php

use yii\helpers\Html;
use app\models\CandidateTransactions;
use yii\widgets\ActiveForm;
use app\models\TestSession;

$written = $candidate->getWrittenTestSession();
$practical = $candidate->getPracticalSession();
$school = false;
if($written !== false){
	$ses = TestSession::findOne($written->test_session_id);
	if($ses){
		$school = $ses->school;
	}
}
if($school === false && $practical !== false){
	$ses = TestSession::findOne($practical->test_session_id);
	if($ses){
		$school = $ses->school;
	}
}
/* @var $this yii\web\View */
?>
<form id="add-payment-form" method="POST" action="/admin/candidates/payment?id=<?php echo md5($candidate->id)?>" data-candidate-id="<?php echo md5($candidate->id)?>">
<div class="form-group">
    <label class="control-label">Amount:</label>
    <input class="form-control" type="number" name="paymentAmount" value=""/>
    <div class="help-block"></div>
</div>
<div class="form-group">
    <label class="control-label">Type:</label>
    <select class="form-control select-payment" name="type" data-electronic-payment-id="<?php echo CandidateTransactions::TYPE_ELECTRONIC_PAYMENT?>">
            <option value="">Please Select</option>
            <option value="<?= CandidateTransactions::TYPE_CASH ?>">Cash</option>
            <option value="<?= CandidateTransactions::TYPE_CHEQUE ?>">Check</option>
            <option value="<?= CandidateTransactions::TYPE_PROMO ?>">Promo</option>
            <option value="<?= CandidateTransactions::TYPE_INTUIT ?>">Intuit - Swiper</option>
            <?php if ($school !== false) { ?>
            <option value="<?= CandidateTransactions::TYPE_ELECTRONIC_PAYMENT ?>">Electronic Payment - Authorize.Net</option>
            <?php } ?>
            <option value="<?= CandidateTransactions::TYPE_RECEIVABLES_OTHER ?>">Others</option>
        </select>
    <div class="help-block"></div>
</div>
<div class="form-group" id="field-check-number" style="display: none">
    <label class="control-label">Check Number:</label>
    <input class="form-control" type="text" name="check_number" value="" />
    <div class="help-block"></div>
</div>
<div class="form-group" id="field-remarks">
    <label class="control-label">Remarks:</label>
    <input class="form-control" type="text" name="remarks" value="" />
    <div class="help-block"></div>
</div>
<div class="form-group pull-right">
    <input type="button" style="display: none; margin-right: 20px;" class="btn btn-success btn-charge-cc" value="Charge w/ Authorize.net" />
    <input type="button" class="btn btn-info btn-add-payment" value="Add Payment" />
</div>
<br /><br />
</form>
<div id="e-payment"></div>

</div>
