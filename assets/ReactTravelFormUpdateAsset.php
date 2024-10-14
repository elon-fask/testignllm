<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactTravelFormUpdateAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.travelFormUpdate.js'
    ];

    public $depends = [
        'app\assets\AppAssetNew',
        'app\assets\BulmaCalendarAsset',
        'app\assets\BulmaSwitchAsset',
        'app\assets\ReactJSAsset'
    ];
}
