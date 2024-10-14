<?php
use app\assets\ReactUserLogAsset;
ReactUserLogAsset::register($this);
?>

<div id="react-entry"></div>

<script>
var apiUrl = "<?= $apiUrl ?>";
var googleMapsApiKey = "<?= $googleMapsApiKey ?>";
</script>
