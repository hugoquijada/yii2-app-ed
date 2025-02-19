<?php

namespace v1\controladores;

use app\modelos\RefreshTokenUsuario;
use app\modelos\Sesion;
use app\modelos\Usuario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use eDesarrollos\rest\JsonController;
use Yii;

class RefrescarUsuarioController extends AuthController
{

  public function actionIndex()
  {
    $usuario = $this->usuario;

    $modelo = Sesion::find()
      ->andWhere(["id" => $usuario->id])
      ->andWhere('eliminado is null')
      ->one();


    return new Respuesta($modelo);
  }
}
