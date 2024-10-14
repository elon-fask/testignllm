
function getValidUrl(href) {
  let baseUrl = location.origin;
  const baseTags = document.getElementsByTagName('base');
  // print(baseTags.length)
  if (baseTags.length > 0) {
    baseUrl += '/payment';
  }
  url = `${baseUrl}${href}`;
  console.log(url);
  return url;
}

function redirectTo(href) {
  url = getValidUrl(href);
  window.location.href = url;
}

var Cranes = {
  delete: function(type) {
    $.confirm({
      title: 'Delete File',
      content: 'Are you sure you want to delete this file?',
      confirmButton: 'Yes, Remove',
      cancelButton: 'No, Keep it',
      confirm: function() {
        $.post('/admin/cranes/delete-file', 'id=' + $('.cranes-form').data('id') + '&type=' + type, function(html) {
          var resp = $.parseJSON(html);
          var f = new CM();
          if (resp.status == 1) {
            var f = new CM();
            f.success('File Deleted Successfully');
            $('.' + type).remove();
          } else {
            f.failure('Operation failed, please try again');
          }
        });
      }
    });
  }
};

var CandidatePayment = {
  updateRemark: function(transactionId) {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Edit Remark');

    $.get('/admin/candidates/transaction-remark', 'id=' + transactionId, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');

      $('.btn-update-remark').on('click', function() {
        var $modalBody = $modal.find('.modal-body'),
          formControls = $modalBody.find('textarea[name="remarks"]');

        $modalBody.find('.has-error').removeClass('has-error');

        $.post('/admin/candidates/save-transaction-remark', $('form#update-transaction-remark').serialize(), function(
          html
        ) {
          var resp = $.parseJSON(html);
          var f = new CM();
          if (resp.status == 1) {
            $('.update-remark-' + resp.id).html(resp.remark.replace(/(?:\r\n|\r|\n)/g, '<br />'));
            f.success('Remark Saved Successfully');
            $modal.modal('hide');
          } else {
            f.failure('Operation failed, please try again');
          }
        });
      });
    });
  },
  updateCheckNumber: function(transactionId) {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Edit Check Number');

    $.get('/admin/candidates/check-number', 'id=' + transactionId, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');

      $('.btn-update-remark').on('click', function() {
        var $modalBody = $modal.find('.modal-body'),
          formControls = $modalBody.find('textarea[name="remarks"]');

        $modalBody.find('.has-error').removeClass('has-error');

        $.post('/admin/candidates/save-check-number', $('form#update-check-number').serialize(), function(html) {
          var resp = $.parseJSON(html);
          var f = new CM();
          if (resp.status == 1) {
            $('.update-remark-' + resp.id).html('Check Number: ' + resp.check_number);
            f.success('Check Number Saved Successfully');
            $modal.modal('hide');
          } else {
            f.failure('Operation failed, please try again');
          }
        });
      });
    });
  },
  removeCharge: function(candidateId, reload) {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Add Discount');

    $.get('/admin/candidates/remove-charge', 'candidateId=' + candidateId, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
      $('.btn-remove-charge').on('click', function() {
        var $modalBody = $modal.find('.modal-body'),
          formControls = $modalBody.find('input[type=text]'),
          hasErrors = false;

        $modalBody.find('.has-error').removeClass('has-error');

        $.each(formControls, function() {
          var control = $(this);
          if ($.trim(control.val()) == '') {
            control.parent().addClass('has-error');
            hasErrors = true;
          }
        });

        if (
          $('#remove-charge-form input#candidatetransactions-amount').val() == '' ||
          isNaN(parseFloat($('#remove-charge-form input#candidatetransactions-amount').val())) ||
          parseFloat($('#remove-charge-form input#candidatetransactions-amount').val()) >
            parseFloat($('.btn-remove-charge').data('allowed-removable-charge'))
        ) {
          $('#remove-charge-form input#candidatetransactions-amount')
            .parent()
            .addClass('has-error');
          $('#remove-charge-form input#candidatetransactions-amount')
            .parent()
            .find('.help-block')
            .html(
              'Removable charge is only ' +
                accounting.formatMoney($('.btn-remove-charge').data('allowed-removable-charge'))
            );
          hasErrors = true;
        }
        if (!hasErrors) {
          $.post('/admin/candidates/remove-charge', $('form#remove-charge-form').serialize(), function(html) {
            $modalBody.find('.alert').slideDown();
            setTimeout(function() {
              $modal.modal('hide');
              window.location.reload();
            }, 800);
          });
        }
      });
    });
  },
  payment: function(candidateId, reload) {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Receive Payment');

    $.get('/admin/candidates/paymentscreen', 'id=' + candidateId, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');

      $('.select-payment').on('change', function() {
        var paymentType = $(this).val();
        if (paymentType === '4') {
          $('.btn-charge-cc').show();
        } else {
          $('.btn-charge-cc').hide();
        }

        var checkNumberField = $('#field-check-number');
        var remarksField = $('#field-remarks');

        if (paymentType === '2') {
          checkNumberField.show();
          remarksField.hide();
        } else {
          checkNumberField.hide();
          remarksField.show();
        }
      });

      var validate = function() {
        $('.has-error').removeClass('has-error');
        $('.help-block').html('');
        var hasError = false;
        if ($('input[name="paymentAmount"]').val() == '') {
          $('input[name="paymentAmount"]')
            .parent()
            .addClass('has-error');
          $('input[name="paymentAmount"]')
            .parent()
            .find('.help-block')
            .html('Amount is required');
          hasError = true;
        } else if (parseFloat($('input[name="paymentAmount"]').val()) < 0) {
          $('input[name="paymentAmount"]')
            .parent()
            .addClass('has-error');
          $('input[name="paymentAmount"]')
            .parent()
            .find('.help-block')
            .html('Amount is should be positive');
          hasError = true;
        }
        if ($('select[name="type"]').val() == '') {
          $('select[name="type"]')
            .parent()
            .addClass('has-error');
          $('select[name="type"]')
            .parent()
            .find('.help-block')
            .html('Type is required');
          hasError = true;
        }

        return hasError;
      };

      $('.btn-charge-cc').on('click', function() {
        var hasError = validate();
        if (hasError == false) {
          $.get(
            '/admin/candidates/epayment',
            'id=' +
              $('#add-payment-form').data('candidate-id') +
              '&amount=' +
              $('input[name="paymentAmount"]').val() +
              '&remarks=' +
              $('input[name="remarks"]').val(),
            function(html) {
              if (html == '') {
                alert('Something went wrong, please try again');
              } else {
                $('#e-payment').html(html);
              }
            }
          );
        }
      });

      $('.btn-add-payment').on('click', function() {
        var hasError = validate();
        if (hasError == false) {
          if (reload) {
            $('#add-payment-form').submit();
          } else {
            var d = new CM();

            $.post($('#add-payment-form').attr('action'), $('#add-payment-form').serialize(), function(html) {
              d.success('Payment Successful');
              $modal.modal('hide');
              Roster.autoRefreshPage();
            });
          }
        }
      });
    });
  }
};

var Checklist = {
  deleteItem: function() {},
  saveWrittenPracticalItem: function() {
    var isChecked = $(this).is(':checked') ? 1 : 0;
    var itemId = $(this).data('id');
    var sessionId = $(this).data('session-id');
    $.post(
      '/admin/checklist/update-session-item',
      'id=' + itemId + '&sessionId=' + sessionId + '&status=' + $(this).val(),
      function(resp) {
        var data = $.parseJSON(resp);
        var d = new CM();
        if (data.status == 1) {
          d.success('Saved Successfully');
        } else {
          d.failure('Error Saving, Please try again');
        }
      }
    );
  },
  viewSession: function(sessionId) {
    var $modal = $('#genericModal');

    $modal
      .find('.modal-title')
      .html('Checklist')
      .end()
      .find('.modal-footer')
      .hide();

    $.get('/admin/checklist/roster-session', 'id=' + sessionId, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
    });
  }
};

var SessionCheckList = {
  select: function(id, selectedVal) {
    if ($('#row' + id).length != 0) {
      $('#row' + id + ' .item-status').removeClass('btn-highlight');
      $('#row' + id + ' .item-status[data-val="' + selectedVal + '"]').addClass('btn-highlight');
      $('#row' + id + ' .status').val(selectedVal);

      var d = new CM();
      d.info('Save Your Checklist. It has been changed.');
    } else {
    }
  },
  addNotes: function(id) {
    var $modal = $('#genericModal');

    $modal
      .find('.modal-title')
      .html('Add Checklist Item Notes')
      .end()
      .find('.modal-footer')
      .hide();

    $.get('/admin/checklist/add-note', 'id=' + id, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
    });
  },
  viewNotes: function(id) {
    var $modal = $('#genericModal');

    $modal
      .find('.modal-title')
      .html('View Checklist Item Notes')
      .end()
      .find('.modal-footer')
      .hide();

    $.get('/admin/checklist/view-note', 'id=' + id, function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
    });
  },
  saveNotes: function() {
    $('.has-error').removeClass('has-error');
    if ($('#add-checklist-note-form textarea').val() == '') {
      $('#add-checklist-note-form textarea')
        .parent()
        .addClass('has-error');
    }
    if ($('.has-error').length == 0) {
      $.post('/admin/checklist/save-note', $('#add-checklist-note-form').serialize(), function(resp) {
        var data = $.parseJSON(resp);
        var d = new CM();
        if (data.status == 1) {
          d.success('Note Saved Successfully');
          var $modal = $('#genericModal');
          $modal.modal('hide');
        } else {
          d.failure('Error Saving, Please try again');
        }
      });
    }
  }
};

var CandidateNotes = {
  // Was used on ajax view note. Dead Code
  //openNotes : function(candidateId){
  //var $modal = $('#genericModal');
  //$.get('/admin/candidates/notes', 'id='+candidateId, function(html){
  //    $modal.find('.modal-title').html('Candidate Notes');
  //$modal.find('.modal-body').html(html).end().modal('show');
  //});
  //},
  refreshNotes: function(candidateId) {
    $.get('/admin/candidates/notes', 'ajax=1&id=' + candidateId, function(html) {
      $('.list-body-info').html(html);
      $('#genericModal').modal('hide');
    });
  },
  addNotes: function(candidateId) {
    var $modal = $('#genericModal');
    $.get('/admin/candidates/add-notes', 'id=' + candidateId, function(html) {
      $modal
        .find('.modal-title')
        .html('Add Candidate Notes')
        .end()
        .find('.modal-footer')
        .hide()
        .end()
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
    });
  },
  editNotes: function(id, candidateId) {
    var $modal = $('#genericModal');
    $.get('/admin/candidates/edit-notes', 'id=' + id + '&candidateId=' + candidateId, function(html) {
      $modal
        .find('.modal-title')
        .html('Edit Candidate Notes')
        .end()
        .find('.modal-footer')
        .hide()
        .end()
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
    });
  },
  deleteNotes: function(noteId, candidateId) {
    $.confirm({
      title: 'Delete Candidate Note',
      content: 'Are you sure you want to delete this note?',
      confirmButton: 'Yes, Remove',
      cancelButton: 'No, Keep it',
      confirm: function() {
        $.post('/admin/candidates/delete-notes', 'id=' + noteId, function(html) {
          var resp = $.parseJSON(html);
          var f = new CM();
          if (resp.status == 1) {
            var f = new CM();
            f.success('Notes Deleted Successfully');
            CandidateNotes.refreshNotes(candidateId);
          } else {
            f.failure('Operation failed, please try again');
          }
        });
      }
    });
  },
  saveNotes: function(candidateId) {
    $('#candidate-notes-form .has-error').removeClass('has-error');
    if ($('#candidatenotes-notes').val() == '') {
      $('#candidatenotes-notes')
        .parent()
        .addClass('has-error');
    }
    if ($('#candidate-notes-form .has-error').length == 0) {
      $.post('/admin/candidates/save-notes', $('#candidate-notes-form').serialize(), function(html) {
        var f = new CM();
        f.success('Notes Saved Successfully');
        CandidateNotes.refreshNotes(candidateId);
      });
    }
  }
};
var Report = {
  hasLoaded: 0,
  generateReadiness: function() {
    var hasError = false;
    if ($('#instructor-name').val() == '') {
      $('#instructor-name')
        .parents('.form-group')
        .addClass('has-error');
      hasError = true;
    }
    if ($('#certificate-date').val() == '') {
      $('#certificate-date')
        .parents('.form-group')
        .addClass('has-error');
      hasError = true;
    }
    if (hasError == false) {
      $('input[name="certDate"]').val($('#certificate-date').val());
      $('input[name="certInstructor"]').val($('#instructor-name').val());
      if ($('select[name="output"]').val() == 'xls') {
        $('#report-form').attr('action', '/admin/reports/generate');
        //$('#report-form').attr('method', 'POST');
        $('#report-form').submit();
        $('body').append(
          "<iframe style='display: none' src='/admin/reports/session-certificates/?i=" +
            $('select[name="testSessionId"]').val() +
            "'></iframe>"
        );
        $('#reminder-modal').modal('hide');
      } else {
        NProgress.start();
        $.post('/admin/reports/generate', $('#report-form').serialize(), function(data) {
          $('.report-results').html(data);
          $('#reminder-modal').modal('hide');
          NProgress.done();
        });
      }
    }
  },
  changeReportType: function() {
    $('.report-results').html('');
    $.get('/admin/reports/filters', 'type=' + $('select[name="reportType"]').val(), function(data) {
      $('.filters').html(data);
      Report.setupUI();
      Report.hasLoaded = 1;
      if ($('input[name="start_date"]').val() != '' && $('input[name="end_date"]').val() != '') {
        Report.loadSessions();
      }
      if ($('select[name="reportType"]').val() == 'discrepancy') {
        $('.input-daterange')
          .parent()
          .hide();
      } else {
        $('.input-daterange')
          .parent()
          .show();
      }
    });
  },
  showReportReadinessPopUp: function() {
    $('.has-error').removeClass('has-error');
    if ($('select[name="testSessionId"]').length != 0 && $('select[name="testSessionId"]').val() == '') {
      $('select[name="testSessionId"]')
        .parent()
        .addClass('has-error');
    }

    if ($('.has-error').length == 0) {
      if ($('#gen-cert').is(':checked')) {
        var $modal = $('#reminder-modal');

        $modal.find('.modal-title').html('Certificate Details');

        $.get('/admin/reports/readiness-param', '', function(html) {
          $modal
            .find('.modal-body')
            .html(html)
            .end()
            .modal('show');
          $('.btn-generate-readiness-report-now').on('click', Report.generateReadiness);
        });
      } else {
        if ($('select[name="output"]').val() == 'xls') {
          $('#report-form').attr('action', '/admin/reports/generate');
          //$('#report-form').attr('method', 'POST');
          $('#report-form').submit();
        } else {
          $('#fullPage').remove();
          $.post('/admin/reports/generate', $('#report-form').serialize(), function(data) {
            $('.report-results').html(data);
          });
        }
      }
    }
  },
  setupUI: function() {
    var self = this;

    $('.btn-generate-report-readiness').on('click', Report.showReportReadinessPopUp);

    $('.btn-generate-report').on('click', function() {
      self.generateReport(false);
    });

    $(document).on('click', '.btn-generate-report-fullPage', function() {
      self.generateReport(true);
    });
  },
  generateReport: function(isFullScreen) {
    $('.has-error').removeClass('has-error');

    if ($('select[name="testSessionId"]').length != 0 && $('select[name="testSessionId"]').val() == '') {
      $('select[name="testSessionId"]')
        .parent()
        .addClass('has-error');
    }
    if ($('select[name="testSiteId"]').length != 0 && $('select[name="testSiteId"]').val() == '') {
      $('select[name="testSiteId"]')
        .parent()
        .addClass('has-error');
    }

    if ($('select[name="reportType"]').val() === 'pass_fail') {
      var testSessionId = $('select[name="testSessionId"]')
        .val()
        .toString();
      window.location.href = '/admin/testsession/spreadsheet?id=' + testSessionId;
      return;
    }

    if ($('.has-error').length == 0) {
      if ($('select[name="output"]').val() == 'xls') {
        $('#report-form').attr('action', '/admin/reports/generate');
        //$('#report-form').attr('method', 'POST');
        $('#report-form').submit();
      } else {
        var fullScr = $('#fullPage');
        if (isFullScreen) {
          if (!(fullScr.length > 0)) {
            var inp = $("<input type='hidden' name='fullpage' value='1' id='fullPage'/>");
            $('#report-form').append(inp);
          }
          $('#report-form').submit();
        } else {
          fullScr.remove();
          $.post('/admin/reports/generate', $('#report-form').serialize(), function(data) {
            $('.report-results').html(data);
          });
        }
      }
    }
  },

  loadSessions: function() {
    $.get(
      '/admin/reports/sessions',
      'startDate=' + $('input[name="start_date"]').val() + '&endDate=' + $('input[name="end_date"]').val(),
      function(data) {
        var resp = $.parseJSON(data);
        $('select[name="testSessionId"]').html('');
        $('select[name="testSessionId"]').append(new Option('Select Session', ''));
        var sessionIds = '';
        for (var i in resp) {
          $('select[name="testSessionId"]').append(new Option('(' + resp[i].type + ') : ' + resp[i].desc, resp[i].id));

          if (sessionIds != '') {
            sessionIds += ',';
          }

          sessionIds += resp[i].id;
        }
        if ($('#testSessionIds').length > 0) {
          $('#testSessionIds').val(sessionIds);
        }
      }
    );
  },
  loadSessionsFilterPhotos: function() {
    $.get(
      '/admin/reports/sessions-filter',
      'fromDate=' +
        encodeURIComponent($('input[name="filter[fromDate]"]').val()) +
        '&toDate=' +
        encodeURIComponent($('input[name="filter[toDate]"]').val()),
      function(data) {
        var resp = $.parseJSON(data);
        $('select[name="testSessionId"]').html('');
        $('select[name="testSessionId"]').append(new Option('Select Session', ''));
        var sessionIds = '';
        for (var i in resp) {
          $('select[name="testSessionId"]').append(new Option('(' + resp[i].type + ') : ' + resp[i].desc, resp[i].id));

          if (sessionIds != '') {
            sessionIds += ',';
          }

          sessionIds += resp[i].id;
        }
      }
    );
  }
};
var AppWizard = {
  search: function() {
    if ($('body').hasClass('application-wizard')) {
      $.post('/admin/application/check', $('#wizard-app-form').serialize(), function(data) {
        $('.matching-results').html(data);
      });
    }
  }
};
function validateTestSessionSchool() {
  /*
	var currentSchool = $('#session-form').data('current-school');
	var isNew = $('#session-form').data('is-new');
	var type = $('#session-form').data('type');

	if(type == 1 && isNew == 1){
		$('#session-form').submit();
	}else if(currentSchool != $('#testsession-school').val() && $('#testsession-school').val() != ''){

		var message = 'System detected you changed the school for this session, doing so would update any associated session as well, do you want to continue?';
		if($('.btn-submit-session').hasClass('new')){
			message = 'Saving this session would update any associated session school as well, do you want to continue?';
		}
		if(confirm(message)){
			$('#session-form').submit();
		}
		return false;
	}else{
		$('#session-form').submit();
	}
	*/
  $('#session-form').submit();
}

$(function() {
  if ($('#staff-form').length == 1) {
    $('#staff-form').on('submit', function() {
      $('#staff-form .has-error').removeClass('has-error');
      $('#staff-form .phone').each(function() {
        if ($(this).val() != '') {
          checkPhoneHasAllNumbers($(this));
        }
      });
      if ($('#staff-form .has-error').length == 0) return true;
      return false;
    });
  }
  if ($('.btn-convert-to-complete').length == 1) {
    $('.btn-convert-to-complete').on('click', function() {
      $.confirm({
        title: 'Convert Application',
        content: 'Are you sure you want to convert application to be completed?',
        confirmButton: 'Yes, Convert',
        cancelButton: 'No, Keep it',
        confirm: function() {
          $.post('/admin/candidates/convert-complete', 'id=' + $('.btn-convert-to-complete').data('id'), function(
            data
          ) {
            var resp = $.parseJSON(data);
            if (resp.status == 1) {
              $('.btn-convert-to-complete')
                .parents('.alert')
                .remove();
              $.alert({
                title: 'Convert Application',
                content: 'Application Converted Successfully',
                confirmButtonClass: 'btn-primary',
                confirmButton: 'Close'
              });
            } else {
              $.alert({
                title: 'Convert Application',
                content: 'Operation failed, please try again',
                confirmButtonClass: 'btn-primary',
                confirmButton: 'Close'
              });
            }
          });
        }
      });
    });
  }
  if ($('#testsession-practical_test_session_id').length != 0) {
    $('#testsession-practical_test_session_id').on('change', function() {
      if ($('#testsession-practical_test_session_id').val() != '') {
        var id = $('#session-form').data('id');
        $('.btn-submit-session').attr('disabled', true);
        $.ajax({
          type: 'GET',
          url: '/admin/testsession/associatedwritten',
          data: 'id=' + $('#testsession-practical_test_session_id').val(),
          async: false,
          success: function(data) {
            var jsonData = $.parseJSON(data);
            var valid = true;
            for (var i in jsonData['records']) {
              if (jsonData['records'][i] != id) {
                valid = false;
              }
            }
            if (valid == false) {
              if (
                confirm(
                  'This will remove the Practical Test Session (' +
                    jsonData['sessionNumber'] +
                    ') from its existing Written Test Session and associate it with this Written Test Session. Do you want to confirm?'
                )
              ) {
              } else {
                $('#testsession-practical_test_session_id').val('');
              }
            }
            $('.btn-submit-session').attr('disabled', false);
          }
        });
      }
    });
  }
  if ($('#testsession-school').length != 0) {
    $('#testsession-school').on('change', function() {
      var currentSchool = $('#session-form').data('current-school');
      var isNew = $('#session-form').data('is-new');
      var type = $('#session-form').data('type');

      if (type == 1 && isNew == 1) {
      } else if (currentSchool != $('#testsession-school').val() && $('#testsession-school').val() != '') {
        var message =
          'System detected you changed the school for this session, doing so would update any associated session as well, do you want to continue?';
        if ($('.btn-submit-session').hasClass('new')) {
          message = 'Saving this session would update any associated session school as well, do you want to continue?';
        }
        if (confirm(message)) {
          //$('#session-form').submit();
        } else {
          if (currentSchool != '') {
            $('#testsession-school').val(currentSchool);
          } else {
            $('#testsession-school').val('');
          }
        }
        return false;
      } else {
        //$('#session-form').submit();
      }
    });
  }
  //$('.btn-submit-session').on('click', validateTestSessionSchool);
  $('.resend-activation').on('click', function() {
    $.get(getValidUrl('/register/activation'), 'id=' + $(this).data('id'), function(data) {
      $('.alert')
        .removeClass('alert-danger')
        .addClass('alert-success')
        .html('Account Activation Email Sent');
    });
  });

  // need for the main nav to work with datepickers.
  $('.dropdown-toggle').dropdown();

  $('.terms-of-use-modal').on('click', function(evt) {
    evt.preventDefault();
    $('#myModal').modal('show');
  });

  $('#myModal').on('show.bs.modal', function(e) {
    $.post(getValidUrl('/register/terms'), function(data) {
      $('#myModal')
        .find('.modal-body')
        .html(data)
        .end()
        .find('.modal-header')
        .css({ border: 'none' })
        .end()
        .find('.modal-title')
        .remove()
        .end()
        .find('.modal-body')
        .css({ 'max-height': '500px', 'overflow-y': 'scroll' });
    });

    $('#myModal').on('shown.bs.modal', reposition);
  });

  /**
   *  Sorter popover in table lists
   */
  $('.sorter-link').on('click', function(evt) {
    evt.preventDefault();
    $(this)
      .popover({
        html: true,
        content: function() {
          return $($(this).next('.sorter-content')).html();
        }
      })
      .popover('show');
  });
  $('body').on('click', function(e) {
    var $popovers = $('.sorter-link');
    if (!($popovers.length > 0)) {
      return true;
    } // script loaded everywhere (uu bad) but we want only where we have sorter links
    $.each($popovers, function() {
      //the 'is' for buttons that trigger popups
      //the 'has' for icons within a button that triggers a popup
      if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
        $(this).popover('hide');
        $(this).popover('destroy');
      }
    });
  });
});

// slide Effect on the Registration Process
$.fn.fadeSlideShow = function(speed, dir, fn) {
  return $(this).show('fade', { direction: dir || 'right' }, speed || 400, function() {
    $.isFunction(fn) && fn.call(this);
  });
};
$.fn.fadeSlideHide = function(speed, dir, fn) {
  return $(this).hide('fade', { direction: dir || 'left' }, speed || 400, function() {
    $.isFunction(fn) && fn.call(this);
  });
};

function assignClass(testSessionId, candidateId) {
  var $modal = $('#genericModal');

  $modal
    .find('.modal-title')
    .html('Assign Class')
    .end()
    .find('.modal-footer')
    .hide();

  $.get('/admin/candidatesession/class', 'sId=' + testSessionId + '&i=' + candidateId, function(html) {
    $modal
      .find('.modal-body')
      .html(html)
      .end()
      .modal('show');

    $('.save-candidate-class').off('click');
    $('.save-candidate-class').on('click', function() {
      $('form#candidate-class-form .has-error').removeClass('has-error');
      if ($('select[name="CandidateTestSessionClassSchedule[testSessionClassScheduleId]"]').val() == '') {
        $('select[name="CandidateTestSessionClassSchedule[testSessionClassScheduleId]"]')
          .parent()
          .addClass('has-error');
      }

      if ($('form#candidate-class-form .has-error').length == 0) {
        //we submit
        $.post($('form#candidate-class-form').attr('action'), $('form#candidate-class-form').serialize(), function(
          html
        ) {
          var resp = $.parseJSON(html);
          if (resp.status == 1) {
            var f = new CM();
            f.success('Class Assigned Successfully');
            $modal.modal('hide');
          } else {
            var d = new CM();
            d.failure('Operation failed, please try again');
          }
        });
      }
    });
  });
}

function registerStepMinus() {
  var params = window.location.search,
    candidateId = $('.btn-register-back').data('candidate-id'),
    step = $('.btn-register-back').data('step');

  $.get(getValidUrl('/register/back') + params, 'candidateId=' + candidateId + '&step=' + step, function(data) {
    var jsonResp = $.parseJSON(data);
    $('.registration-wrapper')
      .html(jsonResp.html)
      .fadeSlideShow(250, 'left', Registration.setup());

    setupChooseLocation();

    if ($('#choose-location').val() != '') {
      $('#choose-location').trigger('change');
    }
    if ($('#candidates-birthday').length != 0) {
      $('#candidates-birthday').datepicker({
        startView: 2,
        autoclose: true,
        defaultViewDate: { year: 1990, month: 04, day: 25 }
      });
    }
  });
}

function registerStepPlus() {
  var params = window.location.search;

  $.post(getValidUrl('/register/info') + params, $('#register-form').serialize(), function(data) {
    var jsonResp = $.parseJSON(data);
    $('.registration-wrapper')
      .html(jsonResp.html)
      .fadeSlideShow(250, 'right', Registration.setup());
  });
}

function setupUserPayment() {
  var poNumberFormGroup = $('#form-po-number-group');
  var poNumberField = $('#form-po-number');
  var poNumberApplicable = $('#form-po-number-applicable');

  poNumberApplicable.change(function() {
    if (this.checked) {
      poNumberFormGroup.show();
      poNumberField.attr('required', true);
    } else {
      poNumberFormGroup.hide();
      poNumberField.attr('required', false);
    }
  });

  poNumberField.change(function(event) {
    $('input[name=x_poNumber]').val(event.target.value);
    $('input[name=poNumber]').val(event.target.value);
  });

  $('#crane-training-po-form').submit(function(event) {
    if (poNumberApplicable.prop('checked') && poNumberField.val() === '') {
      alert(
        'Please include a Purchase Order (PO) Number, or un-check the Apply Purchase Order (PO) checkbox in order to continue'
      );
      event.preventDefault();
    }
  });

  $('.btn-submit-form').on('click', function() {
    if (poNumberApplicable.prop('checked') && poNumberField.val() === '') {
      alert(
        'Please include a Purchase Order (PO) Number, or un-check the Apply Purchase Order (PO) checkbox in order to continue'
      );
      return;
    }

    if (
      $(this).data('amount') == $('#crane-training-payment-form').data('amount') &&
      $(this).data('amount') == $('input[name="x_amount"]').val()
    ) {
      $('#crane-training-payment-form').submit();
    } else {
      alert('System detected data tampering, can not continue');
    }
  });

  $('.apply-promo').on('click', function() {
    $(this)
      .parent()
      .removeClass('has-error');
    $(this)
      .parent()
      .find('.help-block')
      .html('');
    var promoCode = $('input[name="promoCode"]').val();
    $('.complete-payment-reminder').show();
    $('#payment-form').show();
    $('.apply-promo')
      .parent()
      .removeClass('has-error')
      .next('.help-block')
      .html('')
      .removeClass('has-error');
    $('.has-discount').hide();

    $.get(
      getValidUrl('/register/promo'),
      'school=' +
        $(this).data('school') +
        '&code=' +
        promoCode +
        '&amount=' +
        $('.original-price').data('price') +
        '&type=' +
        $('input[name="paymentOptions"]:checked').val() +
        '&deposit=' +
        $('input.deposit').data('amount'),
      function(data) {
        var resp = $.parseJSON(data);
        if (resp.status == 0) {
          //error
          $('.apply-promo')
            .parent()
            .addClass('has-error')
            .next('.help-block')
            .html('Invalid Promo Code')
            .addClass('has-error');
          $('#po-form').hide();
          $('#btn-payment-form').show();
          $('input[name="x_promo"]').val('');
          $('input[name="x_fp_timestamp"]').val(resp.timestamp);
          $('input[name="x_fp_sequence"]').val(resp.sequence);
          $('input[name="x_fp_hash"]').val(resp.fingerprint);
          $('input[name="x_amount"]').val(resp.amount);
          $('.btn-submit-form').data('amount', resp.amount);
          $('#crane-training-payment-form').data('amount', resp.amount);
          $('#po-section').fadeOut(250);
          $('#form-po-number').val('');
        } else {
          //we apply the promo code
          $('input[name="x_promo"]').val(promoCode);
          $('input[name="promoCode"]').val(promoCode);
          //we show the discount notation
          $('.original-price').html(accounting.formatMoney($('.original-price').data('price')));
          $('.discount-price').html(accounting.formatMoney(resp.discount));

          $('.has-discount').fadeIn();

          var totalPrice = parseFloat($('.original-price').data('price')) - resp.discount;

          $('.total-price .total-value').html(accounting.formatMoney(resp.amount));

          $('input[name="x_amount"]').val(totalPrice);
          $('.btn-submit-form').data('amount', totalPrice);
          $('#crane-training-payment-form').data('amount', totalPrice);
          if (resp.isPurchaseOrder == 1 || resp.isFullDiscount == 1) {
            if (resp.isPurchaseOrder) {
              $('#po-section').fadeIn();
            }
            $('.complete-payment-reminder').hide();
            $('input[name="isFullDiscount"]').val(resp.isFullDiscount);
            $('#btn-payment-form').hide();
            $('#payment-form').fadeOut(250, function() {
              $('#po-form').fadeIn();
            });
          } else {
            $('#po-section').fadeOut(250);
            $('#form-po-number').val('');
            $('#po-form').fadeOut(250, function() {
              $('#payment-form').fadeIn();
              $('.has-PO').hide();
            });
            $('#btn-payment-form').show();
            $('input[name="x_fp_timestamp"]').val(resp.timestamp);
            $('input[name="x_fp_sequence"]').val(resp.sequence);
            $('input[name="x_fp_hash"]').val(resp.fingerprint);
            $('input[name="x_amount"]').val(resp.amount);

            $('.btn-submit-form').data('amount', resp.amount);
            $('#crane-training-payment-form').data('amount', resp.amount);
          }
        }
      }
    );
  });

  $('input[name="paymentOptions"]').on('change', function() {
    var promoCode = $('input[name="promoCode"]').val();
    $.get(
      getValidUrl('/register/deposit'),
      'school=' +
        $('.apply-promo').data('school') +
        '&code=' +
        promoCode +
        '&amount=' +
        $(this).data('amount') +
        '&type=' +
        $('input[name="paymentOptions"]:checked').val(),
      function(data) {
        var resp = $.parseJSON(data);

        //we apply the promo code
        //$('input[name="x_promo"]').val(promoCode);
        //$('input[name="promoCode"]').val(promoCode);

        $('input[name="x_fp_timestamp"]').val(resp.timestamp);
        $('input[name="x_fp_sequence"]').val(resp.sequence);
        $('input[name="x_fp_hash"]').val(resp.fingerprint);
        $('input[name="x_amount"]').val(resp.amount);

        $('.btn-submit-form').data('amount', resp.amount);
        $('#crane-training-payment-form').data('amount', resp.amount);
      }
    );
  });
}

var getValidator = () =>  ({
  state: {},
  val: '',
  rawVal: '',
  rools: {},
  setState: function (state) {
    this.state = {...this.state, ...state};
  },
  setRools: function (initRoolsFunc) { 
    this.rools = initRoolsFunc(this);
  },
  setValue: function (val) {
    this.rawVal = val;
    this.val = this.sanitise(val);
  },
  sanitise: function (val) {
    return val.replaceAll(/^ */g, '').replaceAll(/ *$/g, '').replaceAll(/[ ]+/g,' ');
  },
  required: function () {
    return this.val !== '';
  },
  requiredCondition: function (cond) {
    if (cond) {
      return this.required;
    }
    return () => true;
  },
  isNotMatch: function (getValue) {
    const isNotMatch = () => this.val.toLowerCase() !== this.sanitise(getValue()).toLowerCase();
    return isNotMatch;
  },
  isMatch: function (getValue) {
    const isMatch = () => !this.isNotMatch(getValue)();
    return isMatch;
  },
  hasFormat: function (format) {
    const hasFormat = () => this.val === '' || format.test(this.rawVal);
    return hasFormat;
  },
  moreThen: function (val1, val2) {
    const moreThen = () => val1 > val2;
    return moreThen;
  },
  
  validateAll: function (errorHandler, succedHandler) {
    Object.entries(this.state).forEach((el) => {
      this.validate(el, errorHandler, succedHandler);
    });
  },

  validate: function (el, errorHandler, succedHandler) {
    key = el[0];
    val = el[1];
    if (key in this.rools) {
      this.setValue(val);
      let rools = this.rools[key]
      if (typeof rools === 'function') {
        rools = rools();
      }
      const res = rools.map((rool) => rool())
      if (res.every(el => el)) {
        succedHandler(key, val);
      } else {
        const reason = rools[res.indexOf(false)].name;
        errorHandler(key, val, reason);
      }
    }
  }
});

var Registration = {
  submitForms: function() {
    if ($('form .has-error').length == 0) {
      $('.registration-wrapper').fadeSlideHide(250, 'left', registerStepPlus);
    } else {
      window.scrollTo({
        left: 0,
        top: $('form .has-error')[0].offsetTop,
        behavior: 'smooth'
      });
    }
  },
  setup: function() {
    $('#register-form').on('submit', function(evt) {
      evt.preventDefault();
      return false;
    });

    const validator = getValidator();
    const getErrorMessage = (key, reason) => {
      const messages = {
        "first_name": "Field is required.",
        "last_name": "Field is required.",
        "birthday": "Field is required.",
        "birthday_": "Field is required.",
        "phone": "Field is required.",
        "email": "Field is required.",
        "confirmEmail": "Email does not match.",
        "address": "Field is required.",
        "city": "Field is required.",
        "state": "Field is required.",
        "zip": "Field is required.",
        "cco_id": "Field is required.",
        "is_company_sponsored": "Field is required.",
        "company_name": "Field is required.",
        "company_fax": "Field is required.",
        "company_phone": "Field is required.",
        "company_address_required": "Field is required.",
        "company_address_isNotMatch": "Company Address should not be the same as the Home Address.",
        "company_city": "Field is required.",
        "company_state": "Field is required.",
        "company_zip": "Field is required.",
        "contactEmail": "Field is required and should not be the same as the candidate email",
        "contactEmail_isNotMatch": "Contact email should not be the same as the candidate email.",
      };

      const reasonKey = `${key}_${reason}`;
      if (reasonKey in messages) {
        return messages[reasonKey];
      } else if (key in messages) {
        return messages[key];
      }

      return '';
    };

    const formErrorHandler = (key, value, reason) => {
      const name = `Candidates[${key}]`;
      const field = $(`[name='${name}']`);
      const errorMessage = getErrorMessage(key, reason);
      field.parent().parent().addClass('has-error');
      // field.parent().parent().removeClass('has-success');
      field.parent().find('.help-block').html(errorMessage);
      console.log('ERROR: ', key, reason, value, errorMessage);
    };

    const formSuccessHandler = (key, value) => {
      console.log('Success: ', key, value);
      const name = `Candidates[${key}]`;
      const field = $(`[name='${name}']`);
      // field.parent().parent().addClass('has-success');
      field.parent().parent().removeClass('has-error');
      field.parent().find('.help-block').html('');
    };

    const formGetState = () => {
      const state = {};
      $('#register-form').find('input, select').map((i, el) => {
        let name = el.name.replace('Candidates[', '').replace(']', '');
        let val = el.value;
        if (name === "application_type_id") {
          name = 'isRecert';
          val = $(el).data('is-recert');
        }
        
        state[name] = val;
      })
      
      return {...state};
    };

    const formAddListeners = (validatorObj) => {
      $('#register-form').find('input, select').map((i, el) => {
        const eventName = el.id === 'candidates-birthday'? 'change' : 'blur';
        $(el).on(eventName, (event) => {
          let name = event.target.name.replace('Candidates[', '').replace(']', '');
          let val = event.target.value;
          if (name === "application_type_id") {
            name = 'isRecert';
            val = $(el).data('is-recert');
          }
          const state = formGetState();
          validatorObj.setState(state);
          console.log('Changed', name, event.target.value, validatorObj.state[name]);
          validatorObj.validate([name, val], formErrorHandler, formSuccessHandler);
        });
      });
    };

    const formGetRools = (obj) => {
      const emailFormat = /^[a-z0-9\-._]+@[a-z0-9\-._]+\.[a-z]+$/i;
      const phoneFormat = /^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/;
      const dateFormat = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
      const moreThen = () => calculateAge(...obj.state.birthday.split('/')) > 17;
      return {
        "first_name": [obj.required],
        "last_name": [obj.required],
        "birthday": [obj.required, obj.hasFormat(dateFormat), moreThen],
        "phone": [obj.required, obj.hasFormat(phoneFormat)],
        "email": [obj.required, obj.hasFormat(emailFormat)],
        "confirmEmail": [obj.isMatch(() => obj.state.email)],
        "address": [obj.required],
        "city": [obj.required],
        "state": [obj.required],
        "zip": [obj.required],
        "cco_id": [obj.required],
        "is_company_sponsored": [obj.required],
        "company_name": () => obj.state.is_company_sponsored === '1'? [obj.required] : [],
        "company_phone": () => obj.state.is_company_sponsored === '1'? [obj.required, obj.hasFormat(phoneFormat)] : [],
        "company_address": () => obj.state.is_company_sponsored === '1'
          ? [obj.required, obj.isNotMatch(() => obj.state.address)] 
          : [],
        "company_city": () => obj.state.is_company_sponsored === '1'? [obj.required] : [],
        "company_state": () => obj.state.is_company_sponsored === '1'? [obj.required] : [],
        "company_zip": () => obj.state.is_company_sponsored === '1'? [obj.required] : [],
        "contactEmail": () => obj.state.is_company_sponsored === '1'
          ? [obj.required, obj.hasFormat(emailFormat), obj.isNotMatch(() => obj.state.email)] 
          : [],
      };
    };

    validator.setState(formGetState());
    validator.setRools(formGetRools);
    formAddListeners(validator);

    $('.btn-register').on('click', function(event) {
      validator.setState(formGetState());
      validator.validateAll(formErrorHandler,formSuccessHandler);

      setTimeout(Registration.submitForms, 300);
    });
    $('.btn-register-back').on('click', function(evt) {
      evt.preventDefault();
      var candidateId = $(this).data('candidate-id');
      var step = $(this).data('step');
      $('.registration-wrapper').fadeSlideHide(250, 'right', registerStepMinus);
    });
  }
};

//$.fn.fadeSlideRight = function(speed,fn) {
//    return $(this).animate({
//        'opacity' : 1,
//        //'width' : '750px'
//    },speed || 400, function() {
//        $.isFunction(fn) && fn.call(this);
//    });
//};
//
//$.fn.fadeSlideLeft = function(speed,fn) {
//    return $(this).animate({
//        'opacity' : 0,
//        //'width' : '0px'
//    },speed || 400,function() {
//        $.isFunction(fn) && fn.call(this);
//    });
//}
var setupChooseLocation = function() {
  $('#choose-location').on('change', function() {
    var additionalParams = '';
    if ($(this).data('referral-code') != '') additionalParams = '&referralCode=' + $(this).data('referral-code');
    if ($(this).data('unique-code') != '') additionalParams += '&uniqueCode=' + $(this).data('unique-code');

    var selectedval = $(this).val(),
      typeId = $(this).data('app-type-id');

    $('.available-sessions').fadeOut(250, function() {
      var s_ttl, s_val;

      if (selectedval == '') {
        (s_ttl = 'Choose Your Class Location') /* if changed change as well in partial _titles */,
          //s_val = 'Step 1 of 5';
          $('.step-1')
            .addClass('step-current')
            .removeClass('step-previous');
        $('.step-2').removeClass('step-current');
      } else {
        $('#spinner').css('display', 'block');
        $.get(
          getValidUrl('/register/sessions'),
          'candidateId=' +
            $('#choose-location').data('candidate-id') +
            '&appTypeId=' +
            typeId +
            '&testSiteId=' +
            $('#choose-location').val() +
            additionalParams,
          function(html) {
            $('#spinner').css('display', 'none');
            $('.available-sessions')
              .html(html)
              .fadeIn();
          }
        );
        (s_ttl = 'Choose Your Class Dates'),
          //s_val = 'Step 2 of 5';
          $('.step-1')
            .addClass('step-previous')
            .removeClass('step-current');
        $('.step-2').addClass('step-current');
      }
      $('.step-title').html(s_ttl);
      $('.step-value').html(s_val);
    });
  });

  if ($('#choose-location').val() != '') {
    $('#choose-location').trigger('change');
  }
};

var SelectSession = {
  choose: function(id, sessionId, transferType, bothTestSessions) {
    var isRetake = 0;
    if ($('.test-session-index').length == 1) {
      isRetake = $('.test-session-index').data('is-retake');
    }
    $.get(
      '/admin/candidates/select',
      'id=' +
        id +
        '&i=' +
        sessionId +
        '&isRetake=' +
        isRetake +
        '&transferType=' +
        transferType +
        '&bothTestSessions=' +
        bothTestSessions,
      function(html) {
        $('.popover').hide();
        var $modal = $('#reminder-modal');
        $modal.find('.modal-title').html('Add Student to Session');
        $modal
          .find('.modal-body')
          .html(html)
          .end()
          .modal('show');
      }
    );
  }
};

$(function() {
  messaging.prototype.updateUnreadMessageBadgeNav = function() {
    if ($('#current-user-key').length != 0) {
      $.ajax({
        type: 'GET',
        url: $('#messaging-url').val() + 'unread',
        data: 'currentUserKey=' + $('#current-user-key').val(),
        success: function(jsonData) {
          if (jsonData.count > 0) {
            $('.inbox-badge').html(jsonData.count);

            var m = jsonData.count > 1 ? ' new messages' : ' new message';

            $('.info-badge-wrapper')
              .show()
              .parents('a')
              .attr('title', 'Inbox <br/>' + jsonData.count + m);
            $('.mail-notice').addClass('has-unread');
          } else {
            $('.mail-notice').removeClass('has-unread');
          }
          $('.mail-notice a').tooltip({ html: true });
        }
      });
    }
  };

  var msg = new messaging();

  msg.updateUnreadMessageBadgeNav();

  $('#candidates-birthday').datepicker({
    startView: 2,
    autoclose: true,
    defaultViewDate: { year: 1990, month: 04, day: 25 }
  });

  Registration.setup();

  setupChooseLocation();

  $('input[name="paymentAmount"]').maskMoney({ thousands: '', decimal: '.' });

  if ($('input[name="photo-type"]').length != 0) {
    $('input[name="photo-type"][value="upload"]').attr('checked', true);
    $('.upload-section').show();
    $('.web-cam-section').hide();
  }
  $('input[name="Candidates[confirmEmail]"]').on('blur', function() {
    $(this)
      .parent()
      .removeClass('has-error');
    $(this)
      .parent()
      .find('.help-block')
      .html('');
    if ($(this).val() != $('input[name="Candidates[email]"]').val()) {
      $(this)
        .parent()
        .addClass('has-error');
      $(this)
        .parent()
        .find('.help-block')
        .html('Email does not match');
    }
  });

  $('.input-daterange').datepicker({
    autoclose: true,
    todayHighlight: true,
    startDate: 'dateToday',
    orientation: 'bottom'
  });

  $('.btn-update-candidate').on('click', function() {
    if (!$.isEmptyObject(changes)) {
      $('#confirm-changes-modal').modal('show');
      $('#confirm-changes-modal-body').html('<ul>' + generateConfirmChangesInnerHtml(changes) + '</ul>');

      $('#btn-confirm-changes').on('click', function() {
        $('#confirm-changes-modal').modal('hide');
        AddCandidate();
      });
    } else {
      AddCandidate();
    }
  });

  $('input[name="photo-type"]').on('change', function() {
    if ($(this).val() == 'webcam') {
      $('.web-cam-section').show();
      $('.upload-section').hide();
      Webcam.attach('#my_camera');
    } else {
      $('.upload-section').show();
      $('.web-cam-section').hide();
    }
  });

  $('.generate-cert').on('click', function() {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Certificate Details');
    var candidateId = $(this).data('candidate-id');
    $.get('/admin/reports/readiness-param', '', function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
      $('.btn-generate-readiness-report-now').on('click', function() {
        var hasError = false;
        if ($('#instructor-name').val() == '') {
          $('#instructor-name')
            .parents('.form-group')
            .addClass('has-error');
          hasError = true;
        }
        if ($('#certificate-date').val() == '') {
          $('#certificate-date')
            .parents('.form-group')
            .addClass('has-error');
          hasError = true;
        }
        if (hasError == false) {
          NProgress.configure({ parent: '.modal-body' });
          NProgress.start();

          $.post(
            '/admin/candidates/generate-certs',
            'id=' +
              candidateId +
              '&certDate=' +
              encodeURIComponent($('#certificate-date').val()) +
              '&certInstructor=' +
              encodeURIComponent($('#instructor-name').val()),
            function(resp) {
              NProgress.done();
              $('#reminder-modal').modal('hide');
              var data = resp;

              if (data.status == 1) {
                $.alert({
                  title: 'Generate Certificate',
                  content: 'Certificate Generation Successful',
                  confirmButtonClass: 'btn-primary',
                  confirmButton: 'Close'
                });
              } else {
                $.alert({
                  title: 'Generate Certificate',
                  content: 'Error, Please try again.',
                  confirmButtonClass: 'btn-primary',
                  confirmButton: 'Close'
                });
              }

              setTimeout(function() {
                window.location.reload();
              }, 2000);
            }
          );
        }
      });
    });
  });

  $('select[name="reportType"]').on('change', Report.changeReportType);
  if ($('select[name="reportType"]').length > 0 && $('select[name="reportType"]').val() != '') {
    Report.generateReport(false);
    Report.setupUI();
  }
  $('.add-reminder').on('click', function() {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Add Reminders');

    $.get('/admin/reminders/create', '', function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');

      $('.btn-add-reminder').on('click', function() {
        var $modalBody = $modal.find('.modal-body'),
          formControls = $modalBody.find('input[type=text], textarea'),
          hasErrors = false;

        $modalBody.find('.has-error').removeClass('has-error');

        $.each(formControls, function() {
          var control = $(this);
          if ($.trim(control.val()) == '') {
            control.parent().addClass('has-error');
            hasErrors = true;
          }
        });

        if (!hasErrors) {
          $.post('/admin/reminders/create', $('form#reminder-form').serialize(), function(html) {
            $modalBody.find('.alert').slideDown();
            if ($('.reminder-panel-body').length > 0) {
              $.get('/admin/reminders/viewpage', 'page=1&userId=' + $('.reminder-pagination').data('user-id'), function(
                html
              ) {
                $('.reminder-panel-body').html(html);
                setupRemindersUi();
              });
            }
            Widgets.refresh();
            setTimeout(function() {
              $modal.modal('hide');
            }, 1500);
          });
        }
      });
    });
  });

  $('.add-charge').on('click', function() {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Add Student Charge');

    $.get('/admin/candidates/charge', 'candidateId=' + $(this).data('candidate-id'), function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
      $('.btn-add-charge').on('click', function() {
        var $modalBody = $modal.find('.modal-body');
        var formControls = $modalBody.find('input[type=text]');
        var hasErrors = false;

        $modalBody.find('.has-error').removeClass('has-error');

        $.each(formControls, function() {
          var control = $(this);
          if ($.trim(control.val()) == '') {
            control.parent().addClass('has-error');
            hasErrors = true;
          }
        });

        if ($('#candidateTransactionsChargeType').val() === '50') {
          var craneField = $('.field-candidatetransactions-retest_crane_selection');
          if (craneField.find('input[type=radio]:checked').length === 0) {
            craneField.addClass('has-error');
            craneField.children('.help-block').html('Crane selection is required if charging a Practical Retest Fee.');
            hasErrors = true;
          }
        }

        if ($('#candidatetransactions-amount').val() === '0.00') {
          var amountField = $('.field-candidatetransactions-amount');
          amountField.addClass('has-error');
          amountField.children('.help-block').html('Cannot add a charge worth $0.00.');
          hasErrors = true;
        }

        if (!hasErrors) {
          $.post('/admin/candidates/charge', $('form#charge-form').serialize(), function(html) {
            $modalBody.find('.alert').slideDown();
            setTimeout(function() {
              $modal.modal('hide');
              window.location.reload();
            }, 800);
          });
        }
      });
    });
  });

  $('.add-refund').on('click', function() {
    var $modal = $('#reminder-modal');

    $modal.find('.modal-title').html('Add Student Refund');

    $.get('/admin/candidates/refund', 'candidateId=' + $(this).data('candidate-id'), function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');
      $('.btn-add-refund').on('click', function() {
        var $modalBody = $modal.find('.modal-body'),
          formControls = $modalBody.find('input[type=text]'),
          hasErrors = false;

        $modalBody.find('.has-error').removeClass('has-error');

        $.each(formControls, function() {
          var control = $(this);
          if ($.trim(control.val()) == '') {
            control.parent().addClass('has-error');
            hasErrors = true;
          }
        });

        if (
          $('#refund-form input#candidatetransactions-amount').val() == '' ||
          isNaN(parseFloat($('#refund-form input#candidatetransactions-amount').val())) ||
          parseFloat($('#refund-form input#candidatetransactions-amount').val()) >
            parseFloat($('.btn-add-refund').data('allowed-refund'))
        ) {
          $('#refund-form input#candidatetransactions-amount')
            .parent()
            .addClass('has-error');
          $('#refund-form input#candidatetransactions-amount')
            .parent()
            .find('.help-block')
            .html(
              'Allowable refund for this student is only ' +
                accounting.formatMoney($('.btn-add-refund').data('allowed-refund'))
            );
          hasErrors = true;
        }
        if (!hasErrors) {
          $.post('/admin/candidates/refund', $('form#refund-form').serialize(), function(html) {
            $modalBody.find('.alert').slideDown();
            window.location.reload();
          });
        }

        $('#close-refund-confirm').click(function() {
          $('#refund-confirm-modal').modal('hide');
          window.location.href = window.location;
        });
      });
    });
  });

  $('.add-payment').on('click', function() {
    CandidatePayment.payment($(this).data('candidate-id'), true);
  });
  $('.remove-charge').on('click', function() {
    CandidatePayment.removeCharge($(this).data('candidate-id'), true);
  });

  setupRemindersUi();

  $('.remove-from-session').on('click', function(e) {
    e.preventDefault();
    var elem = $(this);
    var candidateId = elem.data('candidate-id');
    var sessionId = elem.data('session-id');

    $.confirm({
      title: 'Cancel Candidate Session',
      content:
        'Are you sure you want to cancel user from the session?<br />' +
        "<div class='row col-xs-12'>" +
        "<h3>Remarks:</h3><textarea rows='5' style='width: 100%' id='move-notes' placeholder='Please add remarks here'></textarea>" +
        '</div>',
      confirmButton: 'Yes, Remove',
      cancelButton: 'No, Keep it',
      confirm: function() {
        $('#move-notes')
          .parent()
          .removeClass('has-error');
        if ($('#move-notes').val() == '') {
          $('#move-notes')
            .parent()
            .addClass('has-error');
          return false;
        } else {
          $.post(
            '/admin/candidates/removefromsession',
            'id=' + candidateId + '&sessionId=' + sessionId + '&remarks=' + $('#move-notes').val(),
            function() {
              elem
                .parent()
                .parent()
                .find('div.remove-session-section')
                .html('Not Enrolled');
              window.location.reload();
            }
          );
        }
      }
    });
  });

  $('.select-session').on('click', function(e) {
    e.preventDefault();

    if ($('.btn-convert-to-complete').length != 0) {
      $.alert({
        title: 'Incomplete Application',
        content: 'Please convert the application to complete before you can select a session',
        confirmButtonClass: 'btn-primary',
        confirmButton: 'Close'
      });
    } else {
      var elem = $(this);
      var candidateId = elem.data('md5-candidate-id');
      var sessionType = elem.data('type');

      window.location.href =
        '/admin/testsession?TestSessionSearch[session_type]=' +
        sessionType +
        '&session_type=' +
        sessionType +
        '&candidateId=' +
        candidateId;
    }
  });
  $(document).on('click', '.schedule-retake', function() {
    var elem = $(this);
    var candidateId = elem.data('md5-candidate-id');
    var sessionType = elem.data('type');
    $.confirm({
      title: 'Schedule Retake',
      content: 'Do you really want to schedule a Retake for this Student?',
      confirmButton: 'Yes, Schedule Retake',
      cancelButton: 'No, Cancel',
      confirm: function() {
        window.location.href =
          '/admin/testsession?TestSessionSearch[session_type]=' +
          sessionType +
          '&session_type=' +
          sessionType +
          '&candidateId=' +
          candidateId +
          '&retake=1';
      }
    });
  });

  /* Applications PASS/FAIL Sessions */
  $(document).on('click', '.grade-a-session', function(e) {
    e.preventDefault();
    var el = $(this);
    var sessionId = el.data('test-session-id');
    var candidateId = el.data('candidate-id');

    e.preventDefault();
    var staffId = $(this).data('staffid');

    $.get('/admin/candidates/grade-session', 'id=' + candidateId + '&testSessionId=' + sessionId, function(data) {
      var resp = $.parseJSON(data);

      console.log(resp);

      var $modal = $('#reminder-modal');
      $modal.find('.modal-title').html('Grade Session');
      $modal
        .find('.modal-body')
        .html('List of Cranes: <br /><br />' + resp.html)
        .end()
        .modal('show');
      $('.btn-save-grade-session').off('click');
      $('.btn-save-grade-session').on('click', function() {
        var params = [];

        params.push({ name: 'testSessionId', value: sessionId });
        params.push({ name: 'id', value: candidateId });
        $('td.required').removeClass('required');

        $('td.pass-fail-cranes').each(function() {
          if (
            $('input[data-key="' + $(this).data('key') + '"][value=1]').is(':checked') ||
            $('input[data-key="' + $(this).data('key') + '"][value=0]').is(':checked') ||
            $('input[data-key="' + $(this).data('key') + '"][value=2]').is(':checked') ||
            $('input[data-key="' + $(this).data('key') + '"][value=3]').is(':checked')
          ) {
            params.push({
              name:
                $(this).data('key') +
                ':::::' +
                $(this).data('name') +
                ':::::' +
                $('input[data-key="' + $(this).data('key') + '"]:checked').val(),
              value: $('input[data-key="' + $(this).data('key') + '"][value=1]').is(':checked')
            });
          } else {
            $(this).addClass('required');
          }
        });

        if ($('td.required').length == 0) {
          $.confirm({
            title: 'Grade Confirm',
            content: 'Are you sure you want to save the grades for the cranes?',
            confirmButton: 'Yes',
            cancelButton: 'No, Cancel',
            confirm: function() {
              $.post('/admin/candidates/save-grade-session', params, function(data) {
                var resp = $.parseJSON(data);
                if (resp.status == 1) {
                  if ($('.report-results').length == 0) window.location.reload();
                  else {
                    var f = new CM();
                    f.success('Crane Graded Successfully');
                    //$('.closeIcon').trigger('click')
                    $modal.find('.modal-body').modal('hide');
                    $('.btn-generate-report').trigger('click');
                  }
                } else {
                  var d = new CM();
                  d.failure('Operation failed, please try again');
                }
              });
              $modal.modal('hide');
            }
          });
        }
      });
    });
  });

  //for registration

  $('select[name="Candidates[survey]"]').on('change', surveyChange);
  surveyChange();
  setupUserPayment();
  //$('.phone').mask("(999) 999-9999");
  $('#candidates-birthday').datepicker({
    startView: 2,
    autoclose: true,
    defaultViewDate: { year: 1990, month: 04, day: 25 }
  });
  if ($('.receipt-archive').length == 1) {
    $('[data-toggle="popover"]').popover({ trigger: 'hover' });
    $('.receipt-filter').on('change', function() {
      $.get(
        '/admin/testsession/receipt-filter',
        'id=' + $('.receipt-filter').data('id') + '&date=' + $(this).val(),
        function(html) {
          $('.receipt-archive').html(html);
          $('[data-toggle="popover"]').popover({ trigger: 'hover' });
        }
      );
    });
  }
  //$('[data-toggle="tooltip"]').tooltip();
});
function surveyChange() {
  if ($('select[name="Candidates[survey]"]').val() == 'Other') {
    $('.survey-other').slideDown();
  } else {
    $('.survey-other').slideUp();
    $('textarea[name="Candidates[surveyOther]"]').val('');
  }

  if ($('select[name="Candidates[survey]"]').val() == 'Ad (Online)') {
    $('.field-candidates-ad_online_info').show();
  } else {
    $('.field-candidates-ad_online_info').hide();
    $('#candidates-ad_online_info').val('');
  }
  if ($('select[name="Candidates[survey]"]').val() == 'Heard from a friend') {
    $('.field-candidates-friend_email').show();
  } else {
    $('.field-candidates-friend_email').hide();
    $('#candidates-friend_email').val('');
  }
}
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test($email);
}
var setupRemindersUi = function() {
  listLinkActions();
  if ($('.reminder-pagination').length != 0) {
    // init bootpag
    $('.reminder-pagination')
      .bootpag({
        total: $('.reminder-pagination').data('total-pages'),
        page: $('.reminder-pagination').data('current-page'),
        maxVisible: 10
      })
      .on('page', function(event, /* page number here */ num) {
        $.get(
          '/admin/reminders/viewpage',
          'page=' + num + '&userId=' + $('.reminder-pagination').data('user-id'),
          function(html) {
            //$('.reminder-panel-body').html(html);
            $('#genericModal .modal-body').html(html);
            setupRemindersUi();
          }
        );
      });
  }
  if ($('.session-photo-pagination').length != 0) {
    // init bootpag
    $('.session-photo-pagination')
      .bootpag({
        total: $('.session-photo-pagination').data('total-pages'),
        page: $('.session-photo-pagination').data('current-page'),
        maxVisible: 10
      })
      .off('page')
      .on('page', function(event, /* page number here */ num) {
        $.get(
          '/admin/testsession/view-page-photo',
          'page=' +
            num +
            '&filter[fromDate]=' +
            encodeURI($('.session-photo-pagination').data('from-date')) +
            '&filter[toDate]=' +
            encodeURI($('.session-photo-pagination').data('to-date')) +
            '&filter[testSessionId]=' +
            $('.session-photo-pagination').data('test-session-id'),
          function(html) {
            //$('.reminder-panel-body').html(html);
            $('.session-photos-panel-body').html(html);
            setupRemindersUi();
          }
        );
      });
  }
  if ($('.receipt-pagination').length != 0) {
    // init bootpag
    $('[data-toggle="popover"]').popover({ trigger: 'hover' });

    $('.receipt-pagination')
      .bootpag({
        total: $('.receipt-pagination').data('total-pages'),
        page: $('.receipt-pagination').data('current-page'),
        maxVisible: 10
      })
      .off('page')
      .on('page', function(event, /* page number here */ num) {
        $.get(
          '/admin/testsession/view-receipt',
          'page=' +
            num +
            '&filter[fromDate]=' +
            encodeURI($('.session-photo-pagination').data('from-date')) +
            '&filter[toDate]=' +
            encodeURI($('.session-photo-pagination').data('to-date')),
          function(html) {
            $('.receipts-panel-body').html(html);
            $('[data-toggle="popover"]').popover({ trigger: 'hover' });
          }
        );
      });
  }
  if ($('.discrepancy-pagination').length != 0) {
    // init bootpag
    $('.discrepancy-pagination')
      .bootpag({
        total: $('.discrepancy-pagination').data('total-pages'),
        page: $('.discrepancy-pagination').data('current-page'),
        maxVisible: 10
      })
      .on('page', function(event, /* page number here */ num) {
        $.get(
          '/admin/checklist/viewpage',
          'page=' + num + '&userId=' + $('.reminder-pagination').data('user-id'),
          function(html) {
            //$('.discrepancy-panel-body').html(html);
            $('#genericModal .modal-body').html(html);
            setupRemindersUi();
          }
        );
      });
  }
  if ($('.inbox-pagination').length != 0) {
    var el = $('.inbox-pagination');
    // init bootpag
    el.bootpag({
      total: el.data('total-pages'),
      page: el.data('current-page'),
      maxVisible: 10
    }).on('page', function(event, /* page number here */ num) {
      var isInDashboard = 0;
      if ($('.home-index').length != 0) {
        //means we are in dashboard
        isInDashboard = 1;
      }
      $.get(
        '/admin/messaging/viewpage',
        'inDashboard=' + isInDashboard + '&page=' + num + '&userId=' + $('.inbox-pagination').data('user-id'),
        function(html) {
          //$('.inbox-panel-body').html(html);
          $('#genericModal .modal-body').html(html);
          setupRemindersUi();
        }
      );
    });
  }

  if ($('.session-pagination').length != 0) {
    var el = $('.session-pagination');
    // init bootpag
    el.bootpag({
      total: el.data('total-pages'),
      page: el.data('current-page')
    })
      .off('page')
      .on('page', function(event, /* page number here */ num) {
        $.get(
          '/admin/testsession/viewpage',
          'page=' + num + '&testSiteId=' + $('.session-pagination').data('test-site-id'),
          function(html) {
            $('.session-panel-body').html(html);
            //$('#genericModal .modal-body').html(html);
            setupRemindersUi();
          }
        );
      });
  }

  if ($('.incomplete-pagination').length != 0) {
    var el = $('.incomplete-pagination');
    // init bootpag
    el.bootpag({
      total: el.data('total-pages'),
      page: el.data('current-page'),
      maxVisible: 10
    }).on('page', function(event, /* page number here */ num) {
      $.get('/admin/candidates/viewpage', 'page=' + num, function(html) {
        $('#genericModal .modal-body').html(html);
        setupRemindersUi();
      });
    });
  }

  if ($('.failed-pagination').length != 0) {
    var el = $('.failed-pagination');
    // init bootpag
    el.bootpag({
      total: el.data('total-pages'),
      page: el.data('current-page'),
      maxVisible: 10
    }).on('page', function(event, /* page number here */ num) {
      $.get('/admin/checklist/viewfailedpage', 'page=' + num, function(html) {
        $('#genericModal .modal-body').html(html);
        setupRemindersUi();
      });
    });
  }
  if ($('.recent-pagination').length != 0) {
    // init bootpag
    $('.recent-pagination')
      .bootpag({
        total: $('.recent-pagination').data('total-pages'),
        page: $('.recent-pagination').data('current-page'),
        maxVisible: 10
      })
      .on('page', function(event, /* page number here */ num) {
        $.get(
          '/admin/candidates/recentviewpage',
          'page=' + num + '&time=' + $('#recent-application-time2').val(),
          function(html) {
            //$('.recent-panel-body').html(html);
            $('#genericModal .modal-body').html(html);
            setupRemindersUi();
          }
        );
      });
  }

  //dashboard V1 only
  $('#recent-application-time').on('change', function() {
    $.get('/admin/candidates/recentviewpage', 'page=1&time=' + $('#recent-application-time').val(), function(html) {
      $('.recent-panel-body').html(html);
      setupRemindersUi();
    });
  });

  $(document).off('change', '#recent-application-time2');
  $(document).on('change', '#recent-application-time2', function() {
    $.get('/admin/candidates/recentviewpage', 'page=1&time=' + $('#recent-application-time2').val(), function(html) {
      $('#genericModal')
        .find('.widget-item-modal-content')
        .html(html);
      $('#genericModal').modal('show');
      setupRemindersUi();
    });
  });

  $('.inbox-info').off('click');
  $('.inbox-info').on('click', function() {
    window.location.href = '/admin/messaging?id=' + $(this).data('id');
  });

  //$('.reminder-info').off('click');
  $('.reminder-info').on('click', function() {
    $('#reminder-modal .modal-title').html('View Reminders');
    $.get('/admin/reminders/view', 'id=' + $(this).data('id'), function(html) {
      $('#reminder-modal .modal-body').html(html);
      $('#reminder-modal').modal('show');
      setupRemindersUi();
    });
  });

  //$('.btn-mark-as-complete').off('click');
  $('.btn-mark-as-complete').on('click', function() {
    $.post('/admin/reminders/markcomplete', 'id=' + $(this).data('id'), function(html) {
      $.get('/admin/reminders/viewpage', 'page=1&userId=' + $('.reminder-pagination').data('user-id'), function(html) {
        $('#genericModal .modal-body').html(html);
        setupRemindersUi();
      });
      $('#reminder-modal')
        .find('.alert')
        .slideDown();
      Widgets.refresh();
      setTimeout(function() {
        $('#reminder-modal').modal('hide');
      }, 1000);
    });
  });
};

/* *******-----------/ \----------- *********** */

/**
 *   Check if a phone number validates (xxx) xxx-xxxx with regex
 *
 *   note: moved out of DOM Ready as some caller is outside.
 *
 *  @method checkPhoneHasAllNumbers
 *
 *  @param jQuery Obj
 *  */
var checkPhoneHasAllNumbers = function(el) {
  var phoneFormat = new RegExp(/\([0-9]{3}\)\s[0-9]{3}-[0-9]{4}/);
  var v = el.val();
  if (phoneFormat.test(v)) {
    el.parents('.form-group').removeClass('has-error');
  } else {
    el.parents('.form-group').addClass('has-error');
  }
};

/* LET TRY TO CLEAN THE APP.JS */

/* wrap in anonymous function for namespacing & jquery no conflict*/
(function($) {
  $(function() {
    $('.session-date-picker')
      .datepicker({
        autoClose: true,
        format: 'mm/dd/yyyy'
      })
      .on('changeDate', function(ev) {
        $(this).datepicker('hide');
        Roster.attendance(rosterId, testSessionId);
      });
    $('.session-photo-date-picker')
      .datepicker({
        autoClose: true,
        format: 'mm/dd/yyyy'
      })
      .on('changeDate', function(ev) {
        $(this).datepicker('hide');
        if ($('input[name="filter[fromDate]"]').val() != '' && $('input[name="filter[toDate]"]').val() != '') {
          Report.loadSessionsFilterPhotos();
        }
      });
    if ($('.session-photo-date-picker').length != 0) {
      Report.loadSessionsFilterPhotos();
    }
    if ($('.btn-filter-photos').length == 1) {
      $('.btn-filter-photos').on('click', function() {
        $.get(
          '/admin/testsession/view-page-photo',
          'page=1&filter[fromDate]=' +
            encodeURI($('input[name="filter[fromDate]"]').val()) +
            '&filter[toDate]=' +
            encodeURI($('input[name="filter[toDate]"]').val()) +
            '&filter[testSessionId]=' +
            $('select[name="testSessionId"]').val(),
          function(html) {
            //$('.reminder-panel-body').html(html);
            $('.session-photos-panel-body').html(html);
            setupRemindersUi();
          }
        );
      });
    }
    if ($('.btn-show-receipts').length == 1) {
      $('.btn-show-receipts').on('click', function() {
        $.get(
          '/admin/testsession/view-receipt',
          'page=1&filter[fromDate]=' +
            encodeURI($('input[name="filter[fromDate]"]').val()) +
            '&filter[toDate]=' +
            encodeURI($('input[name="filter[toDate]"]').val()),
          function(html) {
            $('.receipts-panel-body').html(html);
            $('[data-toggle="popover"]').popover({ trigger: 'hover' });
          }
        );
      });
    }
    /*
         // VARS
         // -----------------------------------------------*/

    /* cache jquery obj for body */
    var $body = $('body');

    /* jconfirm default globals :: http://craftpip.github.io/jquery-confirm/#api */
    jconfirm.defaults = {
      animation: 'zoom',
      confirmButtonClass: 'btn-primary',
      cancelButtonClass: 'btn-warning',
      columnClass: 'col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2',
      confirmButton: 'Yes, Confirm',
      cancelButton: 'No, Cancel',
      opacity: 1,
      backgroundDismiss: true,
      closeIcon: true,
      closeIconClass: 'fa fa-times'
    };

    /*
         // COMMONS
         // -----------------------------------------------*/

    initNavBarTools(); /* Bind event on NavBar tools Icons */
    initBackToTop(); /* back to top fixed button  */
    initGlobalSpinner(); /* Spinner Overlay for ajax feedback */
    initLogoutConfirm(); /* Confirmation on logout */
    alwaysCenterModals(); /* Make sure modals are always centers on the viewport */
    listLinkActions(); /* links & popover in list tables */

    $('[data-toggle="tooltip"]').tooltip();
    $('select[data-toggle="tooltip"]').attr('disabled', false);
    $('.phone').mask('(999) 999-9999', { autoclear: false });
    $('.phone').on('blur', function() {
      checkPhoneHasAllNumbers($(this));
    });

    /**
     *  ACTION DELETE : CONFIRM ACTION
     */
    $(document).on('click', '.link-delete', function(e) {
      e.preventDefault();
      var el = $(this);
      var target = el.prop('href');
      var confirmTitle = el.data('confirmtitle') != undefined ? el.data('confirmtitle') : 'Delete Item';
      var confirmContent =
        el.data('confirmcontent') != undefined
          ? el.data('confirmcontent')
          : 'Are you sure you want to delete this item?';
      $.confirm({
        title: confirmTitle,
        content: confirmContent,
        confirmButton: 'Yes, Delete',
        cancelButton: 'No, Keep it',
        confirm: function() {
          // this sucks, the the use of data-method=post on link with YII is a pain
          // and too much refactoring for now...(Need to get this shit working first) so inject & trigger click on a link
          $('<a>')
            .prop('href', target)
            .data('method', 'post')
            .css({ height: '1px', width: '1px', 'font-size': '1px', 'line-height': '1px' })
            .html('&nbsp;')
            .appendTo('body')
            .trigger('click');
        }
      });
    });

    $(document).on('click', '.link-archive', function(e) {
      e.preventDefault();
      var el = $(this);
      var target = el.prop('href');
      var confirmTitle = el.data('confirmtitle') != undefined ? el.data('confirmtitle') : 'Archive Item';
      var confirmContent =
        el.data('confirmcontent') != undefined
          ? el.data('confirmcontent')
          : 'Are you sure you want to archive this item?';

      $.confirm({
        title: confirmTitle,
        content: confirmContent,
        confirmButton: 'Yes, Archive',
        cancelButton: 'No, Keep it',
        confirm: function() {
          $('<a>')
            .prop('href', target)
            .data('method', 'post')
            .css({ height: '1px', width: '1px', 'font-size': '1px', 'line-height': '1px' })
            .html('&nbsp;')
            .appendTo('body')
            .trigger('click');
        }
      });
    });

    $(document).on('click', '.link-unarchive', function(e) {
      e.preventDefault();
      var el = $(this);
      var target = el.prop('href');
      var confirmTitle = el.data('confirmtitle') != undefined ? el.data('confirmtitle') : 'Un-archive Item';
      var confirmContent =
        el.data('confirmcontent') != undefined
          ? el.data('confirmcontent')
          : 'Are you sure you want to un-archive this item?';

      $.confirm({
        title: confirmTitle,
        content: confirmContent,
        confirmButton: 'Yes, Un-archive',
        cancelButton: 'No, Keep it',
        confirm: function() {
          $('<a>')
            .prop('href', target)
            .data('method', 'post')
            .css({ height: '1px', width: '1px', 'font-size': '1px', 'line-height': '1px' })
            .html('&nbsp;')
            .appendTo('body')
            .trigger('click');
        }
      });
    });

    /*
         //
         // PAGES
         //
         // -----------------------------------------------*/

    /*
         // DASHBOARD
         // -----------------------------------------------*/

    if ($body.hasClass('home-index')) {
      // RECENT APPLICATIONS
      // Contact & Fees Details popover
      $('body')
        .tooltip({
          selector: '.show-contact-details',
          placement: 'left auto',
          container: 'body',
          trigger: 'hover',
          title: 'Click for Details'
        })
        .on('click', function() {
          $('.tooltip').tooltip('hide');
        });

      initDashboard();
    }

    /*
         // VIEW PHONE INFO
         // -----------------------------------------------*/
    if ($body.hasClass('phone-index')) {
      /* modal details admin/phone */
      $(document).on('click', '.phone-info-view', function(e) {
        e.preventDefault();
        var phoneInfoId = $(this).data('id');
        var target = '/admin/phone/view?id=' + phoneInfoId;

        $.confirm({
          content: 'url:' + target,
          title: 'Phone Information Details',
          confirmButton: 'Close',
          confirm: function() {
            /* Not implemented */
          }
        });
      });
    }

    /*
         // TEST SITE :: UPDATE
         // -----------------------------------------------*/
    if ($body.hasClass('testsite-update') || $body.hasClass('testsite-create')) {
      var clipboard = new Clipboard('.btn-copy-link');

      clipboard.on('success', function(e) {
        e.clearSelection();
        var d = new CM();
        d.success('Copied text to clipboard: ' + e.text);
      });
      clipboard.on('error', function(e) {
        var d = new CM();
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
        d.failure('An Error occurred. The text was no copied in your Clipboard. Please try again.');
      });
    }

    /*
         // STAFF LIST
         // -----------------------------------------------*/
    if ($body.hasClass('staff-index') || $body.hasClass('staff-index') || $body.hasClass('staff-view')) {
      /*Send staff to archives */
      $(document).on('click', '.staff-archive', function(e) {
        e.preventDefault();
        var staffId = $(this).data('staffid');

        $.get('/admin/staff/sessions', 'id=' + staffId, function(data) {
          if (data.length > 0) {
            var ul = '<ul>';
            $.each(data, function(i, v) {
              ul += '<li>' + v + '</li>';
            });
            ul += '</ul>';
            $.alert({
              title: 'Archive Staff',
              content:
                'This staff member has Upcoming Test Sessions and cannot be archived. <br/><br/>Upcoming Test Sessions:' +
                ul,
              confirmButtonClass: 'btn-primary',
              confirmButton: 'Close'
            });
          } else {
            $.confirm({
              title: 'Archive Staff',
              content: 'Are you sure you want to archive the staff member?',
              confirm: function() {
                $('#form-archive-staff #staffId').val(staffId);
                $('#form-archive-staff').submit();
              }
            });
          }
        });
      });

      $(document).on('click', '.staff-unarchive', function(e) {
        e.preventDefault();
        var staffId = $(this).data('staffid');

        $.confirm({
          title: 'Un-archive Staff',
          content: 'Are you sure you want to un-archive the staff member?',
          confirm: function() {
            $('#form-unarchive-staff #staffId').val(staffId);
            $('#form-unarchive-staff').submit();
          }
        });
      });
    }

    // Website Admin
    if ($body.hasClass('user-index') || $body.hasClass('user-view')) {
      /*Send staff to archives */
      $(document).on('click', '.user-archive', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.confirm({
          title: 'Archive Website Admin',
          content: 'Are you sure you want to archive the Website Admin user?',
          confirm: function() {
            $('#form-archive-id #id').val(id);
            $('#form-archive-id').submit();
          }
        });
      });

      $(document).on('click', '.user-unarchive', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.confirm({
          title: 'Un-archive Website Admin',
          content: 'Are you sure you want to un-archive the Website Admin user?',
          confirm: function() {
            $('#form-unarchive-user #id').val(id);
            $('#form-unarchive-user').submit();
          }
        });
      });
    }

    /*
         // PROMO CODE
         // -----------------------------------------------*/
    if ($body.hasClass('promo-index')) {
      /*Send staff to archives */
      $(document).on('click', '.promo-archive', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var next = $(this).data('next');

        var titleText = '';
        var contentText = '';

        if (next == 0) {
          titleText = 'Un-archive Promo';
          contentText = 'Are you sure you want to un-archive the promo code?';
        } else {
          titleText = 'Archive Promo';
          contentText = 'Are you sure you want to archive the promo code?';
        }

        $.confirm({
          title: titleText,
          content: contentText,
          confirm: function() {
            $('#form-archive-promo #id').val(id);
            $('#form-archive-promo #archive').val(next);
            $('#form-archive-promo').submit();
          }
        });
      });
    }

    /*
         // CHECK LISTS
         // -----------------------------------------------*/
    if ($body.hasClass('checklist-create') || $body.hasClass('checklist-update') || $body.hasClass('checklist-index')) {
      $(document).on('click', '.item-status-new', function(e) {
        e.preventDefault();

        var $el = $(this);
        $el
          .parents('.checklist-item')
          .find('.item-status-new')
          .removeClass('btn-highlight');
        $el
          .parents('.checklist-item')
          .find('.status')
          .val($el.data('val'));
        $el.addClass('btn-highlight');
      });

      $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();

        var $el = $(this);
        var titleText = 'Archive Checklist Item';
        var contentText = 'Are you sure you want to archive the checklist item?';

        $.confirm({
          title: titleText,
          content: contentText,
          confirm: function() {
            $el.parents('.checklist-item').hide();
            $el
              .parents('.checklist-item')
              .find('.archived')
              .val(1);
          }
        });
      });

      $(document).on('click', '.add-item', function(e) {
        e.preventDefault();
        var btnPosition = $(this).data('btnposition');

        var $el = $('.checklist-item:eq(0)').clone();
        //$el.find('.archived').val(0);
        //$el.find('.item-id').val('');
        $el.find('input[type="text"], textarea, .item-id').val('');
        //$el.find('textarea').val('');
        $el.find('select, .archived').val(0);
        $el.find('select.item-type').val('');
        $el.find('.item-type-settings').hide();
        if (btnPosition === 'top') {
          $el.prependTo('.checklists').show();
        } else {
          $el.appendTo('.checklists').show();
        }
        $('.item-type').off('change');
        $('.item-type').on('change', setupChecklistItem);
      });

      $(document).on('click', '.save-checklist', function(e) {
        e.preventDefault();
        $('.has-error').removeClass('has-error');

        if ($('#checklist-name').val() == '') {
          $('#checklist-name')
            .parent()
            .addClass('has-error');
        }
        if ($('#checklist-type').val() == '') {
          $('#checklist-type')
            .parent()
            .addClass('has-error');
        }
        var isWritten = $('#checklist-type').val() == 4 ? true : false;
        $('.checklist-item').each(function(index) {
          var el = $(this);
          /*
                    $(this).find('input[type="text"]').each(function(){
                    	if($(this).val() == '' && el.find('.archived').val() != 1){

                    		if($(this).hasClass('quantity')){
                    			if(isWritten){
                    				if(Number.isInteger($(this).val()) === false){
                        				$(this).parent().addClass('has-error');
                        			}
                    			}else{
                    				//no need to cehck
                    				;
                    			}
                    		}else{
                    			$(this).parent().addClass('has-error');
                    		}

                    	}
                    });
					*/
        });

        if ($('.has-error').length == 0) {
          $('#checklist-form').submit();
        }
      });

      $(document).on('click', '.checklist-archive', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.confirm({
          title: 'Archive Checklist',
          content: 'Are you sure you want to archive the checklist?',
          confirm: function() {
            $('#form-archive-checklist #checklistId').val(id);
            $('#form-archive-checklist').submit();
          }
        });
      });

      $(document).on('click', '.checklist-unarchive', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.confirm({
          title: 'Un-archive Checklist',
          content: 'Are you sure you want to un-archive the checklist?',
          confirm: function() {
            $('#form-unarchive-checklist #checklistId').val(id);
            $('#form-unarchive-checklist').submit();
          }
        });
      });
      $('.item-type').off('change');
      $('.item-type').on('change', setupChecklistItem);
      //setupChecklistItem();
      /*
            $(document).on('change','#checklist-type', function(e){
                e.preventDefault();
                var id = $(this).val();

                	if(id == 4){
                		$('.written').show();
                		$('.non-written').hide();
                	}else{
                		$('.non-written').show();
                		$('.written').hide();
                	}

            });
            if($('#checklist-type').val() == 4){
            	$('.written').show();
            	$('.non-written').hide();
            }else{
            	$('.non-written').show();
            	$('.written').hide();
            }*/
    }

    /*
         // TEST SITE LIST
         // -----------------------------------------------*/
    if ($body.hasClass('testsite-view') || $body.hasClass('testsession-index')) {
      /* Custom ajax delete on the session list */
      $(document).on('click', '.link-delete-session-async', function(e) {
        e.preventDefault();
        var el = $(this);
        var target = el.prop('href');
        var sessionId = el.data('id');

        $.confirm({
          title: 'Delete Test Session',
          content: 'Are you sure you want to delete this Test Session?',
          confirmButton: 'Yes, Delete',
          cancelButton: 'No, Keep it',
          confirm: function() {
            $.post(target, 'id=' + sessionId, function(data) {
              var fb = new CM();
              if (data == 0) {
                /* This should not happend, but just in case. delete link remove from popover */
                fb.failure('This Session has enrolled students. It cannot be deleted.');
              } else if (data == 1) {
                var tr = $('.table-session-list')
                  .find('tr.session-info')
                  .filter(function() {
                    return $(this).data('sessionid') == sessionId;
                  });
                tr.remove();
                $('tr[data-key="' + sessionId + '"]').remove();
                fb.success('Session Successfully deleted.');
              } else if (data == 2) {
                fb.failure('This Session has previous enrolled students. It cannot be deleted.');
              } else {
                fb.failure('An Error has occurred. Please try again.');
              }
            });
          }
        });
      });
    }

    if ($body.hasClass('class-schedule-index')) {
      /* Custom ajax delete on the session list */
      $(document).on('click', '.link-delete-class-async', function(e) {
        e.preventDefault();
        var el = $(this);
        var target = el.prop('href');
        var id = el.data('id');

        $.confirm({
          title: 'Delete Class Schedule',
          content: 'Are you sure you want to delete this class?',
          confirmButton: 'Yes, Delete',
          cancelButton: 'No, Keep it',
          confirm: function() {
            $.post(target, 'id=' + id, function(data) {
              var fb = new CM();
              if (data == 1) {
                $('tr[data-key="' + id + '"]').remove();
                fb.success('Class Schedule Successfully deleted.');
              } else {
                fb.failure('An Error has occurred. Please try again.');
              }
            });
          }
        });
      });
    }
    if ($body.hasClass('cranes-index')) {
      /* Custom ajax delete on the session list */
      $(document).on('click', '.link-delete-cranes-async', function(e) {
        e.preventDefault();
        var el = $(this);
        var target = el.prop('href');
        var id = el.data('id');

        $.confirm({
          title: 'Delete Cranes',
          content: 'Are you sure you want to delete this crane?',
          confirmButton: 'Yes, Delete',
          cancelButton: 'No, Keep it',
          confirm: function() {
            $.post(target, 'id=' + id, function(data) {
              var fb = new CM();
              if (data == 1) {
                $('tr[data-key="' + id + '"]').remove();
                fb.success('Crane Successfully deleted.');
              } else {
                fb.failure('An Error has occurred. Please try again.');
              }
            });
          }
        });
      });
    }
    if ($('#class-schedule-form').length > 0) {
      $('#class-schedule-form').on('submit', function() {
        var startIndex = $('select[name="TestSessionClassSchedule[startTime]"]')[0].selectedIndex;
        var endIndex = $('select[name="TestSessionClassSchedule[endTime]"]')[0].selectedIndex;
        var fb = new CM();
        if (startIndex > endIndex) {
          fb.failure('Start time can not be more than the end time');
          return false;
        } else if (startIndex == endIndex) {
          fb.failure('Start and end time can not be the same');
          return false;
        }
        return true;
        //$('select[name="TestSessionClassSchedule[startTime]"]' )[0].selectedIndex
      });
    }
    if ($('.slider-input').length > 0) {
      $('.slider-input').jRange({
        from: 0,
        to: 4,
        step: 1,
        scale: ['0', '1/4', '1/2', '3/4', '1'],
        format: '%s',
        width: 300,
        showLabels: false,
        snap: true
      });
    }
  });
})(jQuery);

/**
 *  initLogoutConfirm();
 *  confirmation on logout
 */
var initLogoutConfirm = function() {
  $('a.logout').on('click', function(e) {
    e.preventDefault();
    $(this).blur(); // lose th focus on the clicked item
    $.confirm({
      title: 'Logout',
      content: 'Are you sure you want to logout?',
      confirmButton: 'Yes, Logout',
      cancelButton: 'No, Stay Logged in',
      confirm: function() {
        window.location.href = '/admin/default/logout';
      }
    });
  });
};

var setupChecklistItem = function() {
  if ($(this).val() == '') {
    //we hide all
    $(this)
      .parents('.checklist-item')
      .find('.item-type-settings')
      .hide();
  } else {
    $(this)
      .parents('.checklist-item')
      .find('.item-type-settings')
      .hide();
    $(this)
      .parents('.checklist-item')
      .find('.item-type-settings.item-type-' + $(this).val())
      .show();
  }
};

/**
 *  initNavBarTools();
 *  Bind event on NavBar tools Icons
 *
 */
var initNavBarTools = function() {
  /*
   *    Add Phone Information
   * */
  $('.add-phone').on('click', function() {
    var $modal = $('#reminder-modal');
    $modal.find('.modal-title').html('Add Phone Information');

    $.get('/admin/phone/create', '', function(html) {
      $modal
        .find('.modal-body')
        .html(html)
        .end()
        .modal('show');

      $('.btn-add-phone').on('click', function() {
        var $modalBody = $modal.find('.modal-body'),
          formControls = $modalBody.find('input[type=text], input[type=email], select'),
          hasErrors = false;

        $modalBody.find('.has-error').removeClass('has-error');

        $.each(formControls, function() {
          var control = $(this);
          if ($.trim(control.val()) == '' && control.hasClass('optional') == false) {
            control.parent().addClass('has-error');
            hasErrors = true;
          }
        });

        if (!validateEmail($('#phoneinformation-email').val())) {
          $('#phoneinformation-email')
            .parent()
            .addClass('has-error');
          hasErrors = true;
        }

        if (
          'Heard from a friend' == $('#phoneinformation-referral').val() &&
          $('#phoneinformation-friend_email').val() != '' &&
          !validateEmail($('#phoneinformation-friend_email').val())
        ) {
          $('#phoneinformation-friend_email')
            .parent()
            .addClass('has-error');
          hasErrors = true;
        }

        if (!hasErrors) {
          $.post('/admin/phone/create', $('form#phone-form').serialize(), function() {
            $modalBody.find('.alert').slideDown();
            if ($('.phone-panel-body').length > 0) {
              $.get('/admin/phone/viewpage', 'page=1&userId=' + $('.phone-pagination').data('user-id'), function(html) {
                $('.phone-panel-body').html(html);
                //setupRemindersUi();
              });
            }
            setTimeout(function() {
              $modal.modal('hide');
            }, 1500);
          });
        }
      });
    });
  });
};

/**
 *  initBackToTop();
 *  back to top fixed button
 *
 */
var initBackToTop = function() {
  /* on scroll events */
  $(window).scroll(function() {
    var scrollTop = $(window).scrollTop(), // our current vertical position from the top
      navTop = $('body > .container, body > .container-fluid').offset().top;
    if (scrollTop > navTop) {
      $('.back-to-top').fadeIn(450);
    } else {
      $('.back-to-top').fadeOut(250);
    }
  });
  $('.back-to-top').on('click', function(evt) {
    evt.preventDefault();
    $('html, body').animate({ scrollTop: '0px' });
  });
};

/**
 *  initGlobalSpinner();
 *  Toggle, overlay show/hide on ajax requests for feedback messages
 *
 */
var initGlobalSpinner = function() {
  //ajax global callbacks on Start & Complete to show / hide spinner
  $(document)
    .ajaxStart(function() {
      SpinnerManager.addActive();
    })
    .ajaxStop(function() {
      SpinnerManager.resolveActive();
    })
    .ajaxError(function() {
      SpinnerManager.resolveActive();
    });
};

/**
 *  alwaysCenterModals();
 *  Make sure modals are always centered on viewport. Show + window event
 *
 */
var alwaysCenterModals = function() {
  // Reposition when a modal is shown
  $('.modal').on('show.bs.modal', reposition);
  // Reposition when the window is resized
  $(window).on('resize', function() {
    $('.modal:visible').each(reposition);
  });
};

/* *******-----------\ /----------- *********** */

/**
 *
 * @param id
 * @param load
 * @param url
 */
function markStudentNotSigningUp(id, load, url) {
  //if(confirm('Are you sure you want to mark this student as not signing up?')){
  $.post('/admin/candidates/disregard', 'id=' + id, function() {
    //alert('Student Mark as Not Signing Up');
    if (load) {
      if (url == '') window.location.reload();
      else window.location.href = url;
    }
    $.get('/admin/candidates/viewpage', 'page=1', function(html) {
      $('#genericModal .modal-body').html(html);
      setupRemindersUi();
      var f = new CM();
      f.success('Student Mark as Not Signing Up');
    });
    Widgets.refresh();
  });
  //}
}

/**
 *  Trigger the flagging in to know whether the application has been submitted to IAI or not
 *  Ajax; returns feedback custom message display.
 *
 * @param id  Student ID, encoded
 * @param isSubmitted  enum(0,1), flag value
 *
 */
function markApp(id, isSubmitted) {
  $.post('/admin/candidates/mark-app', 'id=' + id + '&mark=' + isSubmitted, function() {
    var d = new CM();
    if (isSubmitted == 1) {
      $('.btn-submitted').fadeOut(250, function() {
        $('#genericModal').modal('hide');
        $('.btn-unsubmitted').fadeOut(250);
        $('.btn-submitted').fadeIn(250);
        d.success('Application Marked as Submitted successfully');
      });
    } else if (isSubmitted == 0) {
      $('.btn-unsubmitted').fadeOut(250, function() {
        $('#genericModal').modal('hide');
        $('.btn-submitted').fadeOut(250);
        $('.btn-unsubmitted').fadeIn(250);
        d.success('Application Marked as Un-Submitted successfully');
      });
    }
  });
}
function calculateAge(birthMonth, birthDay, birthYear) {
  var currentDate = new Date();
  var currentYear = currentDate.getFullYear();
  var currentMonth = currentDate.getMonth();
  var currentDay = currentDate.getDate();
  var age = currentYear - birthYear;

  if (currentMonth < birthMonth - 1) {
    age--;
  }
  if (birthMonth - 1 == currentMonth && currentDay < birthDay) {
    age--;
  }
  return age;
}

/*
 Vertically Centering Bootstrap Modals
 http://www.abeautifulsite.net/vertically-centering-bootstrap-modals/
 */
function reposition() {
  var modal = $(this),
    dialog = modal.find('.modal-dialog');

  modal.css('display', 'block');
  dialog.css({ height: 'auto', 'overflow-y': 'visible' });
  dialog.find('.modal-content').css({ height: 'auto', 'overflow-y': 'visible' });

  if (dialog.height() > $(window).height()) {
    var v = $(window).outerHeight(true) - 60;
    dialog.find('.modal-content').css({ height: v, 'overflow-y': 'scroll' });
  }
  // Dividing by two centers the modal exactly, but dividing by three
  // or four works better for larger screens.
  dialog.css('margin-top', Math.max(0, ($(window).height() - dialog.height()) / 2));
}
function AddCandidate() {
  $('.has-error').removeClass('has-error');

  $.each($('.phone.required'), function(i, el) {
    checkPhoneHasAllNumbers($(el));
  });

  $('.help-block').html('');
  $('.required').each(function() {
    if ($.trim($(this).val()) == '') {
      $(this)
        .parent()
        .addClass('has-error');
      $(this)
        .parent()
        .find('.help-block')
        .html('Field is required');
    }
  });

  $('.email').each(function() {
    if ($.trim($(this).val()) != '' && !validateEmail($(this).val())) {
      $(this)
        .parent()
        .addClass('has-error');
      $(this)
        .parent()
        .find('.help-block')
        .html('Invalid Email');
    }
  });

  var companyAddressField = $('input[name="Candidates[company_address]"]');
  var homeAddressField = $('input[name="Candidates[address]"]');

  var homeAddress = homeAddressField
    .val()
    .toLowerCase()
    .split(' ')
    .join('');
  var companyAddress = companyAddressField
    .val()
    .toLowerCase()
    .split(' ')
    .join('');

  var homeAddressAndCompanyAddressSame = homeAddress === companyAddress;

  if (companyAddressField.val() !== '' && homeAddressAndCompanyAddressSame) {
    companyAddressField.parent().addClass('has-error');
    companyAddressField
      .parent()
      .find('.help-block')
      .html('Home Address should not be the same as the Company Address.');
    homeAddressField.parent().addClass('has-error');
    homeAddressField
      .parent()
      .find('.help-block')
      .html('Home Address should not be the same as the Company Address.');
  }

  var poSelectField = $('#candidate-is-po-select');
  var poInputField = $('input[name="Candidates[purchase_order_number]"]');
  if (poSelectField.val().toString() === '1' && poInputField.val() === '') {
    poInputField.parent().addClass('has-error');
    poInputField
      .parent()
      .find('.help-block')
      .html('PO Number is required if the Candidate Application is a Purchase Order.');
  }

  if ($('input[name="Candidates[birthday]"]').length != 0 && $('input[name="Candidates[birthday]"]').val() != '') {
    $('input[name="Candidates[birthday]"]').val();
    var x = $('input[name="Candidates[birthday]"]').val();
    var dates = x.split('/');
    var age = calculateAge(dates[0], dates[1], dates[2]);
    if (age < 18) {
      $('input[name="Candidates[birthday]"]')
        .parent()
        .addClass('has-error');
      $('input[name="Candidates[birthday]"]')
        .parent()
        .find('.help-block')
        .html('Age should be more than 18 yrs old');
      hasError = true;
    }
  }

  var appTypeField = $('#app-type');
  var appTypeNotFound = !$('#app-type-list')
    .get(0)
    .options.namedItem('app-type-list-' + appTypeField.val());
  if (appTypeNotFound) {
    appTypeField.parent().addClass('has-error');
    appTypeField
      .parent()
      .find('.help-block')
      .html('Invalid Application Type.');
  }

  var isRecert = $('input[name="Candidates[application_type_id]"]').attr('data-is-recert');
  if (isRecert === 'true') {
    ccoCertNumberField = $('input[name="Candidates[ccoCertNumber]"]');
    if (ccoCertNumberField.val() === '') {
      ccoCertNumberField.parent().addClass('has-error');
      ccoCertNumberField
        .parent()
        .find('.help-block')
        .html('CCO Certification Number field is required if candidate is taking a Recertification exam.');
    }
  }

  if ($('.has-error').length == 0) {
    $.each($('.phone').not('.required'), function(i, el) {
      var el = $(el);
      checkPhoneHasAllNumbers(el);
      if (el.parents('.form-group').hasClass('has-error')) {
        el.val('');
      }
    });
    if (
      $('form#update-candidate input[name="testSessionId"]').length == 1 &&
      $('form#update-candidate input[name="testSessionId"]').val() != ''
    ) {
      $.post('/admin/candidates/create-simple', $('form#update-candidate').serialize(), function(data) {
        var resp = $.parseJSON(data);
        if (resp.status == 1) {
          $.alert({
            title: 'Add Application',
            content: 'Application Added Successfully',
            confirmButtonClass: 'btn-primary',
            confirmButton: 'Close'
          });
          $('#genericModal').modal('hide');
          Roster.autoRefreshPage();
        } else {
          $.alert({
            title: 'Add Application',
            content: 'Process failed, please try again',
            confirmButtonClass: 'btn-primary',
            confirmButton: 'Close'
          });
        }
      });
    } else $('form#update-candidate').submit();
  }
}
/**
 *  Popover for the link action in list pages
 */
function listLinkActions() {
  $('.show-action').on('click', function(e) {
    e.preventDefault();
    var $_this = $(this),
      options = { html: true, placement: 'auto right', container: 'body' },
      content = $_this.next('.pop-content').html();

    $_this.data('content', content);
    $_this.popover(options).popover('show');
  });
  /* hide on widow resize not to have popover position issues */
  $(window).on('resize', function() {
    $('.show-action').popover('hide');
  });
  /* Hide all pops */
  $(document).on('click', function(e) {
    $('.show-action').each(function() {
      //the 'is' for buttons that trigger popups
      //the 'has' for icons within a button that triggers a popup
      if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
        $(this)
          .popover('hide')
          .popover('destroy');
      }
    });
  });
}
var Widgets = {
  refresh: function() {
    $.get('/admin/home/', '', function(html) {
      $('.widget-index').html(html);
      initDashboard();
    });
  }
};

var initDashboard = function() {
  // build some modal for the previews
  var modalPreview = function() {
    var args = arguments[0] || {};

    var $modal = $('#genericModal');
    var title = args.title !== undefined ? args.title : 'Preview'; // default title
    var cssClass = args.cssClass !== undefined ? args.cssClass : 'modal-lg'; // default css Class for modal
    var content = args.content !== undefined ? args.content : 'No Data'; // default content

    $modal.find('.modal-dialog').addClass(cssClass);
    $modal.find('.modal-title').html(title);
    $modal
      .find('.modal-body')
      .html('')
      .append(content);
    // remove all buttons in footer. Except first one (= close)
    $modal
      .find('.modal-footer')
      .find('.btn')
      .not(':first-child')
      .hide();
    $modal.modal('show');
  };

  // widget content clickable == big link in the main widget content
  // works for all widgets
  $('.wic-link').on('click', function(e) {
    e.preventDefault();
    var self = $(this);
    var options = {
      title: self.parents('.widget-item').data('modaltitle'),
      content: self
        .parents('.widget-item')
        .find('.widget-item-modal-content')
        .clone(true)
    };
    modalPreview(options);
  });

  /**
   *   Triggers click on a widget content item, from the footer link
   */
  var clickWidgetContentLink = function(el, nodeIdx) {
    var nodeIdx = typeof nodeIdx !== 'undefined' ? nodeIdx : 0;
    $(el)
      .parents('.widget')
      .find('.wic-link')
      .eq(nodeIdx)
      .trigger('click');
  };

  // links in widget item footers
  // loop all widgets, find footer links, & bind widget item content target click to same list offset
  // only if link has href="#"
  $.each($('.widget'), function(idx, widgt) {
    $.each(
      $(widgt)
        .find('.widget-item-links')
        .find('a'),
      function(i, el) {
        if ($(el).attr('href') == '#') {
          $(el).on('click', function() {
            clickWidgetContentLink(this, i);
          });
        }
      }
    );
  });

  setupRemindersUi();
};

/* **** EXTERNALIZE **** */
/*  Spinner Obj & global window bind */
var SpinnerIndicator = function() {};

SpinnerIndicator.prototype.start = function() {
  $('body').css({ overflow: 'hidden' });
  $('#blocker').show();
};

SpinnerIndicator.prototype.stop = function() {
  $('#blocker').hide();
  $('body').css({ overflow: 'auto' });
};

/* global Spinner */
window.SpinnerManager = (function() {
  'use strict';
  var activeConnectionsAvailable = false;
  var DELAY = 750;
  var spinner = new SpinnerIndicator();

  function startSpinnerIfNecessary() {
    if (activeConnectionsAvailable) {
      $('.popover')
        .hide()
        .remove();
      spinner.start();
    }
  }

  var service = {
    addActive: function() {
      service.addActiveWithCustomDelay(DELAY);
    },
    addActiveWithCustomDelay: function(timeoutDelay) {
      if (!activeConnectionsAvailable) {
        setTimeout(startSpinnerIfNecessary, timeoutDelay);
        activeConnectionsAvailable = true;
      }
    },
    resolveActive: function() {
      activeConnectionsAvailable = false;

      spinner.stop();
    }
  };
  return service;
})();
