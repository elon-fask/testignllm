<?php 
use app\models\User;

            	$id=$message['id'];
            	$user = User::findOne(['id'=>$message['sender_id']]);
            	$fromName = $user ? $user->getMessageFullName() : 'System Application';
                $subject = $message['subject'];
              
                $sentDate =  date('m-d-Y h:m a', strtotime($message['created_at']));
                $isDeleted = isset($data['deleted_by']) ? true : false;
                $message = $message['body'];                
                
        ?>  
        <div class="col-xs-12 clearfix" style="margin-bottom: 20px;">
		    	<?php if($showReply){?>
		    	<button class="btn btn-default btn-reply" data-message-id="<?php echo $id?>" data-toggle="tooltip" title="Reply" data-placement="top">
                    <i class="fa fa-mail-reply"></i>
                </button>
		    	<?php }?>
		    	<?php  if($showDelete){?>
		        <button class="btn btn-default text-danger btn-delete-message" data-message-id="<?php echo $id?>" data-toggle="tooltip" title="Delete" data-placement="top">
                    <i class="fa fa-trash"></i>
                </button>
		        <?php }?>
                <span class="message-links">
                    <a class="btn btn-default text-info btn-cancel" data-toggle="tooltip" title="Cancel" data-placement="top">
                        <i class="fa fa-times"></i>
                    </a>
                </span>
		</div>
        <div style="line-height: 0; font-size: 0; height: 25px">&nbsp;</div>
        <div class="container-fluid container-message" style="padding-right:20px !important;">

            <div class="row">
                <div class="col-xs-12">
                    <p style="font-weight: bold; margin-bottom: 0">From:</p>
                </div>
                <div class="col-xs-12">
                    <p style="padding-left: 25px;"><?php echo $fromName?> <span>, <?php echo $sentDate;?></span></p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <p style="font-weight: bold; margin-bottom: 0">Subject:</p>
                </div>
                <div class="col-xs-12">
                    <p style="padding-left: 25px;"><?php echo $subject;?></p>
                </div>
            </div>


            <div class="row" style=" padding: 20px;">
                <div class="col-xs-12"  style="border-top: 1px solid #ddd; padding-top: 20px; padding-bottom: 50px">
                    <div><?php echo nl2br($message);?></div>
                </div>
            </div>
        </div>

<script>
$( document ).ready(function() {
    var msg = new messaging();

	$('.btn-delete-message').on('click', function(){
		var deleteIds = [];
		deleteIds[deleteIds.length] = $(this).data('message-id');
        msg.deleteMessage(deleteIds);
	});

	$('.btn-reply').on('click', function(){
        msg.replyMessage($(this).data('message-id'));
	});
});
</script>