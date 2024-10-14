<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactLegacyImportAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react/frontend-new';
    public $baseUrl = '@web/js/react/frontend-new';

    public $js = [
        'bundle.legacyImport.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
