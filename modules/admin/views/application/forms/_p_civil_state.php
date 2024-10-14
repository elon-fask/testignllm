

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
                <label for="P_FIRST_NAME" class="control-label">First</label>
                <input type="text"  class="form-control" id="P_FIRST_NAME" name="P_FIRST_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_MIDDLE_NAME" class="control-label">Middle</label>
                <input type="text" class="form-control" id="P_MIDDLE_NAME" name="P_MIDDLE_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_LAST_NAME" class="control-label">Last</label>
                <input type="text"  class="form-control" id="P_LAST_NAME" name="P_LAST_NAME"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="P_SUFFIX" class="control-label"l>Suffix (Jr., Sr., III)</label>
                <input type="text"  class="form-control" id="P_SUFFIX" name="P_SUFFIX"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="P_CCO_CERT_NUMBER" class="control-label">CCO CERTIFICATION NUMBER (if previously certified)</label>
                <input type="text" class="form-control" id="P_CCO_CERT_NUMBER" name="P_CCO_CERT_NUMBER"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="P_DOB" class="control-label">DATE OF BIRTH</label>
                <input type="text" class="form-control" id="P_DOB" name="P_DOB"/>
            </div>
        </div>

        <div class="col-xs-5">
            <div class="form-group">
                <label class="control-label" style="width: 100%;">SOCIAL SECURITY #</label>
                <input type="text" class="form-control" id="P_SSN_1-3" name="P_SSN_1-3" style="float: left; width: 52px; margin-right: 5px;" maxlength="3"/>
                <input type="text" class="form-control" id="P_SSN_4-5" name="P_SSN_4-5" style="float: left; width: 45px; margin-right: 5px;" maxlength="2"/>
                <input type="text" class="form-control" id="P_SSN_6-9" name="P_SSN_6-9" style="float: left; width: 60px;" maxlength="4"/>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="P_ADDRESS" class="control-label">MAILING ADDRESS</label>
                <input type="text" class="form-control" id="P_ADDRESS" name="P_ADDRESS"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_CITY" class="control-label">CITY</label>
                <input type="text" class="form-control" id="P_CITY" name="P_CITY"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="P_STATE" class="control-label">STATE</label>
                <input type="text" class="form-control" id="P_STATE" name="P_STATE"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="P_ZIP" class="control-label">ZIP</label>
                <input type="text" class="form-control" id="P_ZIP" name="P_ZIP"/>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_PHONE" class="control-label">PHONE</label>
                <input type="text" class="form-control" id="P_PHONE" name="P_PHONE"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_CELL" class="control-label">CELL</label>
                <input type="text" class="form-control" id="P_CELL" name="P_CELL"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_FAX" class="control-label">FAX</label>
                <input type="text" class="form-control" id="P_FAX" name="P_FAX"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_EMAIL" class="control-label">E-MAIL</label>
                <input type="text" class="form-control" id="P_EMAIL" name="P_EMAIL"/>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-9">
            <div class="form-group">
                <label for="P_COMPANY_NAME" class="control-label">COMPANY/ORGANIZATION</label>
                <input type="text" class="form-control" id="P_COMPANY_NAME" name="P_COMPANY_NAME"/>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_COMPANY_PHONE" class="control-label">PHONE</label>
                <input type="text" class="form-control" id="P_COMPANY_PHONE" name="P_COMPANY_PHONE"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5">
            <div class="form-group">
                <label for="P_COMPANY_ADDRESS" class="control-label">COMPANY MAILING ADDRESS</label>
                <input type="text" class="form-control" id="P_COMPANY_ADDRESS" name="P_COMPANY_ADDRESS"/>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label for="P_COMPANY_CITY" class="control-label">CITY</label>
                <input type="text" class="form-control" id="P_COMPANY_CITY" name="P_COMPANY_CITY"/>
            </div>
        </div>

        <div class="col-xs-2">
            <div class="form-group">
                <label for="P_COMPANY_STATE" class="control-label">STATE</label>
                <input type="text" class="form-control" id="P_COMPANY_STATE" name="P_COMPANY_STATE"/>
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label for="P_COMPANY_ZIP" class="control-label">ZIP</label>
                <input type="text" class="form-control" id="P_COMPANY_ZIP" name="P_COMPANY_ZIP"/>
            </div>
        </div>
    </div>

</div>
