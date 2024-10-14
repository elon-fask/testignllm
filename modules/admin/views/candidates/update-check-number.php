<?php
?>

<div class="update-transaction">
    <div class="alert alert-success text-center" style="display: none;">Check Number saved successfully.</div>
    <div class="phone-information-form">
    <form id="update-check-number">
    <input type="hidden" name="id" value="<?= $transaction->id ?>"/>

    <div class="field-iaiAmount">
        <label for="" class="control-label">Check Number</label>
        <input type="text" name="check_number" class="form-control" value="<?= $transaction->check_number ?>" />
        <div class="help-block"></div>
    </div>

    <div class="form-group pull-right">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input type="button" class="btn btn-success btn-update-remark" value="Update"/>
    </div>
    <br />
    <br />
    </form>
</div>

</div>
