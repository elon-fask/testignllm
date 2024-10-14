<?php

namespace app\assets;

use yii\web\AssetBundle;

class MomentJSAsset extends AssetBundle
{
    public $sourcePath = '@npm/moment/min';

    public $js = [
        'moment.min.js',
    ];
}
