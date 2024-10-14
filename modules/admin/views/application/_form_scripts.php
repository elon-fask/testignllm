
<script>
    var calculateFinalIaiFee = function(){
        var finalIaiFee = 0;
        var writtenAiaFee = 0;
        var practicalIaiFee = 0;
        $('.dynamic-forms:checked').each(function(){
            var sectionId = $(this).data('section-id');

            if($('#'+sectionId + ' #W_TOTAL_DUE').val() != null){
                finalIaiFee += parseInt($('#'+sectionId + ' #W_TOTAL_DUE').val(),10);
                writtenAiaFee += parseInt($('#'+sectionId + ' #W_TOTAL_DUE').val(),10);
            }

            var practicalCrane = $('#'+sectionId+' .practical-cranes:checked').length;

            if(practicalCrane == 1){
                var am = isNaN(parseFloat($('.practicalCharge1Crane').val(),10)) ? 0 : parseFloat($('.practicalCharge1Crane').val(),10);
                finalIaiFee += parseFloat(am);
                practicalIaiFee = parseFloat(am);
            }else if(practicalCrane == 2){
                var am = isNaN(parseFloat($('.practicalCharge2Crane').val(),10)) ? 0 : parseFloat($('.practicalCharge2Crane').val(),10);
                finalIaiFee += am;
                practicalIaiFee = parseFloat(am);
            }
        });
        $('#applicationtype-iaifee').val(finalIaiFee)
        $('.practical-iai-fee').html('$'+practicalIaiFee);
        $('.written-iai-fee').html('$'+writtenAiaFee);
        AppWizard.search();
    };

    function computeTotalFees(sectionId, el){
        var $_this = $(el),
            currentTotal = $('#'+sectionId + ' #W_TOTAL_DUE').val();
        newTotal = 0,
            newTotalSpan ='';

        if( $_this.is(':checked')) {
            newTotal = parseInt(currentTotal) + parseInt($_this.data('price'));
        }else{
            newTotal = parseInt(currentTotal) - parseInt($_this.data('price'));
        }
        console.log(el);
        console.log(newTotal);

        $('#'+sectionId + ' #W_TOTAL_DUE').val(newTotal);

        newTotalSpan = ( parseInt(newTotal) == 0 ) ? '' :  newTotal;
        $('#'+sectionId + ' #fee-total-price').html(newTotalSpan);
    }


    /**
     *  Event checkboxes
     *
     *  Panels : (Practical &&/|| written) || (recert)
     *  Elements : auto check title when check a child
     *
     */
    function bindCheckboxesEvents(){
        /* check title on check child */
        $('.panel-collapse').find('input').off('change');
        $('.panel-collapse').find('input').on('change', function(){
            var $el = $(this);
            var $title_check = $el.parents('.panel').find('.panel-title input');
            if($el.is(':checked')){
                /* not usign prop(checked) to make sure we bubble event binded on the checkbox, only if unchecked */
                if(!($title_check.is(':checked'))){
                    $title_check.trigger('click');
                }
            }
        });

        /* (Practical &&/|| written) || (recert) */
        var $panelPractical = $('.panel-iai-blank-practical-test-application-form'),
            $panelRecert    = $('.panel-iai-blank-recert-with-1000-hours-application'),
            $panelWritten   = $('.panel-iai-blank-written-test-site-application-new-candidate');

        var $checkPractical = $panelPractical.find('.panel-heading input'),
            $checkRecert    = $panelRecert.find('.panel-heading input'),
            $checkWritten   = $panelWritten.find('.panel-heading input');
    }

    $('#form-submit').click(function(e) {
        e.preventDefault();
        $('#application-form').submit();
    });


    $(function() {

        bindCheckboxesEvents();
        $('.test-fees input[type=checkbox], .other-fees input[type=checkbox]').off('change');
        $('.test-fees input[type=checkbox], .other-fees input[type=checkbox]').on('change',function(evt){
            computeTotalFees($(this).parents('.panel-body').data('section-id'), evt.target);
            calculateFinalIaiFee();
        });
        calculateFinalIaiFee();
        $('.dynamic-forms').on('change', calculateFinalIaiFee);

        $('.practical-cranes').on('change', function(){
            var numCranes = $(this).parents('.panel-body').find('.practical-cranes:checked').length;
            if(numCranes > 2){
                alert('The system only supports 2 cranes for now.');
                $(this).prop('checked', false);
            }
            calculateFinalIaiFee();
        });
    });
</script>
