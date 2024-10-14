<?php

use app\models\ApplicationType;
use app\models\TestSite;
use app\models\TestSession;

function getFilteredSites($testSites)
{
    $timeNow = date('Y-m-d H:i:s', strtotime('now'));
    $testSitesFiltered = [];
    
    $counts = [];

    foreach ($testSites as $testSite) {
        $items = TestSession::find()
            // ->where(['>', 'registration_close_date', $timeNow])
            ->where(['=', 'test_site_id', $testSite->id])
            ->orderBy('start_date asc')
            ->all();

        $items = array_filter($items, function ($item) {
            $itemTime = strtotime($item->registration_close_date);
            return $itemTime > strtotime('now');
        });

        $items = array_values($items);

        if (count($items) > 0) {
            $testSitesFiltered[] = $testSite;
            $counts[$testSite->id] = [
                'days' => 0,
                'sessions' => count($items),
            ];

            try {
                $session = $items[0];

                $startDateStr = strtotime($session->start_date);
                $endDateStr = strtotime($session->end_date);
                $count_date_session_var = (((($endDateStr - $startDateStr) / 60) / 60) / 24) + 1;

                $counts[$testSite->id] = [
                    'days' => $count_date_session_var,
                    'sessions' => count($items),
                ];
            } catch (\Throwable $e) {
            }
        }
    }

    return [$testSitesFiltered, $counts];
}

$hasUniqueCode = false;
$testSiteId = false;
if ($uniqueCode != '') {
    $testSites = TestSite::findAll([
        'type' => TestSite::TYPE_WRITTEN,
        'uniqueCode' => $uniqueCode,
        'scheduleType' => TestSite::SCHEDULE_TYPE_OPENED
    ]);
    $hasUniqueCode = true;
    $testSiteId = count($testSites) == 1 ? $testSites[0]->id : false;
    list($testSites, $infoCounts) = getFilteredSites($testSites);
} else {
    $testSites = TestSite::findAll([
        'type' => TestSite::TYPE_WRITTEN,
        'enrollmentType' => TestSite::ENROLLMENT_TYPE_PUBLIC,
        'scheduleType' => TestSite::SCHEDULE_TYPE_OPENED
    ]);
    list($testSites, $infoCounts) = getFilteredSites($testSites);
}


//$testSitees = TestSession::findOne(['test_site_id' => 1]);var_dump($testSitees);die;
?>

<style>
    .available-sessions {
        display: none;
    }
</style>

<?= yii\base\View::render('partials/wizard', ['step' => 1]) ?>
<?= yii\base\View::render('partials/_titles', ['step' => 1]) ?>

<div class="row form-horizontal">
    <div class="col-xs-12">
        <div class="clearfix">
            <div class="section-title">Class Location</div>
        </div>
        <div class="section-content">
            <div class="form-group">
                <label class="col-md-4 col-sm-3 control-label">Select a Class Location</label>
                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-5">

                    <select data-current-val="<?= isset($testSiteId) ? $testSiteId : '' ?>" id="choose-location" class="form-control" data-app-type-id="<?= base64_encode($appType->id) ?>" data-candidate-idx="<?= ($candidateId) ?>" data-candidate-id="<?= md5($candidateId) ?>" data-referral-code="<?= $referralCode ?>" data-unique-code="<?= $uniqueCode ?>">
                        <option value="">Please Choose</option>
                        <?php foreach ($testSites as $testSite) { ?>
                            <?php if ($testSite->id) : ?>
                                <?php $testSitees = TestSession::findOne(['test_site_id' => $testSite->id]);
                                $classdays = (strtotime($testSitees['end_date']) - strtotime($testSitees['start_date'])) / 86400;

                                if (!empty($days) and $days == $classdays + 1) : ?>
                                    <option
                                        <?= $testSiteId == $testSite->id || $hasUniqueCode ? 'selected' : '' ?>
                                        value="<?= $testSite->id ?>"
                                    >
                                        <?= $testSite->city . ', ' . $testSite->state . ' ' ?>
                                        -
                                        <?= $infoCounts[$testSite->id]['days'] . ' ' ?> Day Class
                                    </option>

                                <?php elseif ($days == '') : ?>
                                    <option
                                        <?= $testSiteId == $testSite->id || $hasUniqueCode ? 'selected' : '' ?>
                                        value="<?= $testSite->id ?>"
                                    >
                                        <?= $testSite->city . ', ' . $testSite->state ?>
                                       -
                                        <?= $infoCounts[$testSite->id]['days'] . ' ' ?>Day Class
                                    </option>

                                <?php endif; ?>
                            <?php endif; ?>
                        <?php } ?>

                        <? //= $testSite->getTestSiteLocationForRegistration() 
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12" id="spinner" style="text-align: center; display: none;">
        <i class="fa fa-spinner fa-spin" style="font-size: 50px; color: #005094;" aria-hidden="true"></i>
    </div>
    <div class="col-xs-12">
        <div class="available-sessions"></div>
    </div>
</div>

