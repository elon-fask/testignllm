<?php

use app\helpers\UtilityHelper;
//use Yii;
$this->title = 'Home';
$info = UtilityHelper::getSiteBrandingInfo();
$branding = UtilityHelper::getSubdomain();
?>

<div class="login-wrap">
    <div class="row login_content">
        <div class="col-xs-12">
            <!-- <h1 class="text-center step-title" style="margin-top: 150px; margin-bottom: 30px;">Please call <? echo $_SERVER['HTTP_HOST']?></h1> -->
            <?php if ($branding == 'acs') { ?>
                <!-- <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">(888) 957-7277 to get a password for American Crane School</h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 30px;">- or -</h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 30px;">(888) 967-7277 to get a password for California Crane School</h1> -->

                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    Welcome to the American Crane School sign up process.
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    To get registered for program please call
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    one of our NCCCO certification Specialists
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    to get you started on your certification process.
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    (800) 957-7277
                </h1>
            <?php } else { ?>
                <!-- <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 30px;">(888) 967-7277 to get a password for California Crane School</h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 30px;">- or -</h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">(888) 957-7277 to get a password for American Crane School</h1> -->
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    Welcome to the California Crane School sign up process.
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    To get registered for program please call
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    one of our NCCCO certification Specialists
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    to get you started on your certification process.
                </h1>
                <h1 class="text-center step-title" style="margin-top: 30px; margin-bottom: 50px;">
                    (800) 967-7277
                </h1>
            <?php } ?>
        </div>

        <div class="col-xs-8 col-xs-offset-2">
            <?php if (isset($message) && $message !== false) { ?>
                <div class="alert alert-danger text-center"><?= $message ?></div>
            <?php } ?>

            <form action="/register<?= isset($uniqueCode) && $uniqueCode != '' ? '?id=' . $uniqueCode: '' ?>" method="POST">
                <div class="form-group">
                    <input required type="text" name='keyword' id="passcode" class="keyword form-control text-center" placeholder="Please enter enrollment code"/>
                </div>
                <input type="hidden" name='referralCode' value="<?= isset($referralCode) ? $referralCode : '' ?>"/>
                <input type="hidden" name='uniqueCode' value="<?= isset($uniqueCode) ? $uniqueCode : '' ?>"/>
                <div class="text-center"><input type="submit" id="submit-btn" class="btn btn-cta" value="Submit"/></div>
            </form>

        </div>
        <?php

        date_default_timezone_set(timezone_name_from_abbr("CST"));
       // var_dump(strtotime(date('h:i A')));var_dump(strtotime(date('h:i A',strtotime('09:00 AM'))));
           //var_dump(date('l'));
        if(date('l') == 'Sunday' or date('l') == 'Saturday' or strtotime(date('h:i A'))< strtotime(date('h:i A',strtotime('09:00 AM'))) or strtotime(date('h:i A'))>strtotime(date('h:i A',strtotime('18:00 PM')))){
            ?>
            <div>
                <div class="w-100 d-f f-d-c  j-c-c a-a-c">
                    <div class="d-f f-d-c j-c-c a-a-c loginconfirmation">
                        <h3 class="fs-20 font-b text-center">SCHEDULE A CALL BACK</h3>
                        <p class="c-blue fs-18 text-center font-b info-call">
                            We are open from 9am to 5:30pm CST.<br>
                            Please use our call back option below to receive a call from us during normal business hours.<br>
                            We look forward to speaking with you.

<!--                            It's after normal buisness hours now,please, choose<br> the date and the time for a call back from our<br> customer service representative  </p>-->
                        <p class="fs-18 text-center Choose">Choose Date</p>
                        <p class="fs-18 text-center call-err c-red"></p>
                        <div name="" id="" class="callbackdate bs margin-bottom-50">
                            <?php
                            $first = '';
                            $date = date('Y-m-d');
                            $k = 0;
                            for($i = 1;$i<8;$i++){
                                $date1 = str_replace('-', '/', $date);
                                $tomorrow = date('F j',strtotime($date1 . "+$i days"));
                                $t = date('w', strtotime($tomorrow));
                                if($t != 0 and $t !=6){
                                    if($k==0){
                                        ?>
                                        <p  class="focus1"><?=$tomorrow?></p>
                                        <?php
                                        $first = $tomorrow;
                                        $k++;}else {
                                        ?>
                                        <p class="focus"><?= $tomorrow ?></p>
                                        <?php
                                    }
                                }
                            } ?>
                        </div>
                        <p class="fs-18 text-center select-time">Choose Time</p>
                        <div name="" id="" class="callbackdate1 bs margin-bottom-50 select-time">
                            <p class="focuss1">9:00 AM-10:00 AM</p>
                            <p class="focuss">10:00 AM-11:00 AM</p>
                            <p class="focuss">11:00 AM-12:00 PM</p>
                            <p class="focuss">12:00 PM-1:00 PM</p>
                            <p class="focuss">2:00 PM-3:00 PM</p>
                            <p class="focuss">3:00 PM-4:00 PM</p>
                            <p class="focuss">4:00 PM-5:00 PM</p>
                            <p class="focuss">5:00 PM-5:30 PM</p>
                        </div>
                        <div class="d-f j-c-c a-a-c  f-d-c w-70 sendcall-cont">
                            <input type="text" placeholder="Your name" class="nameuser form-control mb-20 ">
                            <input type="text" placeholder="Your phone" class="phoneuser form-control mb-20">
                            <input type="text" placeholder="Your email" class="emailuser form-control mb-20">
                            <input type="hidden" value="9:00 AM-10:00 AM" class="calltime">
                            <input type="hidden" value="<?=$first?>" class="calldate">
                            <button type="button" class="btn-yelow  d-f j-c-c a-a-c sendcall" name="sendcall"> Submit</button>
                        </div>
                        <div class="call-sucess-text">
                            <p class="fs-18 text-center font-b">We'll call you back on</p>
                            <p class="fs-18 text-center c-blue responsecall font-b"></p>
                        </div>
                    </div>
                    <!--                <div class="w-100 d-f f-d-c  j-c-c a-a-c margin-bottom-50">-->
                    <!--                    <img src="public/images/header/CCS-logo.png" alt="" width="120">-->
                    <!--                </div>-->
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<style>
    .keyword{width: 250px; margin:0 auto }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function options() {
        var  c = 0;
        $('.callbackdate').click(function () {
            if(c==0){
                $('.focus').css({display:'flex'});
                c++;
            }else {
                $('.focus').css({display:'none'});

                c =0 ;
            }

        })
        $('.focus').click(function () {
            var html = $(this).html();
            var html2 = $('.focus1').html();
            $('.focus1').html(html);
            $(this).html(html2);
            $('.calldate').val(html);
            $('.select-time').show();
        })
        $('.callbackdate1').click(function () {
            if(c==0){
                $('.focuss').css({display:'flex'});
                c++;
            }else {
                $('.focuss').css({display:'none'});

                c =0 ;
            }

        })
        $('.focuss').click(function () {
            var html = $(this).html();
            var html2 = $('.focuss1').html();
            $('.focuss1').html(html);
            $(this).html(html2);
            $('.calltime').val(html);
            $('.sendcall-cont').css({display:'flex'});
        })
        $('.sendcall').click(function () {
            var date = $('.calldate').val();
            // if(date == ''){
            //     $('.callbackdate').css({'border':'1px solid red'})
            // }
            var time = $('.calltime').val();
            var nameuser = $('.nameuser').val();
            var phoneuser = $('.phoneuser').val();
            var emailuser = $('.emailuser').val();
         //   if(date != '' && time != '' && nameuser != '' && phoneuser != '' && date emailuser '' ){
                $.ajax({
                    type: 'post',
                    //  dataType: "text",
                    dataType: 'text',
                    //url: "/register1/sendcall",
                    url: "<?php echo Yii::$app->getUrlManager()->createUrl('register/sendcall') ?>",
                    data: {date: date,time:time,nameuser:nameuser,phoneuser:phoneuser,emailuser:emailuser},
                    success: function (res) {
                        if (res == 'ok') {
                            $('.callbackdate').hide();
                            $('.callbackdate1').hide();
                            $('.sendcall').hide();
                            $('.select-time').hide();
                            $('.Choose').hide();
                            $('.info-call').removeClass('c-blue');
                            $('.info-call').html('Congratulations! Your call back service has been <br> Scheduled.');
                            $('.responsecall').html(date+' '+time);
                            $('.call-sucess-text').show();
                        }else {
                            $('.call-err').html('Please sign in to your account.');
                        }
                    }
                })
           // }

        })
    }
    options();
</script>
