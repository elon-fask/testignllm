<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PhoneInformation */

$this->title = 'Update Phone Information: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Phone Informations', 'url' => ['/admin/phone/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['admin/phone/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="phone-information-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
