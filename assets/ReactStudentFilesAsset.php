<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactStudentFilesAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.studentFiles.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset',
        'app\assets\AnimateCSSAsset'
    ];
}
