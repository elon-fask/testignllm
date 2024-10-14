<?php
use app\helpers\UtilityHelper; 
?>

<h4 class="text-center"><b>EXAMINATIONS</b></h4>
<ul class="written-exams">

    <li class="clearfix fee-title">
        <div class="col-xs-7"><strong style="font-size: 14px;">RECERTIFICATION EXAMS</strong></div>
        <div class="col-xs-5 "><strong style="font-size: 14px;">LOAD CHARTS</strong></div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_CORE') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_CORE]" id="W_EXAM_CORE"/>Core Exam</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652605</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><small>(Check one for each Specialty Exam)</small></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_LBC') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_LBC]" id="W_EXAM_LBC"/>Lattice Boom Crawler (LBC)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652625</div>
                <div class="col-xs-12">652608</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_LBC_TEREX') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_LBC_TEREX]" id="W_EXAM_LBC_TEREX"/>Terex/American</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_LBC_MANITOWOC') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_LBC_MANITOWOC]" id="W_EXAM_LBC_MANITOWOC"/>Manitowoc</label></div>
            </div>
        </div>
    </li>


    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_LBT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_LBT]" id="W_EXAM_LBT"/>Lattice Boom Truck (LBT)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652611</div>
                <div class="col-xs-12">652635</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_LBT_LINK-BELT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_LBT_LINK-BELT]" id="W_EXAM_LBT_LINK-BELT"/>Link-Belt</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_LBT_MANITOWOC') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_LBT_MANITOWOC]" id="W_EXAM_LBT_MANITOWOC"/>Manitowoc</label></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TLL') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TLL]" id="W_EXAM_TLL"/>Telescopic Boom-Swing Cab (TLL)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652614</div>
                <div class="col-xs-12">652645</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TLL_GROVE') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TLL_GROVE]" id="W_EXAM_TLL_GROVE"/>Grove (Truck Mount)</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TLL_LINK_BELT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TLL_LINK_BELT]" id="W_EXAM_TLL_LINK_BELT"/>Link-Belt (Rough Terrain)</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_ADD_TLL_NATIONAL') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_ADD_TLL_NATIONAL]" id="W_EXAM_ADD_TLL_NATIONAL"/>National (Boom Track)</label></div>

            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TSS') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TSS]" id="W_EXAM_TSS"/>Telescopic Boom-Fixed Cab (TSS)</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">652656</div>
                <div class="col-xs-12">652665</div>
            </div>
        </div>

        <div class="col-xs-5"><div class="row">
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TSS_MANITEX') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TSS_MANITEX]" id="W_EXAM_TSS_MANITEX"/>Manitex (Boom Truck)</label></div>
                <div class="form-group"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TSS_SHUTTLELIFT') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TSS_SHUTTLELIFT]" id="W_EXAM_TSS_SHUTTLELIFT"/>Shuttlelift (Carry Deck)</label></div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_TOWER') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_TOWER]" id="W_EXAM_TOWER"/>Tower Crane</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">654602</div>
            </div>
        </div>
    </li>

    <li class="clearfix">
        <div class="col-xs-5">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_EXAM_OVERHEAD') ? 'checked' : ''?> name="<?php echo $formName?>[W_EXAM_OVERHEAD]" id="W_EXAM_OVERHEAD"/>Overhead Crane</label>
        </div>
        <div class="col-xs-2"><div class="row">
                <div class="col-xs-12">653602</div>
            </div>
        </div>
    </li>
</ul>
