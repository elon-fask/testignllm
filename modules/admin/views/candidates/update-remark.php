<?php

use yii\helpers\Html;
use app\models\CandidateTransactions;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\CandidateTransactions */
?>
<div class="update-transaction">
    <div class="alert alert-success text-center" style="display: none;">Student Payment Charged Successfully</div>
    <div class="phone-information-form">
    <form id='update-transaction-remark'>
    <input type="hidden" name="id" value="<?php echo $transaction->id?>"/>
    

    <div class=" field-iaiAmount">
        <label for="" class="control-label">Remark</label>
        <textarea rows='5' cols='25' name='remarks' class='form-control'><?php echo $transaction->remarks;?></textarea>
        <div class="help-block"></div>
    </div>

    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" class="btn btn-success btn-update-remark" value="Update"/>
    </div>
    <br />
    <br />
    </form>
</div>

</div>
