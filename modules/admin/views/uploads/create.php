<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Uploads */

$this->title = 'Add Uploads';
$this->params['breadcrumbs'][] = ['label' => 'Uploads', 'url' => ['/admin/uploads/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uploads-create">

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
