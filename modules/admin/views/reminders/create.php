<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Reminders */

$this->title = 'Create Reminders';
$this->params['breadcrumbs'][] = ['label' => 'Reminders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reminders-create">
    <div class="alert alert-success text-center" style="display: none;">Reminder Saved Successfully</div>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<script>
$('#reminders-reminddate').datepicker({
	 startDate: "today",
	 autoclose: true	
});
</script>