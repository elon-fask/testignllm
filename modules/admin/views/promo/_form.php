<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PromoCodes */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$template       = '{label}<div class="col-xs-12 col-md-5">{input}{error}{hint}</div>';
$labelOptions   = ['class'=>'col-xs-4 control-label'];

$options = ['template'=>$template,'labelOptions'=>$labelOptions];

$template       = '{label}<div class="col-xs-9 col-xs-offset-3">{input}{error}{hint}</div>';
$labelOptions   = ['class'=>'col-xs-4 control-label'];

$options1 = ['template'=>$template,'labelOptions'=>$labelOptions];
?>

<div class="promo-codes-form  form-horizontal">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code', $options)->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'isPurchaseOrder', $options1)->checkbox([0=>'No', 1=>'Yes']) ?>
    <?= $form->field($model, 'discount', $options)->textInput() ?>
    <?= $form->field($model, 'assignedToName', $options)->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <div class=" col-xs-12 col-md-offset-4 col-md-5">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>label{font-weight: normal;}</style>