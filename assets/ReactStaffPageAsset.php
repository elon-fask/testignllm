<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactStaffPageAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.staffPage.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
