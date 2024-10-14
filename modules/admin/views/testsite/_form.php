<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;
use app\models\TestSite;
use app\models\ApplicationType;
use app\models\ChecklistTemplate;
use app\models\User;
use app\assets\ReactTestSiteUpdateAsset;

ReactTestSiteUpdateAsset::register($this);
$apiUrl = getenv('API_HOST_INFO') . getenv('API_URL');
$googleMapsApiKey = getenv('GOOGLE_MAPS_API_KEY');

/* @var $this yii\web\View */
/* @var $model app\models\TestSite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-site-form form-horizontal">

    <?php
    $template       = '{label}<div class="col-xs-12 col-md-5">{input}{error}{hint}</div>';
    $labelOptions   = ['class'=>'col-xs-4 control-label'];

    $options = ['template'=>$template,'labelOptions'=>$labelOptions];
    ?>

    <?php $form = ActiveForm::begin(); ?>
    <input type="hidden" name="TestSite[type]" value="<?php echo $type?>"/>

    <?= $form->field($model, 'enrollmentType', $options)->dropDownList(
        UtilityHelper::getEnrollmentTypes(),           // Flat array ('id'=>'label')
        ['prompt'=>'', 'required'=>'required']    // options
    );?>

    <?= $form->field($model, 'scheduleType', $options)->dropDownList(
        UtilityHelper::getScheduleTypes(),           // Flat array ('id'=>'label')
        ['prompt'=>'', 'required'=>'required']    // options
    ); ?>
    <?= $form->field($model, 'name', $options)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state', array_merge($options, array('inputOptions'=>['class' => 'form-control form-state'])))->dropDownList(
        UtilityHelper::StateList(),
        ['prompt'=>'', 'required'=>'required']    // options
    ); ?>

    <?= $form->field($model, 'zip',array_merge($options, array('inputOptions'=>['class' => 'form-control form-zip'])) )->textInput(['maxlength' => 10]) ?>

    <div id="react-entry"></div>

    <?php if($type == TestSite::TYPE_PRACTICAL){?>
        <?= $form->field($model, 'siteNumber', $options)->textInput(['maxlength' => true, 'disabled' => $type == TestSite::TYPE_WRITTEN ? true : false]) ?>
    <?php }else{?>
        <input type='hidden' name='TestSite[siteNumber]' value='<?php echo $model->siteNumber == null || $model->siteNumber == '' ? strtotime('now') : $model->siteNumber?>'/>
    <?php }?>
    <?= $form->field($model, 'phone', $options)->textInput(['maxlength' => true, 'class'=>'form-control phone']) ?>

    <?= $form->field($model, 'fax', $options)->textInput(['maxlength' => true, 'class'=>'form-control phone']) ?>

    <?= $form->field($model, 'email', $options)->textInput(['maxlength' => true, 'type'=>'email']) ?>

    <?= $form->field($model, 'remark', $options)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nickname', $options)->textInput(['class'=>'form-control']) ?>

    <?php if($type == TestSite::TYPE_PRACTICAL){?>

        <?= $form->field($model, 'siteManagerId', $options)->dropDownList(
            UtilityHelper::getStaff(User::TYPE_SITE_MANAGER),           // Flat array ('id'=>'label')
            ['prompt'=>'']    // options
        )->label('Site Manager'); ?>

    <?php }else{?>
        <?= $form->field($model, 'writtenChecklistId', $options)->dropDownList(ChecklistTemplate::getAllChecklists(ChecklistTemplate::TYPE_WRITTEN), ['prompt'=>'', ] )->label('Pre Written ChecklistTemplate') ?>
        <?= $form->field($model, 'writtenPostChecklistId', $options)->dropDownList(ChecklistTemplate::getAllChecklists(ChecklistTemplate::TYPE_WRITTEN_POST), ['prompt'=>'', ] )->label('Post Written ChecklistTemplate') ?>

    <?php }?>

    <?php
    $uniqueCode  = $model->isNewRecord ? UtilityHelper::generateUniqueCodeForTestSite() : $model->uniqueCode;
    ?>
    <input type="hidden" name="TestSite[uniqueCode]" value="<?php echo $uniqueCode?>"/>
    <?php if($type == TestSite::TYPE_WRITTEN){?>
        <div class="form-group field-testsite-remark">
            <label for="testsite-remark" class="col-xs-4 control-label" style="padding-top: 0">Access URLs</label>
            <div class="col-xs-12 col-md-6">
                <input  type="hidden" readonly value="/register/?id=<?php echo $uniqueCode?>" name="" class="form-control" id="testsite-unique-code">
                <div class="clearfix" style="margin-bottom: 20px">
                    <div class="clearfix">
                        <div class="pull-left"><b>ACS:</b></div>
                        <div class="pull-right"><a href="javascript: void(0)" data-clipboard-text="<?php echo  \Yii::$app->params['acs.url'];?>/register/?id=<?php echo $uniqueCode?>" class="btn-copy-link" id='btn-copy-acs'><i class="fa fa-copy"> Copy ACS URL to clipboard</i></a></div>
                    </div>
                    <div style="width:100%">
                        <span style="padding-left: 25px"><?php echo  \Yii::$app->params['acs.url'];?>/register/?id=<?php echo $uniqueCode?></span>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="clearfix">
                        <div class="pull-left"><b>CCS:</b></div>
                        <div class="pull-right"><a href="javascript: void(0)"  data-clipboard-text="<?php echo  \Yii::$app->params['ccs.url'];?>/register/?id=<?php echo $uniqueCode?>"  class="btn-copy-link" id='btn-copy-ccs'><i class="fa fa-copy"> Copy CCS URL to clipboard</i></a></div>
                    </div>
                    <div style="width:100%">
                        <span style="padding-left: 25px"><?php echo  \Yii::$app->params['ccs.url'];?>/register/?id=<?php echo $uniqueCode?></span>
                    </div>
                </div>

                <div class="help-block"></div></div>
        </div>
    <?php } ?>
    <div class="form-group field-testsite-services" style="display: none">
        <h4 class="col-xs-12 col-md-8 col-md-offset-4">Available Services</h4>
        <div class="col-md-offset-4 check-services">
            <?php
            $currentServices = $model->getTestSiteServices()->all();

            $appTypes = ApplicationType::find()->all();
            $appTypes = [];
            foreach($appTypes as $appType){
                $checked = '';
                foreach($currentServices as $siteService){
                    if($siteService->application_type_id == $appType->id){
                        $checked = 'checked';
                        break;
                    }
                }
                ?>
                <label class="col-xs-10 col-md-6 control-label label-default text-lefts"><input type="checkbox" <?php echo $checked?> name="services[]" value="<?php echo $appType->id?>">&nbsp;<?php echo $appType->description?></label>

            <?php }?>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-4"><div class="col-xs-12">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    label{
        font-weight: normal;
    }

    .form-zip, .form-state {
        width:75px;
    }

    .check-services .control-label{
        text-align: left; background: transparent
    }
</style>

<script>
var testSiteId = "<?= $model->id ?>";
var apiUrl = "<?= $apiUrl ?>";
var googleMapsApiKey = "<?= $googleMapsApiKey ?>";
</script>
