<?php
use app\assets\ReactStudentRegistrationAsset;

ReactStudentRegistrationAsset::register($this);

$this->title = "CSO-CCS";
?>
<div id="react-entry"></div>

<script>
    var testSites = <?= json_encode($testSites) ?>;
    console.log(testSites);
</script>