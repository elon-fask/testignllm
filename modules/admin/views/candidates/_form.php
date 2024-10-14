<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Candidates;
use app\helpers\UtilityHelper;
use app\models\ApplicationType;
use app\models\ApplicationTypeFormSetup;
use app\models\AppConfig;
use app\models\TestSite;
use app\models\TestSession;
use app\models\CandidatePreviousSession;
use app\assets\DatalistPolyfillAsset;
use app\assets\ReactStudentUpdateAsset;

$hasApplicationType = isset($candidate->applicationType);

if (!$candidate->isNewRecord) {
    ReactStudentUpdateAsset::register($this);
}

DatalistPolyfillAsset::register($this);

$written = $candidate->getWrittenTestSession();
$practical = $candidate->getPracticalSession();

$testSessionIds = [];
if ($written) {
    $testSessionIds[] = $written->test_session_id;
}
if ($practical) {
    $testSessionIds[] = $practical->test_session_id;
}

$stateList = UtilityHelper::StateList();
$appTypes = ApplicationType::find()->all();

$appTypesJson = json_encode(ArrayHelper::toArray($appTypes, [
    'app\models\ApplicationType' => [
        'id',
        'name',
        'keyword',
        'description',
        'price',
        'cross_out_cc_fields',
        'is_recert' => function($appType) {
            return $appType->hasRecertForm() ? true : false;
        }
    ]
]));

$prevWrittenSessions = $candidate->getAllPreviousWrittenSession();
$prevPracticalSessions = $candidate->getAllPreviousPracticalSession();

$writtenSessionGraded = false;
if ($written && count($prevWrittenSessions) > 0) {
    $writtenSessionGraded = array_reduce($prevWrittenSessions, function($acc, $prevSession) use ($written) {
        if ($prevSession->test_session_id == $written->test_session_id && count(json_decode($prevSession->craneStatus)) > 0) {
            return true;
        }
        return $acc;
    }, false);
}

$practicalSessionGraded = false;
if ($practical && count($prevPracticalSessions) > 0) {
    $practicalSessionGraded = array_reduce($prevPracticalSessions, function($acc, $prevSession) use ($practical) {
        if ($prevSession->test_session_id == $practical->test_session_id && count(json_decode($prevSession->craneStatus)) > 0) {
            return true;
        }
        return $acc;
    }, false);
}

$instructor = '';
$startDate = '';
$endDate = '';

try {
    if ($practical) {
        $testSession = TestSession::findOne($practical->test_session_id);
        $instructor = $testSession->getInstructorName(false);
        $startDate = $testSession->start_date;
        $endDate = $testSession->end_date;
    }

    if ($written) {
        $testSession = TestSession::findOne($written->test_session_id);
        $instructor = $testSession->getInstructorName(false);
        $startDate = $testSession->start_date;
        $endDate = $testSession->end_date;
    }
} catch (Exception $e) {
}


$candidateArr = ArrayHelper::toArray($candidate, [
    'app\models\Candidates' => [
        'id',
        'md5' => function($candidate) {
            return md5($candidate->id);
        },
        'email',
        'signed_w_form_received',
        'signed_p_form_received',
        'confirmation_email_last_sent',
        'app_form_sent_to_nccco',
        'custom_form_setup',
        'applicationType' => function($candidate) {
            $applicationType = $candidate->applicationType;
            return ArrayHelper::toArray($applicationType, [
                'app\models\ApplicationType' => [
                    'price',
                    'applicationFormSetups'
                ]
            ]);
        },
        'transactions',
        'practicalTestSchedule',
        'grades' => function ($candidate) use($testSessionIds) {
            $grades = CandidatePreviousSession::find()->where(['candidate_id' => $candidate->id])->andWhere(['in', 'test_session_id', $testSessionIds])->all();
            $gradesArr = ArrayHelper::toArray($grades, [
                'app\models\CandidatePreviousSession' => [
                    'results' => function($grade) {
                        return json_decode($grade->craneStatus);
                    },
                    'date_created',
                    'id',
                    'isConfirmed',
                    'isGraded',
                    'isPass',
                    'remarks',
                    'test_session_id',
                    'type' => function($grade) {
                        return isset($grade->testSession->practical_test_session_id) ? 'written' : 'practical';
                    }
                ]
            ]);
            return $gradesArr;
        },
        'written_nccco_fee_override',
        'practical_nccco_fee_override',
        'practice_time_credits',
        'writtenTestSession' => function($candidate) use ($written, $writtenSessionGraded) {
            $testSession = $written;

            if ($testSession) {
                return [
                    'id' => $testSession->test_session_id,
                    'description' => $testSession->getFullTestSessionDescription(),
                    'graded' => $writtenSessionGraded,
                    'passed' => !!$testSession->isPass
                ];
            }

            return null;
        },
        'practicalTestSession' => function($candidate) use($practical, $practicalSessionGraded) {
            $testSession = $practical;

            if ($testSession) {
                return [
                    'id' => $testSession->test_session_id,
                    'description' => $testSession->getFullTestSessionDescription(),
                    'graded' => $practicalSessionGraded,
                    'passed' => !!$testSession->isPass
                ];
            }

            return null;
        },
        'instructor' => function() use ($instructor) {
            return $instructor;
        },
        'classStartDate' => function() use ($startDate) {
            return $startDate;
        },
        'classEndDate' => function() use ($endDate) {
            return $endDate;
        },
        'declinedTests'
    ]
]);

/* Read only on View Application */
if(isset($isView) && $isView == true){
    $isView = true;
}else{
    $isView = false;
}

/* View or Edit */
if( $isView){
    ?>
    <script>
        $(function() {
            $('#update-candidate').find('input, select').prop('disabled', true);
        });
    </script>
    <?php
}
?>

<form action="/admin/candidates/deletesigned" method="POST" id="delete-signed-form">
    <input type='hidden' name="id" value="<?= md5($candidate->id) ?>"/>
    <input type='hidden' name="f" value=""/>
    <input type='hidden' name="formName" value=""/>
</form>
<form action="/admin/candidates/update?id=<?= md5($candidate->id) ?>" method="POST" id="reset">
    <input type='hidden' name="id" value="<?= md5($candidate->id) ?>"/>
    <input type='hidden' name="reset" value="1"/>
    <input type='hidden' name="x" value="<?= $candidate->id ?>"/>
</form>
<div class="candidates-form">
    <?php if ($candidate->isNewRecord == false) { ?>
        <div id="react-entry"></div>
    <?php } ?>

<?php if ($candidate->hasPreviousSessionsIncludingNonGraded()) { ?>
<div class="panel panel-default">
<div class="panel-heading"><h4>Previous Student Sessions:</h4></div>
<div class="panel-body">
<h5>Written Sessions</h5>

<?php foreach ($prevWrittenSessions as $prevSess) {
    $sessionPrevData = TestSession::findOne($prevSess->test_session_id);
?>

    <div class="panel panel-default" style='margin-bottom: 0px'>
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $prevSess->id?>" aria-expanded="false" aria-controls="collapseOne">
                    <span class='previous-session-info'><?php echo $sessionPrevData->getFullTestSessionDescription()?></span>
                </a>
            </h4>
        </div>

        <div id="collapse-<?php echo $prevSess->id?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <?php $craneStatus = json_decode($prevSess->craneStatus, true); ?>
                <div class='col-xs-12'>
                    <?php
                    if ($prevSess->isGraded == 0) {
                        echo 'Remarks: ' . $prevSess->remarks;
                    } else if ($prevSess->isGraded == 1) { ?>
                        <table class='table table-condensed'>
                            <thead>
                            <tr>
                                <th>Crane Type</th>
                                <th>Pass / Fail / Did Not Test</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($craneStatus as $key => $info) { ?>
                                <tr>
                                    <td><?= $info['name'] ?></td>
                                    <td>
                                        <?php if (isset($info['val'])) { ?>
                                            <?php if ($info['val'] == 1) { ?>
                                                <i class="fa fa-check text-success" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Passed"></i>
                                            <?php } elseif ($info['val'] == 2) { ?>
                                                <i class="fa fa-circle-o text-warning" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Did Not Test"></i>
                                            <?php } elseif ($info['val'] == 0) { ?>
                                                <i class="fa fa-times text-danger" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Failed"></i>
                                            <?php } elseif ($info['val'] == 3) { ?>
                                                <i class="fa fa-times text-danger" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Self Disqualified (SD)"></i>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<h5>Practical Sessions</h5>
<?php
    foreach ($prevPracticalSessions as $prevSess) {
        $sessionPrevData = TestSession::findOne($prevSess->test_session_id);
?>
    <div class="panel panel-default" style='margin-bottom: 0px'>
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?= $prevSess->id ?>" aria-expanded="false" aria-controls="collapseOne">
                    <span class='previous-session-info'><?= $sessionPrevData->getFullTestSessionDescription() ?></span>
                </a>
            </h4>
        </div>
        <div id="collapse-<?= $prevSess->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <?php
                $craneStatus = json_decode($prevSess->craneStatus, true);
                ?>
                <div class='col-xs-12'>
                    <?php
                        if ($prevSess->isGraded == 0) {
                            echo 'Remarks: '. $prevSess->remarks;
                        } elseif ($prevSess->isGraded == 1) {
                    ?>
                        <table class='table table-condensed'>
                            <thead>
                            <tr>
                                <th>Crane Type</th>
                                <th>Pass / Fail / Did Not Test</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($craneStatus as $key => $info) { ?>
                                <tr>
                                    <td><?= $info['name'] ?></td>
                                    <td>
                                        <?php if (isset($info['val'])) { ?>
                                            <?php if ($info['val'] == 1) { ?>
                                                <i class="fa fa-check text-success" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Passed"></i>
                                            <?php } elseif ($info['val'] == 2) { ?>
                                                <i class="fa fa-circle-o text-warning" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Did Not Test"></i>
                                            <?php } elseif ($info['val'] == 0) { ?>
                                                <i class="fa fa-times text-danger" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Failed"></i>
                                            <?php } elseif ($info['val'] == 3) { ?>
                                                <i class="fa fa-times text-danger" style="font-size: 24px;" data-toggle="tooltip" data-placement="left" title="Self Disqualified (SD)"></i>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php } ?>
</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Application Form Details:</h4>
    </div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id'=>'update-candidate',
        'action' => '/admin/candidates/update?id='.md5($candidate->id)]]); ?>

    <input type="hidden" value="<?= $candidate->id ?>" name="Candidates[id]" />
    <input type="hidden" value="<?= $hasApplicationType && $candidate->applicationType->id ? $candidate->applicationType->id : '' ?>" data-is-recert="<?= $candidate->isNewRecord ? 'false' : $candidate->applicationType->hasRecertForm() ? 'true' : 'false' ?>" name="Candidates[application_type_id]" />
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label for="app-type" class="control-label">Application Type</label>
                <input id="app-type" list="app-type-list" class="form-control" value="<?= $hasApplicationType && $candidate->applicationType->keyword ? $candidate->applicationType->keyword : '' ?>" class="form-control required" required <?= ($candidate->isNewRecord || !$isView) ? '' : 'disabled' ?> />
                <div class="help-block"></div>
                <datalist id="app-type-list">
                <?php foreach($appTypes as $appType) { ?>
                    <option id=<?= 'app-type-list-' . $appType->keyword ?> value=<?= $appType->keyword ?> />
                <?php } ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="app-type-details" class="control-label">Application Type Details</label>
                <figure id="app-type-details" class="highlight">
                </figure>
            </div>
            <div class="form-group">
                <label for="candidate-is-po-select" class="control-label">Is Purchase Order</label>
                <select name="Candidates[isPurchaseOrder]" class="form-control" id="candidate-is-po-select" required>
                    <option value="0" <?= $candidate->isPurchaseOrder == 0 ? 'selected' : '' ?> >No</option>
                    <option value="1" <?= $candidate->isPurchaseOrder == 1 ? 'selected' : '' ?> >Yes</option>
                </select>
            </div>
            <div class="form-group" id="candidate-po-input-field" style="display: <?= $candidate->isPurchaseOrder == 0 ? 'none' : 'block'?>">
                <label for="candidate-po" class="control-label">Purchase Order Number</label>
                <input id="candidate-po" type="text" value="<?= $candidate->purchase_order_number ?>" class="form-control" name="Candidates[purchase_order_number]"/>
                <div class="help-block"></div>
            </div>
            <div class="form-group" id="candidate-invoice-number-field">
                <label for="candidate-invoice-number" class="control-label">Invoice Number</label>
                <input id="candidate-invoice-number" type="text" value="<?= $candidate->invoice_number ?>" class="form-control" name="Candidates[invoice_number]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <?php if ($candidate->isNewRecord == false) { ?>
        <div id="react-entry-nccco-fees"></div>
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-xs-2">
            <div>FULL LEGAL NAME</div>
            <div style="padding-top: 5px"><small>(as shown on driver's license)</small></div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_FIRST_NAME" class="control-label">First</label>
                <input type="text"  value="<?php echo $candidate->first_name?>" class="form-control required"   id="W_FIRST_NAME" name="Candidates[first_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_MIDDLE_NAME" class="control-label">Middle</label>
                <input type="text" value="<?php echo $candidate->middle_name?>" class="form-control"   id="W_MIDDLE_NAME" name="Candidates[middle_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_LAST_NAME" class="control-label">Last</label>
                <input type="text"  value="<?php echo $candidate->last_name?>" class="form-control required" id="W_LAST_NAME" name="Candidates[last_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_SUFFIX" class="control-label"l>Suffix (Jr., Sr., III)</label>
                <input type="text"  value="<?php echo $candidate->suffix?>" class="form-control "   id="W_SUFFIX" name="Candidates[suffix]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_CCO_ID" class="control-label">CCO ID</label>
                <input type="number" minlength="9" maxlength="9" required value="<?php echo $candidate->cco_id ?>" class="form-control required" id="W_CCO_ID" name="Candidates[cco_id]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_CCO_CERT_NUMBER" class="control-label">CCO CERTIFICATION NUMBER (if previously certified)</label>
                <input type="text" value="<?php echo $candidate->ccoCertNumber?>" class="form-control " id="W_CCO_CERT_NUMBER" name="Candidates[ccoCertNumber]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_DOB" class="control-label">DATE OF BIRTH</label>
                <input type="text" value="<?php echo $candidate->birthday?>" class="form-control required" id="candidates-birthday" name="Candidates[birthday]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_ADDRESS" class="control-label">HOME ADDRESS</label>
                <input type="text" value="<?php echo $candidate->address?>" class="form-control " id="W_ADDRESS" name="Candidates[address]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CITY" class="control-label">CITY</label>
                <input type="text" value="<?php echo $candidate->city?>" class="form-control "   id="W_CITY" name="Candidates[city]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_STATE" class="control-label">STATE</label>
                <select class="form-control" name="Candidates[state]">
                    <option value="">Select State</option>
                    <?php foreach($stateList as $key => $val){?>
                        <option <?php echo $candidate->state == $key ? 'selected' : ''?>  value="<?php echo $key?>"><?php echo $val?></option>
                    <?php }?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_ZIP" class="control-label">ZIP</label>
                <input type="text"  maxlength="12" value="<?php echo $candidate->zip?>" class="form-control "   id="W_ZIP" name="Candidates[zip]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_PHONE" class="control-label">PHONE</label>
                <input type="text" value="<?php echo $candidate->phone?>" class="form-control phone required"   id="W_PHONE" name="Candidates[phone]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CELL" class="control-label">CELL</label>
                <input type="text" value="<?php echo $candidate->cellNumber?>" class="form-control phone"   id="W_CELL" name="Candidates[cellNumber]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_FAX" class="control-label">FAX</label>
                <input type="text" value="<?php echo $candidate->faxNumber?>" class="form-control phone"   id="W_FAX" name="Candidates[faxNumber]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_EMAIL" class="control-label">E-MAIL</label>
                <input type="email" value="<?php echo $candidate->email?>" class="form-control required email"   id="W_EMAIL" name=Candidates[email]/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h4 class="zone-title">Company Information</h4>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-xs-9">
            <div class="form-group">
                <label for="W_COMPANY_NAME" class="control-label">COMPANY/ORGANIZATION</label>
                <input type="text" value="<?= $candidate->company_name ?>" class="form-control "   id="W_COMPANY_NAME" name="Candidates[company_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_PHONE" class="control-label">PHONE</label>
                <input type="text" value="<?= $candidate->company_phone ?>" class="form-control phone"   id="W_COMPANY_PHONE" name="Candidates[company_phone]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_COMPANY_ADDRESS" class="control-label">COMPANY ADDRESS</label>
                <input type="text" value="<?php echo $candidate->company_address?>" class="form-control "   id="W_COMPANY_ADDRESS" name="Candidates[company_address]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_CITY" class="control-label">CITY</label>
                <input type="text" value="<?php echo $candidate->company_city?>" class="form-control "   id="W_COMPANY_CITY" name="Candidates[company_city]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_STATE" class="control-label">STATE</label>
                <select class="form-control" name="Candidates[company_state]">
                    <option value="">Select State</option>
                    <?php foreach($stateList as $key => $val){?>
                        <option <?= $candidate->company_state == $key ? 'selected' : '' ?>  value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_ZIP" class="control-label">ZIP</label>
                <input type="text" maxlength="12" value="<?= $candidate->company_zip ?>" class="form-control "   id="W_COMPANY_ZIP" name="Candidates[company_zip]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label">CONTACT NAME</label>
                <input type="text" value="<?= $candidate->contact_person ?>" class="form-control " name="Candidates[contact_person]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label  class="control-label">CONTACT EMAIL</label>
                <input type="email" value="<?= $candidate->contactEmail ?>" class="form-control email"   name="Candidates[contactEmail]"/>
                <div class="help-block"></div>
            </div>
        </div>

    </div>

    <input type="hidden" name="Candidates[requestAda]" value="0"/>

    <?= $this->render('../application/form-styles') ?>

    <?php if (isset($isView) && $isView == true) { ?>
    <?php } else { ?>
        <div class="form-group pull-right">
            <?= Html::button($candidate->isNewRecord ? 'Create' : 'Update', ['class' => $candidate->isNewRecord ? 'btn btn-success btn-update-candidate' : 'btn btn-primary btn-update-candidate']) ?>
        </div>
    <?php }?>
    <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
<script>
var reactCandidate = <?= json_encode($candidateArr) ?>;
<?php if ($practical) { ?>
    var reactPracticalTestSessionId = <?= $practical->test_session_id ?>;
<?php } else { ?>
    var reactPracticalTestSessionId = null;
<?php } ?>

var appTypes = <?= $appTypesJson ?>;
var appTypeDetails = $('#app-type-details');

var currentAppType = $('#app-type').val();
var appTypeField = $('input[name="Candidates[application_type_id]"]');

if (currentAppType !== '') {
    var appType = appTypes.find(function(appType) {
        return appType.keyword === currentAppType
    });

    appTypeDetails.html(`
        Name: ${appType.name}<br />
        Keyword: ${appType.keyword}<br />
        Price: $${appType.price}<br />
        Description: ${appType.description}
    `);

    appTypeField.attr('data-is-recert', appType.is_recert);
}

$('#app-type').on('input', function(event) {
    var appType = appTypes.find(function(appType) {
        return appType.keyword === event.target.value
    });

    if (typeof appType !== 'undefined') {
        appTypeField.val(appType.id);
        appTypeField.attr('data-is-recert', appType.is_recert);

        var ccoCertNumberField = $('input[name="Candidates[ccoCertNumber]"]');

        if (!appType.is_recert && ccoCertNumberField.parent().hasClass('has-error')) {
            ccoCertNumberField.parent().find('.help-block').html('');
            ccoCertNumberField.parent().removeClass('has-error');
        }

        appTypeDetails.html(`
        Name: ${appType.name}<br />
        Keyword: ${appType.keyword}<br />
        Price: $${appType.price}<br />
        Description: ${appType.description}
        `);
        return;
    }

    $('input[name="Candidates[application_type_id]"]').val('');
    appTypeDetails.html('Application Type not found. <a href="/admin/application" target="_blank">Search Application Types</a>');
});

$('#update-candidate').submit(function(event) {
    console.log('testing');
    event.preventDefault();
})

    $('#candidate-is-po-select').change(function(event) {
        var isPO = event.target.value === '1';
        var poFormGroup = $('#candidate-po-input-field');
        var poField = $('#candidate-po-input-field input.form-control')
        if (isPO) {
            poFormGroup.css('display', 'block');
            poField.addClass('required');
        } else {
            poFormGroup.css('display', 'none');
            poField.removeClass('required');
        }
    });

    $(function() {
        $('.do-manual-confirm').on('click', function(){
            var self = $(this);
            $.confirm({
                title: "Confirm Signed PDF Submission",
                content: "Do you really want to confirm that the student has submitted a signed PDF?",
                confirmButton: 'Yes, PDF Submitted',
                cancelButton:'No, Cancel',
                confirm: function(){
                    $.post('/admin/candidates/manual-confirm', 'formName=' + self.data('form-name')+'&id=' + self.data('candidate-id'), function(data){
                        var resp = $.parseJSON(data);
                        if(resp.status==1){
                            window.location.reload();
                        }else{
                            alert('Manual confirm did not work, please try again');
                        }
                    })
                }
            });
        });
    });

    var changes = {};

    $('#update-candidate input').on("change paste keyup",
        function() {
            changes[$(this).siblings('label').text()] = $(this).val();
        }
    );

    function generateConfirmChangesInnerHtml(changes) {
        if ($.isEmptyObject(changes)) {
            return '';
        }

        var result = '';

        for (var key in changes) {
            result += ('<li>' + key + ': ' + changes[key] + '</li>');
        }

        return result;
    }
</script>

<div class="modal fade" id="confirm-changes-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-changes-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="confirm-changes-modal-label">Confirm Values</h4>
            </div>
            <div class="modal-body" id="confirm-changes-modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn-confirm-changes" class="btn btn-primary">Confirm values</button>
            </div>
        </div>
    </div>
</div>
