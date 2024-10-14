<?php
$list = $items['list'];
$totalCount = $items['count'];
?>
<?php if ($totalCount == 0) { ?>
<div class='form-group row'>
    <div class='col-xs-12'>
        <label>No Receipts</label>
    </div>
    
</div>
<?php }?>
<div class="form-group row">
<?php foreach($list as $receipt){
    
    ?>
    
            <div class='col-xs-4' style='margin-bottom: 10px;'>
                <a data-container="body" data-placement="left" data-toggle='popover'  title='Description' 
                data-content="<?php echo $receipt->description?>" href="<?php echo $receipt->getPhoto()?>" target='_blank'><img style='width: 200px; height: 200px' src='<?php echo $receipt->getPhoto()?>'/></a>
                <p><label class=''>Vendor Name:</label> <?php echo $receipt->vendorName?></p>
                <p><label class=''>Amount:</label> $<?php echo number_format($receipt->amount,2)?></p>
            </div>
        
<?php }?>
</div>

<div class="receipt-pagination" data-from-date='<?php echo isset($fromDate) ? urlencode($fromDate) : ''?>' data-to-date='<?php echo isset($toDate) ? urlencode($toDate) : ''?>' 
data-total-pages="<?php echo ceil($totalCount / 20) ?>" 
data-current-page="<?php echo isset($currentPage) ? $currentPage : 1 ?>">

</div>