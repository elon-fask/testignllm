<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cranes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cranes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cranes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'model',
            'manufacturer',
            'unitNum',
            'serialNum',
            'cad',
            'weightCerts',
            'loadChart',
            'manual',
            'certificate',
            'certificateExpirateDate',
            'companyOwner',
            'preChecklistId',
            'postChecklistId',
            'date_created',
            'isDeleted',
        ],
    ]) ?>

</div>
