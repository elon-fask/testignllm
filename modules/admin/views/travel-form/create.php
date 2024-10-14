<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TravelForm */

$this->title = 'Create Travel Form';
$this->params['breadcrumbs'][] = ['label' => 'Travel Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="travel-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
