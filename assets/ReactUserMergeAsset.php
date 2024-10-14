<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactUserMergeAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.userMerge.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
