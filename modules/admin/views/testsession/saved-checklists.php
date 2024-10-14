<?php

use yii\helpers\Html;
use app\assets\ReactJSAsset;

/* @var $checklists */

ReactJSAsset::register($this);
$this->title = 'Saved Checklists';
?>

<div id="app-container" class="container">
    <h2><?= Html::encode($this->title) ?></h2>
    <?php foreach ($checklists as $checklist) { ?>
        <div>
            <button type="button" class="btn btn-info"><?= $checklist->name ?></button>
            <ul>
                <?php foreach ($checklist->checklistItems as $checklistItem) { ?>
                    <li><?= $checklistItem->name ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>

<script>
</script>