<?php
namespace app\assets;

use yii\web\AssetBundle;

class BulmaSwitchAsset extends AssetBundle 
{
    public $sourcePath = '@npm/bulma-switch'; 
    public $css = [
        'dist/bulma-switch.min.css', 
    ];
    public $publishOptions = [
        'only' => [
            'dist/bulma-switch.min.css',
        ]
    ];
}
