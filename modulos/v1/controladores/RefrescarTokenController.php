<?php

namespace v1\controladores;

use app\modelos\RefreshTokenUsuario;
use app\modelos\Sesion;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;

class RefrescarTokenController extends JsonController
{

  public function actionGuardar()
  {
    $req = Yii::$app->getRequest();

    $token = trim($req->getBodyParam("refreshToken", ""));
    return new Respuesta($token);

    $refreshToken = RefreshTokenUsuario::find()
      ->andWhere(["token" => $token])
      ->andWhere(["expiracion" => ">=", date("Y-m-d H:i:s")])
      ->one();

    if ($refreshToken === null) {
      return (new Respuesta($refreshToken))
        ->esError()
        ->mensaje("Refresh token Inválido");
    }

    $modelo = Sesion::find()
      ->andWhere(["id" => $refreshToken->idUsuario])
      ->andWhere('eliminado is null')
      ->one();

    /** @var \app\models\Sesion $modelo */
    if ($modelo === null) {
      $modelo = new Sesion();
      $modelo->addError("correo", "No se encontró el Usuario.");
      return new Respuesta($modelo);
    }


    return new Respuesta($modelo);
  }
}
