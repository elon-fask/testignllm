<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\UtilityHelper;
use app\models\TestSite;
use app\models\Staff;
use app\models\TestSession;
use dosamigos\datepicker\DatePicker;
use app\models\ChecklistTemplate;
use app\models\User;
use app\assets\BootstrapDateTimePickerAsset;

BootstrapDateTimePickerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\TestSession */
/* @var $form yii\widgets\ActiveForm */
?>


<?php
$template       = '{label}<div class="col-xs-12 col-md-5">{input}{error}{hint}</div>';
$labelOptions   = ['class'=>'col-xs-4 control-label'];

$options = ['template'=>$template,'labelOptions'=>$labelOptions];

$testSites = TestSite::find()->all();
$types = array();
$practicalSessions = array();
$practicalTestSitesId = array();
foreach($testSites as $site){
    if($site->type == $type){
        $info = [];
        $info['id'] = $site->id;
        $info['siteNumber'] = $site->siteNumber;
        $types[] = $info;
    }
    if($site->type == TestSite::TYPE_PRACTICAL){
        $practicalTestSitesId[] = $site->id;
    }
}
if(count($practicalTestSitesId) > 0){
    $testSessions = TestSession::find()->where('test_site_id in ('.implode(",", $practicalTestSitesId).')')->orderBy(['start_date' => SORT_DESC])->all();
    foreach($testSessions as $session){

        $practicalSessions[$session->id] = $session->getFullTestSessionDescription(). ' - ('.$session->school.')';
    }
}
$isWrittenTestSession = $type == TestSite::TYPE_WRITTEN ? true : false;

$model->session_type = $type;

$currentTestSite = false;
if(isset($_GET['siteId']) && $_GET['siteId'] != ''){
    $currentTestSite = TestSite::findOne($_GET['siteId'] );
}
if($model->isNewRecord && $currentTestSite){
    if($isWrittenTestSession && !($model->writtenChecklistId > 0) )
        $model->writtenChecklistId = $currentTestSite->writtenChecklistId;
    else if($isWrittenTestSession === false){
        if(!($model->postChecklistId > 0))
            $model->postChecklistId = $currentTestSite->postChecklistId;
        if(!($model->preChecklistId > 0))
            $model->preChecklistId = $currentTestSite->preChecklistId;
    }

    $model->test_site_id = $currentTestSite->id;
}

$timeZones = array_reduce($testSites, function ($acc, $testSite) {
    $tz = new \DateTimeZone($testSite->timeZone);
    $now = new \DateTime('now', $tz);
    $acc[$testSite->id] = $now->format('T P');
    return $acc;
}, []);

$timeZoneStr = null;

if (isset($model->testSite)) {
    $tz = new \DateTimeZone($model->testSite->timeZone);
    $currentDateTime = new \DateTime('now', $tz);
    $timeZoneStr = ' (' . $currentDateTime->format('T P') . ')';
}

$testingDate = null;
$registrationCloseDate = null;
$dateNowStr = new \DateTime('now');

if (isset($model->testing_date)) {
    $testingDateObj = new \DateTime($model->testing_date);
    $testingDate = $testingDateObj->format('m/d/Y h:i A');
} else {
    $testingDateObj = new \DateTime($dateNowStr->format('Y-m-d'));
    $testingDate = $testingDateObj->format('m/d/Y h:i A');
}

if (isset($model->registration_close_date)) {
    $registrationCloseDateObj = new \DateTime($model->registration_close_date);
    $registrationCloseDate = $registrationCloseDateObj->format('m/d/Y h:i A');
} else {
    $registrationCloseDateObj = new \DateTime($dateNowStr->format('Y-m-d'));
    $registrationCloseDate = $registrationCloseDateObj->format('m/d/Y h:i A');
}

?>

<div class="test-session-form form-horizontal">

    <?php $form = ActiveForm::begin(['options'=>['id'=>'session-form', 'data-id' => $model->isNewRecord ? '' : $model->id, 'data-is-new' => $model->isNewRecord ? 1 : 0, 'data-type' => $type, 'data-current-school'=> $model->school]]); ?>

    <?= $form->field($model, 'school', $options)->dropDownList(
        [TestSession::SCHOOL_CCS => TestSession::SCHOOL_CCS, TestSession::SCHOOL_ACS => TestSession::SCHOOL_ACS],           // Flat array ('id'=>'label')
        ['prompt'=>'', 'required'=>'required']    // options
    ); ?>

    <?= $form->field($model, 'test_site_id', $options)->dropDownList(
        UtilityHelper::getTestSites($type),           // Flat array ('id'=>'label')
        ['prompt'=>'', 'required'=>'required', 'onchange'=>'javascript: loadSessionNumber()', 'data-new-record'=>$model->isNewRecord ? 1 : 0, 'data-test-site-mapping' => json_encode($types)]    // options
    ); ?>
    <?= $form->field($model, 'session_number', $options)->textInput(['required'=>'required']); ?>

    <?= $form->field($model, 'enrollmentType', $options)->dropDownList(
        UtilityHelper::getEnrollmentTypes(),           // Flat array ('id'=>'label')
        ['prompt'=>'', 'required'=>'required']    // options
    ); ?>

    <?= $form->field($model, 'numOfCandidates', $options)->textInput(
        ['required'=>'required']    // options
    ); ?>

    <?= $form->field($model, 'nccco_fee_notes', $options)->textarea()->label('NCCCO Fee Notes') ?>
    <div class="form-group" id="datepicker">
        <label for="classes-name" class="control-label col-xs-2 col-md-4">Dates (Training + Testing)</label>
        <?php if(isset($errors['date'])):?>
        
            <span style="margin-left: 25px;" class='text-danger'><?= $errors['date'][0];?></span>
        <?php endif; ?>
        <div class=" col-sm-8" style="padding-left: 25px">
            <input required type="text" class="form-control col-sm-2 pull-left <?= isset($errors['date']) ? 'has-error' : ''?>" name="TestSession[start_date]" value="<?= UtilityHelper::dateconvert($model->start_date, 2) ?>" placeholder="Start Date" style="width: 100px;"/>
            <span class="input-group-addon pull-left text-center" style="width: 40px; line-height: 24px; ">to</span>
            <input required type="text" class="form-control col-sm-2 pull-left <?= isset($errors['date']) ? 'has-error' : ''?>" name="TestSession[end_date]" placeholder="End Date" value="<?= UtilityHelper::dateconvert($model->end_date, 2) ?>" style="width: 100px;"/>
        </div>
    </div>
    <?php if ($isWrittenTestSession) { ?>
        <div class="form-group field-testsession-testing_date">
            <label class="col-xs-4 control-label" for="testsession-testing_date">Testing Date<?= $timeZoneStr ?></label>
            <div class="col-xs-12 col-md-5">
                <div class="input-group date date-time-picker" id="testing-date-field">
                    <input type="text" id="testsession-testing_date" class="form-control" name="TestSession[testing_date]" value="<?= $testingDate ?>" aria-invalid="false">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="form-group field-testsession-registration_close_date">
            <label class="col-xs-4 control-label" for="testsession-registration_close_date">Registration Close Date<?= $timeZoneStr ?></label>
            <div class="col-xs-12 col-md-5">
                <div class="input-group date date-time-picker" id="registration-close-date-field">
                    <input type="text" id="testsession-registration_close_date" class="form-control" name="TestSession[registration_close_date]" value="<?= $registrationCloseDate ?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
            <div class="help-block"></div></div>
        </div>
        <?= $form->field($model, 'instructor_id', $options)->dropDownList(
            $instructors,
            ['prompt'=>'', 'required'=>'required']
        )->label('Instructor'); ?>
    <?php } else { ?>
    <?= $form->field($model, 'staff_id', $options)->dropDownList(
        $practicalExaminers,
        ['prompt'=>'', 'required'=>'required']
    ); ?>
    <?= $form->field($model, 'proctor_id', $options)->dropDownList(
        $proctors,
        ['prompt'=>'']
    ); ?>
    <?php } ?>

    <?= $form->field($model, 'test_coordinator_id', $options)->dropDownList(
        $testSiteCoordinators,
        ['prompt'=>'', 'required'=>'required']
    )->label( ($isWrittenTestSession) ? "Written Test Site Coordinator" : "Practical Test Site Coordinator"); ?>

    <?php
    if ($isWrittenTestSession) {
        echo $form->field($model, 'practical_test_session_id', $options)->dropDownList($practicalSessions,['prompt'=>'']);
        ?>
        <div class="form-group" style="margin-top: -15px">
            <div class=" col-xs-12 col-md-offset-4 col-md-8">
                <p style="font-size: 0.85em">If the Practical Test Session that you wish to select is not listed, then it has not been created yet.<br/>
                    Please go to Sites & Sessions > Add New Practical Session or
                    <?php echo Html::a('click here to create one', '/admin/testsession/create?type=MQ==', array("title"=>"Add New Practical Session", "data-toggle"=>"tooltip", "data-placement"=>"bottom"));?>.</p>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="form-group">
        <div class=" col-xs-12 col-md-offset-4 col-md-5">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-submit-session new' : 'btn btn-primary btn-submit-session old']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    label{ font-weight: normal;}
    @media (max-width: 991px) {
        .form-horizontal .control-label{text-align: left !important;}
    }
</style>
<script>
function loadSessionNumber(){
    if ($('#testsession-test_site_id').data('new-record') == 1) {
        if ($('#testsession-test_site_id').val() == '') {
            $('#testsession-session_number').val('');
        }
        var mapping = $('#testsession-test_site_id').data('test-site-mapping');
        for(var i in mapping){
            if(mapping[i].id == $('#testsession-test_site_id').val()){
                $('#testsession-session_number').val(mapping[i].siteNumber);
                return;
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var timeZones = <?= json_encode($timeZones) ?>;

    $('#testsession-test_site_id').change(function(e) {
        var testSiteId = e.target.value;
        var timeZone = timeZones[testSiteId];

        $('.field-testsession-testing_date > label').text('Testing Date (' + timeZone + ')');
        $('.field-testsession-registration_close_date > label').text('Registration Close Date (' + timeZone + ')');
    });

    $('#testing-date-field').datetimepicker({
        format: 'MM/DD/YYYY hh:mm A'
    });

    $('#registration-close-date-field').datetimepicker({
        format: 'MM/DD/YYYY hh:mm A'
    });

    $('#testsession-testing_date').click(function() {
        $('#testing-date-field').data('DateTimePicker').show();
    });

    $('#testsession-registration_close_date').click(function() {
        $('#registration-close-date-field').data('DateTimePicker').show();
    });

    $('#datepicker input').datetimepicker({
        format: 'MM/DD/YYYY'
    });
}, false);
</script>
