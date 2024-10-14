<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSessionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="candidate-session-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'candidate_id') ?>

    <?= $form->field($model, 'test_session_id') ?>

    <?= $form->field($model, 'application_type_id') ?>

    <?= $form->field($model, 'promoCode') ?>

    <?php // echo $form->field($model, 'transactionId') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
