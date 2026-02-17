<?php

$jwtKeyPath = __DIR__ . '/../.jwt-key';
$jwtKey = file_exists($jwtKeyPath) ? trim(file_get_contents($jwtKeyPath)) : 'SecretKey123';

return [
  'adminEmail' => 'admin@example.com',
  'senderEmail' => 'noreply@example.com',
  'senderName' => 'Mailer',
  'jwt.key' => $jwtKey,
];
