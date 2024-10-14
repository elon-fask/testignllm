<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PromoCodes */

$this->title = 'Edit Promo Codes: ' .  $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Promo Codes', 'url' => ['/admin/promo']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['/admin/promo/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promo-codes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
