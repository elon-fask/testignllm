<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApplicationType */

$this->title = 'Create Application Type: ';
$this->params['breadcrumbs'][] = ['label' => 'Application Types', 'url' => ['index']];
?>
<div class="application-type-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>