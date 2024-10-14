<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Candidates;
use app\models\ApplicationType;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['/admin/candidates']];
$this->params['breadcrumbs'][] = $this->title;
 
$candidate = $model;
?>
<style>
        .public-DraftEditor-content>div>div:nth-child(1){
height:0px
}
</style>
<style>
    .container-civil-state .control-label{
        font-weight: normal;
        margin-bottom: 0;
    }
    .container-civil-state {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
    }
    .form-control.readonly{
    	background-color: white;
    }
</style>
<div class="candidate-session-view">

    <h1>Student: <?php echo $candidate->getFullName()?></h1>

    <?php  echo $this->render('./partial/_subnav', ['active' => 'details', 'candidate'=>$candidate]); ?>

    <?php if(isset($message) && $message != ''){?>
        <div class="alert alert-success">
            <p>You made a change to the PDF. Do you want to send updated PDF's the students email?</p>
            <p><a href="javascript: void(0)" class="send-app-form" data-candidate-id="<?php echo $model->id?>">Click here if you want to <strong>send updated PDFs.</strong></a></p>
        </div>
    <?php }?>


    <?php echo $this->render('_buttons', ['model' => $model, 'isView'=>true]) ?>
    <?php  echo $this->render('_form', ['candidate' => $model, 'isView'=>true]) ?>
</div>
