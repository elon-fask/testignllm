<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactStudentSearchNewAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.studentSearchNew.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset',
        'app\assets\BootstrapDateTimePickerAsset'
    ];
}
