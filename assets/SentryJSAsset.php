<?php

namespace app\assets;

use yii\web\AssetBundle;

class SentryJSAsset extends AssetBundle
{
    public $basePath = '@webroot/js';
    public $baseUrl = '@web/js';

    public $js = [
        'https://browser.sentry-cdn.com/5.5.0/bundle.min.js',
        'sentry-init.js'
    ];
}
