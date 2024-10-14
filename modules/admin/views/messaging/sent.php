<?php
use app\models\User;
?>
<div class="col-xs-12 clearfix btn-delete-wrapper" style="margin-bottom: 10px;">
    <div class="pull-left">
        <button class="btn btn-danger btn-delete"><i class="fa fa-trash" style="margin-right: 5px;"></i> Delete</button>
    </div>
</div>
<br />
<br />
<div class="col-xs-12">

<?php echo yii\base\View::render('_notification'); ?>

<table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th style="text-align:left; width: 5px;">
                <input style="display: block" type="checkbox" value="1" name="select-all" id="select-all"/>
                </th>
                <th>To</th>
                <th>Subject</th>
                <th>Sent Date</th>
            </tr>
        </thead>
        <tbody>
        <?php 
       foreach($messages as $message){            
            	$id=$message['id'];                   
            	$user = User::findOne(['id'=>$message['receiver_id']]);
            	$sentToName = '';
            	if($user != null)
            	   $sentToName = $user->getMessageFullName();
                $subject = $message['subject'];
                $sentDate = date('m-d-Y h:m a', strtotime($message['created_at']));
                
                
        ?>   
        	<tr class="messages-row" data-message-id="<?php echo $id?>">
                <td><input type="checkbox" style="display: block" data-message-id="<?php echo $id?>" class="delete-checkbox" name="delete-checkbox"></td>
                <td class="message-info" data-message-id="<?php echo $id?>"><?php echo $sentToName?></td>
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