<?php
use app\models\TestSite; 
use app\models\ChecklistTemplate;
use app\models\ChecklistItemTemplate;
use app\models\TestSession;
use app\models\TestSessionChecklistItems;
use app\models\Cranes;
?>
<div>
<?php 
$isWritten = false;
$session = $testSession;
if($session->getTestSessionTypeId() == TestSite::TYPE_WRITTEN){
	$isWritten = true;
}
?>
<table class="table table-striped table-bordered">
<tbody>
<?php if($isWritten === false){
        //we get all the cranes of the test site
        $availableCranes = $session->getAllCranesOfTestSite();
        
    ?>
<tr>
<th>Pre Checklist:</th>
<td>
<ul>
<?php 
$hasCheckList = false;

$craneIds = [];
$currentList = TestSessionChecklistItems::findAll(['type' => ChecklistTemplate::TYPE_PRE, 'testSessionId' => $session->id]);
foreach($currentList as $cur){
    if($cur->craneId != null && $cur->craneId > 0 && !isset($craneIds[$cur->craneId])){
        $craneIds[$cur->craneId] = Cranes::findOne($cur->craneId);
    }
}  
foreach($availableCranes as $crane){
        if($crane->preChecklistId > 0){
            if(isset($craneIds[$crane->id])){
                unset($craneIds[$crane->id]);
            }
            $hasCheckList = true;
            ?>
            <li><?php echo $crane->getDescription()?> - Click <a target='_blank' href="<?php echo '/admin/checklist/session?craneId='.$crane->id.'&type='.ChecklistTemplate::TYPE_PRE.'&id=' . md5($session->id)?>">here</a> to view checklist
            </li>
            <?php 
        }
}
foreach($craneIds as $crane){
    $hasCheckList = true;
    ?>
    <li><?php echo $crane->getDescription()?> - Click <a target='_blank' href="<?php echo '/admin/checklist/session?craneId='.$crane->id.'&type='.ChecklistTemplate::TYPE_PRE.'&id=' . md5($session->id)?>">here</a> to view checklist
    </li>
    <?php 
                
}
    ?>
</ul>
</td>
</tr>
<tr>
<th>Post Checklist:</th>
<td>
<ul>
<?php 
$hasCheckList = false;
$craneIds = [];
$currentList = TestSessionChecklistItems::findAll(['type' => ChecklistTemplate::TYPE_POST, 'testSessionId' => $session->id]);
foreach($currentList as $cur){
    if($cur->craneId != null && $cur->craneId > 0 && !isset($craneIds[$cur->craneId])){
        $craneIds[$cur->craneId] = Cranes::findOne($cur->craneId);
    }
}

foreach($availableCranes as $crane){
        if($crane->postChecklistId > 0){
            if(isset($craneIds[$crane->id])){
                unset($craneIds[$crane->id]);
            }
            $hasCheckList = true;
            ?>
            <li><?php echo $crane->getDescription()?> - Click <a target='_blank' href="<?php echo '/admin/checklist/session?craneId='.$crane->id.'&type='.ChecklistTemplate::TYPE_POST.'&id=' . md5($session->id)?>">here</a> to view checklist
            </li>
            <?php 
        }
}
foreach($craneIds as $crane){
        $hasCheckList = true;
        ?>
            <li><?php echo $crane->getDescription()?> - Click <a target='_blank' href="<?php echo '/admin/checklist/session?craneId='.$crane->id.'&type='.ChecklistTemplate::TYPE_POST.'&id=' . md5($session->id)?>">here</a> to view checklist
            </li>
            <?php 
}
?>
</ul>
</td>
</tr>
<?php }?>

<?php if($session->writtenChecklistId > 0){?>
<tr>
<th>Pre Written Checklist:</th>
<td>Click <a target='_blank' href="<?php echo '/admin/checklist/session?type='.ChecklistTemplate::TYPE_WRITTEN.'&id=' . md5($session->id)?>">here</a> to view checklsit</td>
</tr>
<?php }?>
<?php if($session->writtenPostChecklistId > 0){?>
<tr>
<th>Post Written Checklist:</th>
<td>Click <a target='_blank' href="<?php echo '/admin/checklist/session?type='.ChecklistTemplate::TYPE_WRITTEN_POST.'&id=' . md5($session->id)?>">here</a> to view checklsit</td>
</tr>
<?php }?>
</tbody>
</table>
<?php 
$checklist = false;
$allItems = [];
$checklistHeader = '';
if($isWritten === false){
    $checklist = ChecklistTemplate::findOne(['type' => ChecklistTemplate::TYPE_PRACTICAL, 'name' => 'Practical ChecklistTemplate']);
    $allItems = ChecklistItemTemplate::findAll(['checklistId' => $checklist->id, 'isArchived' => 0]);
    $checklistHeader = 'Practical Calendar ChecklistTemplate:';
}else{
    $checklist = ChecklistTemplate::findOne(['type' => ChecklistTemplate::TYPE_WRITTEN_CALENDAR_CHECKLIST, 'name' => 'Written Calendar ChecklistTemplate']);
    $allItems = ChecklistItemTemplate::findAll(['checklistId' => $checklist->id, 'isArchived' => 0]);
    $checklistHeader = 'Written Calendar ChecklistTemplate:';
}
$choices = [];
$choices[0] = "Haven't Checked Yet";
$choices[1] = 'Everything OK/No Issues';
$choices[2] = "Issues but Can't Resolve at this Time";
?>
<label><?php echo $checklistHeader?></label>
<table class="table table-striped table-bordered">
<tbody>
<tr>
<th>Name</th>
<th>Status</th>
</tr>
<?php foreach($allItems as $item){?>
<tr>
<td><?php echo $item->name?></td>
<td>
<select data-v='<?php echo $session->getChecklistItemValue($item->id)?>' class='practical-written-checklist-item form-control' data-id='<?php echo $item->id?>' data-session-id='<?php echo $session->id?>' name=''>
<?php foreach($choices as $key => $val){?>
<option <?php echo $key == $session->getChecklistItemValue($item->id) ? 'selected' : ''?> value='<?php echo $key?>'><?php echo $val ?></option>
<?php }?>
</select>
</td>
</tr>
<?php }
?>

</tbody>
</table>
<button class="btn btn-info pull-right" data-dismiss="modal">Close</button>
<br />
<br />
</div>

<script>
$('.practical-written-checklist-item').on('change', Checklist.saveWrittenPracticalItem);
</script>