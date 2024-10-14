<?php 
use app\models\TestSession;

$protocol = '';
if(isset($_SERVER['HTTPS'])){
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
}
else{
    $protocol = 'http';
}
$applicationFormUrl = $protocol .'://'.$_SERVER['HTTP_HOST'].'/admin/candidates/payment?id='.md5($candidate->id);
$paymentSuccessUrl = $protocol .'://'.$_SERVER['HTTP_HOST'].'/admin/candidates/payment?id='.md5($candidate->id).'&s=1';
$relay_response_url = $protocol .'://'.$_SERVER['HTTP_HOST'].'/site/confirmationpayment';
$api_login_id = Yii::$app->params['authorize.net.login.id'];// '96tk7ZP2WY';
$transaction_key = Yii::$app->params['authorize.net.transaction.key'];
$authorizeUrl = Yii::$app->params['authorize.net.url'];

$written = $candidate->getWrittenTestSession();
$practical = $candidate->getPracticalSession();
$school = false;
if($written !== false){
    $ses = TestSession::findOne($written->test_session_id);
    if($ses){
        $school = $ses->school;
    }
}
if($school === false && $practical !== false){
    $ses = TestSession::findOne($practical->test_session_id);
    if($ses){
        $school = $ses->school;
    }
}
if($school !== false && $school == TestSession::SCHOOL_ACS){
    $api_login_id = Yii::$app->params['authorize.net.login.id.acs'];// '96tk7ZP2WY';
    $transaction_key = Yii::$app->params['authorize.net.transaction.key.acs'];
}

$description = $candidate->getFullName().' - Additional Payment for '.$candidate->getApplicationTypeDesc();

$amount = number_format((float)$amount, 2, '.', '');


$date = date_create();

$fp_sequence = date_format($date, 'YmdHis'); // Any sequential number like an invoice number.
$testMode = false;

$fp_timestamp = time();

$fingerprint = \AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key, 
$amount, $fp_sequence, $fp_timestamp);
?>
<form id="crane-training-payment-form" method='post' action="<?php echo $authorizeUrl?>">
  <input type='hidden' name="x_login" value="<?php echo $api_login_id?>" />
  <input type='hidden' name="x_fp_hash" value="<?php echo $fingerprint?>" />
  <input type='hidden' name="x_amount" value="<?php echo $amount?>" />
  <input type='hidden' name="x_fp_timestamp" value="<?php echo $fp_timestamp?>" />
  <input type='hidden' name="x_fp_sequence" value="<?php echo $fp_sequence?>" />
  <input type='hidden' name="x_version" value="3.1" />
  <input type='hidden' name="x_show_form" value="payment_form" />
  <input type='hidden' name="x_test_request" value="false" />
  <input type='hidden' name='x_description' value='<?php echo $description; ?>' />
  <input type='hidden' name='x_remarks' value='<?php echo $remarks; ?>' />    
  <input type="hidden" name="x_relay_url" value="<?=$relay_response_url?>">   <!-- would be redirected to this page after payment -->
  <input type="hidden" name="x_cId" value="<?=base64_encode($candidate->id)?>"/>
  <input type="hidden" name="x_promo" value=""/>
  <input type="hidden" name="x_cancel_url" value="<?=$applicationFormUrl?>"/>
  <input type="hidden" name="x_payment_success_url" value="<?=$paymentSuccessUrl?>"/>
  <input type='hidden' name="x_method" value="cc" />
  <input type="hidden" name="x_relay_response" value="true">    
  <input type="hidden" name="Candidate Name" value="<?php echo $candidate->getFullName()?>">
</form>

<script>
$(document).ready(function() {
    $('#crane-training-payment-form').submit();
})
</script>
