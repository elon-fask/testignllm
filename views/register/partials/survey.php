<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;
use app\helpers\AppFormHelper;
?>

    <?php echo yii\base\View::render('wizard', ['step'=>4]);?>

    <?php echo yii\base\View::render('_titles', ['step'=>4]);?>


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
     <input type="hidden" name="step" value="1"/>
     <input type="hidden" name="survey" value="1"/>
     <input type="hidden" name="Candidates[registration_step]" value="1"/>
              
            

    <div class="clearfix">
        <div class="section-title" style="">Survey</div>
    </div>
    <div class="section-content">		

        <div class="form-group">
            <?= $form->field($model, 'survey', $options)->dropDownList(
                UtilityHelper::surveyOptions(),
                ['class'=>'form-control', 'required'=>true, 'style'=>'width:200px']); ?>
        </div>

        <div class="form-group survey-other">
                <?= $form->field($model, 'surveyOther', $options)->textarea(['maxlength' => true, 'rows'=>'4'])->label('Details:', ['style'=>'padding-top:0']) ?>
        </div>
        
        <div class="form-group">
            <?= $form->field($model, 'ad_online_info', $options)->dropDownList(
                ['Google' => 'Google', 'Bing'=>'Bing', 'Yahoo'=>'Yahoo!'],
                ['class'=>'form-control', 'required'=>true, 'style'=>'width:200px']); ?>
        </div>
        
        <div class="form-group">
            <?= $form->field($model, 'friend_email', $options)->textInput(
                ['class'=>'form-control', 'required'=>true, 'style'=>'width:200px']); ?>
        </div>
    </div>


    </div>

    <div class="col-xs-12 row-buttons">
        <div class="form-group">
            <div class="pull-right" style="padding-right: 15px;">
                <?= Html::button($model->isNewRecord ? 'Create <i class="fa fa-long-arrow-right"></i>' : 'Continue <i class="fa fa-long-arrow-right"></i>', ['class' => $model->isNewRecord ? 'btn btn-cta btn-more-info-submit' : 'btn btn-cta btn-more-info-submit']) ?>
            </div>
            <?php if($model->isNewRecord == false){?>
                <div class=" pull-left register-back" style="padding-left: 15px">
                    <?= Html::a('<i class="fa fa-long-arrow-left"></i><span class="back-step">Back to previous step</span>', ['#'], ['class' =>'btn-register-back', 'data-candidate-id' => $model->id, 'data-step' => 1]);?>
                </div>
            <?php }?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<style>
.row-double-input label{
width: 230px;
}            
}
</style>
<script>
var surveyChange = function(){
	if($('select[name="Candidates[survey]"]').val() == 'Other'){
		$('.survey-other').slideDown();
	}else{
		$('.survey-other').slideUp();
		$('textarea[name="Candidates[surveyOther]"]').val('');
	}

	if($('select[name="Candidates[survey]"]').val() == 'Ad (Online)'){
		$('.field-candidates-ad_online_info').show();
	}else{
		$('.field-candidates-ad_online_info').hide();
		$('#candidates-ad_online_info').val('');
	}
	if($('select[name="Candidates[survey]"]').val() == 'Heard from a friend'){
		$('.field-candidates-friend_email').show();
	}else{
		$('.field-candidates-friend_email').hide();
		$('#candidates-friend_email').val('');
	}
}

$(function(){
    $('select[name="Candidates[survey]"]').on('change', surveyChange);
    surveyChange();
});
</script>