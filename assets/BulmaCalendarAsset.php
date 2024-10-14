<?php
namespace app\assets;

use yii\web\AssetBundle;

class BulmaCalendarAsset extends AssetBundle 
{
    public $sourcePath = '@npm/bulma-calendar/dist'; 

    public $css = [
        'bulma-calendar.min.css', 
    ];

    public $js = [
        'bulma-calendar.min.js',
    ];

    public $publishOptions = [
        'only' => [
            'bulma-calendar.min.css',
            'bulma-calendar.min.js'
        ]
    ];
}
