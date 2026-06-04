<?php

namespace v1\controladores;

use app\modelos\Sesion;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;

class IniciarSesionController extends JsonController {

  public function actionGuardar() {
    $req = Yii::$app->getRequest();
    $correo = trim($req->getBodyParam("correo", ""));
    $clave = trim($req->getBodyParam("clave", ""));

    $modelo = Sesion::find()
      ->andWhere(["correo" => $correo])
      ->andWhere('eliminado is null')
      ->one();

    /** @var \app\models\Sesion $modelo */
    if ($modelo === null) {
      $modelo = new Sesion();
      $modelo->addError("correo", "No se encontró el Usuario.");
      return new Respuesta($modelo);
    }

    if (!$modelo->validarClave($clave)) {
      $modelo->addError("clave", "Contraseña incorrecta");
      return new Respuesta($modelo);
    }

    return new Respuesta($modelo);
  }
}
