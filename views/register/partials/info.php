<?php 
use yii\helpers\Html;
?>


<div class="registration-wrapper">


    <?php echo yii\base\View::render('wizard', ['step'=>3]);?>

    <?php echo yii\base\View::render('_titles', ['step'=>3]);?>


    <div class="candidates-create">

        <h1><?= Html::encode($this->title) ?></h1>
            <div class="callback-content">
                <?= $this->render('_form', [
                    'model' => $model, 'referralCode' => $referralCode, 'appTypeId' => $appTypeId
                ]) ?>
            </div>

    </div>
</div>

<style>

</style>