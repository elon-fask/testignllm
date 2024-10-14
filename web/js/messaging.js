$(document).ready(function() {
  var messageOptions = { updateBadge: 60000, msgContentWrapper: '.messaging-body' };
  var messageTab = new messaging(messageOptions);
  messageTab.init($('.btn-new-message').data('message-id'));
});
/* Messaging Object : do & deal all msg stuff */
var messaging = function() {
  var args = arguments[0] || {},
    _this = this;

  this.hashes = ['inbox', 'deleted', 'sent', 'newmessage'];
  this.updateFrequency = args.updateBadge !== undefined ? args.updateBadge : 600000; // 10min
  this.msgContentWrapper = args.msgContentWrapper !== undefined ? args.msgContentWrapper : '.messaging-body'; // 10min
  this.isWidget = args.isWidget !== undefined ? args.isWidget : false; // embedded
  this.inboxPreviewCount = args.inboxPreviewCount !== undefined ? args.inboxPreviewCount : 10; // 10 items only
};

messaging.prototype.setupdelete = function() {
  $('#select-all').on('change', function() {
    $('.messages-row .delete-checkbox').prop('checked', $(this).is(':checked'));
  });

  $('.btn-delete').on('click', function() {
    var msg = new messaging();
    var deleteMessageIds = [];
    $('.messages-row .delete-checkbox').each(function() {
      if ($(this).is(':checked')) {
        deleteMessageIds[deleteMessageIds.length] = $(this).data('message-id');
      }
    });
    if (deleteMessageIds.length == 0) {
      msg.displayFeedback('error', 'Please select at least one message to be deleted.');
    } else {
      msg.deleteMessage(deleteMessageIds);
    }
  });
};

// load initial page depending on url hash
// binds main nav events
messaging.prototype.init = function(messageId) {
  var _this = this;

  // links & buttons listeners
  if (!_this.isWidget) {
    $('.message-links a').on('click', function(evt) {
      evt.preventDefault();
      var page = $(this).data('ops');
      _this.loadMessagePage(page);
    });
  }
  if (messageId !== undefined && messageId != '') {
    _this.readMessage(messageId);
  } else {
    // load init page based on hash in url => can load more than only inbox
    if ($('body').hasClass('messaging-index')) {
      _this.loadMessagePage(_this.getPagetoLoad());
    }
  }
  // update badges on timed based param
};

// update UI only on timer based
messaging.prototype.updateMessageBadge = function() {
  this.updateUnreadMessageBadge();
};

// get the page to be loaded from the url hash
// @return: (string) hash of page if within acceptable or inbox
messaging.prototype.getPagetoLoad = function() {
  var loadPage = window.location.hash.substring(1);
  loadPage = this.hashes.indexOf(loadPage) == -1 ? 'inbox' : loadPage;
  return loadPage;
};

// get & display a page
// @param: (string) type, name of page to load
messaging.prototype.loadMessagePage = function(type) {
  var _this = this,
    page = type;

  $('.message-links a').removeClass('active');
  if ($('#current-user-key').length != 0) {
    $.ajax({
      type: 'GET',
      url: $('#messaging-url').val() + page,
      data: 'currentUserKey=' + $('#current-user-key').val() + _this.getInboxLimitCount(),
      async: false,
      success: function(data) {
        _this.displayPageContent(data);
        if (!_this.isWidget) {
          window.location.hash = '#' + type;
          $('.' + type).addClass('active');
        }
      }
    });
  }
};

messaging.prototype.getInboxLimitCount = function() {
  var _this = this;
  if (_this.isWidget) return '&isWidget=1&limit=' + _this.inboxPreviewCount;
  return '&isWidget=0';
};

// get & display the content of a message to read
// @param : messageId to read
messaging.prototype.readMessage = function(messageId) {
  var _this = this;
  if (_this.isWidget) {
    window.location = 'inbox?id=' + messageId;
  } else {
    $.ajax({
      type: 'POST',
      url: $('#messaging-url').val() + 'read',
      data: 'id=' + messageId + '&currentUserKey=' + $('#current-user-key').val(),
      success: function(data) {
        // remove the ?id= message id if present when comming form overview
        // IE < 9 no support for pushState...Preventing side effects
        if ($('html').not('.ie6, .ie7, .ie8, .ie9')) {
          var w = window.location;
          window.history.pushState({}, '', w.pathname + w.hash);
        }
        $('.message-links a').removeClass('active');
        _this.displayPageContent(data);
      }
    });
  }
};

// post delete a message the reload the inbox
// @param: message Id to delete
messaging.prototype.deleteMessage = function(deleteIds) {
  var _this = this;

  $.ajax({
    type: 'POST',
    url: $('#messaging-url').val() + 'delete',
    data: { ids: deleteIds, currentUserKey: $('#current-user-key').val() },
    success: function() {
      $('.message-links a').removeClass('active');
      _this.loadMessagePage('inbox');
      _this.updateUnreadMessageBadge();
      _this.updateUnreadMessageBadgeNav();
    }
  });
};

// get & display the reply form with filled recipient & received content in textarea
// @param: message Id to reply to
messaging.prototype.replyMessage = function(messageId) {
  var _this = this;

  $.ajax({
    type: 'POST',
    url: $('#messaging-url').val() + 'reply',
    data: { id: messageId, currentUserKey: $('#current-user-key').val() },
    success: function(data) {
      $('.message-links a').removeClass('active');
      _this.displayPageContent(data);
    }
  });
};

// display the received data in the correct DOM element with some "nice effect"
// @param: data to display. HTML content
messaging.prototype.displayPageContent = function(data) {
  var _this = this;

  $(_this.msgContentWrapper).fadeOut(250, function() {
    $(_this.msgContentWrapper)
      .html(data)
      .fadeIn(250);
    if (_this.isWidget) {
      $('.btn-delete')
        .parents('.btn-delete-wrapper')
        .remove();
      _this.hideFeedback();
    }
    _this.bindListeners();
    _this.updateUnreadMessageBadge();
    _this.updateUnreadMessageBadgeNav(); // hack for ccs-cso only. Prototype in app.js
  });
};

// Feeadback management Error & Success.
// @input: string/enum type: (= error / = success )
// @input: string msg : Feedback message to be displayed
messaging.prototype.displayFeedback = function(type, msg) {
  if (type == 'error') {
    $('.message-notification')
      .find('.alert-success')
      .css({ display: 'none' })
      .end()
      .find('.alert-danger')
      .html(msg)
      .show()
      .end()
      .slideDown();
  } else if (type == 'success') {
    $('.message-notification')
      .find('.alert-danger')
      .css({ display: 'none' })
      .end()
      .find('.alert-success')
      .html(msg)
      .show()
      .end()
      .slideDown();
  } else {
    alert('Unkown Error');
  }
};

// Feedback hide.
messaging.prototype.hideFeedback = function() {
  $('.message-notification')
    .find('.alert')
    .hide()
    .end()
    .hide();
};

// Send the message
// @input; evt = jQuery event
messaging.prototype.sendMessage = function(evt) {
  var _this = this,
    isReply = $(evt.target).data('is-reply');

  // hide all notifications
  _this.hideFeedback();

  // Error & exit if not recipient
  if ($('#to_pid').val() == null) {
    _this.displayFeedback('error', 'Error: Please add a recipient to your message.');
    return false;
  }

  // shoot mail
  var postData = $('#message-form').serialize();

  $.ajax({
    type: 'POST',
    url: $('#messaging-url').val() + 'send',
    data: postData + '&currentUserKey=' + $('#current-user-key').val(),
    success: function() {
      // new-message only
      if (isReply != 1) {
        var fromPid = $('#from_pid').val();
        //clear form items
        $('#message-form')
          .find('input, select, textarea')
          .not('.btn')
          .val('');

        // clear recipient input & associated hidden select
        $('.select2-selection__rendered')
          .find('.select2-selection__choice')
          .remove();
        $('#to_pid')
          .find('option')
          .remove();

        $('#from_pid').val(fromPid);
      }

      _this.displayFeedback('success', 'Your message was successfully sent.');
      _this.updateUnreadMessageBadge();
      _this.updateUnreadMessageBadgeNav();
    },
    error: function() {
      _this.displayFeedback('error', 'There was an Error sending your message. Please try again.');
    }
  });
};

messaging.prototype.updateUnreadMessageBadge = function() {
  if ($('#current-user-key').length != 0) {
    $.ajax({
      type: 'GET',
      url: $('#messaging-url').val() + 'unread',
      data: 'currentUserKey=' + $('#current-user-key').val(),
      success: function(data) {
        var jsonData = $.parseJSON(data);
        if (jsonData.count > 0) {
          $('.inbox-badge').html(jsonData.count);
          $('.info-badge-wrapper').show();
          //$('.info-badge-wrapper').show();
        } else {
          $('.info-badge-wrapper').hide();
          $('.inbox-badge').html('');
        }
      }
    });
  }
};

// on every ajax content update, need to bind listener to newly inserted DOM elements
messaging.prototype.bindListeners = function() {
  var _this = this;

  $('.btn-cancel').on('click', function(evt) {
    evt.preventDefault();
    var loadPage = _this.getPagetoLoad();
    loadPage = loadPage == 'newMessage' ? 'inbox' : loadPage;
    _this.loadMessagePage(loadPage);
  });

  $('.btn-send').on('click', function(evt) {
    _this.sendMessage(evt);
  });

  $("[data-toggle='tooltip']").tooltip();

  // dataTables
  $('.messages-row td.message-info').on('click', function() {
    _this.readMessage($(this).data('message-id'));
  });

  var dataTablesOptions = {};

  if (_this.isWidget) {
    dataTablesOptions = {
      searching: false,
      ordering: false,
      lengthChange: false,
      info: false
      //iDisplayLength:5,
      //paging:false,
      //pageLength:10,
    };
  }
  if (!_this.isWidget) {
    var dTable = $('.messaging-body table').DataTable(dataTablesOptions);
  }
};
