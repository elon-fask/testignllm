<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactAccountBalanceAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.accountBalance.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
