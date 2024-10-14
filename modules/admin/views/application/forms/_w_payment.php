

<style>

    .container-payment .control-label{
        font-weight: normal;
        margin-bottom: 0;
    }
    .container-payment {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
    }

    .container-payment .list-inline input[type=checkbox]{float: left; margin-right: 4px}
</style>


<div class="row row-payment">
    <div class="col-xs-12">
        <h2>METHOD OF PAYMENT FOR CANDIDATE EXAMINATION FEES</h2>
    </div>
</div>

<div class="container-payment">
    <div class="row">
        <ul class="list-inline" style="margin:10px 15px">
            <li class="col-xs-2">
                <label class="control-label"><input type="checkbox" name="W_PAYMENT_VISA" id="W_PAYMENT_VISA"/><i class="fa fa-2x fa-cc-visa"></i></label>
            </li>

            <li class="col-xs-2">
                <label class="control-label"><input type="checkbox" name="W_PAYMENT_MASTERCARD" id="W_PAYMENT_MASTERCARD"/><i class="fa fa-2x fa-cc-mastercard"></i></label>
            </li>

            <li class="col-xs-2">
                <label class="control-label"><input type="checkbox" name="W_PAYMENT_AMEX" id="W_PAYMENT_AMEX"/><i class="fa fa-2x fa-cc-amex"></i></label>
            </li>

            <li class="col-xs-2">
                <label class="control-label"><input type="checkbox" name="W_PAYMENT_PERSONAL_CHECK" id="W_PAYMENT_PERSONAL_CHECK"/><small>Personal check enclosed</small></label>
            </li>

            <li class="col-xs-2">
                <label class="control-label"><input type="checkbox" name="W_PAYMENT_EMPLOYER_CHECK" id="W_PAYMENT_EMPLOYER_CHECK"/><small>Employer check enclosed</small></label>
            </li>

            <li class="col-xs-2">
                <label class="control-label"><input type="checkbox" name="W_PAYMENT_MONEY_ORDER" id="W_PAYMENT_MONEY_ORDER"/><small>Money Order enclosed</small></label>
            </li>
        </ul>
    </div>


    <div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="W_TC_CITY" class="control-label">CREDIT CARD NUMBER</label>
                    <input type="text"  class="form-control" id="W_CC_NUMBER" name="W_CC_NUMBER"/>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="form-group">
                    <label for="W_CC_EXP" class="control-label">EXPIRATION DATE</label>
                    <input type="text"  class="form-control" id="W_CC_EXP" name="W_CC_EXP"/>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label for="W_CC_HOLDER_NAME" class="control-label">NAME (Print as it appears on card)</label>
                    <input type="text"  class="form-control" id="W_CC_HOLDER_NAME" name="W_CC_HOLDER_NAME"/>
                </div>
            </div>

            <div class="col-xs-6">
                <div class="form-group">
                    <label for="W_CC_CODE" class="control-label">SECURITY CODE</label>
                    <input type="text"  class="form-control" id="W_CC_CODE" name="W_CC_CODE" style="width: 60px" maxlength="4"/>
                </div>
            </div>
        </div>


    </div>

</div>

