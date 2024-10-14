<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactJSAsset extends AssetBundle
{
    public $basePath  = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.vendor.js'
    ];
}
