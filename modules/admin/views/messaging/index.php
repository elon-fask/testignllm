
<div class="content-block content-dashboard">
    <div class="panel panel-default panel-inbox clearfix">
        <br />        
		<div class="col-xs-12 item  pad-small">
		    <div class="row">
		        <div class="col-xs-2">
		        	<div class="message-links"><a href="#" class="btn btn-info btn-new-message pull-right" data-message-id="<?php echo $messageIdToRead?>" data-ops="newmessage">
                            <i class="fa fa-pencil" style="margin-right: 5px;"></i>New Message</a>
                    </div>
		        	<br />
		        	<br />
		    		<div class="list-group message-links">
					  <a href="#" data-ops="inbox" class="list-group-item inbox">
					    Inbox <span class="badge inbox-badge"><?php echo $unread > 0 ? $unread : ''?></span>
					  </a>
					  <a href="#" data-ops="sent" class="list-group-item sent">Sent</a>
					  <a href="#" data-ops="deleted" class="list-group-item deleted">Deleted</a>
					</div>
		        </div>
		        <div class="col-xs-10">
		        	<div class="row messaging-body">
		    		</div>
		        </div>
		    </div>    
		</div>
    </div>
</div>
    