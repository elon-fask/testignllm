<?php

use yii\helpers\Html;
use app\models\Candidates;
use app\helpers\UtilityHelper;
use app\models\ApplicationType;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = 'Update Student: '.$model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['/admin/candidates']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => ['/admin/candidates/view', 'id' => md5($model->id)]];
$this->params['breadcrumbs'][] = $this->title;
$candidate = $model;
//UtilityHelper::generateCertificate($candidate->id);
?>

<div class="candidate-session-update">
    <h1>Student: <?php echo $candidate->getFullName()?></h1>

     <?php if($model->isNewRecord == false && ($model->registration_step == 1 || $model->registration_step == 2) ){?>
     <div class="col-xs-12 alert alert-danger">Incomplete Application
            <div class='row col-xs-12 form-group'>
                <button style='margin-top: 10px;' type='button' class='btn btn-success btn-convert-to-complete' data-id='<?php echo md5($model->id)?>'>Convert To Complete</button>
                <br />
            </div>
    </div>
     <?php }?>

    <?php echo $this->render('./partial/_subnav', ['active' => 'edit', 'candidate'=>$candidate]); ?>

    <?php if(isset($message) && $message != ''){?>
    <div class="alert alert-success" ><?php echo $message?></div>
    <?php }else{?>
    <div class="alert alert-success" style="display: none"></div>
    <?php }?>
    
    <?php if($model->isArchived == 1){?>
    <div class="alert alert-danger" >Candidate Application is already archived.</div>
    <?php }?>

    <?php echo $this->render('_buttons', ['model' => $model]) ?>
    <?php echo  $this->render('_form', ['candidate' => $model]) ?>

</div>
