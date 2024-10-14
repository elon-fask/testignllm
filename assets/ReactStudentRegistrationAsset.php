<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactStudentRegistrationAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.studentRegistration.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
