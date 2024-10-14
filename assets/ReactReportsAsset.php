<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactReportsAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.reports.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
