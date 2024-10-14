<?php
use app\assets\ReactCompanyAsset;

ReactCompanyAsset::register($this);
?>

<div id="react-entry"></div>

<script>
var companies = <?= json_encode($companies) ?>;
</script>
