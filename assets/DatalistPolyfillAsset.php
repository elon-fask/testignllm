<?php

namespace app\assets;

use yii\web\AssetBundle;

class DatalistPolyfillAsset extends AssetBundle
{
    public $sourcePath = '@npm/datalist-polyfill';

    public $js = [
        'datalist-polyfill.min.js',
    ];
}
