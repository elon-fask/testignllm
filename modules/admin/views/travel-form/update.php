<?php

use yii\helpers\Html;
use app\assets\ReactTravelFormUpdateAsset;

/* @var $this yii\web\View */
/* @var $model app\models\TravelForm */

$this->title = 'Update Travel Form';
$this->params['breadcrumbs'][] = ['label' => 'Travel Forms', 'url' => ['/admin/travel-form']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

ReactTravelFormUpdateAsset::register($this);
?>

<div id="react-entry"></div>
<script>
var travelForm = <?= json_encode($travelForm) ?>;
</script>
