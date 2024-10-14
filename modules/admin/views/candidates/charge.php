<?php

use yii\helpers\Html;
use app\models\CandidateTransactions;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\CandidateTransactions */
$model = new CandidateTransactions();

$retestCranes = $candidate->applicationFormCranes;
$recertEnabled = !empty($retestCranes);

$retestCranesRadioList = [];
$swEnabled = in_array('sw', $retestCranes);
$fxEnabled = in_array('fx', $retestCranes);

if ($swEnabled) {
    $retestCranesRadioList['sw'] = 'Swing Cab (SW)';
}

if ($fxEnabled) {
    $retestCranesRadioList['fx'] = 'Fixed Cab (FX)';
}

if ($swEnabled && $fxEnabled) {
    $retestCranesRadioList['both'] = 'Both';
}
?>
<div class="charge-information-create">
    <div class="alert alert-success text-center" style="display: none;">Student Payment Charged Successfully</div>
    <div class="phone-information-form">

    <?php $form = ActiveForm::begin(['id'=>'charge-form']); ?>
    <input type="hidden" name="CandidateTransactions[candidateId]" value="<?= $candidate->id ?>"/>
    <input type="hidden" name="CandidateTransactions[paymentType]" value="<?= CandidateTransactions::TYPE_STUDENT_CHARGE ?>"/>

    <div class="form-group">
        <label class="control-label">Type:</label>
        <select class="form-control" required id='candidateTransactionsChargeType' name="CandidateTransactions[chargeType]">
            <option value="<?= CandidateTransactions::SUBTYPE_OTHERS ?>">Others</option>
            <option value="<?= CandidateTransactions::SUBTYPE_ADD_PRACTICE_TIME ?>">Additional Practice Time</option>
            <option value="<?= CandidateTransactions::SUBTYPE_WALK_IN_FEE ?>">Walk-in Fee</option>
            <option value="<?= CandidateTransactions::SUBTYPE_LATE_FEE ?>">Late Fee</option>
            <option value="<?= CandidateTransactions::SUBTYPE_CHANGE_FEE ?>">Change Fee/Incomplete Application Fee</option>
            <?php if($recertEnabled) { ?>
            <option value="<?= CandidateTransactions::SUBTYPE_PRACTICAL_RETEST ?>">Practical Retest Fee</option>
            <?php } ?>
            <option value="<?= CandidateTransactions::SUBTYPE_NCCCO_OTHERS ?>">NCCCO - Others</option>
        </select>
        <div class="help-block"></div>
    </div>

    <?php if($recertEnabled) { ?>
    <?= $form->field($model, 'retest_crane_selection')->radioList($retestCranesRadioList) ?>
    <?php } ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'required' => true]) ?>
    <?= $form->field($model, 'remarks')->textarea(['maxlength' => true])->label('Remarks') ?>

    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" class="btn btn-success btn-add-charge" value="Add Charge"/>
    </div>
    <br />
    <br />
    <?php ActiveForm::end(); ?>

</div>
<script>
var defaultCharges = <?= json_encode(CandidateTransactions::DEFAULT_CHARGES) ?>;
var amountField = $('input[name="CandidateTransactions[amount]"]');

amountField.maskMoney({thousands: '', decimal: '.'});

var Charges = {
    showUI : function(){
        var modal = $('#reminder-modal');
        modal.find('.has-error').removeClass('has-error');
        modal.find('.help-block').html('');
        var chargeTypeId = $('#candidateTransactionsChargeType').val();
        var defaultCharge = defaultCharges[chargeTypeId] || 0;

        amountField.maskMoney('mask', defaultCharge);
        $('#candidatetransactions-retest_crane_selection input[type=radio]').prop('checked', false);

        if (chargeTypeId === '<?= CandidateTransactions::SUBTYPE_PRACTICAL_RETEST ?>') {
            $('.field-candidatetransactions-retest_crane_selection').show();
        } else {
            $('.field-candidatetransactions-retest_crane_selection').hide();
        }
    }
};

Charges.showUI();
$('#candidateTransactionsChargeType').on('change', Charges.showUI);

$('#candidatetransactions-retest_crane_selection').change(function(event) {
    var crane = event.target.value;

    if (crane === 'sw' || crane == 'fx') {
        amountField.maskMoney('mask', 250);
    }

    if (crane === 'both') {
        amountField.maskMoney('mask', 500);
    }
});

</script>
</div>
