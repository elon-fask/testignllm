<?php
use app\assets\ReactUserMergeAsset;

ReactUserMergeAsset::register($this);

$this->title = 'Merge Staff';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="react-entry"></div>

<script>
var primaryUser = <?= json_encode($primaryUser) ?>;
var secondaryUser = <?= json_encode($secondaryUser) ?>;
</script>
