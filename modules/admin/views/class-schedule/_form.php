<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TestSession;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TestSessionClassSchedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-session-class-schedule-form">

    <?php $form = ActiveForm::begin(['id' => 'class-schedule-form']); ?>
    <input type='hidden' name='TestSessionClassSchedule[testSessionId]' value='<?php echo $model->testSessionId?>'/>
    <div class="form-group field-testsessionclassschedule-classdate required">
    <label class="control-label" for="testsessionclassschedule-classdate">Class Date</label>
        
        <?php 
        $testSession = TestSession::findOne($model->testSessionId);
        $availDates = $testSession->getStartEndDatesForClass();
        $operatingTime = UtilityHelper::getOperatingTime();
        ?>
        
        
        <select class="form-control" name="TestSessionClassSchedule[classDate]"  required>
            <option value=''>Select Date</option>
            <?php 
            foreach($availDates as $key => $display){?>
            <option <?php echo $model->classDate == $key ? 'selected' : ''?> value='<?php echo $key?>'><?php echo $display?></option>
            <?php }?>
            
        </select>
    <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'startTime')->dropDownList(
            $operatingTime, 
            ['prompt'=>'Select Time', 'required'=>'required']    // options
        );  ?>

    <?= $form->field($model, 'endTime')->dropDownList(
            $operatingTime, 
            ['prompt'=>'Select Time', 'required'=>'required']    // options
        );?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
