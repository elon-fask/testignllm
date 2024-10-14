


<style>

    h2{margin-top: 50px;}

    .container-civil-state .control-label{
        font-weight: normal;
        margin-bottom: 0;
    }
    .container-civil-state, .container-types {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
    }
    .row-types {
    }
    .row-types ul{
        list-style-type: none; margin: 0; padding-left: 0; /*border: 1px solid #ddd; padding: 5px; border-radius: 4px;*/
    }

    .row-types ul .control-label {
        width: 100%;
        margin-bottom: 0;
        font-weight: normal;
    }
    .row-types li{
        /*font-size: 12px;*/
        line-height: 20px;
        margin-bottom: 5px;
    }

    .row-types ul input[type=checkbox]{
        position: relative;
        top:2px;
        margin-right: 4px
    }
</style>



<div class="row">
    <div class="col-xs-12">
        <h1>Candidate Application</h1>
        <h2>PRACTICAL EXAMINATION—MOBILE, TOWER, & OVERHEAD</h2>
    </div>
</div>
<?php echo $this->render('forms/_p_civil_state', ['formName'=>'', 'dynamicFormDetails' => null]) ?>



<div class="row"><div class="col-xs-12">
        <h2>INDICATE WITH A CHECK THE CRANE TYPE(S) YOU WISH TO BE TESTED ON:</h2>
</div></div>
<?php echo $this->render('forms/_p_cranes_types', ['formName'=>'', 'dynamicFormDetails' => null]) ?>


<div class="row row-site">
    <div class="col-xs-12">
        <h2>TEST SITE AT WHICH YOU INTEND TO TAKE THE PRACTICAL EXAMINATION</h2>
    </div>
</div>
<?php echo $this->render('forms/_p_site', ['formName'=>'', 'dynamicFormDetails' => null]) ?>



<div class="row row-submit" style="margin-top: 100px;">
    <div class="col-xs-12 text-center">
        <p class="text-center text-warning"><i class="fa fa-warning"></i>&nbsp;All Fields are filled as expected !&nbsp;<i class="fa fa-warning"></i></p>
        <input type="submit" value="SUBMIT THIS APPLICATION" class="btn btn-success btn-lg"/>
    </div>
</div>
