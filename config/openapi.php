<?php

return [
  'title' => 'Yii2 App ED API',
  'version' => '1.0.0',
  'description' => 'Documentacion generada automaticamente a partir de controladores y modelos.',
  'formats' => ['json'],
  'servers' => [
    [
      'url' => 'http://localhost',
      'description' => 'Servidor local',
    ],
  ],
  'controllers' => [
    [
      'module' => 'v1',
      'path' => '@app/modulos/v1/controladores',
      'namespace' => 'v1\controladores',
    ],
  ],
  'output' => '@app/publico/openapi.json',
  'scalar' => [
    'title' => 'Yii2 App ED API Docs',
    'specUrl' => './openapi.json',
    'output' => '@app/publico/scalar.html',
    'cdnUrl' => 'https://cdn.jsdelivr.net/npm/@scalar/api-reference',
  ],
];
