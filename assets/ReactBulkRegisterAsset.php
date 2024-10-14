<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactBulkRegisterAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.bulkRegistration.js'
    ];

    public $css = [
        '@app/frontend/node_modules/'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
