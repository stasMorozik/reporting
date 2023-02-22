<?php

use Dotenv\Dotenv;

Dotenv::createUnsafeImmutable(__DIR__, '.env')->load();

return [
  'id' => 'reporting',
  'basePath' => __DIR__.'/src',
  'aliases' => [
    '@app' => __DIR__.'/src'
  ],
  'bootstrap' => ['log'],
  'components' => [
    'request' => [
      'cookieValidationKey' => '123',
    ],
    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'enableStrictParsing' => false,
      'rules' => [
      ],
    ],
    'db' => [
      'class' => 'yii\db\Connection',
      'dsn' => "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};port={$_ENV['DB_PORT']}",
      'username' => $_ENV["DB_USER"],
      'password' => $_ENV["DB_PASSWORD"],
      'charset' => 'utf8'
    ],
    'log' => [
      'targets' => [
        'class' => 'yii\log\FileTarget'
      ]
    ],
  ],
  'modules' => [
    'users' => [
      'class' => 'app\modules\user\Module'
    ],
    'reports' => [
      'class' => 'app\modules\reports\Module'
    ]
  ]
];
