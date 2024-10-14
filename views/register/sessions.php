<?php
session_start();

use app\helpers\UtilityHelper;
use app\models\ApplicationType;
use yii\helpers\Html;

?>
<?php if (count($sessions) == 0) { ?>
    <div style="margin-top: 20px;">
        <div class="alert alert-warning text-center">No Sessions Available</div>
    </div>
<?php } else { ?>
    <div class="clearfix">
        <div class="section-title">Class Dates</div>
    </div>

    <div class="section-content">
        <div class="form-group">
            <div class="noSelection alert alert-danger text-center col-xs-10 col-xs-offset-1" style="display: none;">
                Please select a Class in the list below
            </div>

            <label class="col-md-4 col-sm-3 control-label">Select a Class Date</label>
            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-5">
                <ul class="available-sessions-list">
                    <?php
                    $currentYear = false;
                    $colors = ['#0000FF', '#A52A2A', '#6495ED', '#00FFFF', '#006400'];
                    $x = 0;
                    foreach ($sessions as $session) {
                        $lateFeeApplicable = $session->isLateFeeApplicable && !$appTypeIsPracticalOnly;
                        $startDateStr = strtotime($session->start_date);
                        $endDateStr = strtotime($session->end_date);


                        $count_date_session_var = (((($endDateStr - $startDateStr) / 60) / 60) / 24) + 1;
                        $_SESSION['count_date_session'] = $count_date_session_var;


                        $displayInfo = UtilityHelper::jb_verbose_date_range($startDateStr, $endDateStr);
                        $year = date('Y', strtotime($session->start_date));


                        if ($year !== $currentYear) {
                            $currentColor = $colors[$x % count($colors)];
                            $x++;
                            $currentYear = $year;
                            ?>
                            <li>
                                <h4 style="color: <?php echo $currentColor ?>"><b><?php echo $currentYear ?></b></h4>
                            </li>
                            <?php
                        }
                        ?>
                        <li>
                            <?php if ($session->getAvailableSlots() > 0) { ?>
                                <label class="control-label">
                                    <input type="radio" id=<?= 'session-' . $session->id ?> name="sessionRadio"
                                           value="/register/info?uniqueCode=<?= $uniqueCode ?>&candidateId=<?= ($candidateId) ?>&referralCode=<?= $referralCode ?>&appTypeId=<?= $appTypeId ?>&sesId=<?= base64_encode($session->id) ?>&d=<?= base64_encode(date('Ymd', strtotime('now'))) ?>"> <?= $displayInfo ?> <?= $lateFeeApplicable ? '<span style="color: #a94442">(Late registration +$50)</span>' : '' ?>
                                </label>
                            <?php } else { ?>
                                <label class="control-label"><?= $displayInfo ?></label>
                            <?php } ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row-buttons" style="margin-top: 0px; padding-right: 0px;">
        <div class="form-group">
            <div class="pull-right" style="padding-right: 0px;">
                <button type="submit" class="btn btn-cta btn-submit-date">Continue <i
                            class="fa fa-long-arrow-right"></i></button>
            </div>

            <div class=" pull-left register-back" style="padding-left: 0px">
                <?= Html::a('<i class="fa fa-long-arrow-left"></i><span class="back-step">Back to previous step</span>', ['#'], ['class' => 'btn-back-2-1']); ?>
            </div>
        </div>
    </div>
    <?php
}
?>


<style>
    input[type=radio] {
        position: relative;
        top: 2px;
    }

    .available-sessions-list {
        list-style-type: none;
        padding-left: 0px;
    }
</style>

<script>
    $(function () {
        $('.btn-submit-date').on('click', function () {
            var selectedSession = $('input[name=sessionRadio]:checked').val();
            if (selectedSession == undefined || selectedSession == null) {
                $('.noSelection').fadeIn();
            } else {
                $('.noSelection').slideUp('fast');
                // window.location.href = selectedSession;
                redirectTo(selectedSession);
            }
        });

        $('.btn-back-2-1').on('click', function (evt) {
            evt.preventDefault();
            $('#choose-location').val('').trigger('change');
        });
    });

</script>
