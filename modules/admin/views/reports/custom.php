<?php
use app\assets\ReactCustomReportAsset;

ReactCustomReportAsset::register($this);

$this->title = 'Custom Report Generator';
?>

<div id="react-entry"></div>

<script>
var apiUrl = "<?= $apiUrl ?>";
</script>
