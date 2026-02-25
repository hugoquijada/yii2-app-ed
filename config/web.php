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
  'layoutPath' => '@app/modulos/v1/vistas/layouts',
  'layout' => 'main',
  'aliases' => [
    '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
    '@npm'   => '@vendor/npm-asset',
    '@excel' => '@app/modulos/excel',
    '@pdf' => '@app/modulos/pdf',
    '@mail' => '@app/modulos/mail',
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
    'errorHandler' => [
      'errorAction' => 'v1/default/error',
    ],
    'user' => [
      'identityClass' => 'app\modelos\Usuario',
      'enableAutoLogin' => false,
      'enableSession' => false,
    ],
    'mailer' => [
      'class' => 'yii\swiftmailer\Mailer',
      // send all mails to a file by default. You have to set
      // 'useFileTransport' to false and configure a transport
      // for the mailer to send real emails.
      'useFileTransport' => true,
    ],
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
    'response' => [
      'class' => 'yii\web\Response',
      'format' => yii\web\Response::FORMAT_JSON,
      'on beforeSend' => function ($event) {
        /** @var \yii\web\Response $response */
        $response = $event->sender;

        // Salir si no es un error a transformar o si ya está en el formato correcto
        if (!($response->isClientError || $response->isServerError) || !is_array($response->data) || isset($response->data['errores'])) {
          return;
        }

        $data = $response->data;
        $r = new \eDesarrollos\data\Respuesta();

        $r->esError($response->statusCode);
        $r->mensaje($data['message'] ?? $data['mensaje'] ?? 'Ha ocurrido un error.');
        if (isset($data['type'])) {
          $r->cuerpo['type'] = $data['type'];
        }
        $response->data = $r->cuerpo;
      }
    ],
  ],

  'params' => $params,
  'modules' => [
    'v1' => ['class' => 'v1\Modulo'],
    'pdf' => ['class' => 'pdf\Modulo'],
    'excel' => ['class' => 'excel\Modulo'],
    'word' => ['class' => 'word\Modulo'],
    'mail' => ['class' => 'mail\Modulo'],
  ]
];

if (YII_ENV_DEV) {
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = ['class' => 'yii\debug\Module'];

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = ['class' => 'eDesarrollos\gii\Module'];

  $config['components']['urlManager']['baseUrl'] = '';
  $config['modules']['mail'] = ['class' => 'mail\Modulo'];
}

return $config;
