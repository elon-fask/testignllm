
<div class="row"><div class="col-xs-12">
        <h2>WRITTEN EXAMINATION(S) FOR WHICH YOU ARE APPLYING</h2>
        <p><strong>This application is for recertification only</strong>. You may ONLY recertify for the designation(s) in which you are currently certified.
            FILL IN the circle next to the crane type(s) for which you are applying for recertification. If you would like to take Additional
            Examinations for cranes that you are not currently certified on, then FILL IN the examinations of your choice and CHECK the load
            chart you want to use for that crane type. </p>
    </div></div>

<div class="row row-fees">

    <div class="col-xs-6">
        <?php echo $this->render('../forms/_r_exams',  ['formName'=>$formName, 'dynamicFormDetails' => $dynamicFormDetails]) ?>

        <?php echo $this->render('../forms/_r_additional_exams',  ['formName'=>$formName, 'dynamicFormDetails' => $dynamicFormDetails]) ?>
    </div>

    <div class="col-xs-6">
        <?php echo $this->render('../forms/_r_fees',  ['formName'=>$formName, 'dynamicFormDetails' => $dynamicFormDetails]) ?>
    </div>

</div>