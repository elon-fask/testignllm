<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Reminders */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Reminders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reminders-view">

    <div class="alert alert-success text-center" style="display: none;">Marked as completed</div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'note',
            [
                'label' => 'Date',
                'value' => date('Y-m-d', strtotime($model->remindDate))    
            ]
        ],
    ]) ?>
    
    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" class="btn btn-success btn-mark-as-complete" data-id="<?php echo $model->id?>" value="Mark As Complete"/>
    </div>
    <br />
    <br />
</div>
