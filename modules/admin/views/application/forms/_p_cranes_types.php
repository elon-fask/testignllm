<?php
use app\helpers\UtilityHelper; 
?>
<div class="container-types">
    <div class="row row-types">
        <div class="col-xs-12">
            <ul>
                <li class="row">
                    <div class="col-xs-12 col-md-8"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_LATTICE') ? 'checked' : ''?> name="<?php echo $formName?>[P_LATTICE]" id="P_LATTICE" class="practical-cranes"/>Lattice Boom Crane</label></div>
                    <div class="col-xs-12 col-md-4"><label class="control-label"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TOWER') ? 'checked' : ''?> name="<?php echo $formName?>[P_TOWER]" id="P_TOWER" class="practical-cranes"/>Tower Crane</label></div>
                </li>

                <li class="row">
                    <div class="col-xs-12 col-md-4 pull-right">
                        <label class="control-label"><input class="practical-cranes" type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_OVERHEAD') ? 'checked' : ''?> name="<?php echo $formName?>[P_OVERHEAD]" id="P_OVERHEAD" />Overhead Crane</label>
                    </div>
                    <div class="col-xs-12 col-md-8 pull-left">
                        <label class="control-label" style="width: auto;margin-right: 15px;"><input class="practical-cranes" type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TELESCOPIC_TLL') ? 'checked' : ''?> name="<?php echo $formName?>[P_TELESCOPIC_TLL]" id="P_TELESCOPIC_TLL"/>
                            <strong>Telescopic Boom Crane - Swing Cab (TLL)</strong>:  Testing on a boom truck?
                        </label>
                        <label class="control-label" style="width: auto;"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TELESCOPIC_TLL_YES') ? 'checked' : ''?> name="<?php echo $formName?>[P_TELESCOPIC_TLL_YES]" id="P_TELESCOPIC_TLL_YES"/>Yes</label>
                        <label class="control-label" style="width: auto;"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TELESCOPIC_TLL_NO') ? 'checked' : ''?> name="<?php echo $formName?>[P_TELESCOPIC_TLL_NO]" id="P_TELESCOPIC_TLL_NO"/>No</label>
                    </div>

                </li>

                <li>
                    <label class="control-label" style="width: auto;margin-right: 15px;">
                        <input class="practical-cranes" type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TELESCOPIC_TSS') ? 'checked' : ''?> name="<?php echo $formName?>[P_TELESCOPIC_TSS]" id="P_TELESCOPIC_TSS"/><strong>Telescopic Boom Crane - Fixed Cab (TSS)</strong>:  Testing on a boom truck?
                    </label>
                    <label class="control-label" style="width: auto;"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TELESCOPIC_TSS_YES') ? 'checked' : ''?> name="<?php echo $formName?>[P_TELESCOPIC_TSS_YES]" id="P_TELESCOPIC_TSS_YES"/>Yes</label>
                    <label class="control-label" style="width: auto;"><input type="checkbox" <?php echo UtilityHelper::isDynamicFieldChecked($dynamicFormDetails, 'P_TELESCOPIC_TSS_NO') ? 'checked' : ''?> name="<?php echo $formName?>[P_TELESCOPIC_TSS_NO]" id="P_TELESCOPIC_TSS_NO"/>No</label>
                </li>

            </ul>
        </div>
    </div>
</div>