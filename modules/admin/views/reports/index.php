<?php
use app\assets\ReactReportsAsset;

ReactReportsAsset::register($this);

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="react-entry"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#main-container').removeClass('container');
    $('#main-container').addClass('container-fluid');
}, false);
</script>
