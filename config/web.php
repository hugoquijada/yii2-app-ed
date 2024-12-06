<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/correo.php';

$config = [
  'id' => 'basic',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'language' => 'es',
  'timezone' => 'America/Hermosillo',
  'defaultRoute' => 'v1/default',
  'aliases' => [
    '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
    '@npm'   => '@vendor/npm-asset',
    '@excel' => '@app/modulos/excel',
    '@pdf' => '@app/modulos/pdf',
    '@word' => '@app/modulos/word',
    '@edesarrollos' => '@app/vendor/edesarrollos/yii2-ed/src',
  ],
  'components' => [
    'request' => [
      'cookieValidationKey' => 'a',
      'parsers' => [
        'application/json' => 'yii\web\JsonParser',
      ],
      'baseUrl' => '',
      'scriptUrl' => '/index.php',
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'user' => [
      'identityClass' => 'app\modelos\Usuario',
      'enableAutoLogin' => false,
    ],
    'mailer' => $mailer,
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'db' => $db,
    'urlManager' => [
      'class' => 'eDesarrollos\rest\UrlManager'
    ],
  ],

  'params' => $params,
  'modules' => [
    'v1' => ['class' => 'v1\Modulo'],
    'pdf' => ['class' => 'pdf\Modulo'],
    'excel' => ['class' => 'excel\Modulo'],
    'word' => ['class' => 'word\Modulo'],
  ]
];

if (YII_ENV_DEV) {
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = ['class' => 'yii\debug\Module'];

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'generators' => [
      'model' => [
        'class' => 'yii\gii\generators\model\Generator',
        'templates' => [
          'test-generator' => '@edesarrollos/gii/generators/model/default',
        ]
      ]
    ]
  ];

  $config['components']['urlManager']['baseUrl'] = '';
}

return $config;
