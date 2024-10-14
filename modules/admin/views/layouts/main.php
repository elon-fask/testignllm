<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\SentryJSAsset;

$isAdmin = \app\helpers\UserHelper::isAdmin();

if (!YII_ENV_DEV) {
    SentryJSAsset::register($this);
}

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
        <script src='/js/jquery//moment.min.js'></script>
        <script src="/js/jquery/jquery-2.1.4.min.js"></script>



        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/jquery.dataTables.min.css" rel="stylesheet">

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"/>
        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
        <link href="/css/bootstrap/bootstrap-datepicker.min.css" rel="stylesheet" />
        <link href="/css/nprogress.css" rel="stylesheet" />

        <link href='/css/fullcalendar.min.css' rel='stylesheet' />
        <link href='/css/fullcalendar.print.css' rel='stylesheet' media='print' />

        <link href='/vendor/jquery-confirm/jquery-confirm.min.css' rel='stylesheet' />


        <link rel="stylesheet" href="/css/jquery.range.css">

        <link href="/css/main.css?v=<?php echo time();?>" rel="stylesheet">
        <link rel="icon" type="image/png" href="/images/site/favicon.png">
    </head>

    <body class="<?php echo Yii::$app->controller->id.'-'.Yii::$app->controller->action->id ?>">

    <!-- Modal -->
    <div class="modal fade" id="genericModal" tabindex="-1" role="dialog" aria-labelledby="genericModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="genericModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    Generic Modal
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    echo yii\base\View::render('_nav');
    ?>


    <div class="container" id="main-container">
        <?php
        if($isAdmin) {
            echo yii\base\View::render('_breadcrumb');
        }
        ?>
        <?= $content ?>
    </div>

    <div class="back-to-top"><a href="#" title="Back to Top"><i class="fa fa-chevron-up fa-2x"></i></a></div>


    <!-- Modal -->
    <div class="modal fade" id="reminder-modal"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="height: 550px">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>

    <div id="blocker">
        <div></div>
        <div><div class="clearfix"><div><i class="fa fa-spinner fa-pulse fa-2x"></i></div><div>Loading</div></div></div>
    </div>


    <?php
    echo yii\base\View::render('_footer');
    ?>

    <?php $this->endBody() ?>
    </body>
    <script src="/js/bootstrap/bootstrap.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script src="/js/messaging.js"></script>
    <script src="/js/bootstrap/bootstrap-datepicker.min.js"></script>
    <script src="/js/bootstrap/validator.js"></script>
    <script src="/js/jquery/jquery.maskedinput.min.js"></script>
    <script src="/js/jquery/jquery.maskMoney.min.js"></script>

    <script src="/js/jquery.dataTables.min.js"></script>

    <script src="/js/jquery/jquery.knob.js"></script>


    <script src="/vendor/jquery-confirm/jquery-confirm.min.js"></script>

    <!-- jQuery File Upload Dependencies -->
    <script src="/js/jquery/jquery.ui.widget.js"></script>
    <script src="/js/jquery/jquery.iframe-transport.js"></script>
    <script src="/js/jquery/jquery.fileupload.js"></script>
    <script src="/js/jquery/jquery.bootpag.min.js"></script>
    <script src="/js/nprogress.js"></script>
    <script src="/js/upload.js"></script>
    <script src="/js/clipboard.min.js"></script>
    <script src="/js/jquery.range.js"></script>
    <script src="/js/CustomMessages.js"></script>
    <script src="/js/app.js?v=<?= time(); ?>"></script>
    <script src="/js/accounting.min.js"></script>

    <script src='/js/jquery/fullcalendar.min.js'></script>
    <script src='/js/jquery.query-object.js'></script>
    </html>
<?php $this->endPage() ?>
