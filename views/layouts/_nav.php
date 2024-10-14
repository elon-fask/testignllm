<?php
use yii\helpers\Html;
use app\helpers\UtilityHelper;
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container" style="display: flex; justify-content: center;">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><img src="/images/site/acs/logo-sm.png">American Crane School</a>
            <br />
            <a class="navbar-brand sub-brand" href="tel://8889577277">(888) 957-7277</a>
        </div>
    </div><!-- /.container-fluid -->
</nav>

<style>
    .navbar-brand {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: auto;
        padding: 0;
        padding-top: 2px;
    }

    .navbar-brand.sub-brand {
        padding-left: 41px;
    }

    .navbar-brand > img {
        display: inline-block !important;
        width: 31px;
        margin-right: 10px;
        height: 46px;
    }
</style>
