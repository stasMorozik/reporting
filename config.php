<?php

return [
  'id' => 'reporting',
  'basePath' => __DIR__.'/src',
  'aliases' => [
    '@app' => __DIR__.'/src'
  ],
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
    ]
  ],
  'bootstrap' => [
    'users',
  ],
  'modules' => [
    'users' => [
      'class' => 'app\modules\user\Module'
    ]
  ]
];
