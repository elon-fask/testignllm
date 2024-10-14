<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TestSession;
use app\helpers\UtilityHelper;


/* @var $this yii\web\View */
/* @var $model app\models\TestSessionClassSchedule */

$this->title = 'Assign '.$candidate->getFullName().' class';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <div class="">

        <?php $form = ActiveForm::begin(['id' => 'candidate-class-form']); ?>
    <input type='hidden' name='CandidateTestSessionClassSchedule[id]' value='<?php echo $model->id?>'/>
    <input type='hidden' name='CandidateTestSessionClassSchedule[candidateId]' value='<?php echo $model->candidateId?>'/>
    <div class="form-group field-testsessionclassschedule-classdate required">
    <label class="control-label" for="testsessionclassschedule-classdate">Class Date</label>
        
        
        <select class="form-control" name="CandidateTestSessionClassSchedule[testSessionClassScheduleId]"  required>
            <option value=''>Select Date</option>
            <?php foreach($availableSchedules as $sched){?>
            <option <?php echo $model->testSessionClassScheduleId == $sched->id ? 'selected' : ''?> value='<?php echo $sched->id?>'><?php echo $sched->showInfo();?></option>
            <?php }?>
            
        </select>
    <div class="help-block"></div>
    </div>
    
   <div class="form-group">
        <button type="button" class="btn btn-primary save-candidate-class">Save</button>    
    </div>

    <?php ActiveForm::end(); ?>

</div>
    

</div>
