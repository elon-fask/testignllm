<?php

use yii\helpers\Html;
use app\models\Candidates;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = 'Add Student';
$this->params['breadcrumbs'][] = $this->title;

$candidate = new Candidates();
if (isset($model)) {
    $candidate = $model;
}
?>
<div class="candidate-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['candidate' => $candidate]) ?>
</div>
