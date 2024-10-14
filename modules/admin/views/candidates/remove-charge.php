<?php

use yii\helpers\Html;
use app\models\CandidateTransactions;
use yii\widgets\ActiveForm;
use app\models\Candidates;


/* @var $this yii\web\View */
/* @var $model app\models\CandidateTransactions */
$model = new CandidateTransactions();

//we need to calculate all charges
$candidate = Candidates::findOne($candidateId);

$allowableAmount = $candidate->amountOwed;

?>
<div class="charge-information-create">
    <div class="alert alert-success text-center" style="display: none;">Discount Added Successfully</div>
    <div class="phone-information-form">
    <h4>Maximum Discount: $<?= number_format($allowableAmount, 2, '.', ',') ?></h4>
    <br />
    <?php $form = ActiveForm::begin(['id'=>'remove-charge-form']); ?>
    <input type="hidden" name="CandidateTransactions[candidateId]" value="<?= $candidateId ?>"/>
    <input type="hidden" name="CandidateTransactions[paymentType]" value="<?= CandidateTransactions::TYPE_DISCOUNT ?>"/>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true, 'required' => true]) ?>
    <?= $form->field($model, 'remarks')->textarea(['maxlength' => true])->label('Remarks') ?>
    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" data-allowed-removable-charge='<?= $allowableAmount ?>' class="btn btn-success btn-remove-charge" value="Add Discount"/>
    </div>
    <br />
    <br />
    <?php ActiveForm::end(); ?>
</div>
<script>
$('input[name="CandidateTransactions[amount]"]').maskMoney({thousands:'', decimal:'.'});
</script>
</div>
