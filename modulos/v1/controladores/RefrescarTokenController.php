<?php

namespace v1\controladores;

use app\modelos\RefreshTokenUsuario;
use app\modelos\Sesion;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;

class RefrescarTokenController extends JsonController {

  public $accionesPermitidas = ["post"];

  public static function mostrarEnOpenapi(): bool {
    return false;
  }

  public function actionPost() {
    $req = Yii::$app->getRequest();

    $token = trim($req->getBodyParam("refreshToken", ""));

    $refreshToken = RefreshTokenUsuario::find()
      ->andWhere(["token" => $token])
      ->andWhere(["expiracion" => ">=", date("Y-m-d H:i:s")])
      ->andWhere(["eliminado" => null])
      ->one();

    if ($refreshToken === null) {
      $respuesta = (new Respuesta())
        ->esError(401)
        ->mensaje("Refresh token inválido");
      $respuesta->errores = ["refreshToken" => "El refresh token no es válido o expiró."];
      return $respuesta;
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


    $modelo->removeRefreshToken($token);

    return new Respuesta($modelo);
  }
}
