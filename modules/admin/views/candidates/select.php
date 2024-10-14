<?php 
use app\models\TestSite;
use app\models\ApplicationType;
$sameSession = false;


$message = '<div style="margin: 20px;">Please confirm that you want to move Student: <div style="font-size:18px; padding-left: 25px; margin-bottom: 15px; line-height: 25px; font-weight:bold">'.$candidate->getFullName().'</div> to Session: <div style="font-size:18px; padding-left: 25px; font-weight:bold">'.$newTestSession->getFullTestSessionDescription().'</div></div>';

if($existingTestSession !== false){
    $message = '<div>Please confirm that you want to move Student: <div style="font-size:18px; padding-left: 25px; margin-bottom: 15px; line-height: 25px; font-weight:bold">'.$candidate->getFullName().'</div>';
    $message .= ' from Session: <div style="font-size:18px; padding-left: 25px; font-weight:bold">'.$existingTestSession->getFullTestSessionDescription().'</div>';
    $message .= ' to Session: <div style="font-size:18px; padding-left: 25px; font-weight:bold">'.$newTestSession->getFullTestSessionDescription().'</div></div>';
    if($existingTestSession->id == $newTestSession->id){
        $sameSession = true;
    }
}
?>

<div class="row">
    <?php if($sameSession){?>
    <div class="col-xs-12 text-center" >
        <div class="alert alert-warning">Candidate is being moved to the same session, no action necessary.</div>
    </div>
    <?php }else if($candidate->registration_step == 1 || $candidate->registration_step == 2){?>
    
    <div class="col-xs-12 text-center" >
        <div class="alert alert-danger">Please convert the application to complete before you can select a session</div>
    </div>
    <?php 
    }else{?>
        <div class="col-xs-12">
            <?php echo $message?>
        </div>
        <div  class="col-xs-12">
        <form action="/admin/candidates/select" method="POST">
        <input type="hidden" name="i" value="<?php echo md5($newTestSession->id)?>"/>
        <input type="hidden" name="id" value="<?php echo md5($candidate->id)?>"/>
        <input type="hidden" name="isNCCCOPaid" value="0"/>
        <input type="hidden" name="hasExcuseLetter" value="0"/>
        <input type="hidden" name="isRetake" value="<?php echo $isRetake?>"/>
        
        <?php if($existingTestSession !== false){?>
        <?php if($isRetake == 0 && $existingTestSession->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){?>
            <h3><input type='checkbox' name='isNCCCOPaid' value='1'/> Is NCCCO Paid for current session?</h3>
            <h3><input type='checkbox' name='hasExcuseLetter' value='1'/> Has excuse letter?</h3>
        <?php }?>
        <?php if($isRetake == 1){
        $appTypes = ApplicationType::find()->where('')->all();
            ?>
        <h3>Retake Application Type: <select required name='appType'>
        <option value=''>Select</option>
        <?php foreach($appTypes as $appType){?>
        <option value='<?php echo $appType->id?>'><?php echo $appType->keyword?></option>
        <?php }?>
        </select></h3>
        <?php }?>
        <h3>Remarks: </h3>
        <textarea rows='5' style='width: 100%' id='move-notes' placeholder='Please add remarks here' required id='move-note' name='remarks'></textarea>
        <?php }?>
        <div class="col-xs-12" style="text-align: center; margin-top: 10px;">
            <button type='button' data-dismiss="modal" class="btn btn-danger">Cancel</button>
            <button class="btn btn-info">Move</button>
        </div>
    </form>
    </div>
    <?php } ?>
</div>
