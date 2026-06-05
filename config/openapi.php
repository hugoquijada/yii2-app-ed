<?php

return [
  'title' => 'API YII2',
  'version' => '1.0.0',
  'description' => 'Descripción de la Documentación.',
  'formats' => ['json', 'xml', 'html', 'pdf', 'xlsx', 'csv', 'docx'],
  'servers' => [
    [
      'url' => 'http://localhost:8080',
      'description' => 'Servidor local',
    ],
  ],
  'controllers' => [
    [
      'module' => 'v1',
      'path' => '@app/modulos/v1/controladores',
      'namespace' => 'v1\controladores',
    ],
    [
      'module' => 'p',
      'path' => '@app/modulos/p/controladores',
      'namespace' => 'p\controladores',
    ],
  ],
  'output' => '@publico/openapi.json',
  'scalar' => [
    'title' => 'Yii2 App ED API Docs',
    'specUrl' => './openapi.json',
    'output' => '@publico/scalar.html',
    'cdnUrl' => 'https://cdn.jsdelivr.net/npm/@scalar/api-reference',
  ],
];
