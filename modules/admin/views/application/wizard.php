<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ApplicationType */

$this->title = 'Application Type Wizard: ';
$this->params['breadcrumbs'][] = ['label' => 'Application Types', 'url' => ['index']];
?>
<?= $this->render('form-styles') ?>
<div class="application-type-wizard">

    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['class' => 'form-horizontal', 'id' => 'wizard-app-form']); ?>
     <?php 
   echo $this->render('_dynamic_forms', ['model'=>$model, 'styling' => 'col-xs-12 col-md-12']);
   ?>  

    <?php ActiveForm::end(); ?>

</div>
<br />
<div class='matching-results'>

</div>