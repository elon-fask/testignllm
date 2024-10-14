<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAssetNew extends AssetBundle 
{
    public $depends = [
        'app\assets\BulmaAsset',
        'app\assets\FontAwesomeAsset'
    ];
}
