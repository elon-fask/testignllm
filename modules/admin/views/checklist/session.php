<?php

use yii\helpers\Html;
use app\models\ChecklistTemplate;
use app\models\ChecklistItemTemplate;
use app\models\TestSessionChecklistItems;


/* @var $this yii\web\View */
/* @var $model app\models\ChecklistTemplate */
$suffix = ChecklistTemplate::getTypes()[$type];

$this->title = 'Session ' . $suffix;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="checklist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($message !== false) { ?>
        <div class='alert alert-success'><?php echo $message ?></div>
    <?php } ?>

    <?php if (count($checkListItems) == 0 && $type != ChecklistTemplate::TYPE_WRITTEN) { ?>
        <div class='alert alert-danger'>No Checklist Items</div>
    <?php }else { ?>
        <input type='hidden' id='failed' value='<?php echo implode(',',$failed)?>'/>
        <form method="POST" action="/admin/checklist/session?<?php echo $craneId !== false ? 'craneId='.$craneId.'&' : ''?>type=<?php echo $type ?>&id=<?php echo md5($testSession->id) ?>">

            <div class="clearfix">

                <div class="row" style="padding-bottom: 10px; font-weight: bold;  border-bottom: 1px solid #ddd;">
                    <div class="col-xs-5">Name</div>
                    <div class="col-xs-5">Status</div>
<!--                    <div class="col-xs-2 text-right" >&</div>-->
                </div>

            <?php foreach ($checkListItems as $checkListItem) {
                $item = false;
                if($type == ChecklistTemplate::TYPE_PRE || $type == ChecklistTemplate::TYPE_POST){
                    $item = TestSessionChecklistItems::findOne(['checkListItemId' => $checkListItem->id, 'testSessionId' => $testSession->id,'type' => $type, 'craneId' => $craneId]);
                    
                }else{
                    $item = TestSessionChecklistItems::findOne(['checkListItemId' => $checkListItem->id, 'testSessionId' => $testSession->id, 'type' => $type]);
                }
                if(!$item){
                    $item = new TestSessionChecklistItems();
                    $item->testSessionId = $testSession->id;
                    $item->type = $type;
                    if($craneId !== false){
                        $item->craneId = $craneId;
                    }
                    $item->checkListItemId = $checkListItem->id;
                    if($checkListItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){
                        if($checkListItem->status == ChecklistItemTemplate::STATUS_FAIL){
                            $item->status = $checkListItem->status;
                        }else{
                            $item->status = ChecklistItemTemplate::STATUS_NOT_CHECKED;
                        }
                    }
                    $item->save();
                }
                ?>
                <div id="row<?php echo $checkListItem->id ?>"  class="row" style="border-top: 1px solid #ddd; padding:10px 0">
                    <div class="col-xs-5" style=" position: relative; padding-top:9px;padding-right: 20px">
                        <?php echo $checkListItem->name ?>
                        <div style=" position: absolute; right: 0; top: 9px;" data-container="body" data-toggle="popover" data-content="<?php echo $checkListItem->description ?>" title="Description">
                            <i class="fa fa-info-circle"></i>
                        </div>

                    </div>
                    <?php if($checkListItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL){?>
                    <div class="col-xs-5">
                        <?php foreach (ChecklistItemTemplate::getStatuses() as $val => $desc) { ?>
                            <button type="button" class="btn item-status <?php echo $val == $item->status ? 'btn-highlight' : '' ?>" onclick="javascript: SessionCheckList.select('<?php echo $checkListItem->id ?>', '<?php echo $val ?>')" data-val='<?php echo $val ?>'><?php echo $desc ?></button>
                        <?php } ?>
                        <input type="hidden" name="itemId[]" value="<?php echo $item->id ?>"/>
                        <input type="hidden" class="status" name="itemStatus<?php echo $item->id ?>" value="<?php echo $checkListItem->status ?>"/>
                        
                        
                        
                    </div>

                    <div class="col-xs-2 text-right">
                        <button type="button"  class="btn btn-info checklist-notes" data-checklistid="<?php echo $checkListItem->id ?>">Notes</button>

                    </div>
                   <?php }else if($checkListItem->itemType == ChecklistItemTemplate::TYPE_NUMBER){?>
                   
                   <div class="col-xs-7">
                   <label class='control-label'><?php echo $checkListItem->val?></label>
                   </div>
                   <?php }else if($checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION){?>
                   
                   <div class="col-xs-7">
                   
                        <input type="hidden" name="itemId[]" value="<?php echo $item->id ?>"/>
                        <input type="hidden" class="status" name="itemStatus<?php echo $item->id ?>" value=""/>
                        <?php foreach(ChecklistItemTemplate::getAvailableRateConditionValues() as $key => $val){?>
                        <input type='radio' <?php echo $key == $item->status ? 'checked' : '' ?> name='itemStatus<?php echo $item->id ?>' value="<?php echo $key?>" /> <?php echo $val?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php }?>
                   </div>
                   <?php }else if($checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS){?>
                   
                   <div class="col-xs-7" style='margin-top: 15px'>
                        <input type="hidden" name="itemId[]" value="<?php echo $item->id ?>"/>
                        
                        <input type="hidden" class="slider-input" name="itemStatus<?php echo $item->id ?>" value="<?php echo $item->status?>" />
                        
                  </div>
                   <?php }?>
                </div>
            <?php } ?>

            </div>


            <div class="form-group">
                <div class="col-xs-12 text-center" style="margin-top: 40px">
                    <button class="btn btn-success">Save Checklist</button>
                </div>
            </div>
        </form>
    <?php } ?>

</div>

<style>
    .btn{ margin-bottom: 2px; margin-top: 2px}
</style>

<script>
    $(function () {
        $('[data-toggle="popover"]').popover({'placement':'top', 'trigger':'hover'})
        if($('#failed').length > 0 && $('#failed').val() != ''){
            $.post('/admin/checklist/send-notification', 'testSiteId=' + <?= $testSession->test_site_id ?>+'&id=' + <?php echo $testSession->id ?>+'&failed='+$('#failed').val(), function(){
            });
        }


        $('.checklist-notes').on('click', function(e){
            e.preventDefault();
            var el = $(this);
            var checkListID = el.data('checklistid');
            SessionCheckList.viewNotes(checkListID);

        });

        $(document).on('click', '.btn-add-note', function(e){
            e.preventDefault();
            var el = $(this);
            var checkListID = el.data('checklistid');
            SessionCheckList.addNotes(checkListID);

        });
    })
</script>