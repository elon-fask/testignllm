<?php
use app\assets\BootstrapDateTimePickerAsset;
use app\assets\ReactCompanyTransactionAsset;

BootstrapDateTimePickerAsset::register($this);
ReactCompanyTransactionAsset::register($this);
?>

<div id="react-entry"></div>

<script>
var apiUrl = "<?= $apiUrl ?>";
var transactions = <?= json_encode($transactions, JSON_NUMERIC_CHECK) ?>;
var companies = <?= json_encode($companies, JSON_NUMERIC_CHECK) ?>;
var testSites = <?= json_encode($testSites, JSON_NUMERIC_CHECK) ?>;
</script>
