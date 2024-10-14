



var CM = function() {

    var args = arguments[0] || {},
        _this = this,
        modalContentDefault = {
            title: '',
            message: '',
            callback: '',
            options: {},
            btn_1: ['Confirm', 'btn-primary'],
            btn_2: ['Cancel', 'btn-warning'],
            modalOptions: {}
        };




    this.id             = args.id !== undefined ? args.id : 'custom_message_'+$.now();
    this.node           = args.node !== undefined ? args.node : '';
    this.type           = args.type !== undefined ? args.type : '';
    this.cssClasses     = args.cssClasses !== undefined ? args.cssClasses : ''; // Bootstrap CSS classes used for plain feedback messages
    //this.modalOptions   = args.modalOptions !== undefined ? args.modalOptions : '';
    this.modalContent   = args.modalContent !== undefined ? args.modalContent : modalContentDefault;
    this.returnValue    = args.returnValue !== undefined ? args.returnValue : 0;

    //this.hashes            = ['inbox', 'deleted', 'sent', 'newmessage'];
    //this.updateFrequency   = args.updateBadge !== undefined ? args.updateBadge : 600000; // 10min
    //this.msgContentWrapper = args.msgContentWrapper !== undefined ? args.msgContentWrapper : '.messaging-body'; // 10min
    //this.isWidget          = args.isWidget !== undefined ? args.isWidget : false ; // embedded
    //this.inboxPreviewCount = args.inboxPreviewCount !== undefined ? args.inboxPreviewCount : 10; // 10 items only


    //$(function(){
    //    $('#genericModal').on('hide.bs.modal', function(e){
    //        console.log(e);
    //    });
    //});
};



/*
 *  Feedback Messages
 *  ---------------------
 * */

// "PUBLIC"
CM.prototype.success =  function(message){
    this.type = 'success';
    this.buildMessage(message);
};
CM.prototype.failure = function(message){
    this.type = 'failure';
    this.buildMessage(message);
};

CM.prototype.info = function(message){
    this.type = 'info';
    this.buildMessage(message);
};

// "PRIVATE"
CM.prototype.buildMessage = function(message){

    var html = '<div class="custom-message '+ this.getMessageClassesForType() +'" id="'+this.id+'">'+
        ' <button type="button" class="close" aria-label="Close" style="margin-right: 4px;"><span aria-hidden="true">×</span></button>'+
        '    <p>'+message+'</p>'+
        '</div>'
    $('body').append(html);
    this.node = $('#'+this.id);

    var width = this.node.outerWidth(true);
    this.node.css({'margin-left': '-'+width/2+'px'});

    var obj = this;
    var timer = setTimeout(function(){ obj.hideAndDestroyMessage();}, 5000);
    this.node.on('click', function(){ clearTimeout(timer); obj.hideAndDestroyMessage(); })
};

CM.prototype.hideAndDestroyMessage = function(){
    this.node.fadeOut(250, function(){ $(this).remove(); });
};

CM.prototype.getMessageClassesForType = function(){
    if(this.type === 'success') {
        this.cssClasses = 'alert alert-success';
    }else if(this.type === 'failure'){
        this.cssClasses = 'alert alert-danger';
    }else{
        this.cssClasses = 'alert alert-info';
    }
    return this.cssClasses;
};


CM.prototype.defaultButtons1 = function(){
    this.modalContent.btn_1 = ['Confirm', 'btn-primary'];
};
CM.prototype.defaultButtons2 = function(){
    this.modalContent.btn_2 = ['Cancel', 'btn-warning'];
};

CM.prototype.buildModal = function(){
    var obj = this;
    var $modal = $('#genericModal');
    $modal.find('.modal-title').text( this.modalContent.title);
    $modal.find('.modal-body').html('<p class="text-center" style="font-size: 1.25em">' + this.modalContent.message + '</p>');
    //$modal.find('.btn').eq(0).addClass( this.modalContent.btn_1[1]).text( this.modalContent.btn_1[0]);
    //$modal.find('.btn').eq(1).addClass( this.modalContent.btn_2[1]).text( this.modalContent.btn_2[0]).on('click', function(){ eval(obj.modalContent.callback); });
    $modal.find('.btn').eq(0).addClass( this.modalContent.btn_1[1]).text( this.modalContent.btn_1[0])
        .on('click', function(e){
            e.preventDefault();
            obj.returnValue = 0;
            obj.closeModal();
    });
    $modal.find('.btn').eq(1).addClass( this.modalContent.btn_2[1]).text( this.modalContent.btn_2[0]).on('click', function(){ eval(obj.modalContent.callback); });

    $modal.modal( this.modalContent.modalOptions);
};
CM.prototype.closeModal = function() {
    $('#genericModal').modal('hide');
};


CM.prototype.deleteConfirm = function(title, message, callback, modalOptions){
   this.modalContent = {
        title : title,
        message  : message,
        callback : callback,
        options : modalOptions,
        btn_1 : ['No, Keep it','btn-primary'],
        btn_2 : ['Yes, Delete it','btn-warning'],
        modalOptions : modalOptions
    };
    this.buildModal();
};


CM.prototype.actionConfirm = function(title, message, callback,modalContent, modalOptions){
    this.modalContent = {
        title : title,
        message  : message,
        callback : callback,
        options : modalOptions,
        btn_1 : !modalContent.btn_1 ? this.defaultButtons1: modalContent.btn_1,
        btn_2 : !modalContent.btn_2 ? this.defaultButtons2: modalContent.btn_2,
        modalOptions : modalOptions
    };
    this.buildModal();
};

var CustomMessages = {
    /*
     *  Feedback Messages
     *  ---------------------
     * */

    hideAndDestroyMessage: function(id){
        $('#'+id).fadeOut(250, function(){ $(this).remove(); });
    },

    buildMessage : function(type, message){
        var id = 'custom_message_greeting_'+$.now();
        var html = '<div class="custom-message '+ type +'" id="'+id+'">'+
            ' <button type="button" class="close" aria-label="Close" style="margin-right: 4px;"><span aria-hidden="true">×</span></button>'+
            '    <p>'+message+'</p>'+
            '</div>'
        $('body').append(html);
        var width = $('#'+id).outerWidth(true);
        $('#'+id).css({'margin-left': '-'+width/2+'px'});

        return id;
    },

    greeting: function(message){
        var el =  this;
        var id = el.buildMessage('alert alert-success', message);
        var timer = setTimeout(function(){ el.hideAndDestroyMessage(id);}, 5000);
        $('#'+id).on('click', function(){ clearTimeout(timer); el.hideAndDestroyMessage(id); })
    },

    failure: function(message){
        var el = this;
        var id = el.buildMessage('alert alert-danger', message);
        var timer = setTimeout(function(){ el.hideAndDestroyMessage(id);}, 5000);
        $('#'+id).on('click', function(){ clearTimeout(timer); el.hideAndDestroyMessage(id); })
    },

    /*
     *  Confirmation Messages
     *  ---------------------
     * */
    buildModal: function(modalOptions){
        var options= modalOptions.modalOptions || {};

        var $modal = $('#genericModal');
        $modal.find('.modal-title').text(modalOptions.title);
        $modal.find('.modal-body').html('<p class="text-center" style="font-size: 1.25em">' + modalOptions.message + '</p>');
        $modal.find('.btn').eq(0).addClass(modalOptions.btn_1[1]).text(modalOptions.btn_1[0]);
        $modal.find('.btn').eq(1).addClass(modalOptions.btn_2[1]).text(modalOptions.btn_2[0]).on('click', function(){ eval(modalOptions.callback); });

        $modal.modal(options);
    },

    deleteConfirm: function(title, message, callback, modalOptions){
        var modalContent = {
            title : title,
            message  : message,
            callback : callback,
            options : modalOptions,
            btn_1 : ['No, Keep it','btn-primary'],
            btn_2 : ['Yes, Delete it','btn-warning'],
            modalOptions : modalOptions
        };
        this.buildModal(modalContent);
    },

    removeConfirm: function(title, message, callback, modalOptions){
        var modalContent = {
            title : title,
            message  : message,
            callback : callback,
            options : modalOptions,
            btn_1 : ['No, Keep it','btn-primary'],
            btn_2 : ['Yes, Remove it','btn-warning'],
            modalOptions : modalOptions
        };
        this.buildModal(modalContent);
    },

    leavePageConfirm: function(title, message, callback, modalOptions){
        var modalContent = {
            title : title,
            message  : message,
            callback : callback,
            options : modalOptions,
            btn_1 : ['No, Stay on This Page','btn-primary'],
            btn_2 : ['Yes, Leave This Page','btn-warning'],
            modalOptions : modalOptions
        };
        this.buildModal(modalContent);
    },

    alert: function(text){
        alert(text);
    }
};