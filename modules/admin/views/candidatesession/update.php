<?php

use yii\helpers\Html;
use app\models\Candidates;
use app\helpers\UtilityHelper;
use app\models\ApplicationType;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = 'Update Candidate';
$this->params['breadcrumbs'][] = ['label' => 'Candidate Sessions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$stateList = UtilityHelper::StateList();
$candidate = Candidates::findOne($model->candidate_id);
$appTypes = ApplicationType::find()->all();
?>
<div class="candidate-session-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <form action="/admin/candidatesession/update?id=<?= md5($model->id) ?>" method="POST">
    <input type="hidden"  value="<?= $candidate->id ?>" class="form-control"  name="Candidates[id]"/>
    <div class="container-civil-state">

    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <label for="app-type" class="control-label">Application Type</label>
                <select name="applicationTypeId" class="form-control required" disabled>
                    <option value="">Please Select</option>
                    <?php foreach ($appTypes as $appType) { ?>
                    <option value="<?= $appType->id ?>" <?= $model->application_type_id == $appType->id ? 'selected': '' ?>><?= $appType->name . ' - ' . $appType->price ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-2">
            <div>FULL LEGAL NAME</div>
            <div style="padding-top: 5px"><small>(as shown on driver's license)</small></div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_FIRST_NAME" class="control-label">First</label>
                <input type="text"  value="<?= $candidate->first_name ?>" class="form-control required"   id="W_FIRST_NAME" name="Candidates[first_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_MIDDLE_NAME" class="control-label">Middle</label>
                <input type="text" value="<?= $candidate->middle_name ?>" class="form-control required"   id="W_MIDDLE_NAME" name="Candidates[middle_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_LAST_NAME" class="control-label">Last</label>
                <input type="text"  value="<?= $candidate->last_name ?>" class="form-control required"   id="W_LAST_NAME" name="Candidates[last_name]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_SUFFIX" class="control-label"l>Suffix (Jr., Sr., III)</label>
                <input type="text"  value="<?= $candidate->suffix ?>" class="form-control "   id="W_SUFFIX" name="Candidates[suffix]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_CCO_CERT_NUMBER" class="control-label">CCO CERTIFICATION NUMBER (if previously certified)</label>
                <input type="text" value="<?= $candidate->ccoCertNumber ?>" class="form-control "   id="W_CCO_CERT_NUMBER" name="Candidates[ccoCertNumber]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_DOB" class="control-label">DATE OF BIRTH</label>
                <input type="text" value="<?= $candidate->birthday ?>" class="form-control required"   id="candidates-birthday" name="Candidates[birthday]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-5">
            <div class="form-group">
                <label class="control-label" style="width: 100%;">SOCIAL SECURITY #</label>
                <input type="text" value="<?= $candidate->ssn1 ?>" class="form-control "   id="W_SSN_1-3" name="Candidates[ssn1]" style="float: left; width: 52px; margin-right: 5px;" maxlength="3"/>                
                <input type="text" value="<?= $candidate->ssn2 ?>" class="form-control "   id="W_SSN_4-5" name="Candidates[ssn2]" style="float: left; width: 45px; margin-right: 5px;" maxlength="2"/>
                <input type="text" value="<?= $candidate->ssn3 ?>" class="form-control "   id="W_SSN_6-9" name="Candidates[ssn3]" style="float: left; width: 60px;" maxlength="4"/>
                <div class="help-block col-xs-12"></div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_ADDRESS" class="control-label">MAILING ADDRESS</label>
                <input type="text" value="<?= $candidate->address ?>" class="form-control "   id="W_ADDRESS" name="Candidates[address]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CITY" class="control-label">CITY</label>
                <input type="text" value="<?= $candidate->city ?>" class="form-control "   id="W_CITY" name="Candidates[city]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_STATE" class="control-label">STATE</label>
                <select class="form-control" name="Candidates[state]">
                    <option value="">Select State</option>
                    <?php foreach($stateList as $key => $val) { ?>
                    <option <?= $candidate->state == $key ? 'selected' : ''?> value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_ZIP" class="control-label">ZIP</label>
                <input type="text" value="<?= $candidate->zip ?>" class="form-control "   id="W_ZIP" name="Candidates[zip]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_PHONE" class="control-label">PHONE</label>
                <input type="text" value="<?= $candidate->phone ?>" class="form-control phone required"   id="W_PHONE" name="Candidates[phone]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CELL" class="control-label">CELL</label>
                <input type="text" value="<?= $candidate->cellNumber ?>" class="form-control phone"   id="W_CELL" name="Candidates[cellNumber]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_FAX" class="control-label">FAX</label>
                <input type="text" value="<?= $candidate->faxNumber ?>" class="form-control phone"   id="W_FAX" name="Candidates[faxNumber]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_EMAIL" class="control-label">E-MAIL</label>
                <input type="email" value="<?= $candidate->email ?>" class="form-control required"   id="W_EMAIL" name=Candidates[email]/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>



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
                <label for="W_COMPANY_ADDRESS" class="control-label">COMPANY MAILING ADDRESS</label>
                <input type="text" value="<?= $candidate->company_address ?>" class="form-control "   id="W_COMPANY_ADDRESS" name="Candidates[company_address]"/>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_CITY" class="control-label">CITY</label>
                <input type="text" value="<?= $candidate->company_city ?>" class="form-control "   id="W_COMPANY_CITY" name="Candidates[company_city]"/>
                <div class="help-block"></div>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_STATE" class="control-label">STATE</label>                
                <select class="form-control" name="Candidates[company_state]">
                    <option value="">Select State</option>
                    <?php foreach($stateList as $key => $val) { ?>
                    <option <?= $candidate->company_state == $key ? 'selected' : '' ?> value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_ZIP" class="control-label">ZIP</label>
                <input type="text" value="<?= $candidate->company_zip ?>" class="form-control "   id="W_COMPANY_ZIP" name="Candidates[company_zip]"/>
                <div class="help-block"></div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <input type="checkbox" value="1" <?= $candidate->requestAda == 1 ? 'checked' : '' ?> id="W_ADA_REQUEST_ACCOMMODATIONS" name="Candidates[requestAda]" style="float: left; margin-right: 4px;"/>
                <label for="W_ADA_REQUEST_ACCOMMODATIONS" class="control-label">I AM REQUESTING TESTING ACCOMMODATIONS IN COMPLIANCE WITH THE AMERICAN WITH DISABILITIES ACT (ADA).<br/>(For details on NCCCO’s Testing Accommodations policy, please see www.nccco.org/accommodations.)</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group pull-right">
                <input type="button" class="btn btn-info btn-update-candidate" value="Update"/>
                
            </div>
        </div>
    </div>
    
</div>
</form>
</div>
