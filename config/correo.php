<?php

return  [
  'class' => \yii\symfonymailer\Mailer::class,
  'viewPath' => '@app/mail',
  'useFileTransport' => false,
  'transport' => [
    'class' => 'Swift_SmtpTransport',
    'host' => 'smtp.gmail.com',
    'username' => '',
    'password' => '',
    'port' => '587',
    'encryption' => 'tls',
  ],
];