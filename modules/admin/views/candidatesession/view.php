<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Candidates;
use app\models\ApplicationType;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = 'View Candidate';
$this->params['breadcrumbs'][] = ['label' => 'Candidate Sessions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$appTypes = ApplicationType::find()->all();
?>

<div class="candidate-session-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => md5($model->id)], ['class' => 'btn btn-primary']) ?>
        <?php  Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    

<style>
    .container-civil-state .control-label{
        font-weight: normal;
        margin-bottom: 0;
    }
    .container-civil-state {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
    }
    .form-control.readonly{
    	background-color: white;
    }
</style>
<?php 
$candidate = Candidates::findOne($model->candidate_id);
?>
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
                <input type="text"  value="<?= $candidate->first_name ?>" class="form-control readonly"  readonly id="W_FIRST_NAME" name="W_FIRST_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_MIDDLE_NAME" class="control-label">Middle</label>
                <input type="text" value="<?= $candidate->middle_name ?>" class="form-control readonly"  readonly id="W_MIDDLE_NAME" name="W_MIDDLE_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_LAST_NAME" class="control-label">Last</label>
                <input type="text"  value="<?= $candidate->last_name ?>" class="form-control readonly"  readonly id="W_LAST_NAME" name="W_LAST_NAME"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_SUFFIX" class="control-label"l>Suffix (Jr., Sr., III)</label>
                <input type="text"  value="<?= $candidate->suffix ?>" class="form-control readonly"  readonly id="W_SUFFIX" name="W_SUFFIX"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_CCO_CERT_NUMBER" class="control-label">CCO CERTIFICATION NUMBER (if previously certified)</label>
                <input type="text" value="<?= $candidate->ccoCertNumber ?>" class="form-control readonly"  readonly id="W_CCO_CERT_NUMBER" name="W_CCO_CERT_NUMBER"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_DOB" class="control-label">DATE OF BIRTH</label>
                <input type="text" value="<?= $candidate->birthday ?>" class="form-control readonly"  readonly id="W_DOB" name="W_DOB"/>
            </div>
        </div>

        <div class="col-xs-5">
            <div class="form-group">
                <label class="control-label" style="width: 100%;">SOCIAL SECURITY #</label>
                <input type="text" value="<?= $candidate->ssn1 ?>" class="form-control readonly"  readonly id="W_SSN_1-3" name="W_SSN_1-3" style="float: left; width: 52px; margin-right: 5px;" maxlength="3"/>
                <input type="text" value="<?= $candidate->ssn2 ?>" class="form-control readonly"  readonly id="W_SSN_4-5" name="W_SSN_4-5" style="float: left; width: 45px; margin-right: 5px;" maxlength="2"/>
                <input type="text" value="<?= $candidate->ssn3 ?>" class="form-control readonly"  readonly id="W_SSN_6-9" name="W_SSN_6-9" style="float: left; width: 60px;" maxlength="4"/>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_ADDRESS" class="control-label">MAILING ADDRESS</label>
                <input type="text" value="<?= $candidate->address ?>" class="form-control readonly"  readonly id="W_ADDRESS" name="W_ADDRESS"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CITY" class="control-label">CITY</label>
                <input type="text" value="<?= $candidate->city ?>" class="form-control readonly"  readonly id="W_CITY" name="W_CITY"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_STATE" class="control-label">STATE</label>
                <input type="text" value="<?= $candidate->state ?>" class="form-control readonly"  readonly id="W_STATE" name="W_STATE"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_ZIP" class="control-label">ZIP</label>
                <input type="text" value="<?= $candidate->zip ?>" class="form-control readonly"  readonly id="W_ZIP" name="W_ZIP"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_PHONE" class="control-label">PHONE</label>
                <input type="text" value="<?= $candidate->phone ?>" class="form-control readonly"  readonly id="W_PHONE" name="W_PHONE"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CELL" class="control-label">CELL</label>
                <input type="text" value="<?= $candidate->cellNumber ?>" class="form-control readonly"  readonly id="W_CELL" name="W_CELL"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_FAX" class="control-label">FAX</label>
                <input type="text" value="<?= $candidate->faxNumber ?>" class="form-control readonly"  readonly id="W_FAX" name="W_FAX"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_EMAIL" class="control-label">E-MAIL</label>
                <input type="text" value="<?= $candidate->email ?>" class="form-control readonly"  readonly id="W_EMAIL" name="W_EMAIL"/>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-9">
            <div class="form-group">
                <label for="W_COMPANY_NAME" class="control-label">COMPANY/ORGANIZATION</label>
                <input type="text" value="<?= $candidate->company_name ?>" class="form-control readonly"  readonly id="W_COMPANY_NAME" name="W_COMPANY_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_PHONE" class="control-label">PHONE</label>
                <input type="text" value="<?= $candidate->company_phone ?>" class="form-control readonly"  readonly id="W_COMPANY_PHONE" name="W_COMPANY_PHONE"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_COMPANY_ADDRESS" class="control-label">COMPANY MAILING ADDRESS</label>
                <input type="text" value="<?= $candidate->company_address ?>" class="form-control readonly"  readonly id="W_COMPANY_ADDRESS" name="W_COMPANY_ADDRESS"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_CITY" class="control-label">CITY</label>
                <input type="text" value="<?= $candidate->company_city ?>" class="form-control readonly"  readonly id="W_COMPANY_CITY" name="W_COMPANY_CITY"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_STATE" class="control-label">STATE</label>
                <input type="text" value="<?= $candidate->company_state ?>" class="form-control readonly"  readonly id="W_COMPANY_STATE" name="W_COMPANY_STATE"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_ZIP" class="control-label">ZIP</label>
                <input type="text" value="<?= $candidate->company_zip ?>" class="form-control readonly"  readonly id="W_COMPANY_ZIP" name="W_COMPANY_ZIP"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <input type="checkbox" <?= $candidate->requestAda == 1 ? 'checked' : '' ?> id="W_ADA_REQUEST_ACCOMMODATIONS" disabled name="W_ADA_REQUEST_ACCOMMODATIONS" style="float: left; margin-right: 4px;"/>
                <label for="W_ADA_REQUEST_ACCOMMODATIONS" class="control-label">I AM REQUESTING TESTING ACCOMMODATIONS IN COMPLIANCE WITH THE AMERICAN WITH DISABILITIES ACT (ADA).<br/>(For details on NCCCO’s Testing Accommodations policy, please see www.nccco.org/accommodations.)</label>
            </div>
        </div>
    </div>
</div>
</div>
