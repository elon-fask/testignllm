

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

</style>
<div class="container-civil-state">
    <div class="row">
        <div class="col-xs-2">
            <div>FULL LEGAL NAME</div>
            <div style="padding-top: 5px"><small>(as shown on driver’s license)</small></div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_FIRST_NAME" class="control-label">First</label>
                <input type="text"  class="form-control" id="W_FIRST_NAME" name="W_FIRST_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_MIDDLE_NAME" class="control-label">Middle</label>
                <input type="text" class="form-control" id="W_MIDDLE_NAME" name="W_MIDDLE_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_LAST_NAME" class="control-label">Last</label>
                <input type="text"  class="form-control" id="W_LAST_NAME" name="W_LAST_NAME"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_SUFFIX" class="control-label"l>Suffix (Jr., Sr., III)</label>
                <input type="text"  class="form-control" id="W_SUFFIX" name="W_SUFFIX"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_CCO_CERT_NUMBER" class="control-label">CCO CERTIFICATION NUMBER (if previously certified)</label>
                <input type="text" class="form-control" id="W_CCO_CERT_NUMBER" name="W_CCO_CERT_NUMBER"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_DOB" class="control-label">DATE OF BIRTH</label>
                <input type="text" class="form-control" id="W_DOB" name="W_DOB"/>
            </div>
        </div>

        <div class="col-xs-5">
            <div class="form-group">
                <label class="control-label" style="width: 100%;">SOCIAL SECURITY #</label>
                <input type="text" class="form-control" id="W_SSN_1-3" name="W_SSN_1-3" style="float: left; width: 52px; margin-right: 5px;" maxlength="3"/>
                <input type="text" class="form-control" id="W_SSN_4-5" name="W_SSN_4-5" style="float: left; width: 45px; margin-right: 5px;" maxlength="2"/>
                <input type="text" class="form-control" id="W_SSN_6-9" name="W_SSN_6-9" style="float: left; width: 60px;" maxlength="4"/>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_ADDRESS" class="control-label">MAILING ADDRESS</label>
                <input type="text" class="form-control" id="W_ADDRESS" name="W_ADDRESS"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CITY" class="control-label">CITY</label>
                <input type="text" class="form-control" id="W_CITY" name="W_CITY"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_STATE" class="control-label">STATE</label>
                <input type="text" class="form-control" id="W_STATE" name="W_STATE"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_ZIP" class="control-label">ZIP</label>
                <input type="text" class="form-control" id="W_ZIP" name="W_ZIP"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_PHONE" class="control-label">PHONE</label>
                <input type="text" class="form-control" id="W_PHONE" name="W_PHONE"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_CELL" class="control-label">CELL</label>
                <input type="text" class="form-control" id="W_CELL" name="W_CELL"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_FAX" class="control-label">FAX</label>
                <input type="text" class="form-control" id="W_FAX" name="W_FAX"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_EMAIL" class="control-label">E-MAIL</label>
                <input type="text" class="form-control" id="W_EMAIL" name="W_EMAIL"/>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-9">
            <div class="form-group">
                <label for="W_COMPANY_NAME" class="control-label">COMPANY/ORGANIZATION</label>
                <input type="text" class="form-control" id="W_COMPANY_NAME" name="W_COMPANY_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_PHONE" class="control-label">PHONE</label>
                <input type="text" class="form-control" id="W_COMPANY_PHONE" name="W_COMPANY_PHONE"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="W_COMPANY_ADDRESS" class="control-label">COMPANY MAILING ADDRESS</label>
                <input type="text" class="form-control" id="W_COMPANY_ADDRESS" name="W_COMPANY_ADDRESS"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="W_COMPANY_CITY" class="control-label">CITY</label>
                <input type="text" class="form-control" id="W_COMPANY_CITY" name="W_COMPANY_CITY"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_STATE" class="control-label">STATE</label>
                <input type="text" class="form-control" id="W_COMPANY_STATE" name="W_COMPANY_STATE"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="W_COMPANY_ZIP" class="control-label">ZIP</label>
                <input type="text" class="form-control" id="W_COMPANY_ZIP" name="W_COMPANY_ZIP"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <input type="checkbox" id="W_ADA_REQUEST_ACCOMMODATIONS" name="W_ADA_REQUEST_ACCOMMODATIONS" style="float: left; margin-right: 4px;"/>
                <label for="W_ADA_REQUEST_ACCOMMODATIONS" class="control-label">I AM REQUESTING TESTING ACCOMMODATIONS IN COMPLIANCE WITH THE AMERICAN WITH DISABILITIES ACT (ADA).<br/>(For details on NCCCO’s Testing Accommodations policy, please see www.nccco.org/accommodations.)</label>
            </div>
        </div>
    </div>
</div>
