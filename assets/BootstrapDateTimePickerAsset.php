<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapDateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/eonasdan-bootstrap-datetimepicker/build'; 

    public $css = [
        'css/bootstrap-datetimepicker.min.css'
    ];

    public $js = [
        'js/bootstrap-datetimepicker.min.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
        'app\assets\MomentJSAsset'
    ];
}
