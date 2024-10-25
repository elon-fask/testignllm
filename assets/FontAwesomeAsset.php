<?php
namespace app\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle 
{
    public $sourcePath = '@npm/font-awesome'; 
    public $css = [
        'css/font-awesome.min.css', 
    ];
    public $publishOptions = [
        'only' => [
            'fonts/*',
            'css/*',
        ]
    ];
}
