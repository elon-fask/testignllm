<?php
use yii\helpers\Html;
use yii\helpers\Url;

$school = (isset($this->params['school']) && $this->params['school'] === 'CCS') ? 'ccs' : 'acs';

$headerImgUrl = Url::base(true) . '/images/site/' . $school . '/' . $school . '-header.png';
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <meta property="og:title" content=<?= Html::encode($this->title) ?> />
    <title><?= Html::encode($this->title) ?></title>
    <style type="text/css">
    #outlook a{padding:0;}
    body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
    body{-webkit-text-size-adjust:none;}
    body{margin:0; padding:0;}
    img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
    table td{border-collapse:collapse;}
    #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}
    body, #backgroundTable{
        background-color:#FAFAFA;
    }
    #templateContainer{
        border: 1px solid #DDDDDD;
    }
    h1, .h1{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:34px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
    }
    h2, .h2{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:30px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
    }
    h3, .h3{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:26px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
    }
    h4, .h4{
        color:#202020;
        display:block;
        font-family:Arial;
        font-size:22px;
        font-weight:bold;
        line-height:100%;
        margin-top:0;
        margin-right:0;
        margin-bottom:10px;
        margin-left:0;
        text-align:left;
    }
    #templateHeader{
        background-color:#FFFFFF;
        border-bottom:0;
    }
    .headerContent{
        color:#202020;
        font-family:Arial;
        font-size:34px;
        font-weight:bold;
        line-height:100%;
        padding:0;
        text-align:center;
        vertical-align:middle;
    }
    .headerContent a:link, .headerContent a:visited, .headerContent a .yshortcuts{
        color:#336699;
        font-weight:normal;
        text-decoration:underline;
    }
    #headerImage{
        height:auto;
        max-width:600px !important;
    }
    #templateContainer, .bodyContent{
        background-color:#FFFFFF;
    }
    .bodyContent div{
        color:#505050;
        font-family:Arial;
        font-size:14px;
        line-height:150%;
        text-align:left;
    }
    .bodyContent div a:link, .bodyContent div a:visited, .bodyContent div a .yshortcuts{
        color:#336699;
        font-weight:normal;
        text-decoration:underline;
    }
    .bodyContent img{
        display:inline;
        height:auto;
    }
    #templateFooter{
        background-color:#FFFFFF;
        border-top:0;
    }
    .footerContent div{
        color:#707070;
        font-family:Arial;
        font-size:12px;
        line-height:125%;
        text-align:left;
    }
    .footerContent div a:link, .footerContent div a:visited, .footerContent div a .yshortcuts{
        color:#336699;
        font-weight:normal;
        text-decoration:underline;
    }
    .footerContent img{
        display:inline;
    }
    </style>
    <?php $this->head() ?>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <center>
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader">
                        <tr>
                            <td class="headerContent">
                                <a href="http://www.cranetrainingtexas.com/"><img src="<?= $headerImgUrl ?>" style="max-width:600px;" id="headerImage" /></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                        <tr>
                            <td valign="top" class="bodyContent">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                    <tr>
                                        <td valign="top">
                                            <?php $this->beginBody() ?>
                                            <?= $content ?>
                                            <?php $this->endBody() ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter">
                        <tr>
                            <td valign="top" class="footerContent">
                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                    <tr>
                                        <td colspan="2" valign="middle">
                                            <div>&copy; California Crane School, Inc. dba American Crane School <?= date('Y') ?></div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
<?php $this->endPage() ?>
