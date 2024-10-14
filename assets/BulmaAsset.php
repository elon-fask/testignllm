<?php
namespace app\assets;

use yii\web\AssetBundle;

class BulmaAsset extends AssetBundle 
{
    public $sourcePath = '@npm/bulma'; 

    public $css = [
        'css/bulma.css', 
    ];

    public $publishOptions = [
        'only' => [
            'css/bulma.css',
        ]
    ];

    public $depends = [
        'app\assets\AnimateCSSAsset'
    ];
}
