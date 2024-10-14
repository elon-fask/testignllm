<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PhoneInformation */

?>
<div class="phone-information-view">
    <div class="alert alert-success text-center" style="display: none;">Marked as completed</div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'email:email',
            'phone',
            'referral',
            [
                'label' => 'More Information',
                'attribute' => 'referralOther',
                'value' => $model->referral == 'Other' ? $model->referralOther : '-',
            ]
        ],
    ]) ?>
</div>

<style>
    .phone-information-view table th{
        width: 160px;
    }
</style>