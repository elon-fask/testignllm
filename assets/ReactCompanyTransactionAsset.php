<?php

namespace app\assets;

use yii\web\AssetBundle;

class ReactCompanyTransactionAsset extends AssetBundle
{
    public $basePath = '@webroot/js/react';
    public $baseUrl = '@web/js/react';

    public $js = [
        'bundle.companyTransaction.js'
    ];

    public $depends = [
        'app\assets\ReactJSAsset'
    ];
}
