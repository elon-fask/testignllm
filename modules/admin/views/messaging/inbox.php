<?php 
use app\models\User;
?>
<style>
    .table-striped tr:hover td{background:#315f81 !important; color: #fff;border-color: #315f81}
</style>

<div class="col-xs-12 clearfix btn-delete-wrapper" style="margin-bottom: 10px;">
    <div class="pull-left">
        <button class="btn btn-danger btn-delete"><i class="fa fa-trash" style="margin-right: 5px;"></i> Delete</button>
    </div>
</div>

<div class="col-xs-12">

<?php echo yii\base\View::render('_notification'); ?>

<table class="table table-striped table-bordered table-condensed inbox">
        <thead>
            <tr>
                <th style="text-align:left; width: 5px;">
                <input type="checkbox" value="1" name="select-all" id="select-all"/>
                </th>
                <th>From</th>
                <th>Subject</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($messages as $message) {
            	$id=$message['id'];
            	$user = User::findOne(['id'=>$message['sender_id']]);
                $senderName = $user ? $user->getMessageFullName() : 'System Application';
                $subject = $message['subject'];
                $sentDate = date('m-d-Y h:m a', strtotime($message['created_at']));
                $isRead = $message['is_read'];

        ?>
        	<tr data-message-id="<?php echo $id?>" class="messages-row <?= $isRead == 1 ? 'read' : 'unread'; ?>">
                <td><input type="checkbox" data-message-id="<?php echo $id?>" class="delete-checkbox" name="delete-checkbox"></td>
                <td class="message-info" data-message-id="<?php echo $id?>"><?php echo $senderName?></td>
                <td class="message-info" data-message-id="<?php echo $id?>"><?php echo $subject?></td>
                <td class="message-info" data-message-id="<?php echo $id?>"><?php echo $sentDate?></td>
            </tr>

        <?php

          }

        ?>
        </tbody>
    </table>
</div>
<script>
$( document ).ready(function() {
	var msg = new messaging();
	msg.setupdelete();
});
</script>