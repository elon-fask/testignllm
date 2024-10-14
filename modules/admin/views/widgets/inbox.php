<?php 
use app\models\User;
$list = $items['list'];
$totalCount = $items['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Messages</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Date</th>
            <th>From</th>
            <th>Subject</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $message){
        $user = User::findOne(['id'=>$message->sender_id]);
        $isRead = $message->is_read;
        ?>
        <tr class="inbox-info"  style=" <?php echo $isRead == 0 ? 'font-weight: bold' : ''?>" data-id="<?php echo ($message->id)?>">
            <td><?php echo date('m-d-Y', strtotime($message->created_at))?></td>
            <td><?php echo $user ?  $user->getMessageFullName() : 'System Application'?></td>
            <td width="40%" class="body"><?php echo mb_strimwidth($message->subject, 0, 40, "...");?></td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="inbox-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>