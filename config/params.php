<?php

$jwtKeyPath = __DIR__ . '/../.jwt-key';
$jwtKey = file_exists($jwtKeyPath) ? trim(file_get_contents($jwtKeyPath)) : 'SecretKey123';
$exportacion = require __DIR__ . '/exportacion.php';
$openapi = require __DIR__ . '/openapi.php';

return [
  'adminEmail' => 'admin@example.com',
  'senderEmail' => 'noreply@example.com',
  'senderName' => 'Mailer',
  'jwt.key' => $jwtKey,
  'exportacion' => $exportacion,
  'openapi' => $openapi,
];
