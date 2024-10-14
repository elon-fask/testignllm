<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\SentryJSAsset;

if (!YII_ENV_DEV) {
    SentryJSAsset::register($this);
}

$isAdmin = \app\helpers\UserHelper::isAdmin();

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
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
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href="/css/main.css?v=<?= time() ?>" rel="stylesheet">
    </head>

    <body class="<?= Yii::$app->controller->id . '-' . Yii::$app->controller->action->id ?>">

    <?= yii\base\View::render('_nav') ?>

    <div class="container" id="main-container">
        <?php
            if ($isAdmin) {
                echo yii\base\View::render('_breadcrumb');
            }
        ?>
        <?= $content ?>
    </div>

    <?= yii\base\View::render('_footer') ?>
    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>
