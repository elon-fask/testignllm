$(function(){

    /**
     *  candidate/update => remove an attached file
     *
     */
	$(document).on('click','.btn-delete-attachment', function(e){
        e.preventDefault();
		var $el = $(this);
        $.confirm({
            title: 'Delete Attachment',
            content: 'Are you sure you want to permanently delete this attachment?',
            confirmButton: 'Yes, Delete',
            cancelButton:'No, Keep it',
            confirm: function(){
                $.post('/admin/candidates/deleteattachment', 'id='+$el.data('candidate-id')+'&f='+$el.data('file')+'&pFile='+$el.data('pfile'), function(){
                    $el.parent().parent().remove();
                    var d = new CM();
                    d.success('Attached document removed successfully.');
                })
            }
		});
	});

    /**
     * testsession/Update => remove an attached file
     *
     */
	$(document).on('click','.btn-delete-session-attachment', function(e){
        e.preventDefault();
	    var $el = $(this);
        $.confirm({
            title: 'Delete Attachment',
            content: 'Are you sure you want to permanently delete this attachment?',
            confirmButton: 'Yes, Delete',
            cancelButton:'No, Keep it',
            confirm: function(){
                $.post('/admin/testsession/deleteattachment', 'id='+$el.data('id')+'&f='+$el.data('file'), function(){
                    $el.parents('tr').remove();
                    var d = new CM();
                    d.success('Attached document removed successfully.');
                });
            }
        });
	});

    /**
     * candidates/update => Not tested, no test case. (Should be working though! :p )
     *
     */
	$(document).on('click','.btn-delete-signed', function(e){
        e.preventDefault();
        var $el = $(this);
        $.confirm({
            title: 'Delete Attachment',
            content: 'Are you sure you want to permanently delete this attachment?',
            confirmButton: 'Yes, Delete',
            cancelButton:'No, Keep it',
            confirm: function(){
                $('#delete-signed-form')
                    .find('input[name="f"]').val($el.data('file')).end()
                    .find('input[name="formName"]').val($el.data('form-name')).end()
                    .submit();
                //$('#delete-signed-form input[name="f"]').val($(this).data('file'));
                //$('#delete-signed-form input[name="formName"]').val($(this).data('form-name'));
                //$('#delete-signed-form').submit();
            }
        });
	});
	
    var ul = $('#upload ul');

    $('#drop a').click(function(e){
        // Simulate a click on the file input button
        // to show the file browser dialog
        e.preventDefault();
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },
        done: function (e, data) {
            var response = $.parseJSON(data.result)
            if(response.status == 1){
	        	$('.file-attachments').html(response.html);
	        	//$('.btn-delete-attachment').off('click');
	        	//$('.btn-delete-attachment').on('click', function(){
	        	//	var elem = $(this);
	        	//	$.post('/admin/candidates/deleteattachment', 'id='+$(this).data('candidate-id')+'&f='+$(this).data('file'), function(){
	        	//		elem.parent().parent().remove();
	        	//	})
	        	//});
	        	//$('.btn-delete-session-attachment').off('click');
	        	//$('.btn-delete-session-attachment').on('click', function(){
	        	//	var elem = $(this);
	        	//	if(confirm('Are you sure you want to delete this attachment?')){
	        	//		$.post('/admin/testsession/deleteattachment', 'id='+$(this).data('id')+'&f='+$(this).data('file'), function(){
	        	//			elem.parent().parent().remove();
	        	//		})
	        	//	}
	        	//});
            }
        },
        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
               
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

});