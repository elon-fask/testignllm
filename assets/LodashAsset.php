<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class LodashAsset extends AssetBundle
{
    public $sourcePath = '@npm/lodash';

    public $js = [
        'lodash.min.js'
    ];
}
