
<div class="row">
    <div class="col-xs-12">
        <h1>Recertification Application</h1>
        <h2>WRITTEN EXAMINATION—MOBILE, TOWER, & OVERHEAD
            CRANE OPERATOR (PAPER/PENCIL TESTS ONLY)</h2>
    </div>
</div>


<?php echo $this->render('forms/_r_civil_state', []) ?>


<div class="row"><div class="col-xs-12">
        <h2>WRITTEN EXAMINATION(S) FOR WHICH YOU ARE APPLYING</h2>
        <p><strong>This application is for recertification only</strong>. You may ONLY recertify for the designation(s) in which you are currently certified.
            FILL IN the circle next to the crane type(s) for which you are applying for recertification. If you would like to take Additional
            Examinations for cranes that you are not currently certified on, then FILL IN the examinations of your choice and CHECK the load
            chart you want to use for that crane type. </p>
    </div></div>


<style>

    h2{margin-top: 50px;}

    /* All fee list */
    .row-fees ul{
        list-style-type: none; margin: 0; padding-left: 0; border: 1px solid #ddd; padding: 5px; border-radius: 4px;
    }
    .row-fees ul .control-label {
        width: 100%;
        margin-bottom: 0;
        font-weight: normal;
    }
    .row-fees li{
        font-size: 12px;
        line-height: 20px;
        margin-bottom: 5px;
    }
    li.fee-title{
        border-bottom: 0;
        margin-bottom: 0;
    }
    .row-fees li.fee-title h4{
        margin-bottom: 0;
    }
    .row-fees li .form-group{
        margin-bottom: 0
    }
    .row-fees ul input[type=checkbox]{
        position: relative;
        top:2px;
        margin-right: 4px
    }

    /* Written Exams */
    ul.written-exams li{
        border-bottom: 1px dotted #ccc;
        line-height: 20px;
        padding: 5px 0;
    }
    .written-exams > li:last-child, .written-exams > li:first-child{
        border-bottom: 0;
        margin-bottom: 0;
    }
    /* Other Fees*/
    .row-fees ul.other-fees{
        margin-top: 85px;
    }
    .other-fees > li > div:nth-child(2){
        text-align: right
    }
    /* Test fees */
    .test-fees h4{
        margin-top: 20px; padding-top: 10px; border-top: 1px solid #ccc;
    }
    .test-fees .fee-title:first-of-type h4,
    .test-fees .fee-title:last-child h4{
        margin-top: 0;
        border-top: none;
    }
    .test-fees > li > div:nth-child(2){
        text-align: right
    }
    .fee-total > div:nth-child(2) {
        background: #e3e3e3;
        border: 1px solid #ccc;
        margin-top: 5px;
        font-size:18px;
        line-height: 1.1em;
        padding-top: 3px;
        padding-bottom: 3px
    }
</style>


<div class="row row-fees">

    <div class="col-xs-6">
        <?php echo $this->render('forms/_r_exams', ['formName'=>'', 'dynamicFormDetails' => null]) ?>

        <?php echo $this->render('forms/_r_additional_exams', ['formName'=>'', 'dynamicFormDetails' => null]) ?>
    </div>

    <div class="col-xs-6">
        <?php echo $this->render('forms/_r_fees', ['formName'=>'', 'dynamicFormDetails' => null]) ?>
    </div>

</div>


<?php echo $this->render('forms/_r_site', ['formName'=>'', 'dynamicFormDetails' => null]) ?>


<?php echo $this->render('forms/_r_payment', ['formName'=>'', 'dynamicFormDetails' => null]) ?>


<?php echo $this->render('forms/_r_checklist', ['formName'=>'', 'dynamicFormDetails' => null]) ?>




<div class="row row-submit" style="margin-top: 100px;">
    <div class="col-xs-12 text-center">
        <p class="text-center text-warning"><i class="fa fa-warning"></i>&nbsp;All Fields are filled as expected !&nbsp;<i class="fa fa-warning"></i></p>
        <input type="submit" value="SUBMIT THIS APPLICATION" class="btn btn-success btn-lg"/>
    </div>
</div>


<script>
    $(function() {

        function computeTotalFees(el){
            var $_this = $(el),
                currentTotal = $('#W_EXAM_TOTAL_DUE').val();
            newTotal = 0,
                newTotalSpan ='';

            //console.log(currentTotal);

            if( $_this.is(':checked')) {
                newTotal = parseInt(currentTotal) + parseInt($_this.data('price'));
            }else{
                newTotal = parseInt(currentTotal) - parseInt($_this.data('price'));
            }
            $('#W_EXAM_TOTAL_DUE').val(newTotal);

            newTotalSpan = ( parseInt(newTotal) == 0 ) ? '' :  newTotal;
            $('#fee-total-price').html(newTotalSpan);

        };


        $('.test-fees input[type=checkbox], .other-fees input[type=checkbox]').on('change',function(evt){
            computeTotalFees(evt.target);
        });


    });
</script>

