<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/correo.php';

$config = [
  'id' => 'basic-console',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log', 'mail'],
  'controllerNamespace' => 'app\comandos',
  'aliases' => [
    '@excel' => '@app/modulos/excel',
    '@pdf' => '@app/modulos/pdf',
    '@mail' => '@app/mail',
    '@word' => '@app/modulos/word',
    '@edesarrollos' => '@app/vendor/edesarrollos/yii2-ed/src',
  ],
  'components' => [
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'log' => [
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'db' => $db,
    'mailer' => $mailer,
  ],
  'controllerMap' => [
    'migrate' => [
      'class' => 'yii\console\controllers\MigrateController',
      'migrationPath' => '@app/migraciones',
    ],
    'openapi' => [
      'class' => 'eDesarrollos\openapi\comandos\OpenapiController',
    ],
  ],
  'params' => $params,
  'modules' => [
    'mail' => ['class' => 'eDesarrollos\mail\Module'],
  ],
];

if (YII_ENV_DEV) {
  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = ['class' => 'yii\gii\Module'];
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = ['class' => 'yii\debug\Module'];
}

return $config;
