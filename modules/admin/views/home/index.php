<?php
use app\assets\ReactDashboardAsset;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

ReactDashboardAsset::register($this);
?>

<div id="react-entry"></div>
<div class='widget-index'>
<?= $this->render('_all_widgets', []) ?>
</div>

<script>
var ongoingClasses = <?= json_encode($ongoingClasses) ?>;
var upcomingClasses = <?= json_encode($upcomingClasses) ?>;
</script>
