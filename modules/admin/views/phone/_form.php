<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $model app\models\PhoneInformation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phone-information-form">

    <?php $form = ActiveForm::begin(['id'=>'phone-form']); ?>
<input type="hidden" name="PhoneInformation[userId]" value="<?php echo \Yii::$app->user->id?>"/>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'required' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email', 'required' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'required' => true]) ?>

    <?= $form->field($model, 'referral')->dropDownList(UtilityHelper::surveyOptions(),
                ['class'=>'form-control', 'required'=>true, 'style'=>'width:200px', 'prompt'=>'Please Select']) ?>

    <?= $form->field($model, 'referralOther')->textarea(['maxlength' => true, 'disabled'=>'disabled'])->label('More Information') ?>
    <?= $form->field($model, 'ad_online_info')->dropDownList(['Google' => 'Google', 'Bing'=>'Bing', 'Yahoo'=>'Yahoo!'],
                ['class'=>'form-control optional', 'required'=>false, 'style'=>'width:200px', 'prompt'=>'Please Select']) ->label('Type') ?>
    <?= $form->field($model, 'friend_email')->textInput(['maxlength' => true, 'type' => 'email', 'class' => 'form-control optional'])->label('Friend\'s Email') ?>


    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" class="btn btn-success btn-add-phone" value="Create"/>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>

var surveyChange = function(evt){
    var $phoneControl = $(evt.target);
	if($phoneControl.val() == 'Other'){
		$('textarea[name="PhoneInformation[referralOther]"]').val('').attr('disabled', false);
		$('.field-phoneinformation-referralother').show();
	}else{
		$('textarea[name="PhoneInformation[referralOther]"]').attr('disabled', true).val('');
		$('.field-phoneinformation-referralother').hide();
	}
	if($phoneControl.val() == 'Ad (Online)'){
		$('.field-phoneinformation-ad_online_info').show();
	}else{
		$('.field-phoneinformation-ad_online_info').hide();
		$('#phoneinformation-ad_online_info').val('');
	}
	if($phoneControl.val() == 'Heard from a friend'){
		$('.field-phoneinformation-friend_email').show();
	}else{
		$('.field-phoneinformation-friend_email').hide();
		$('#phoneinformation-friend_email').val('');
	}
};

$(function(){
    $('select[name="PhoneInformation[referral]"]').on('change', surveyChange);
//    surveyChange();
    $('input[name="PhoneInformation[phone]"]').mask("(999) 999-9999");
});
$('.field-phoneinformation-referralother').hide();
$('.field-phoneinformation-friend_email').hide();
$('.field-phoneinformation-ad_online_info').hide();

</script>
