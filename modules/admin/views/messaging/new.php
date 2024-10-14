
    <form id="message-form">
            <input type="hidden" name="from_pid" id="from_pid" value="<?php echo $senderKey?>"/>

         <?php        echo    yii\base\View::render('_notification'); ?>

        <div class="form-group">
            <label for="subject">Subject</label>
            <input class="form-control" size="120" name="subject" id="subject"/>
        </div>

        <div class="form-group">
            <label for="recipients">Recipient</label>
            <div class="clearfix"></div>
            <select  id="to_pid" name="to_pid[]" multiple="multiple" class="send-to-list" id="recipients" style="width:520px;">
            </select>
        </div>

        <div class="form-group">
            <label for="recipients">Message</label>
            <textarea class="form-control" style="min-height: 150px" name="message_body" id="subject" rows="5" cols="130"></textarea>
        </div>

        <div class="form-group">
            <div class="pull-right">
                <input type="button" class="btn btn-success btn-send" value="Send" />
            </div>
            <div class="clearfix"></div>
            <div class="pull-right" style="margin-top:5px; text-decoration: underline;">
                <a href="#" class="btn-cancel"><i class="fa fa-close" style="margin-right: 5px;"></i>Cancel</a>
            </div>
        </div>

    </form>


<script>
$( document ).ready(function() {	
	 	function formatRepo(subject) {
	 		if(typeof subject.fullname != 'undefined')
	 			return "<span>" + subject.fullname + "</span>";
	        return 'Searching...';

	    }  
//
//	    function formatRepoSelection(subject) {
//	    	if(typeof subject.fullname != 'undefined')
//		        return subject.fullname;
//	        return 'Searching...';
//	    }
    $(".send-to-list").select2({
        ajax: {
            url: "/admin/messaging/searchuser",
            placeholder: "Search for a user",  
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                    };
                },
            processResults: function (data, page) {
            // parse the results into the format expected by Select2.
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data
                return {
                	results: data
                };
            },
            cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepo, // omitted for brevity, see the source of this page
            allowClear: true
        });
});
</script>
