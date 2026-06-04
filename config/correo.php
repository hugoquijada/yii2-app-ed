<?php

return  [
  'class' => \yii\symfonymailer\Mailer::class,
  'viewPath' => '@app/mail',
  'useFileTransport' => false,
  'transport' => [
    'scheme' => 'smtp',
    'host' => 'smtp.gmail.com',
    'username' => '',
    'password' => '',
    'port' => 587,
  ],
];
