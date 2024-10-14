
<style>
    .container-applicant-checklist {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
    }
    .container-applicant-checklist ul{
        list-style-type: none; margin: 0; padding-left: 0;
    }
    .container-applicant-checklist .control-label{
        font-weight: normal;
        margin-bottom: 0;
    }
    .container-applicant-checklist input[type=checkbox]{float: left; margin-right: 4px}

</style>
<div class="row row-payment">
    <div class="col-xs-12">
        <h2>CANDIDATE APPLICATION CHECKLIST</h2>
    </div>
</div>


<div class="container-applicant-checklist">
    <div class="row">
        <div class="col-xs-12">
            <ul>
                <li>
                    <label class="control-label">
                        <input type="checkbox" name="W_CONFIRM_COMPLETE_APPLICATION" id="W_CONFIRM_COMPLETE_APPLICATION"/>I have completed and signed the <em>Candidate Application</em>
                    </label>
                </li>

                <li>
                    <label class="control-label">
                        <input type="checkbox" name="W_CONFIRM_PAYMENT" id="W_CONFIRM_PAYMENT"/>I have provided credit card information or a check or money order for the correct amount due.
                    </label>
                </li>

                <li style=" border-bottom: 1px dotted #ccc; padding-bottom: 25px;">
                    <label class="control-label"><input type="checkbox" name="W_CONFIRM_PHOTO" id="W_CONFIRM_PHOTO"/>I have submitted a color digital photo (full face, no sunglasses, no hat).
                        A passport photo may be substituted for a digital photo.</label>
                </li>

                <li style="margin-top: 25px;">
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label class="control-label">Photo</label>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <input type="file" name="W_PHOTO" id="W_PHOTO"/>
                        </div>
                    </div>

                </li>

            </ul>
        </div>
    </div>
</div>