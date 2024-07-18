<?php

namespace app\comandos;

use yii\console\Controller;
use yii\console\ExitCode;

class DbController extends Controller {

  public function actionInsertarUsuario() {
    $usuario = new \app\models\Usuario();
    $usuario->uuid();
    $usuario->correo = "soporte@admin.com";
    $usuario->agregarClave("Soporte@" . date("Y"));
    $usuario->nombre = "Soporte";
    $usuario->apellidos = "Técnico";
    $usuario->rol = "admin";
    $usuario->telefono = "1234567890";
    if (!$usuario->save()) {
      $this->stdout(json_encode($usuario->getFirstErrors()));
    }
    return ExitCode::OK;
  }
}
