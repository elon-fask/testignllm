<?php 
$list = $phones['list'];
$totalCount = $phones['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Phone Information</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Source</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $phone){?>
        <tr class="phone-info" data-id="<?php echo $phone->id?>">
            <td><?php echo $phone->name?></td>
            <td><?php echo $phone->email?></td>
            <td><?php echo $phone->phone?></td>
            <td><?php echo $phone->referral?></td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="phone-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>