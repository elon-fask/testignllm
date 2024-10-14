<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Resources;

/* @var $this yii\web\View */
/* @var $model app\models\Resources */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resources-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(Resources::getTypes()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textarea() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
