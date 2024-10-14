<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactDashboardAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react/frontend-new';
    public $baseUrl = '@web/js/react/frontend-new';

    public $js = [
        'bundle.dashboard.js'
    ];
}
