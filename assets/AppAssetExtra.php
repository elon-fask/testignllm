<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAssetExtra extends AssetBundle
{
    public $basePath = '@webroot/js';
    public $baseUrl = '@web/js';

    public $js = [
        'jquery/jquery-2.1.4.min.js',
        'bootstrap/bootstrap.min.js',
        'app.js'
    ];

    public $depends = [
        'app\assets\AppAsset'
    ];
}
