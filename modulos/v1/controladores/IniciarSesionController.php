<?php

namespace v1\controladores;

use app\modelos\RefreshTokenUsuario;
use app\modelos\Sesion;
use app\modelos\Usuario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;
use yii\filters\VerbFilter;

class IniciarSesionController extends JsonController
{

  public function actionGuardar()
  {
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

  public function actionRefrescarToken() {
    $req = Yii::$app->getRequest();
    
    $token = trim($req->getBodyParam("refreshToken", ""));

    $refreshToken = RefreshTokenUsuario::find()
      ->andWhere(["token" => $token])
      ->one();

    if($refreshToken === null){
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
