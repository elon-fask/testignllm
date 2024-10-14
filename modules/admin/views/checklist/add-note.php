<?php 
use yii\bootstrap\ActiveForm;
?>
<div class="add-checklist-note-form">

    <?php $form = ActiveForm::begin(['id' => 'add-checklist-note-form']); ?>
    <input type='hidden' name='TestSessionChecklistNotes[testSessionChecklistItemId]' value='<?php echo $model->testSessionChecklistItemId?>'/>
    <input type='hidden' name='TestSessionChecklistNotes[created_by]' value='<?php echo \Yii::$app->user->id?>'/>
    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>

    <div class="form-group text-right">
        <button class="btn btn-success" onclick="javascript: SessionCheckList.saveNotes()" type="button">Save</button>
        <button class="btn btn-warning" data-dismiss="modal" type="button">Cancel</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
