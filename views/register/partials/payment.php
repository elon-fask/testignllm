<?php
use yii\helpers\Html;
use app\models\ApplicationType;
use app\models\PromoCodes;
use app\models\TestSession;
use app\helpers\UtilityHelper;
use app\models\TestSite;
?>

<?php if($nonAjax == true){?>
<div class="registration-wrapper">
    <?php }?>
    <div class="registration-wizard">

        <?php echo yii\base\View::render('wizard', ['step'=>4]);?>

        <?php echo yii\base\View::render('_titles', ['step'=>4]);?>

        <?php

        $protocol = '';
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        $applicationFormUrl = $protocol .'://'.$_SERVER['HTTP_HOST'].'/register/info?uniqueCode='.$uniqueCode.'&candidateId='.md5($model->id).'&referralCode='.$model->referralCode.'&appTypeId='.$appTypeId.'&sesId='.$sesId.'&d='.$d.'&paymentStep=1';

        $relay_response_url = $protocol .'://'.$_SERVER['HTTP_HOST'].'/register/confirmation';
        $thankYouUrl = $protocol .'://'.$_SERVER['HTTP_HOST']. "/register/thankyou?cId=".base64_encode($id);

        $authorizeUrl = Yii::$app->params['authorize.net.url'];
        $notifyEmail = 'dev-only@californiacraneschool.com';

        $api_login_id = Yii::$app->params['authorize.net.login.id'];
        $transaction_key = Yii::$app->params['authorize.net.transaction.key'];

        if($testSession->school == TestSession::SCHOOL_ACS){
            $api_login_id = Yii::$app->params['authorize.net.login.id.acs'];
            $transaction_key = Yii::$app->params['authorize.net.transaction.key.acs'];
        }

        $description = $model->getFullName() .' - Payment for ' . $appType->description;
        $discount  = 0;

        $promoCodes = null;
        $isPurchaseOrder = false;
        if($model->referralCode != ''){
            $promoCodes = PromoCodes::findOne(['code' => $model->referralCode]);
            if($promoCodes != null){
                $discount = $promoCodes->discount;
                $isPurchaseOrder = $promoCodes->isPurchaseOrder == 1 ? true : false;
            }
        }

        $fee = $appType->price - $discount ;

        $lateFeeApplicable = $testSession->isLateFeeApplicable;// && !$appTypeIsPracticalOnly;
        $lateFee = $lateFeeApplicable ? 50 : 0;

        $totalFee = $fee + $lateFee;

        $amountPartial = number_format((float)$fee, 2, '.', '');
        $amount = number_format((float)$totalFee, 2, '.', '');


        $date = date_create();

        //$fp_sequence = date_format($date, 'U = Y-m-d H:i:s'); // Any sequential number like an invoice number.
        $fp_sequence = date_format($date, 'YmdHis'); // Any sequential number like an invoice number.
        $testMode = false;

        $fp_timestamp = time();

        $fingerprint = \AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key,
            $amount, $fp_sequence, $fp_timestamp);
        $info = UtilityHelper::getSiteBrandingInfo();
        ?>
        <?php if(isset($message) && $message !== false){?>
            <div class="alert alert-danger"><?php echo $message?></div>
        <?php }?>


        <div class="row row-content">

            <?php if(\Yii::$app->getSession()->hasFlash('error')){?>
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
                    </div>
                </div>
            <?php } ?>

            <div class="col-xs-12 col-md-6">
                <div class="clearfix">
                    <div class="section-title" style="">Terms &amp; Conditions</div>
                </div>
                <div class="section-content" style="margin-bottom: 10px;">
                    <p style=" margin: 40px 0; font-weight: bold;"><i class="fa fa-warning text-danger"></i>
                        NOTE: By continuing to process your application and going to the next screen, you confirm that you agree with the terms and conditions stated below.
                    </p>
                    <div class="terms-conditions" style="width:100%; height: 150px;overflow-y: auto; border: 1px solid #ccc; padding: 15px;">
                        There are many risks that are associated with working in the crane industry. Working on, in, or around a crane can be very dangerous.
                        Mistakes can be very costly and can often result in serious injuries or death. California Crane School / American Crane School conducts
                        a condensed test preparation course that is designed to assist candidates with the successful completion of the NCCCO crane operator
                        certification written exams and provides candidates with the opportunity to take the NCCCO crane operator certification practical
                        examinations (practical examinations may be subject to crane availability and weather conditions). This course is not intended to
                        replace a crane apprenticeship program, or a crane safety training program. Nor is it intended to provide candidates with all the
                        necessary information to perform their duties in the crane field safely and proficiently. We are not qualified to give legal advice.
                        Students should always follow all applicable manufacturers, state and federal regulations. Candidates should also be familiar with the
                        operator's manuals and load charts for the specific cranes they will operate.
                        <ul>
                            <li>
                                A $500 non-refundable deposit will be charged at the time of sign up. The remaining amount will be due at the time of class. If after paying the deposit you do not attend the course, the deposit will be non-refundable and all other course costs will be forfeited. You may sign up again upon paying an additional $500 deposit, California Crane School / American Crane School does not charge a cancellation fee. However, the testing company (NCCCO) now charges a $25.00 reschedule fee that must be accompanied by an excuse letter. To reschedule and get assistance with the submittal of an excuse letter, contact an enrollment specialist at 888-967-7277.
                            </li>
                            <li>
                                Typically, it takes the testing company, the National Commission for the Certification of Crane Operators (NCCCO), 2-3 weeks to score the written exams and 3-5 weeks to score the practical exams. Your results will be mailed to you directly from NCCCO upon all scoring being completed. Upon the successful completion of all certification exams the certification card will be mailed to the candidates address with the results for the practical exams.
                            </li>
                        </ul>
                        <h3 style="text-decoration: underline">GENERAL PROVISIONS</h3>
                        <ol>
                            <li>
                                <strong>NO OTHER AGREEMENT:</strong>
                                These terms and conditions shall govern the entire agreement between the parties hereto and supersede all other agreements or understandings, written or oral. The Customer disclaims any and all representations or warranties expressed or implied, not specifically listed herein.
                            </li>
                            <li>
                                <strong>INDEMNIFICATION:</strong>
                                To the fullest extent permitted by law, Customer shall defend, indemnify and hold harmless California Crane School / American Crane School and its agents and employees from and against all claims, damages, losses, and expenses, including but not limited to attorneys’ fees arising out of or resulting from the performance of the work, provided that any such claim, damage, loss or expense (1) is attributable to bodily injury, sickness, disease or death, or to injury to or destruction of tangible property including the loss of use resulting therefrom, and (2) is caused in whole or in part by any negligent act or omission of Customer, or anyone directly or indirectly employed by Customer or anyone for whose acts Customer may be liable. Provided, however, that where any such claim, damage, loss or expense arises from the concurrent negligence of (1) the Customer or anyone from whose acts it may be liable (2) California Crane School / American Crane School or anyone for whose acts is may be liable and (3) the Owner or anyone for whose acts it may be liable, it is expressly agreed that California Crane School / American Crane School obligations of the indemnity under this paragraph shall be effective only the extent of California Crane School / American Crane School negligence. In no case will California Crane School / American Crane School be obligated for more than California Crane School / American Crane School on-hook or general liability insurance limits. Further, the indemnification obligation under this contract shall not be limited in any way be any limitation on the amount or type of damages, compensation or benefits payable to or for any third party under workers’ compensation acts, disability benefits acts, or other employee benefits acts; provided Contractor’s waiver of immunity by the provisions of this paragraph extends only to claims against Contract by Owner and does not include, or extend to, any claims by Contractor’s employees directly against Contractor.
                            </li>
                            <li>
                                <strong>EXCUSE OF PERFORMANCE:</strong>
                                Any prevention, delay or stoppage due to strikes, lockouts, labor disputes, accidents, adverse weather conditions, acts of GOD, inability to obtain labor or materials or reasonable substitutes, therefore, governmental action, domestic or foreign, riot, civil commotion, fire, and other casualty and all other causes beyond the reasonable control of California Crane School / American Crane School shall excuse California Crane School / American Crane School performance for a period equal to such prevention, delay or stoppage. Customer hereby waives all claims against California Crane School / American Crane School for any delay or loss of materials by reason of any shutdown, or failure of the equipment for any reason. All charges shall apply in the event the course is rescheduled. California Crane School / American Crane School will give proper notification and help to reschedule Customer.
                            </li>
                            <li>
                                <strong>APPLICABLE LAW:</strong>
                                The proposal shall be interpreted under the applicable state laws.
                            </li>
                            <li>
                                <strong>JURISDICTION:</strong> The parties expressly consent to and submit to the jurisdiction of the Superior Courts of the State of California.
                            </li>
                            <li>
                                <strong>VENUE:</strong>
                                The parties expressly agree that any suit brought pursuant to this agreement shall be brought in the Courts of Sacramento County and the parties expressly submit to such Courts jurisdiction.
                            </li>
                        </ol>
                        <h3 style="text-decoration: underline">OPERATIONAL PROVISIONS:</h3>
                        <ol>
                            <li>
                                The customer agrees to direct the use of the equipment in strict compliance with all applicable rules, laws, regulations, and orders. The customer further agrees to use said equipment in accordance with the manufacturer’s instructions and agrees not to exceed the manufacturer’s rated load capacities
                            </li>
                        </ol>
                        <h3 style="text-decoration: underline">TERMS OF PAYMENT</h3>
                        <ol>
                            <li>
                                A $500 non-refundable deposit will be charged. The remaining amount will be due at the time of class. If after paying the deposit you do not attend the course, the deposit will be non-refundable and all course costs will be forfeited. You may sign up again upon paying another $500 deposit. You may qualify to reschedule the class without paying another deposit fee contingent upon NCCCO’s acceptance of Customer’s excuse letter.
                            </li>
                            <li>
                                On amounts not paid within thirty (30) days from the date of invoice, a finance charge shall be added equal to one and one-half percent per month (18% APR). If payments for amounts due on this application, or any portion thereof, are not paid in accordance with the terms of the contract, the Customer agrees to pay an additional 25% for fees in addition to all court costs and attorney’s fees.
                            </li>
                            <li>
                                NSF Checks will be assessed a $50.00 NFS Check Fee.
                            </li>
                            <li>
                                Any federal, state, local taxes, or other similar governmental charges imposed upon California Crane School and American Crane School or Customer that may be assesses on these transactions shall be paid by the Customer regardless of when said tax or charge is assessed or imposed. Said payment shall be in addition to the aforementioned amounts.
                            </li>
                        </ol>
                    </div>


                    <ul style="l" class="payment-notes">
                        <li>Notes:</li>
                        <li style="padding-left: 25px; margin-bottom: 15px;">California Crane School dba ACS does not charge a cancellation fee. However,  the testing company (NCCCO) now charges a $25.00 reschedule fee that must be  accompanied by an excuse letter.</li>
                        <li style="padding-left: 25px;">Typically it takes the testing company (NCCCO) 1-2 weeks to score the  written tests and 2-3 weeks to score the practical tests. Upon successful  completion the certification card will be mailed to the candidates address   with the results for the practical exams.</li>
                    </ul>

                </div>

                <?php if($model->isNewRecord == false){?>
                    <div class=" pull-right register-back" style="line-height: 30px;">
                        <?= Html::a('<i class="fa fa-long-arrow-left"></i><span class="back-step">Back to previous step</span>', ['#'], ['class' =>'btn-register-back', 'data-candidate-id' => $model->id, 'data-step' => 1]);?>
                    </div>
                <?php }?>
            </div>


            <div class="col-xs-12 col-md-6">

                <div class="clearfix">
                    <div class="section-title">Class &amp; Payment Information</div>
                </div>

                <div class="section-content" style="margin-bottom: 10px;">

                    <p>California Crane School dba ACS requires a $500 non-refundable deposit to reserve a space in the class and cover all of the candidate certification testing fees required by the testing company National Commission For The Certification of Crane Operators (NCCCO).</p>
                    <p>This deposit will be applied to the price of the California Crane School dba ACS crane operator certification/re-certification training program.</p>

                    <p style="display: none">Training class starts <strong>2 days prior</strong> to the test date listed below.</p>

                    <div class="clearfix" style="margin-bottom: 10px">
                        <div class="col-xs-3" style="text-align: right;line-height: 22px; ">Location:</div>
                        <div class="col-xs-8" style="font-weight: bold; font-size: 16px; line-height: 22px;"><?php echo $testSession->getTestSiteName()?></div>
                    </div>

                    <div  class="clearfix" style="margin-bottom: 10px">
                        <div class="col-xs-3" style="text-align: right;line-height: 22px; ">Class Dates:</div>
                        <div class="col-xs-8" style="font-weight: bold;font-size: 16px; line-height: 22px;"><?php echo $testSession->getDateInfo()?></div>
                    </div>

                    <?php if ($appType->price > 0) { ?>

                        <div class="form-group" style="margin-bottom: 20px">
                            <h4>Select payment options:</h4>
                            <p style="font-weight: bold; margin: 20px 0 20px 0;" class='complete-payment-reminder'>Signed applications must be received 14 days prior to the test date, or NCCCO will apply a $50 late fee.</p>
                            <label class="control-label" style="display: block; margin-left: 25px;">
                                <input type="radio" name="paymentOptions" class="deposit" value="deposit" data-amount="500"/> A - Deposit - $500.00
                            </label>
                            <label class="control-label" style="display: block; margin-left: 25px;">
                                <input type="radio" name="paymentOptions" value="full" checked data-amount="<?php echo $appType->price?>"/> B - Pay in full - $<?php echo number_format($appType->price, 2, '.', ',')?> <?= $lateFeeApplicable ? '<span style="color: #a94442">+$50 Late Fee</span>' : '' ?>
                            </label>
                        </div>


                        <div class="form-group" style="margin-bottom: 10px">
                            <h4><label for="promoCode" class="control-label">Company Code</label></h4>
                            <div class="clearfix" style="margin-left: 25px;">
                                <input type="text" class="form-control" name="promoCode" id="promoCode" <?php echo $promoCodes != null ? 'readonly' : ''?> value="<?php echo $promoCodes != null ? $promoCodes->code : ''?>"/>
                                <?php if($promoCodes == null){?>
                                    <button class="btn btn-cta apply-promo" data-school='<?php echo $testSession->school?>'>Apply</button>
                                <?php }?>
                            </div>
                            <div class="help-block" style="margin-left: 25px;"></div>
                        </div>

                        <div class="clearfix" id="po-section" style="margin-bottom: 10px; display: none;">
                            <p style="font-weight: bold; margin: 20px 0 20px 0;">Were you provided a Purchase Order (PO) Number by your company?</p>
                            <div class="checkbox">
                                <label><input id="form-po-number-applicable" type="checkbox" value="true">Yes, apply a Purchase Order (PO) to my application</label>
                            </div>
                            <div class="form-group" style="display: none" id="form-po-number-group">
                                <label for="form-po-number">PO Number:</label>
                                <input type="text" class="form-control" id="form-po-number">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="clearfix has-discount" style="display:<?php echo $discount != 0 ? 'block' : 'none'?>">
                                <div style="text-align: right; float: left; width: 180px; padding-right: 25px">
                                    Original Price:
                                </div>
                                <div class="original-price" data-price="<?php echo $amount?>" style="float: left; width: 90px; text-align: right;">
                                    $ <?php echo number_format($amount, 2, '.', ',')?>
                                </div>
                            </div>

                            <div class="clearfix has-discount" style="display:<?php echo $discount != 0 ? 'block' : 'none'?>;">
                                <div style="text-align: right; float: left; width: 180px; padding-right: 25px">
                                    Discount:
                                </div>
                                <div class="discount-price" data-price="<?php echo $appType->price?>" style="float: left; width: 90px; text-align: right;">
                                    $ <?php echo number_format($discount, 2, '.', ',')?>
                                </div>
                            </div>

                            <div class="clearfix" style="font-weight: bold">
                                <div style="float: left; width: 180px;font-weight: bold">
                                    <h2 style="font-weight: bold">Total Price:</h2>
                                </div>
                                <div style="float: left;">
                                    <h2 class="total-price" style="font-weight: bold"><span class="total-value">$<?php echo number_format($amount, 2,'.',',')?></span></h2>
                                </div>
                            </div>


                        </div>

                        <div class="form-group has-PO" style="<?php echo $isPurchaseOrder == true ? 'display: block' : 'display: none'?>">
                            <p><strong>I'm associated with a College.</strong></p>
                        </div>

                    <?php } ?>


                </div>

                <div class="form-group clearfix">
                    <div class="pull-right">
                        <div  id="payment-form">
                            <form data-amount="<?php echo $amount?>" id="crane-training-payment-form" method='post' action="<?php echo $authorizeUrl?>">
                                <input type='hidden' name="x_login" value="<?php echo $api_login_id?>" />
                                <input type='hidden' name="x_fp_hash" value="<?php echo $fingerprint?>" />
                                <input type='hidden' name="x_amount" value="<?php echo $amount?>" />
                                <input type='hidden' name="x_fp_timestamp" value="<?php echo $fp_timestamp ?>" />
                                <input type='hidden' name="x_fp_sequence" value="<?php echo $fp_sequence ?>" />
                                <input type='hidden' name="x_version" value="3.1" />
                                <input type='hidden' name="x_show_form" value="payment_form" />
                                <input type='hidden' name="x_test_request" value="false" />
                                <input type='hidden' name="x_description" value="<?=$description; ?>" />
                                <input type="hidden" name="x_relay_url" value="<?=$relay_response_url?>">
                                <input type="hidden" name="x_appTypeId" value="<?=$appTypeId?>"/>
                                <input type="hidden" name="x_sesId" value="<?=($sesId) ?>"/>
                                <input type="hidden" name="x_cId" value="<?=base64_encode($id) ?>"/>
                                <input type="hidden" name="x_poNumber" value="" />
                                <input type="hidden" name="x_customer_ip" value = "<?=$_SERVER['REMOTE_ADDR']?>"/>
                                <input type="hidden" name="x_promo" value="<?=$promoCodes != null ? $promoCodes->code : '' ?>"/>
                                <input type="hidden" name="x_cancel_url" value="<?=$applicationFormUrl ?>"/>
                                <input type="hidden" name="x_profile_url" value="<?=$applicationFormUrl ?>"/>
                                <input type="hidden" name="x_thankyou_url" value="<?=$thankYouUrl ?>"/>
                                <input type="hidden" name="x_notify_email" value="<?=$notifyEmail ?>"/>
                                <input type='hidden' name="x_method" value="cc" />
                                <input type="hidden" name="x_relay_response" value="true">
                                <input type="hidden" name="Candidate Name" value="<?php echo $model->getFullName()?>">
                                <input type="hidden" name="x_logo_url" value="<?php echo UtilityHelper::curPageURL().UtilityHelper::getSiteBrandingLogo()?>"/>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="clearfix">

                    <div class="pull-right">
                        <img src="/images/Splitit.png" alt="Splitit" style="margin-bottom: 20px;width: 100%">
                    </div>
                </div>
                <div class="clearfix">
                    <!--            <div class="section-title" style="background: red; border-color: red;"><i class="fa fa-warning"></i> Notice <i class="fa fa-warning"></i></div>-->
                    <div class="pull-right">
                        <button id='btn-payment-form' style="<?= $isPurchaseOrder == true || $appType->price == 0 ? 'display: none' : '' ?>" type='button' data-amount="<?= $amount ?>" class="btn btn-cta btn-submit-form">Click here for the secure payment form <i class="fa fa-long-arrow-right"></i></button>
                        <div  id="po-form" style="<?= $isPurchaseOrder == true || $appType->price == 0 ? 'display: block' : 'display: none' ?>">
                            <form id="crane-training-po-form" action="<?= $appType->price > 0 ? '/register/passcode' : '/register/free' ?>" method="POST">
                                <input type="hidden" name="appTypeId" value="<?= $appTypeId ?>"/>
                                <input type="hidden" name="sesId" value="<?= $sesId ?>"/>
                                <input type="hidden" name="d" value="<?= $d ?>"/>
                                <input type="hidden" name="isFullDiscount" value="0"/>
                                <input type="hidden" name="cId" value="<?= base64_encode($id) ?>"/>
                                <input type="hidden" name="isCompanySponsored" value="" />
                                <input type="hidden" name="poNumber" value="" />
                                <input type="hidden" name="promoCode" value="<?=$promoCodes != null ? $promoCodes->code : ''?>"/>
                                <div class="po-form-button">
                                    <button id='btn-po-form' type='submit' class="btn btn-cta" >Proceed & Download Application <i class="fa fa-long-arrow-right"></i></button>
                                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!---->
                <!--        <div class="section-content" style="box-shadow: 0 1px 1px red; border-color: red">-->
                <!--            <p style="-->
                <!--        margin: 20px;-->
                <!--        color: red;-->
                <!--        font-weight: bold;-->
                <!--        font-size: 16px;">-->
                <!--                Please print out your application form, sign it and send it back to us.<br /><br />-->
                <!--                E-mail American Crane School applications to pass@americancraneschool.com or fax to 888-761-7277.<br /><br />-->
                <!--                E-mail California Crane School applications to pass@californiacraneschool.com or fax to 888-701-7277.<br /><br />-->
                <!--            </p>-->
                <!--        </div>-->



            </div>
        </div>

    </div>

    <?php if($nonAjax == true){?>
</div>
<?php }?>


<script>
    if(typeof setupUserPayment != 'undefined'){
        setupUserPayment();
    }
    $('#btn-po-form').on('click', function (event) {
        $(event.target).parent().addClass('loading');
    })
</script>
