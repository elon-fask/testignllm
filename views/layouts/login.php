<?php
use yii\helpers\Html;
use app\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <?= Html::csrfMetaTags() ?>
    <title>CSO-CCS</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
    
    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="index">
    <?php $this->beginBody() ?>
    <div class="container site-wrapper">
          <?= $content ?>
    </div>
    <?php $this->endBody() ?>
</body>

<script src="/js/app.js"></script> 
<script src="/js/jquery/jquery.maskedinput.min.js"></script> 
<script src="/js/bootstrap/bootstrap.min.js"></script>

<script>
    $(function () {
        var loginBoxPadding = function() {

            var pad = Math.floor(( $(document).height() - $('.site-wrapper').height() ) / 2);
            pad = pad < 0 ? 10 : pad;
            //console.log($('.site-wrapper'));
            $('.site-wrapper').css({'padding-top': pad + 'px'});
        }

        loginBoxPadding();
        $(window).on('resize', function(){loginBoxPadding()});
    });
</script>

</html>
<?php $this->endPage() ?>