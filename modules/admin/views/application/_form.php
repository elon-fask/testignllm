<?php 
use yii\helpers\Html;
use app\helpers\UtilityHelper;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use app\models\ApplicationTypeFormSetup;
use app\models\AppConfig;
use app\models\ApplicationType;

?>

<style>
    @media only screen and (min-width : 992px) {
        .col-label-wrapper{text-align: right}
    }
    @media only screen and (max-width : 992px) {
        .col-label-wrapper + .col-xs-12 > .form-group{
            margin-left: 0;
            margin-right: 0;
        }
    }
    .form-horizontal .form-group{
        margin-bottom: 5px;
    }
    .affix{
        top:100px;
        z-index: 10000;
    }
    .affix-top{
        position: absolute;
        z-index: 10000;

    }
    .fa.fa-info-circle{
        cursor: help;
    }
</style>


<script>

    $(function () {

        $('.fa-info-circle').tooltip({'container':'body', placement:'right'});


        $('.basic-setup').on('click', function(e){
            e.preventDefault();
            $("html, body").animate({ scrollTop: "0px" });
        });
        $('.form-setup').on('click', function(e){
            e.preventDefault();
            var p = $('#formSetup').offset();
                p = parseInt(p.top)-100;
            $("html, body").animate({ scrollTop: p + "px" });
        });
    });
</script>

 <?= $this->render('form-styles') ?>
<?php $form = ActiveForm::begin(['id' => 'application-form', 'class' => 'form-horizontal']); ?>
    <div class="application-type-form form-horizontal">

        <div data-spy="affix" data-offset-top="75" data-offset-bottom="500" id="subnav" style="width: 100px; margin-left: -15px; ">
            <ul style="margin-left: 0; padding-left: 0; list-style-type: none;width: 100px; margin-left: 15px; padding: 10px; background: #ddd; border:1px solid #ccc; border-radius: 4px;">
                <li><a href="#basicSetup" class="basic-setup" style="margin-bottom: 5px;">Basic Setup</a></li>
                <li><a href="#formSetup" class="form-setup">Form Setup</a></li>
            </ul>
        </div>

<?php
    $tpl ='{input}{error}' ;
?>

    <div class="col-xs-offset-2 col-xs-10 col-lg-12 col-lg-offset-0">

    <input type="hidden" class="practicalCharge1Crane" value="<?php echo UtilityHelper::getAppConfig(AppConfig::IAI_1_PRACTICAL_CRANE, 0)?>"/>
    <input type="hidden" class="practicalCharge2Crane" value="<?php echo UtilityHelper::getAppConfig(AppConfig::IAI_2_PRACTICAL_CRANE, 0)?>"/>

    <div class="form-group">
        <div class="col-md-7 col-md-offset-3">
            <p class="text-danger">* More settings are available down the page. Please scroll down to see more settings.</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-name">Name <i class="fa fa-info-circle" title=" The name of application type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'name', ['template'=> $tpl])->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-keyword">Keyword <i class="fa fa-info-circle" title="The keyword/password to be used when a candidate registers for a test session"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'keyword', ['template'=> $tpl])->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-keyword">Written/Recert Only<i class="fa fa-info-circle" title="Candidate will not be added to the matching practical test session upon registration."></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo $form->field($model, 'isRecertify', ['template'=> $tpl])->checkbox(['label' => '']);?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-keyword">Cross Out Credit Card Fields<i class="fa fa-info-circle" title="The credit card information fields will be crossed out in the application forms."></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?= $form->field($model, 'cross_out_cc_fields', ['template'=> $tpl])->checkbox(['label' => '']) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-description">Description <i class="fa fa-info-circle" title="A short description of the application types and its details"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'description', ['template'=> $tpl])->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-app_type">Public/Private Setting <i class="fa fa-info-circle" title="Public/Private Setting of the application type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
          <?= $form->field($model, 'app_type', ['template'=> $tpl])->dropDownList(
            ApplicationType::getAppTypes(),           // Flat array ('id'=>'label')
            ['prompt'=>'Please Choose', 'required'=>'required']    // options
            ); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-price">Price <i class="fa fa-info-circle" title="The price in dollars to be paid by the candidate"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'price', ['template'=> $tpl])->textInput(['maxlength' => true, 'type' => 'number', 'step' => '0.01', 'min' => '0.01']) ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-writteniaiFee">Written NCCCO Testing Services Fee <i class="fa fa-info-circle" title="The written fee to paid to NCCCO Testing Services for this program type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <label class="control-label written-iai-fee"></label>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-practicaliaiFee">Practical NCCCO Testing Services Fee <i class="fa fa-info-circle" title="The practical fee to paid to NCCCO Testing Services for this program type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <label class="control-label practical-iai-fee"></label>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-iaiFee">Total NCCCO Testing Services Fee <i class="fa fa-info-circle" title="The total fee to paid to NCCCO Testing Services for this program type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'iaiFee', ['template'=> $tpl])->textInput(['maxlength' => true,  'readonly' => true]) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'lateFee',$options)->textInput() ?>
    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-iaiFee">Late Fee <i class="fa fa-info-circle" title="The amount in Late Fee to paid by the candidate automatically, on top of the Price of enrollment itself, for candidates signing up using this application type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'lateFee', ['template'=> $tpl])->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php //$form->field($model, 'practicalCharge',$options)->textInput(['class'=>'form-control practicalCharge1Crane']) ?>
    <?php //$form->field($model, 'practicalCharge2Crane',$options)->textInput(['class'=>'form-control practicalCharge2Crane']) ?>
    <?php //$form->field($model, 'iaiLessThan12',$options)->textInput(['class'=>'form-control amount-val']) ?>
    <?php //$form->field($model, 'iaiLessThan15',$options)->textInput(['class'=>'form-control amount-val']) ?>


    <div class="form-group">
        <div class="col-xs-4 col-md-3 col-label-wrapper">
            <label class="control-label" for="applicationtype-iaiFee">Info Text <i class="fa fa-info-circle" title="Any additional notes regarding this application type, this will be shown at the registration page if the candidate enters the keyword for this application type"></i></label>
        </div>
        <div class="col-xs-12 col-md-9 col-lg-8">
            <?php echo  $form->field($model, 'infoText', ['template'=> $tpl])->widget(TinyMce::className(), [
                'options' => ['rows' => 10],
                //'language' => 'en',
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste textcolor"
                    ],
                    'toolbar' => "forecolor backcolor | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                ]
            ]);?>
        </div>
    </div>

    <?php /* echo $form->field($model, 'infoText',$optionsRich)->widget(TinyMce::className(), [
    'options' => ['rows' => 10],
    //'language' => 'en',
    'clientOptions' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste textcolor"
        ],
        'toolbar' => "forecolor backcolor | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    ]
	]);*/ ?>
    </div>
    
</div>

   
   <?php 
   echo $this->render('_dynamic_forms', ['model'=>$model,'styling' => 'col-xs-10 col-xs-offset-2 col-md-11 col-md-offset-1']);
   ?> 
   
<div class="form-group">
        <div class=""><div class="pull-right">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id' => 'form-submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div></div>
    </div>

    <?php ActiveForm::end(); ?>


<?php 
   echo $this->render('_form_scripts', []);
   ?> 
