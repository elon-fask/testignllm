<?php

use yii\helpers\Html;
use app\models\TestSite;

/* @var $this yii\web\View */
/* @var $model app\models\TestSite */

$testSiteType = $type == TestSite::TYPE_PRACTICAL ? 'Practical' : 'Written';
$this->title = 'Create ' . $testSiteType . ' Test Site';

$this->params['breadcrumbs'][] = ['label' => $testSiteType . ' Test Sites', 'url' => '/admin/testsite/' . strtolower($testSiteType)];
array_push($this->params['breadcrumbs'], ['label' => 'Create Site', 'url' => '']);


?>
<div class="test-site-create">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]);
    ?>
</div>
