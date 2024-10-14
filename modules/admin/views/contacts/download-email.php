<?php

use yii\helpers\Html;

$titlePage = 'Download Candidate Email Addresses';
$this->title = $titlePage;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div>
<a href="/admin/contacts/download-all-email?school=acs" class="btn btn-primary">Download ACS Candidate Emails</a>
</div>
<div style="margin-top:20px;">
<a href="/admin/contacts/download-all-email?school=ccs" class="btn btn-primary">Download CCS Candidate Emails</a>
</div>
