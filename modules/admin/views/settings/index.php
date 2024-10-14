<?php
$this->title = 'Application Settings';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (isset($message) && $message !== false) { ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php } ?>

<h1>Application Settings</h1>
<form action="/admin/settings" method="POST" class="form-horizontal">

<?php
foreach ($appConfigs as $conf) {
    $inputOptions = $conf->getInputOptions(); ?>
    <div class="form-group field-applicationtype-keyword required">
        <label for="applicationtype-keyword" class="col-xs-4 control-label"><?= $conf->name ?></label>
        <div class="col-xs-12 col-md-5">
            <input
                type="<?= $inputOptions['type'] ?>"
                class="form-control currency-val"
                maxlength="255"
                value="<?= $conf->val ?>"
                name="AppConfig[<?= $conf->code ?>]"
                style="<?= $inputOptions['width'] ?>"
            >
            <div class="help-block"></div>
        </div>
    </div>
<?php } ?>
    <div class="form-group field-applicationtype-keyword required">
        <label for="applicationtype-keyword" class="col-xs-4 control-label">PipeDrive Initial Stage</label>
        <div class="col-xs-12 col-md-5">
            <select name="AppConfig[PIPEDRIVE_INITIAL_STAGE]" class="form-control">
            <?php if (count($pipedriveStages) < 1) { ?>
            <option value="" selected></option>
            <?php } ?>
            <?php foreach ($pipedriveStages as $stage) { ?>
            <?php $isSelected = (string)$stage['id'] === $pipedriveInitialStage ?>
            <option value="<?= $stage['id'] ?>" <?= $isSelected ? 'selected' : '' ?>><?= $stage['name'] ?></option>
            <?php } ?>
            </select>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="form-group">
        <div class=" col-xs-12 col-md-offset-4 col-md-5">
            <button class="btn btn-success" type="submit">Save</button>
        </div>
    </div>
</form>
