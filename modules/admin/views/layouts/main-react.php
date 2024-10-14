<?php
use yii\helpers\Html;
use app\assets\AppAssetNew;
use app\assets\SentryJSAsset;

if (!YII_ENV_DEV) {
    SentryJSAsset::register($this);
}

AppAssetNew::register($this);
?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <?= Html::csrfMetaTags() ?>
        <title>CSO-CCS</title>
        <link rel="icon" type="image/png" href="/images/site/favicon.png">
        <?php $this->head(); ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <div>
        <?= $content ?>
    </div>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>

<style>
body {
    height: 100%;
}
</style>
