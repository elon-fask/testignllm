<?php

use yii\helpers\Html;
use app\models\CandidateTransactions;
use yii\widgets\ActiveForm;
use app\models\Candidates;


/* @var $this yii\web\View */
/* @var $model app\models\CandidateTransactions */
$model = new CandidateTransactions();

$allowableAmount = 0;
//we need to calculate all charges

$paymentsList = $candidate->getPaymentLists();
foreach ($paymentsList as $payment) {
    if($payment->paymentType == CandidateTransactions::TYPE_CASH
    || $payment->paymentType == CandidateTransactions::TYPE_CHEQUE
    || $payment->paymentType == CandidateTransactions::TYPE_INTUIT
    || $payment->paymentType == CandidateTransactions::TYPE_RECEIVABLES_OTHER
    || $payment->paymentType == CandidateTransactions::TYPE_ELECTRONIC_PAYMENT) {
        $allowableAmount += $payment->amount;
    } else if($payment->paymentType == CandidateTransactions::TYPE_REFUND ) {
        $allowableAmount -= $payment->amount;
    }
}

$transactions = $candidate->getTransactions()->where([
    'paymentType' => CandidateTransactions::TYPE_STUDENT_CHARGE
])->all();
?>

<div class="charge-information-create">
    <div class="alert alert-success text-center" style="display: none;">Student Payment Refund Saved Successfully</div>
    <div class="phone-information-form">
        <h4>Note: Allowed to refund $<?= number_format($allowableAmount, 2, '.', ',') ?></h4>
        <br />
    <?php $form = ActiveForm::begin(['id'=>'refund-form']); ?>
    <input type="hidden" name="CandidateTransactions[candidateId]" value="<?= $candidate->id ?>"/>
    <input type="hidden" name="CandidateTransactions[paymentType]" value="<?= CandidateTransactions::TYPE_REFUND ?>"/>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'required' => true]) ?>
        <input type="checkbox" id="cc-refund" value="is-cc-refund" checked />
        &nbsp;
        <label for="cc-refund">Refund Credit Card Payment</label>
    <?= $form->field($model, 'remarks')->textarea(['maxlength' => true])->label('Remarks') ?>

    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" data-allowed-refund='<?= $allowableAmount ?>' class="btn btn-success btn-add-refund" value="Add Refund"/>
    </div>
    <br />
    <br />
    <?php ActiveForm::end(); ?>
</div>

<script>
var amountField = $('#candidatetransactions-amount');
amountField.maskMoney({thousands:'', decimal:'.'});

$('#candidatetransactions-transaction_ref_id').change(function() {
    var selectedAmount = $(this).children('option:selected').data('amount');
    console.log(selectedAmount);
    amountField.maskMoney('mask', selectedAmount);
});
</script>
</div>
