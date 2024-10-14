<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Reminders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reminders-form">

    <?php $form = ActiveForm::begin(['id'=>'reminder-form']); ?>
    <input type="hidden" name="Reminders[userId]" value="<?php echo \Yii::$app->user->id?>"/>
    <?= $form->field($model, 'remindDate')->textInput(['readonly' => true, 'class' => 'form-control readonly']) ?>
    
    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>

    



    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" class="btn btn-success btn-add-reminder" value="Create"/>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<br />
<br />