<?php

if (PHP_SAPI == 'cli-server') {
  $url  = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if (is_file($file)) return false;
}

// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');

require('../vendor/autoload.php');
require('../vendor/yiisoft/yii2/Yii.php');

$config = require 'config.php';

$app = new yii\web\Application($config);

($app)->run();
