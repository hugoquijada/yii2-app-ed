<?php

namespace p\controladores;

use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;

class DefaultController extends JsonController {

  public function actionIndex() {
    return (new Respuesta())
      ->mensaje("Hola mundo desde p");
  }
}
