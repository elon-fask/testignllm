<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\assets\SentryJSAsset;

if (!YII_ENV_DEV) {
    SentryJSAsset::register($this);
}

$isAdmin = \app\helpers\UserHelper::isAdmin();

AppAsset::register($this);
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
        <script src="/js/jquery/jquery-2.1.4.min.js"></script>
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"/>
        <link href="/css/main.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="/images/site/favicon.png">
    </head>

    <body class="<?= Yii::$app->controller->id . '-' . Yii::$app->controller->action->id ?>">

    <?= $this->render('_nav') ?>

    <div class="container-fluid" style="margin-top: 80px;">
        <?= $content ?>
    </div>

    <?= $this->render('_footer') ?>

    <?php $this->endBody(); ?>
    </body>
    <script src="/js/bootstrap/bootstrap.min.js"></script>
    </html>
<?php $this->endPage(); ?>
