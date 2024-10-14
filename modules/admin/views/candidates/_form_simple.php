<?php
error_reporting(0);
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Candidates;
use app\helpers\UtilityHelper;
use app\models\ApplicationType;
use app\models\ApplicationTypeFormSetup;
use app\models\AppConfig;
use app\models\TestSite;
use app\models\TestSession;
use app\models\CandidatePreviousSession;


$stateList = UtilityHelper::StateList();
$appTypes = ApplicationType::find()->all();

/* Read only on View Application */
if(isset($isView) && $isView == true){
    $isView = true;
}else{
    $isView = false;
}

/* View or Edit */
if( $isView){
    ?>
    <script>
        $(function() {

            $('#update-candidate').find('input, select').prop('disabled', true);
        });
    </script>


<?php
}
?>

<?php 
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id'=>'update-candidate',
    'action' => '/admin/candidates/create']]); ?>
<input type="hidden"  value="" class="form-control roster-session-id"  name="testSessionId"/>

<input type="hidden"  value="<?php echo $candidate->id?>" class="form-control"  name="Candidates[id]"/>
<!--    <div class="container-civil-state-old">-->
<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label for="app-type" class="control-label">Application Type</label>
            <select data-toggle="<?php echo $candidate->isNewRecord ? '' : 'tooltip'?>" data-placement="bottom" title="Application Type can not be changed.  Please create a new student application." name="<?php echo $candidate->isNewRecord ? 'Candidates[application_type_id]' : ''?> " class="form-control required" required <?php echo ($candidate->isNewRecord || !$isView) ? '' : 'disabled'?>>
                <?php if($candidate->isNewRecord){?>
                    <option value="">Please Select</option>
                <?php }?>
                <?php foreach($appTypes as $appType){

                    ?>
                    <?php if($candidate->isNewRecord){?>
                        <option value="<?php echo $appType->id?>" <?php echo $candidate->application_type_id == $appType->id ? 'selected': ''?>><?php echo $appType->name.' - '.$appType->price?></option>
                    <?php }else if($candidate->isNewRecord == false && $candidate->application_type_id == $appType->id){?>
                        <option value="<?php echo $appType->id?>" <?php echo $candidate->application_type_id == $appType->id ? 'selected': ''?>><?php echo $appType->name.' - '.$appType->price?></option>
                    <?php }?>
                <?php
                }?>
            </select>
            <div class="help-block"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label for="app-type" class="control-label">Is Purchase Order: </label>
            <select name="Candidates[isPurchaseOrder]" class="form-control" required>
                
                    <option value="0" <?php echo $candidate->isPurchaseOrder == 0 ? 'selected': ''?>>No</option>
                    <option value="1" <?php echo $candidate->isPurchaseOrder == 1 ? 'selected': ''?>>Yes</option>
                 
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div>FULL LEGAL NAME</div>
        <div style="padding-top: 5px"><small>(as shown on driver's license)</small></div>
    </div>

    <div class="col-xs-4">
        <div class="form-group">
            <label for="W_FIRST_NAME" class="control-label">First</label>
            <input type="text"  value="<?php echo $candidate->first_name?>" class="form-control required"   id="W_FIRST_NAME" name="Candidates[first_name]"/>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="form-group">
            <label for="W_MIDDLE_NAME" class="control-label">Middle</label>
            <input type="text" value="<?php echo $candidate->middle_name?>" class="form-control"   id="W_MIDDLE_NAME" name="Candidates[middle_name]"/>
            <div class="help-block"></div>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="form-group">
            <label for="W_LAST_NAME" class="control-label">Last</label>
            <input type="text"  value="<?php echo $candidate->last_name?>" class="form-control required"   id="W_LAST_NAME" name="Candidates[last_name]"/>
            <div class="help-block"></div>
        </div>
    </div>

</div>


<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label for="W_PHONE" class="control-label">PHONE</label>
            <input type="text" value="<?php echo $candidate->phone?>" class="form-control phone required"   id="W_PHONE" name="Candidates[phone]"/>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group">
            <label for="W_EMAIL" class="control-label">E-MAIL</label>
            <input type="email" value="<?php echo $candidate->email?>" class="form-control required email"   id="W_EMAIL" name=Candidates[email]/>
            <div class="help-block"></div>
        </div>
    </div>
</div>


<input type="hidden" name="Candidates[requestAda]" value="0"/>

<?= $this->render('../application/form-styles') ?>

    <div class="form-group pull-right">
        <?= Html::button($candidate->isNewRecord ? 'Create' : 'Update', ['class' => $candidate->isNewRecord ? 'btn btn-success btn-update-candidate' : 'btn btn-primary btn-update-candidate']) ?>
    </div>
    <div class='clearfix'>&nbsp;</div>
<?php ActiveForm::end(); ?>
