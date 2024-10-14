<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Create Website Admin';
$this->params['breadcrumbs'][] = ['label' => 'Website Admin', 'url' => ['/admin/user']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '']
?>
<div class="user-create row">
    <div class="col-xs-12">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
