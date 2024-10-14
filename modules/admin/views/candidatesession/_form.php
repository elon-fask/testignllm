<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="candidate-session-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'candidate_id')->textInput() ?>

    <?= $form->field($model, 'test_session_id')->textInput() ?>

    <?= $form->field($model, 'application_type_id')->textInput() ?>

    <?= $form->field($model, 'promoCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transactionId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>
   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
