<?php
use app\helpers\UtilityHelper; 
?>
<ul class="written-exams" style="margin-top: 10px;">

    <li class="clearfix fee-title" style="line-height:16px;">
        <div class="col-xs-7"><strong style="font-size: 14px;">ADDITIONAL EXAMINATIONS</strong></div>
        <div class="col-xs-5 "><strong style="font-size: 14px;">LOAD CHARTS</strong><br/><small>(check for each Specialty Exam)</small></div>
    </li>


    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_LBC') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_LBC]" id="W_EXAM_ADD_LBC"/>Lattice Boom Crawler (LBC)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652620</div>
                <div class="col-xs-12">652607</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_LBC_TEREX') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_LBC_TEREX]" id="W_EXAM_ADD_LBC_TEREX"/>Terex/American</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_LBC_MANITOWOC') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_LBC_MANITOWOC]" id="W_EXAM_ADD_LBC_MANITOWOC"/>Manitowoc</label></div>
            </div>
        </div>
    </li>


    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_LBT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_LBT]" id="W_EXAM_ADD_LBT"/>Lattice Boom Truck (LBT)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652609</div>
                <div class="col-xs-12">652610</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_LBT_LINK-BELT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_LBT_LINK-BELT]" id="W_EXAM_ADD_LBT_LINK-BELT"/>Link-Belt</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_LBT_MANITOWOC') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_LBT_MANITOWOC]" id="W_EXAM_ADD_LBT_MANITOWOC"/>Manitowoc</label></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TLL') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TLL]" id="W_EXAM_ADD_TLL"/>Telescopic Boom-Swing Cab (TLL)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652612</div>
                <div class="col-xs-12">652613</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TLL_GROVE') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TLL_GROVE]" id="W_EXAM_ADD_TLL_GROVE"/>Grove (Truck Mount)</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TLL_LINK-BELT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TLL_LINK-BELT]" id="W_EXAM_ADD_TLL_LINK-BELT"/>Link-Belt (Rough Terrain)</label></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TSS') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TSS]" id="W_EXAM_ADD_TSS"/>Telescopic Boom-Fixed Cab (TSS)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652616</div>
                <div class="col-xs-12">652660</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TSS_MANITEX') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TSS_MANITEX]" id="W_EXAM_ADD_TSS_MANITEX"/>Manitex (Boom Truck)</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TSS_SHUTTLELIFT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TSS_SHUTTLELIFT]" id="W_EXAM_ADD_TSS_SHUTTLELIFT"/>Shuttlelift (Carry Deck)</label></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_BTF') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_BTF]" id="W_EXAM_ADD_BTF"/>Boom Truck-Fixed Cab (BTF)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652671</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_BTF_MANITEX') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_BTF_MANITEX]" id="W_EXAM_ADD_BTF_MANITEX"/>Manitex (Boom Truck)</label></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TOWER') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TOWER]" id="W_EXAM_ADD_TOWER"/>Tower Crane</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">654601</div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_OVERHEAD') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_OVERHEAD]" id="W_EXAM_ADD_OVERHEAD"/>Overhead Crane</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">653601</div>
            </div>
        </div>
    </li>
</ul>
