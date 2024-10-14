<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TravelForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="travel-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'one_way')->textInput() ?>

    <?= $form->field($model, 'destination_loc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'destination_date')->textInput() ?>

    <?= $form->field($model, 'destination_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'return_loc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'return_date')->textInput() ?>

    <?= $form->field($model, 'return_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hotel_required')->textInput() ?>

    <?= $form->field($model, 'car_rental_required')->textInput() ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
