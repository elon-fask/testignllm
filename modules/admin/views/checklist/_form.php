<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ChecklistTemplate;
use app\models\ChecklistItemTemplate;

/* @var $this yii\web\View */
/* @var $model app\models\ChecklistTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="checklist-form">

    <?php $form = ActiveForm::begin(['id' => 'checklist-form']); ?>


    <div class="row" style=" padding: 25px; ">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            
                
            <div class="form-group field-checklist-type required">
            <label for="checklist-type" class="control-label">Type</label>
            <select required  name="Checklist[type]" class="form-control" id="checklist-type">
                <option value=""></option>
                <optgroup label="Practical">
                    <option <?php echo $model->type == ChecklistTemplate::TYPE_PRE ? 'selected' : ''?> value="<?php echo ChecklistTemplate::TYPE_PRE?>">Pre Checklist</option>
                    <option <?php echo $model->type == ChecklistTemplate::TYPE_POST ? 'selected' : ''?> value="<?php echo ChecklistTemplate::TYPE_POST?>">Post Checklist</option>
                </optgroup>
                
                <optgroup label="Written">
                    <option <?php echo $model->type == ChecklistTemplate::TYPE_WRITTEN ? 'selected' : ''?> value="<?php echo ChecklistTemplate::TYPE_WRITTEN?>">Pre Written Checklist</option>
                    <option <?php echo $model->type == ChecklistTemplate::TYPE_WRITTEN_POST ? 'selected' : ''?> value="<?php echo ChecklistTemplate::TYPE_WRITTEN_POST?>">Post Written Checklist</option>
                </optgroup>                
            </select>
            
            <div class="help-block"></div>
            </div>
        </div>

    </div>

    <h3  class="clearfix" style="line-height: 34px; margin-bottom: 15px" >Checklist Items:
        <button class="btn btn-info add-item pull-right" type="button" data-btnposition="top"><i class="fa fa-plus"></i> Add Item</button>
    </h3>
    <div class="checklists form-horizontal">
        <?php $hasItems = false; ?>
        <?php if ($model->id > 0) { ?>
            <?php foreach ($model->getChecklistItemTemplates()->all() as $checkListItem) {
                if ($checkListItem->isArchived == 1)
                    continue;

                $hasItems = true;
                ?>
                <div class="checklist-item clearfix" style="border-bottom: 1px solid #ddd; padding: 25px 0;box-shadow:0 4px 8px rgba(0,0,0,0.175)">

                    <div class="col-sm-6">
                        <input type="hidden" class="archived" name="isArchived[]" value="<?php echo $checkListItem->isArchived ?>"/>
                        <input type="hidden" class="item-id" name="itemId[]" value="<?php echo $checkListItem->id ?>"/>

                        <div class="form-group">
                            <label for="checklist-type-<?php echo $checkListItem->id ?>" class="control-label col-xs-2">Name</label>
                            <div class="col-xs-10">
                                <input type="text" class="form-control" name="itemName[]" value="<?php echo $checkListItem->name ?>" id="checklist-type-<?php echo $checkListItem->id ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="checklist-type-<?php echo $checkListItem->id ?>" class="control-label col-xs-2">Type</label>
                            <div class="col-xs-10">
                                <select name='itemType[]' class='form-control item-type'>
                                    <option value=''>Please Choose</option>
                                    <?php foreach (ChecklistItemTemplate::getItemTypes() as $val => $desc) { ?>
                                     <option <?php echo $checkListItem->itemType == $val ? 'selected': ''?> value='<?php echo $val?>'><?php echo $desc?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div style='display: <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_PASS_FAIL ? 'block': 'none'?>' class="form-group item-type-settings item-type-<?php echo ChecklistItemTemplate::TYPE_PASS_FAIL?>">
                            <label class="control-label col-xs-2">Status</label>
                            <div class="col-xs-10">
                            
                                <?php 
                                
                                //we set it to not checked by default
                                $checkListItem->status = ChecklistItemTemplate::STATUS_NOT_CHECKED;
                                foreach (ChecklistItemTemplate::getStatuses() as $val => $desc) { ?>
                                    <button type="button" <?php  echo ChecklistItemTemplate::STATUS_NOT_CHECKED != $val ? 'disabled' : ''?>class="btn btn-sm item-status-new1 <?php echo $val == $checkListItem->status ? 'btn-highlight' : '' ?>" data-val="<?php echo $val ?>"><?php echo $desc ?></button>
                                <?php } ?>
                                <input type="hidden" class="status" name="itemStatus[]" value="<?php echo $checkListItem->status ?>"/>
                            </div>
                        </div>
                        <div style='display: <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_NUMBER ? 'block': 'none'?>' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_NUMBER?>">
                            <label class="control-label col-xs-2">Quantity</label>
                            <div class="col-xs-10">                                
                                <input type="text" class="form-control quantity"  class="val" name="val[]" value="<?php echo $checkListItem->val ?>"/>
                            </div>
                        </div>
                        
                        <div style='display: <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION ? 'block': 'none'?>' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_RATE_CONDITION?>">
                            <label class="control-label col-xs-2">Failing Score</label>
                            <div class="col-xs-10">                                
                                <select class="form-control" name="failingScoreCondition[]">
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION  && ($checkListItem->failingScore == 0 || $checkListItem->failingScore == null) ? 'selected' : ''?> value='0'>0</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION  && $checkListItem->failingScore == 1 ? 'selected' : ''?> value='1'>1</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION  && $checkListItem->failingScore == 2 ? 'selected' : ''?> value='2'>2</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION  && $checkListItem->failingScore == 3 ? 'selected' : ''?> value='3'>3</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION  && $checkListItem->failingScore == 4 ? 'selected' : ''?> value='4'>4</option>
                                </select>
                            </div>
                        </div>
                        <div style='display: <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS ? 'block': 'none'?>' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_RATE_FULLNESS?>">
                            <label class="control-label col-xs-2">Failing Score</label>
                            <div class="col-xs-10">                                
                                <select class="form-control" name="failingScoreFullness[]">
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS  && ($checkListItem->failingScore == 0 || $checkListItem->failingScore == null) ? 'selected' : ''?> value='0'>0</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS  && $checkListItem->failingScore == 1 ? 'selected' : ''?> value='1'>1/4</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS  && $checkListItem->failingScore == 2 ? 'selected' : ''?> value='2'>1/2</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS  && $checkListItem->failingScore == 3 ? 'selected' : ''?> value='3'>3/4</option>
                                    <option <?php echo $checkListItem->itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS  && $checkListItem->failingScore == 4 ? 'selected' : ''?> value='4'>1</option>
                                
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="checklist-description-<?php echo $checkListItem->id ?>" class="control-label col-xs-2 col-sm-3">Description</label>
                            <div class="col-xs-10 col-sm-9">
                                <textarea rows="3" class="form-control" cols="10" name="itemDescription[]"  id="checklist-description-<?php echo $checkListItem->id ?>"><?php echo $checkListItem->description ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 text-right">
                        <button class="btn btn-sm btn-danger delete-item" type="button"><i class="fa fa-times"></i> Remove Item</button>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ($hasItems === false){ ?>
        <div class="checklist-item clearfix"  style="display: none; border-bottom: 1px solid #ddd; padding: 25px 0;box-shadow:0 4px 8px rgba(0,0,0,0.175)">
            <div class="col-sm-6">
                <input type="hidden" class="archived" name="isArchived[]" value="1"/>
                <input type="hidden" class="item-id" name="itemId[]"/>

                <div class="form-group">
                    <label for="checklist-type" class="control-label col-xs-2">Name</label>
                    <div class="col-xs-10">
                        <input type="text" class="form-control" name="itemName[]"/>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="checklist-type-" class="control-label col-xs-2">Type</label>
                    <div class="col-xs-10">
                        <select name='itemType[]' class='form-control item-type'>
                            <option selected value=''>Please Choose</option>
                            <?php foreach (ChecklistItemTemplate::getItemTypes() as $val => $desc) { ?>
                             <option value='<?php echo $val?>'><?php echo $desc?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div style='display: none' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_PASS_FAIL?>">
                    <label for="checklist-type" class="control-label col-xs-2">Status</label>
                    <div class="col-xs-10">
                        <?php foreach (ChecklistItemTemplate::getStatuses() as $val => $desc) { ?>
                            <button type='button' <?php  echo ChecklistItemTemplate::STATUS_NOT_CHECKED != $val ? 'disabled' : ''?>class='btn item-status-new <?php echo $val == ChecklistItemTemplate::STATUS_NOT_CHECKED ? 'btn-highlight' : '' ?>' data-val='<?php echo $val ?>'><?php echo $desc ?></button>
                        <?php } ?>
                    </div>
                    <input type="hidden" class="status" name="itemStatus[]" value="0"/>
                </div>
                <div style='display: none' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_NUMBER?>">
                    <label class="control-label col-xs-2">Quantity</label>
                    <div class="col-xs-10">                                
                        <input type="text" class="form-control quantity"  class="val" name="val[]" value="0"/>
                    </div>
                </div>
                
                
                <div style='display: none' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_RATE_CONDITION?>">
                    <label class="control-label col-xs-2">Failing Score</label>
                    <div class="col-xs-10">                                
                        <select class="form-control" name="failingScoreCondition[]">
                            <option selected value='0'>0</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                        </select>
                    </div>
                </div>
                <div style='display: none' class="form-group item-type-settings  item-type-<?php echo ChecklistItemTemplate::TYPE_RATE_FULLNESS?>">
                    <label class="control-label col-xs-2">Failing Score</label>
                    <div class="col-xs-10">                                
                        <select class="form-control" name="failingScoreFullness[]">
                            <option selected  value='0'>0</option>
                            <option value='1'>1/4</option>
                            <option value='2'>1/2</option>
                            <option value='3'>3/4</option>
                            <option value='4'>1</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="checklist-type" class="control-label col-xs-2 col-sm-3">Description</label>
                    <div class="col-xs-10 col-sm-9">
                        <textarea rows="3" class="form-control" cols="10" name="itemDescription[]"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-12  text-right">
                <button class='btn btn-sm  btn-danger delete-item' type="button"><i class="fa fa-times"></i> Remove Item</button>
            </div>
        </div>

<?php } ?>

</div>



    <div class="clearfix" style="margin-top: 25px">
        <div class="form-group text-center">
            <button class="btn btn-info add-item" type="button" data-btnposition="bottom"><i class="fa fa-plus"></i> Add Item</button>
            <button class="btn btn-success save-checklist" type="button">Save Checklist</button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
