<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TravelFormSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="travel-form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'destination_loc') ?>

    <?= $form->field($model, 'destination_date') ?>

    <?= $form->field($model, 'destination_time') ?>

    <?php // echo $form->field($model, 'return_loc') ?>

    <?php // echo $form->field($model, 'return_date') ?>

    <?php // echo $form->field($model, 'return_time') ?>

    <?php // echo $form->field($model, 'hotel_required') ?>

    <?php // echo $form->field($model, 'car_rental_required') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
