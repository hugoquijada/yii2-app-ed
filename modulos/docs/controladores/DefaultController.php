<?php

namespace excel\controladores;

use eDesarrollos\rest\JsonController;

class DefaultController extends JsonController {

  public function actionIndex() {
    return "Hola mundo";
  }
}
