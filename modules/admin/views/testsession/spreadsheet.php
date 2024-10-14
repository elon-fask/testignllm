<?php
use app\assets\AppAsset;
use app\assets\ReactTestSessionSpreadsheetAsset;

/* @var $this yii\web\View */
/* @var $testSession app\models\TestSession */
/* @var $candidates app\models\Candidates */
/* @var $applicationTypes app\models\ApplicationType */

if ($partial) {
    AppAsset::register($this);
}

ReactTestSessionSpreadsheetAsset::register($this);

$this->title = 'Test Session Spreadsheet View';
$this->params['breadcrumbs'][] = ['label' => 'Spreadsheet', 'url' => ''];
?>

<div id="react-entry"></div>
<script>
    var testSession = <?= json_encode($testSession) ?>;
    var candidates = <?= json_encode($candidates) ?>;
    var companies = <?= json_encode($companies) ?>;
    var applicationTypes = <?= json_encode($applicationTypes) ?>;
    var view = '<?= $view ?>';
    var printerFriendly = !!'<?= $printerFriendly ?>';
    var partial = !!'<?= $partial ?>';
    var columns = <?= json_encode($columns) ?>;
    var options = <?= json_encode($options) ?>;
</script>
