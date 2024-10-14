<?php
$list = $items['list'];
$totalCount = $items['count'];
?>
<?php if ($totalCount == 0) { ?>
<div class='form-group row'>
    <div class='col-xs-12'>
        <label>No Photos</label>
    </div>
    
</div>
<?php }?>
<div class="form-group row">
<?php foreach($list as $sessionPhoto){
    
        if($sessionPhoto->getPhoto() != ''){
    ?>
    
            <div class="col-xs-3" style='margin-top: 10px;' data-id='<?php echo $sessionPhoto->id?>'>
           
                <a target='_blank' href='<?php echo $sessionPhoto->getPhoto()?>'> 
                <img class="candidate-exam-image" src="<?php echo $sessionPhoto->getPhoto()?>" height='150px' width="150px"/></a>
            </div>
        
    <?php 
        }
        ?>
<?php }?>
</div>

<div class="session-photo-pagination" data-from-date='<?php echo isset($fromDate) ? urlencode($fromDate) : ''?>' data-to-date='<?php echo isset($toDate) ? urlencode($toDate) : ''?>' data-test-session-id='<?php echo isset($testSessionId) ? $testSessionId : ''?>'
data-total-pages="<?php echo ceil($totalCount / 20) ?>" 
data-current-page="<?php echo isset($currentPage) ? $currentPage : 1 ?>">

</div>