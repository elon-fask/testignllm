<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CranesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cranes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'model') ?>

    <?= $form->field($model, 'manufacturer') ?>

    <?= $form->field($model, 'unitNum') ?>

    <?= $form->field($model, 'serialNum') ?>

    <?php // echo $form->field($model, 'cad') ?>

    <?php // echo $form->field($model, 'weightCerts') ?>

    <?php // echo $form->field($model, 'loadChart') ?>

    <?php // echo $form->field($model, 'manual') ?>

    <?php // echo $form->field($model, 'certificate') ?>

    <?php // echo $form->field($model, 'certificateExpirateDate') ?>

    <?php // echo $form->field($model, 'companyOwner') ?>

    <?php // echo $form->field($model, 'preChecklistId') ?>

    <?php // echo $form->field($model, 'postChecklistId') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'isDeleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
