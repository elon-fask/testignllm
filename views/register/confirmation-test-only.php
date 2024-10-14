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
        <div>
            <p>Please continue your registration at the NCCCO website using the following details: </p>
            <div style="display: flex; justify-content: center;">
            <table style="border: none;">
            <tbody>
            <tr>
                <td style="border: none;">Test Site Number</td>
                <td style="border: none;"><?= $testSiteNumber ?></td>
            </tr>
            <tr>
                <td style="border: none;">Test Coordinator</td>
                <td style="border: none;"><?= $testCoordinator ?></td>
            </tr>
            <tr>
                <td style="border: none;">Testing Site Address</td>
                <td style="border: none;">
                <div>
                    <?= $testSite->address ?><br />
                    <?= $testSite->city . ', ' . $testSite->state . ' ' . $testSite->zip ?>
                </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;">Testing Date</td>
                <td style="border: none;"><?= $testingDate ?></td>
            </tr>
            <tr>
                <td style="border: none;">NCCCO Website</td>
                <td style="border: none;">
                <div><a href="https://onlineforms.nccco.org/PBT-application-prerequisites.aspx" class="btn btn-primary" target="_blank">Click Here to Continue</a></div>
                </td>
            </tr>
            </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<style>
    td {
        padding: 8px;
    }

    td:first-child {
        font-weight: bold;
        text-align: right;
    }
</style>
