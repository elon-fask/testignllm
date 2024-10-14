<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactTestSessionSpreadsheetAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.testSessionSpreadsheet.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
