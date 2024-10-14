<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\helpers\UtilityHelper;

AppAsset::register($this);

$isAdmin = \app\helpers\UserHelper::isAdmin();
$info = UtilityHelper::getSiteBrandingInfo();
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

        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"/>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='/vendor/jquery-confirm/jquery-confirm.min.css' rel='stylesheet' />
        <link href="/css/main.css" rel="stylesheet">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-N659V6WH6X"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-N659V6WH6X');
        </script>

        <?php if(UtilityHelper::getCurrentBranding() != ''){?>
            <link rel="icon" type="image/png" href="<?php echo $info['logo-medal']?>">
        <?php }?>


    </head>
    <body class="registration-content">
    <?php $this->beginBody(); ?>

    <?= yii\base\View::render('_nav', ['info' => $info]) ?>

    <div class="container">
        <?= $content ?>
    </div>

    <div class="back-to-top"><a href="#" title="Back to Top"><i class="fa fa-chevron-up fa-2x"></i></a></div>

    <?php $this->endBody(); ?>
    </body>

    <script src="/vendor/jquery-ui-1.11.4.custom/jquery-ui.js"></script>

    <script src="/js/bootstrap/bootstrap.min.js"></script>
    <script src="/js/bootstrap/bootstrap-datepicker.min.js"></script>
    <script src="/js/bootstrap/validator.js"></script>
    <script src="/js/jquery/jquery.maskedinput.min.js"></script>
    <script src="/js/jquery/jquery.maskMoney.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/messaging.js"></script>
    <script src="/js/CustomMessages.js"></script>

    <script src="/vendor/jquery-confirm/jquery-confirm.min.js"></script>

    <script src="/js/clipboard.min.js"></script>
    <script src="/js/accounting.min.js"></script>
    <script src="/js/app.js?<?= rand() ?>"></script>

    </html>
<?php $this->endPage() ?>