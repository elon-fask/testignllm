 <?php 
    $targetPath = realpath(Yii::$app->basePath) . '/web/session/'.md5($testSession->id).'/attachments/';
    $fileNames = array();
    if ( is_dir($targetPath) && $handle = opendir($targetPath)) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "confirmation") {
				$fileNames[] = $entry;
			}
		}    
		closedir($handle);
    }
    ?>
		<label for="currentAttachments" class="col-lg-3 control-label">Current Attachments*</label>
		<div class="col-lg-6">
			<?php if(count($fileNames) != 0){?>
			<table class="table table-condensed">
			<?php foreach($fileNames as $attach){?>
			<tr>
			 <td><a href="/admin/testsession/viewattachment/?id=<?php echo md5($testSession->id)?>&f=<?php echo base64_encode($attach)?>"><?php echo ($attach)?></a></td>
			 <td>
			 <a class="btn btn-danger btn-delete-session-attachment" data-id="<?php echo md5($testSession->id)?>" data-file="<?php echo base64_encode($attach)?>" href="javascript: void(0);">X</a></td>
		
			</tr>
			<?php }?>
			</table>
			<?php }else{?>
			None
			<?php }?>
		</div>
