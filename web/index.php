<?php
+//die("<h1><center>Service (new) update in progress. Expected completion in one hour.</center></h1>");
// +ini_set('display_errors', 1);
// +ini_set('display_startup_errors', 1);
// +error_reporting(E_ALL);


date_default_timezone_set("PST8PDT");

require(__DIR__ . '/../vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

define('YII_DEBUG', !!getenv('YII_DEBUG'));
define('YII_ENV_DEV', !!getenv('YII_ENV_DEV'));

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
