<?php foreach($allReceipts as $receipt){?>
<div class='col-xs-4' style='margin-bottom: 10px;'>
    <a data-container="body" data-placement="left" data-toggle='popover'  title='Description' 
    data-content="<?php echo $receipt->description?>" href="<?php echo $receipt->getPhoto()?>" target='_blank'><img style='width: 200px; height: 200px' src='<?php echo $receipt->getPhoto()?>'/></a>
    <p><label class=''>Vendor Name:</label> <?php echo $receipt->vendorName?></p>
    <p><label class=''>Amount:</label> $<?php echo number_format($receipt->amount,2)?></p>
</div>
<?php }?>