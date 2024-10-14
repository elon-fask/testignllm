<?php
namespace app\assets;

use yii\web\AssetBundle;

class AnimateCSSAsset extends AssetBundle 
{
    public $sourcePath = '@npm/animate.css'; 
    public $css = [
        'animate.min.css', 
    ];
    public $publishOptions = [
        'only' => [
            'animate.min.css',
        ]
    ];
}
