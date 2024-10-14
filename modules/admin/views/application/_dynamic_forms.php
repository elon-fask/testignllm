<?php 
use app\models\ApplicationTypeFormSetup;
use app\helpers\UtilityHelper;
?>
 <?php 
    //we need to get all the pdf in the specific folder
    $targetPath = realpath(Yii::$app->basePath) . '/web/forms/';
    $fileNames = array();
    if ($handle = opendir($targetPath)) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "confirmation" && strpos($entry, 'credit-card') === false) {
				$fileNames[] = $entry;
			}
		}    
		closedir($handle);
    }
    ?>



<div class="<?php echo $styling?>">
    <a id="formSetup"></a>
    <h3>Form Setup</h3>

    <div class="form-group">
        <p class="text-danger">
            Click on a PDF file(such as IAI-blank-practical-test-form.pdf) to expand the form. This form corresponds to the same PDF form that will be sent to the candidate.<br/>
            Any checkboxes that are ticked will be ticked on the PDF sent to the candidate. Please make sure that the checkbox for the PDF file itself is ticked, in order to make sure the PDF
            file is sent to candidates who sign up for Test Sessions of this Program Type.
        </p>
    </div>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php foreach($fileNames as $index => $fileName){
        $fileNameKeyId = 'file-'.$index;
        $appFormDynamic = ApplicationTypeFormSetup::findOne(['application_type_id' => $model->id, 'form_name' => str_replace(".pdf", "", $fileName)]);
    ?>
      <div class="panel panel-default panel-<?=str_replace(".pdf", "", $fileName);?>">
        <div class="panel-heading" role="tab" id="heading<?= $index;?>">
          <h4 class="panel-title">
            <input type="checkbox" class="dynamic-forms"  name="form[]"  data-section-id="<?php echo $fileNameKeyId?>" <?php echo $appFormDynamic != null ? 'checked' : ''?> value="<?php echo str_replace(".pdf", "", $fileName)?>"/>
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="<?php echo '#'.$fileNameKeyId?>" aria-expanded="false" aria-controls="<?php echo $fileNameKeyId?>">
              <?php echo $fileName?>
            </a>
          </h4>
        </div>
        <div id="<?php echo $fileNameKeyId?>"  class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $index;?>">
          <div class="panel-body" data-section-id="<?php echo $fileNameKeyId?>">
            <?php
            $customPage = UtilityHelper::getPdfToCustomFormMapping($fileName);
            if($customPage != ''){
                echo $this->render('custom/'.$customPage, ['formName'=>str_replace(".pdf", "", $fileName), 'dynamicFormDetails' => $appFormDynamic]);
            }
            ?>
          </div>
        </div>
      </div>
    <?php }?>
    </div>
</div>
<?php 
   echo $this->render('_form_scripts', []);
   ?> 