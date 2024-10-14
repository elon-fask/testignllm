<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;
use app\helpers\AppFormHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Candidates */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$template       = '{label}<div class="col-xs-12 col-sm-8 col-md-6 col-lg-5">{input}{error}{hint}</div>';
$labelOptions   = ['class'=>'col-md-4 col-sm-3 control-label'];

$options = ['template'=>$template,'labelOptions'=>$labelOptions];


    $checkTemplate = '<div class="col-md-10 col-md-offset-2 col-lg-8 col-lg-offset-2"><label>{input}</label>{error}{hint}</div>';

    $checkOptions = ['template'=>$checkTemplate];
    $dblTemplate       = '{label}<div class="pull-left">{input}{error}{hint}</div>';
    $dblLabelOptions   = ['class'=>'control-label'];

    $dblOptions = ['template'=>$dblTemplate,'labelOptions'=>$dblLabelOptions, 'options'=>['class'=>'pull-left']];

$isRecert = AppFormHelper::hasRecertifyPdf(base64_decode($appTypeId));
?>

<style>
    .field-candidates-survey label {
        width: 177px;
        margin-left: -12px;
    }
</style>


<div class="candidates-form form-horizontal row">
<?php $form = ActiveForm::begin(['options' => ['id'=>'register-form']]); ?>
    <?php $form->enableClientValidation = false; ?>
    <div class="col-xs-12">

        <div class="clearfix">
            <div class="section-title" style="">Candidate Information</div>
        </div>
        <div class="section-content">
            <input type="hidden" name="candidateId" value="<?php echo $model->id?>"/>
            <input type="hidden" name="step" value="1"/>

            <input type="hidden" name="Candidates[isPurchaseOrder]" value="0"/>

            <input type="hidden" name="Candidates[registration_step]" value="1"/>
            <input type="hidden" name="Candidates[application_type_id]" value="<?php echo base64_decode($appTypeId)?>" data-is-recert="<?= $isRecert ? 1 : 0 ?>" />
            <input type="hidden" name="Candidates[referralCode]" value="<?php echo $referralCode?>"/>
            <input type="hidden" name="Candidates[branding]" value="<?php echo UtilityHelper::getCurrentBranding()?>"/>

            <?= $form->field($model, 'first_name', $options)->textInput(['maxlength' => true])->label('First Name*') ?>

            <?= $form->field($model, 'last_name', $options)->textInput(['maxlength' => true])->label('Last Name*') ?>

            <?= $form->field($model, 'middle_name', $options)->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'suffix', $options)->textInput(['maxlength' => true])->label('Suffix (Jr, Sr, III)') ?>

            <?= $form->field($model, 'email', $options)->textInput(['maxlength' => true, 'type' =>'email'])->label('Email*') ?>
            <?= $form->field($model, 'confirmEmail', $options)->textInput(['maxlength' => true, 'type' =>'email'])->label('Confirm Email*') ?>

            <?php if ($isRecert and base64_decode($appTypeId) != 4) { ?>
                <div class="form-group">
                    <div class="col-xs-10 col-xs-offset-1 cco-cert-number-wrapper">
                   <?= $form->field($model, 'ccoCertNumber', $options)->textInput(['maxlength' => true])->label('CCO CERTIFICATION NUMBER<br/><small>(if previously certified)</small>', ['style'=>'padding-top:0']) ?>
                    </div>
                </div>
            <?php } ?>

                <?php
                    $ssnTemplate       = '{label}<div class="pull-left" style="position: relative;"><span class="ssn-b4"></span>{input}{error}{hint}</div>';
                    $ssnLabelOptions   = ['class'=>'control-label'];

                    $ssnOptions = ['template'=>$ssnTemplate,'labelOptions'=>$dblLabelOptions, 'options'=>['class'=>'pull-left']];
                 ?>
                <style>
                    .ssn{ width: 71px; margin-left: 54px}
                    .ssn-b4:before{content: "xxx-xx-"; display: block; width: 47px; position: absolute;
                                    top:0; left: 0; height: 34px; line-height: 34px;}
                </style>

                <div class="clearfix col-md-offset-2 row-double-input">
                    <?= $form->field($model, 'birthday', $dblOptions)->textInput(['maxlength' => true, 'readonly'=> true, 'class' =>'form-control dob'])->label('Date of Birth*') ?>
                </div>

            <?= $form->field($model, 'phone', $options)->textInput(['maxlength' => true, 'class' =>'form-control phone'])->label('Phone*') ?>

            <div class="clearfix col-md-offset-2 row-double-input">
                    <?= $form->field($model, 'cellNumber', $dblOptions)->textInput(['maxlength' => true , 'class' =>'form-control phone']) ?>
                    <?= $form->field($model, 'faxNumber', $dblOptions)->textInput(['maxlength' => true, 'class' =>'form-control phone']) ?>
                </div>

                <?= $form->field($model, 'address', $options)->textInput(['maxlength' => true])->label('Home Address*') ?>
                <?= $form->field($model, 'city', $options)->textInput(['maxlength' => true])->label('City*') ?>


                <div class="clearfix col-md-offset-2 row-double-input">
                    <?= $form->field($model, 'state', $dblOptions)->dropDownList(
                        UtilityHelper::StateList(),
                        ['prompt'=>'', 'class'=>'form-control state'])->label('State*'); ?>
                    <?= $form->field($model, 'zip', $dblOptions)->textInput(['maxlength' => true, 'class'=>'form-control zip'])->label('Zip*') ?>
                </div>

                <?= $form->field($model, 'cco_id', $options)->textInput(['minlength' => 9, 'maxlength' => 9, 'type' =>'number'])->label('CCO ID*') ?>

                <div class="clearfix col-md-offset-4 row-double-input">
                    "Please sign up at CCO website <a href="https://my.ccocert.org/">https://my.ccocert.org</a> to obtain CCO ID"
                </div>

                <div class="clearfix col-md-offset-2 row-double-input">
                    <?= $form->field($model, 'survey', $dblOptions)->dropDownList(
                        $model->surveyOptions,
                        ['prompt'=>'', 'class'=>'form-control state'])->label('How did you hear about us'); ?>
                </div>

                <p class="text-center" style="display: none">IF YOU NEED TESTING ACCOMMODATIONS IN COMPLIANCE WITH THE AMERICAN WITH DISABILITIES ACT (ADA), PLEASE CALL THE OFFICE AT 1-888-967-7277.</p>
        </div>

        <div class="clearfix">
            <div class="section-title company-info" style="margin-right: 16px">Company Information (Optional)</div>
            <label class="control-label" style="margin-left: 25px; display: flex;">
                    <div style="margin-right: 8px; margin-top: 8px; display: flex; align-items: flex-start;">
                        <span>My company will pay the balance of my class fees.*</span>
                    </div>
                    <select id="field-is-company-sponsored" name="Candidates[is_company_sponsored]" class="form-control" style="max-width: 100px;">
                        <option value=""></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </label>
        </div>
        <div class="section-content company-info">
            <?= $form->field($model, 'company_name', $options)->textInput(['maxlength' => true]) ?>


            <div class="clearfix col-md-offset-2 row-double-input">
                <?= $form->field($model, 'company_fax', $dblOptions)->textInput(['maxlength' => true , 'class' =>'form-control phone']) ?>
                <?= $form->field($model, 'company_phone', $dblOptions)->textInput(['maxlength' => true, 'class' =>'form-control phone',])->label('Company Phone') ?>
            </div>

            <?= $form->field($model, 'company_address', $options)->textInput(['maxlength' => true, 'data-toggle' => 'tooltip',
        'title' => 'NOTICE: Company Address and Candidate Address (your address) cannot be the same! Failure to put accurate addresses in each field will result in additional application fees to correct the error! If you have questions please call the office at 1-888-967-7277.',
    'data-placement' => 'bottom']) ?>

            <?= $form->field($model, 'company_city', $options)->textInput(['maxlength' => true]) ?>

            <div class="clearfix col-md-offset-2 row-double-input">
                <?= $form->field($model, 'company_state', $dblOptions)->dropDownList(
                    UtilityHelper::StateList(),
                    ['prompt'=>'', 'class'=>'form-control state']    // options
                )->label('Company State'); ?>
                <?= $form->field($model, 'company_zip', $dblOptions)->textInput(['maxlength' => true, 'class'=>'form-control zip']) ?>
            </div>

            <?= $form->field($model, 'contact_person', $options)->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'contactEmail', $options)->textInput(['maxlength' => true]) ?>

        </div>
         <br />
        <br />
    </div>

        <div class="col-xs-12 row-buttons">
            <div class="form-group">
                <div class="pull-right">
                    <?= Html::button('Continue <i class="fa fa-long-arrow-right"></i>', ['class' => 'btn btn-cta btn-register']) ?>
                </div>


                <div class=" pull-left register-back" style="padding-left: 15px">
                    <?= Html::a('<i class="fa fa-long-arrow-left"></i><span class="back-step">Back to previous step</span>', ['#'], ['class' =>'btn-register-back', 'data-candidate-id' => $model->isNewRecord ? 0 : $model->id, 'data-step' => 0]);?>
                </div>

            </div>
        </div>

            <?php ActiveForm::end(); ?>

</div>




<link href="/css/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
<script>

// if (typeof surveyChange != 'undefined') {
//     $('select[name="Candidates[survey]"]').on('change', surveyChange);
//     surveyChange();
// }

// document.addEventListener('DOMContentLoaded', function() {
//     var companyAddressField = $('input[name="Candidates[company_address]"]');
//     var homeAddressField = $('input[name="Candidates[address]"]');
//     var homeAddressFormGroup = $('.field-candidates-address');
//     var companyAddressFormGroup = $('.field-candidates-company_address');

//     var validateAddress = function() {
//         var homeAddress = homeAddressField.val().toLowerCase().split(' ').join('');
//         var companyAddress = companyAddressField.val().toLowerCase().split(' ').join('');
//         var homeAddressAndCompanyAddressDifferent = homeAddress !== companyAddress;

//         if (homeAddressAndCompanyAddressDifferent || companyAddress === '' || homeAddress === '') {
//             companyAddressFormGroup.removeClass('has-error');
//             companyAddressFormGroup.addClass('has-success');
//             companyAddressFormGroup.find('.help-block').html('');
//             homeAddressFormGroup.removeClass('has-error');
//             homeAddressFormGroup.addClass('has-success');
//             homeAddressFormGroup.find('.help-block').html('');
//             return true;
//         } else {
//             companyAddressFormGroup.removeClass('has-success');
//             companyAddressFormGroup.addClass('has-error');
//             companyAddressFormGroup.find('.help-block').html('Home Address should not be the same as the Company Address.');
//             homeAddressFormGroup.removeClass('has-success');
//             homeAddressFormGroup.addClass('has-error');
//             homeAddressFormGroup.find('.help-block').html('Home Address should not be the same as the Company Address.');
//             return false;
//         }
//     }

//     companyAddressField.change(validateAddress);

//     var companySponsoredField = $('#field-is-company-sponsored');

//     companySponsoredField.on('change', function() {
//         if (this.value === '') {
//             companySponsoredField.parent().removeClass('has-success');
//             companySponsoredField.parent().addClass('has-error');

//             return;
//         } else {
//             companySponsoredField.parent().addClass('has-success');
//             companySponsoredField.parent().removeClass('has-error');
//         }

//         var companyField = $('input[name="Candidates[company_name]"]');
//         var companyAddressField = $('input[name="Candidates[company_address]"]');
//         var companyCityField = $('input[name="Candidates[company_city]"]');
//         var companyStateField = $('select[name="Candidates[company_state]"]');
//         var companyZipField = $('input[name="Candidates[company_zip]"]');
//         var companyPhoneField = $('input[name="Candidates[company_phone]"]');
//         var companyEmailField = $('input[name="Candidates[contactEmail]"]');

//         var companyFields = [companyField, companyAddressField, companyCityField, companyStateField, companyZipField, companyPhoneField, companyEmailField];

//         if (this.value === '1') {
//             companyFields.forEach(function(field) {
//                 field.attr('required', true);
//                 field.parent().siblings('.control-label').text(function(i, textStr) {
//                     if (textStr.substr(-1) === '*') {
//                         return textStr;
//                     }
//                     return textStr + '*';
//                 })
//             })
//         } else {
//             companyFields.forEach(function(field) {
//                 field.attr('required', false);
//                 field.parent().siblings('.control-label').text(function(i, textStr) {
//                     return textStr.replace('*', '');
//                 })
//             })
//         }
//     });
// });
</script>
