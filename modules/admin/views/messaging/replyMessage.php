<?php 
use app\models\User;

$id=$message['id'];
$user = User::findOne(['id'=>$message['sender_id']]);
$fromName = $user->getMessageFullName();

$subject = $message['subject'];
$sentDate = date('m-d-Y h:m a', strtotime($message['created_at']));
$messageBody = $message['body'];
$currentUserKey = $message['receiver_id'];
$senderKey = $message['sender_id'];

?>
		<form id="message-form">

            <?php       echo     yii\base\View::render('_notification'); ?>

    		<input type="hidden" name="from_pid" id="from_pid" value="<?php echo $currentUserKey?>"/>
	    	<input type="hidden" name="to_pid[]" id="to_pid" value="<?php echo $senderKey?>"/>


            <div class="form-group">
                <label for="subject">Subject</label>
                <input class="form-control" size="120" name="subject" id="subject" value="<?php echo 'RE: '.$subject?>"/>
            </div>

            <div class="form-group">
                <label for="subject">Recipient</label>
                <div class="clearfix"><?php echo $fromName?></div>
            </div>

            <div class="form-group">
                <label for="recipients">Message</label>
                <textarea class="form-control" style="min-height: 150px" name="message_body" id="subject" rows="5" cols="130"><?php echo "\r\n \r\n \r\n \r\n -------------------------- ".$sentDate." \r\n \r\n".$messageBody;?></textarea>
            </div>

            <div class="form-group">
                <div class="pull-right"><input type="button" class="btn btn-success btn-send" value="Send"  data-is-reply="1"/></div>
                <div class="clearfix"></div>
                <div class="pull-right" style="margin-top:5px; text-decoration: underline;">
                    <a href="#" class="btn-cancel"><i class="fa fa-close" style="margin-right: 5px;"></i>Cancel</a>
                </div>
            </div>
		</form>
