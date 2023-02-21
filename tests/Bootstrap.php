<?php

namespace tests;

require('../vendor/autoload.php');
require('../vendor/yiisoft/yii2/Yii.php');

use Dotenv\Dotenv;
use yii;

class Bootstrap
{
  public static function factory()
  {
    Dotenv::createUnsafeImmutable(__DIR__.'/../', '.env.test')->load();

    return new yii\db\Connection([
      'dsn' => "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};port={$_ENV['DB_PORT']}",
      'username' => $_ENV["DB_USER"],
      'password' => $_ENV["DB_PASSWORD"],
      'charset' => 'utf8'
    ]);
  }
}
