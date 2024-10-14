<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Uploads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uploads-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data'] // important
    ]); ?>
   <div class="form-group field-uploads-file">
    <label for="uploads-file" class="control-label">File</label>
    
    <input type="file" name="file" id="uploads-file">
    
    <div class="help-block"></div>
    </div>
    
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
