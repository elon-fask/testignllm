<?php use app\helpers\UtilityHelper; ?>

<?php echo yii\base\View::render('partials/wizard', ['step'=>5]);?>

<?php echo yii\base\View::render('partials/_titles', ['step'=>5]);?>



<div class="row" style="margin-top: 40px;">


    <div class="clearfix">
        <div class="section-title" style="background: red; border: red;">APPLICATION FORM</div>
    </div>


    <div class="section-content" style="
        border-color: red;
        box-shadow: 0 1px 1px red;
        text-align: center;
        font-size: 20px;
        padding: 25px;">
    <p style="
        margin-bottom: 25px;
        color: red;
        margin-top: 25px;
        font-weight: bold;
        font-size: 26px;">
        Please print out your application form, sign it and send it back to us.<br /><br />
        E-mail American Crane School applications to pass@americancraneschool.com or fax to 888-761-7277.<br /><br />
        E-mail California Crane School applications to pass@californiacraneschool.com or fax to 888-701-7277.
    </p>
    </div>
</div>
