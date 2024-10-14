<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;
use app\helpers\AppFormHelper;
?>

    <?php echo yii\base\View::render('wizard', ['step'=>5]);?>

    <?php echo yii\base\View::render('_titles', ['step'=>5]);?>


<div class="candidates-form form-horizontal row">
    <div class="col-xs-12">


    <?php
    $template       = '{label}<div class="col-xs-12 col-sm-8 col-md-6 col-lg-5">{input}{error}{hint}</div>';
    $labelOptions   = ['class'=>'col-md-4 col-sm-3 control-label'];

    $options = ['template'=>$template,'labelOptions'=>$labelOptions];


    $checkTemplate = '<div class="col-md-10 col-md-offset-2 col-lg-8 col-lg-offset-2"><label>{input}</label>{error}{hint}</div>';
//    $checkLabelOptions = ['class'=>'control-label pull-left bs-block label-sm'];

    $checkOptions = ['template'=>$checkTemplate];

    /* when 2 form fields on same row */
    $dblTemplate       = '{label}<div class="pull-left">{input}{error}{hint}</div>';
    $dblLabelOptions   = ['class'=>'control-label'];

    $dblOptions = ['template'=>$dblTemplate,'labelOptions'=>$dblLabelOptions, 'options'=>['class'=>'pull-left']];

?>


    <?php $form = ActiveForm::begin(['options' => ['id'=>'register-form']]); ?>
     <input type="hidden" name="candidateId" value="<?php echo $model->id?>"/>
     <input type="hidden" name="step" value="2"/>
     <input type="hidden" name="Candidates[registration_step]" value="2"/>

    <div class="clearfix">
        <div class="section-title" style="">Candidate Information</div>
    </div>
    <div class="section-content">
		<?php if(AppFormHelper::hasRecertifyPdf($model->application_type_id)){?>
        <div class="form-group">
            <div class="col-xs-10 col-xs-offset-1 cco-cert-number-wrapper">
           <?= $form->field($model, 'ccoCertNumber', $options)->textInput(['maxlength' => true])->label('CCO CERTIFICATION NUMBER<br/><small>(if previously certified)</small>', ['style'=>'padding-top:0']) ?>
            </div>
        </div>
		<?php }?>

        <div class="clearfix col-md-offset-2 row-double-input">
            <?= $form->field($model, 'ssn', $dblOptions)->textInput(['maxlength' => 4, 'class' =>'form-control ssn']) ?>
            <?= $form->field($model, 'birthday', $dblOptions)->textInput(['maxlength' => true, 'readonly'=> true, 'class' =>'form-control dob']) ?>
        </div>

        <div class="clearfix col-md-offset-2 row-double-input">
            <?= $form->field($model, 'cellNumber', $dblOptions)->textInput(['maxlength' => true , 'class' =>'form-control phone']) ?>
            <?= $form->field($model, 'faxNumber', $dblOptions)->textInput(['maxlength' => true, 'class' =>'form-control phone']) ?>
        </div>

        <?= $form->field($model, 'address', $options)->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'city', $options)->textInput(['maxlength' => true]) ?>


        <div class="clearfix col-md-offset-2 row-double-input">
            <?= $form->field($model, 'state', $dblOptions)->dropDownList(
                UtilityHelper::StateList(),
                ['prompt'=>'', 'class'=>'form-control state']); ?>
            <?= $form->field($model, 'zip', $dblOptions)->textInput(['maxlength' => true, 'class'=>'form-control zip']) ?>
        </div>

        <p class="text-center">IF YOU NEED TESTING ACCOMMODATIONS IN COMPLIANCE WITH THE AMERICAN WITH DISABILITIES ACT (ADA), PLEASE CALL THE OFFICE AT 1-888-967-7277.</p>
    </div>


        <div class="clearfix">
            <div class="section-title" style="">Company Information</div>
        </div>
        <div class="section-content">

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

        </div>
    </div>

    <div class="col-xs-12 row-buttons">
        <div class="form-group">
            <div class="pull-right" style="padding-right: 15px;">
                <?= Html::button($model->isNewRecord ? 'Create <i class="fa fa-long-arrow-right"></i>' : 'Continue <i class="fa fa-long-arrow-right"></i>', ['class' => $model->isNewRecord ? 'btn btn-cta btn-more-info-submit' : 'btn btn-cta btn-more-info-submit']) ?>
            </div>
            <?php if($model->isNewRecord == false){?>
                <div class=" pull-left register-back" style="padding-left: 15px">
                    <?= Html::a('<i class="fa fa-long-arrow-left"></i><span class="back-step">Back to previous step</span>', ['#'], ['class' =>'btn-register-back', 'data-candidate-id' => $model->id, 'data-step' => 1.1]);?>
                </div>
            <?php }?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>


<link href="/css/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
<script>
$('.phone').mask("(999) 999-9999");
//$('.ssn').mask("XXXX-XX-9999");
$('#candidates-birthday').datepicker({
	startView: 2,
	autoclose: true,
	defaultViewDate: { year: 1990, month: 04, day: 25 }
});
$('[data-toggle="tooltip"]').tooltip();
</script>
