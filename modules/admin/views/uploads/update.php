<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Uploads */

$this->title = 'Update Uploads: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Uploads', 'url' => ['/admin/uploads']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/admin/uploads/view-file', 'id' => base64_encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="uploads-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(\Yii::$app->getSession()->hasFlash('error')){?>
 <div class="">
<div class="alert alert-danger">
    <?php echo \Yii::$app->getSession()->getFlash('error'); ?>
</div>
 </div>
<?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
