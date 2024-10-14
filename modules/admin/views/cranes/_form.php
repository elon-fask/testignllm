<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ChecklistTemplate;
use dosamigos\datepicker\DatePicker;
use app\models\Cranes;
use app\models\TestSite;

$template       = '{label}<div class="col-xs-12 col-md-5">{input}{error}{hint}</div>';
$labelOptions   = ['class'=>'col-xs-4 control-label'];

$options = ['template'=>$template,'labelOptions'=>$labelOptions];
/* @var $this yii\web\View */
/* @var $model app\models\Cranes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cranes-form" data-id='<?php echo $model->id ?>'>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <input type='hidden' name='Cranes[isDeleted]' value='0'/>
    
    <?= $form->field($model, 'model', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manufacturer', $options)->dropDownList(Cranes::getAvailableManufacturer(), ['prompt'=>'', ] ) ?>
    
    
    <?= $form->field($model, 'unitNum',$options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serialNum',$options)->textInput(['maxlength' => true]) ?>

    <?php if($model->cad == 1){?>
    <div class="form-group cad">
        <div class="col-xs-12 col-md-5 col-md-offset-4">
            <a target='_blank' href="<?php echo $model->getCadFile()?>">CAD File</a>
             &nbsp;&nbsp;
            <a href='javascript: Cranes.delete("cad")'><i class='fa fa-trash'></i></a>
        </div>
    </div>
    <?php }?>
    <?= $form->field($model, 'cad',$options)->fileInput() ?>

    <?php if($model->weightCerts == 1){?>
    <div class="form-group weightCerts">
        <div class="col-xs-12 col-md-5 col-md-offset-4">
            <a target='_blank' href="<?php echo $model->getWeightCertsFile()?>">Weight Certs File</a>
             &nbsp;&nbsp;
            <a href='javascript: Cranes.delete("weightCerts")'><i class='fa fa-trash'></i></a>
        </div>
    </div>
    <?php }?>
    <?= $form->field($model, 'weightCerts',$options)->fileInput() ?>

    <?php if($model->loadChart == 1){?>
    <div class="form-group loadChart">
        <div class="col-xs-12 col-md-5 col-md-offset-4">
            <a target='_blank' href="<?php echo $model->getLoadChartFile()?>">Load Chart File</a>
             &nbsp;&nbsp;
            <a href='javascript: Cranes.delete("loadChart")'><i class='fa fa-trash'></i></a>
        </div>
    </div>
    <?php }?>
    <?= $form->field($model, 'loadChart',$options)->fileInput() ?>
    
    <?php if($model->manual == 1){?>
    <div class="form-group manual">
        <div class="col-xs-12 col-md-5 col-md-offset-4">
            <a target='_blank' href="<?php echo $model->getManualFile()?>">Manual File</a>
             &nbsp;&nbsp;
            <a href='javascript: Cranes.delete("manual")'><i class='fa fa-trash'></i></a>
        </div>
    </div>
    <?php }?>
    <?= $form->field($model, 'manual',$options)->fileInput() ?>
    
    <?php if($model->certificate == 1){?>
    <div class="form-group certificate">
        <div class="col-xs-12 col-md-5 col-md-offset-4">
            <a target='_blank' href="<?php echo $model->getCertificateFile()?>">Certificate File</a>
             &nbsp;&nbsp;
            <a href='javascript: Cranes.delete("certificate")'><i class='fa fa-trash'></i></a>
           
        </div>
    </div>
    <?php }?>
    <?= $form->field($model, 'certificate',$options)->fileInput() ?>
   
    <?= $form->field($model, 'certificateExpirateDate', $options)->widget(
            DatePicker::className(), [
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'mm/dd/yyyy'
                ]
        ]);  ?>
        
    <?= $form->field($model, 'companyOwner',$options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preChecklistId', $options)->dropDownList(ChecklistTemplate::getAllChecklists(ChecklistTemplate::TYPE_PRE), ['prompt'=>'', ] )->label('Pre ChecklistTemplate') ?>
    <?= $form->field($model, 'postChecklistId', $options)->dropDownList(ChecklistTemplate::getAllChecklists(ChecklistTemplate::TYPE_POST), ['prompt'=>'', ] )->label('Post ChecklistTemplate') ?>
    <?= $form->field($model, 'testSiteId', $options)->dropDownList(TestSite::getAllTestSite(TestSite::TYPE_PRACTICAL), ['prompt'=>'', ] )->label('Practical Test Site') ?>
    
    
    
    <div class="form-group">
        <div class=" col-xs-12 col-md-offset-4 col-md-5">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
