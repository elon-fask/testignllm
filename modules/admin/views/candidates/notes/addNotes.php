<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="candidate-notes-form">

    <?php $form = ActiveForm::begin(['id'=>'candidate-notes-form']); ?>
    <input type="hidden" name="CandidateNotes[user_id]" value="<?php echo \Yii::$app->user->id?>"/>
    <input type="hidden" name="CandidateNotes[candidate_id]" value="<?php echo $model->candidate_id?>"/>
    <input type="hidden" name="CandidateNotes[id]" value="<?php echo $model->id?>"/>
    <?= $form->field($model, 'notes')->textarea(['maxlength' => true, 'rows'=>8]) ?>


    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" onclick="javascript: CandidateNotes.saveNotes('<?php echo md5($model->candidate_id)?>')" class="btn btn-success btn-add-notes" value="Save"/>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<br />
<br />