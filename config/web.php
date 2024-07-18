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
  'components' => [
    'request' => [
      'cookieValidationKey' => 'a',
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
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [],
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
  $config['modules']['gii'] = ['class' => 'yii\gii\Module'];
}

return $config;
