<?php

namespace v1\controllers;

use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;

class DefaultController extends JsonController {

  public function actionIndex() {
    return new Respuesta(["Hola" => "Mundo"]);
  }
}
