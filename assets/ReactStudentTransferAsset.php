<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactStudentTransferAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.studentTransfer.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
