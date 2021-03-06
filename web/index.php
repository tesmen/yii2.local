<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
const  ASSET_VERSION = 1;

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
Yii::$classMap[\app\app\MyApplication::class] = __DIR__ . '/../app/MyApplication.php';

(new \app\app\MyApplication($config))->run();
