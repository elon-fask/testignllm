<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cranes */

$this->title = 'Create Cranes';
$this->params['breadcrumbs'][] = ['label' => 'Cranes', 'url' => ['/admin/cranes']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cranes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
