<?php
use yii\helpers\Html;
use app\assets\MomentJSAsset;
use app\assets\ReactBulkRegisterAsset;

$titlePage = 'Bulk Register Students';

$this->title = $titlePage;
$this->params['breadcrumbs'][] = $this->title;

ReactBulkRegisterAsset::register($this);
$testSessionJson = json_encode($testSessions);
?>

<h2><?= Html::encode($this->title) ?></h2>
<div id="react-entry"></div>

<script>
    var applicationTypes = <?= json_encode($applicationTypes) ?>;
    var promoCodes = <?= json_encode($promoCodes) ?>;
    var testSites = <?= json_encode($testSites) ?>;
    var testSessions = <?= json_encode($testSessions) ?>;
    console.log(applicationTypes);
    console.log(promoCodes);
    console.log(testSites);
    console.log(testSessions);
</script>
