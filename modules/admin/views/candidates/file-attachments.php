 <?php 
    $defLoc = '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments/';
    $targetPath = realpath(Yii::$app->basePath) . $defLoc ;
    $fileNames = array();
    if($showApplication){
        if ( is_dir($targetPath) && $handle = opendir($targetPath)) {
    		/* This is the correct way to loop over the directory. */
    		while (false !== ($entry = readdir($handle))) {
    			if ($entry != "." && $entry != ".." && $entry != "confirmation") {
    				    $fileNames[] = $entry;
    			}
    		}    
    		closedir($handle);
        }
    }
    
    $defLoc = '/web/app-forms/'.$candidate->getFolderDirectory().'/attachments-payment-files/';
    $targetPath = realpath(Yii::$app->basePath) . $defLoc ;
    $paymentFileNames = array();
    if($showPayment){
        if ( is_dir($targetPath) && $handle = opendir($targetPath)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != "confirmation") {
                    $paymentFileNames[] = $entry;
                }
            }
            closedir($handle);
        }
    }
    
    ?>
 <p for="currentAttachments" class="col-md-3">Current Attachments*</p>
 <div class="col-md-6">
     <?php if (count($fileNames) != 0 || count($paymentFileNames) != 0) { ?>
         <table class="table table-condensed">
             <?php foreach ($fileNames as $attach) { ?>
                 <tr>
                     <td>
                         <a href="/admin/candidates/viewattachment/?id=<?php echo md5($candidate->id) ?>&f=<?php echo base64_encode($attach) ?>&pFile=0"><?php echo($attach) ?></a>
                     </td>
                     <td style="width: 120px; text-align: right;">
                         <?php if (isset($isView) && $isView == true){ ?>
                         <?php }else{ ?>
                         <a class="btn btn-danger btn-delete-attachment" data-candidate-id="<?php echo md5($candidate->id) ?>" data-pfile='0' data-file="<?php echo base64_encode($attach) ?>" href="#"><i class="fa fa-trash"></i> Remove</a>
                     </td>
                     <?php } ?>
                 </tr>
             <?php } ?>
             <?php foreach ($paymentFileNames as $attach) { ?>
                 <tr>
                     <td>
                         <a href="/admin/candidates/viewattachment/?id=<?php echo md5($candidate->id) ?>&f=<?php echo base64_encode($attach) ?>&pFile=1"><?php echo ($attach) . ' (Payment File)' ?></a>
                     </td>
                     <td style="width: 120px; text-align: right;" >
                         <?php if (isset($isView) && $isView == true){ ?>
                         <?php }else{ ?>
                         <a class="btn btn-danger btn-delete-attachment" data-candidate-id="<?php echo md5($candidate->id) ?>" data-pfile='1' data-file="<?php echo base64_encode($attach) ?>" href="#"><i class="fa fa-trash"></i> Remove</a>
                     </td>
                     <?php } ?>
                 </tr>
             <?php } ?>
         </table>
     <?php } else { ?>
         <table class="table table-condensed">
             <tr>
                 <td>No Document attached</td>
             </tr>
         </table>
     <?php } ?>
 </div>
