<div class="row"><div class="col-xs-12">
        <h2>WRITTEN EXAMINATION(S) FOR WHICH YOU ARE APPLYING</h2>
        <p>FILL IN the circle next to the crane type(s) for which you are applying; for Mobile Cranes, CHECK ☑ the load chart you want to
            use for that crane type. Also FILL IN the appropriate circle(s) below for correct fees. NOTE: If you are registering for Mobile Crane
            exams, you must register for the Mobile Core Exam and at least one Specialty Exam (unless you are a Retest Candidate).</p>
        <p><strong>If you are recertifying, please use separate Recertification Written Examination Application Form.</strong></p>
</div></div>
<?php 
//we get the settings

?>
<div class="row row-fees">

    <div class="col-xs-6">
        <?php echo $this->render('../forms/_w_written_exams', ['formName'=>$formName, 'dynamicFormDetails' => $dynamicFormDetails]) ?>

        <?php echo $this->render('../forms/_w_other_fees', ['formName'=>$formName, 'dynamicFormDetails' => $dynamicFormDetails]) ?>
    </div>

    <div class="col-xs-6">
        <?php echo $this->render('../forms/_w_fees', ['formName'=>$formName, 'dynamicFormDetails' => $dynamicFormDetails]) ?>
    </div>

</div>