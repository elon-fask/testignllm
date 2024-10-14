<?php
use app\helpers\UtilityHelper; 
?>
<h3>RECERTIFICATION EXAM FEES/RETEST FEES</h3>
<ul class="test-fees">
<li class="fee-title col-xs-12"><h4>MOBILE CRANE EXAMS</h4></li>

    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_CORE_0') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_CORE_0]" id="W_FEE_CORE_0" data-price="160"/>Core Exam</label>
        </div>
        <div class="w-10">$160</div>
    </li>

    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_CORE_1') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_CORE_1]" id="W_FEE_CORE_1" data-price="180"/>Core Exam plus one Specialty Exam</label>
        </div>
        <div class="w-10">$180</div>
    </li>

    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_CORE_2') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_CORE_2]" id="W_FEE_CORE_2" data-price="200"/>Core Exam plus two Specialty Exams</label>
        </div>
        <div class="w-10">$200</div>
    </li>

    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_CORE_3') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_CORE_3]" id="W_FEE_CORE_3" data-price="220"/>Core Exam plus three Specialty Exams</label>
        </div>
        <div class="w-10">$220</div>
    </li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_CORE_4') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_CORE_4]" id="W_FEE_CORE_4" data-price="240"/>Core Exam plus four Specialty Exams</label>
        </div>
        <div class="w-10">$240</div>
    </li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_ADDED_SPECIALTY_1') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_ADDED_SPECIALTY_1]" id="W_FEE_ADDED_SPECIALTY_1" data-price="75"/>One Specialty Exam</label>
        </div>
        <div class="w-10">$75</div>
    </li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_ADDED_SPECIALTY_2') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_ADDED_SPECIALTY_2]" id="W_FEE_ADDED_SPECIALTY_2" data-price="95"/>Two Specialty Exams</label>
        </div>
        <div class="w-10">$95</div>
    </li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_ADDED_SPECIALTY_3') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_ADDED_SPECIALTY_3]" id="W_FEE_ADDED_SPECIALTY_3" data-price="115"/>Three Specialty Exams</label>
        </div>
        <div class="w-10">$115</div>
    </li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_ADDED_SPECIALTY_4') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_ADDED_SPECIALTY_4]" id="W_FEE_ADDED_SPECIALTY_4" data-price="135"/>Four Specialty Exams</label>
        </div>
        <div class="w-10">$135</div>
    </li>
    <li class="fee-title col-xs-12"><h4>TOWER CRANE OPERATOR EXAM</h4></li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_TOWER_NEW') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_TOWER_NEW]" id="W_FEE_TOWER_NEW" data-price="180"/>Tower Crane Operator Written Exam</label>

        </div>
        <div class="w-10">$180</div>
    </li>

    <li class="fee-title col-xs-12"><h4>OVERHEAD CRANE EXAM</h4></li>
    <li class="clearfix">
        <div class="w-90">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_OVERHEAD_NEW') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_OVERHEAD_NEW]" id="W_FEE_OVERHEAD_NEW" data-price="180"/>Overhead Crane Written Exam</label>
        </div>
        <div class="w-10">$180</div>
    </li>


    <li class="fee-title col-xs-12"><h4>OTHER FEES</h4></li>
    <li class="clearfix">
        <div class="col-xs-10">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_LATE') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_LATE]" id="W_FEE_LATE" data-price="50"/>Candidate Late Fee (if applicable)</label>
        </div>
        <div class="col-xs-2 fee-price">$50</div>
    </li>

    <li class="clearfix">
        <div class="col-xs-10">
            <label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'W_FEE_INCOMPLETE') ? 'checked' : ''?> name="<?php echo $formName?>[W_FEE_INCOMPLETE]" id="W_FEE_INCOMPLETE" data-price="30"/>Incomplete Application Fee (if applicable)</label>
        </div>
        <div class="col-xs-2">$30</div>
    </li>

    <li class="clearfix fee-title fee-total"><div class="col-xs-9">
            <h4>TOTAL AMOUNT DUE</h4>
        </div>
        <div class="col-xs-3">
            <span style="float: left;">$</span>
            <span id="fee-total-price"><?php echo UtilityHelper::getDynamicFieldValue($dynamicFormDetails, 'W_TOTAL_DUE', '') ?></span>
            <input type="hidden" id="W_TOTAL_DUE" name="<?php echo $formName?>[W_TOTAL_DUE]" value="<?php echo UtilityHelper::getDynamicFieldValue($dynamicFormDetails, 'W_TOTAL_DUE', '0') ?>"/>
        </div>
    </li>
</ul>
