<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactCompanyAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.company.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
