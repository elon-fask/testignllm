<?php
use app\assets\ReactLegacyImportAsset;

ReactLegacyImportAsset::register($this);

$titlePage = 'Bulk Register Legacy Students';

$this->title = $titlePage;
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="react-entry"></div>
