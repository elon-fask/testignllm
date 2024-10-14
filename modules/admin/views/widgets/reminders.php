<?php 
$list = $reminders['list'];
$totalCount = $reminders['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Reminders</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Date</th>
            <th>Note</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $remind){?>
        <tr class="reminder-info <?php echo $remind->getDeadlineColor()?>" data-id="<?php echo $remind->id?>">
            <td><?php echo date('m-d-Y', strtotime($remind->remindDate))?></td>
            <td width="70%" class="reminder-note"><?php echo mb_strimwidth($remind->note, 0, 40, "...");?></td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="reminder-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>