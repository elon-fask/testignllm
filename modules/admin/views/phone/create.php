<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PhoneInformation */

$this->title = 'Create Phone Information';
$this->params['breadcrumbs'][] = ['label' => 'Phone Informations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phone-information-create clearfix">
    <div class="alert alert-success text-center" style="display: none;">Phone Information Saved Successfully</div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
