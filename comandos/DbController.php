<?php

namespace app\comandos;

use app\modelos\PermisoUsuario;
use app\modelos\Permiso;
use app\modelos\Grupo;
use app\modelos\GrupoUsuario;
use app\modelos\Usuario;
use Yii;
use yii\db\Expression;
use yii\console\Controller;
use yii\console\ExitCode;

class DbController extends Controller {

  public function stdout($string) {
    parent::stdout("{$string}\n");
  }

  public function actionIndex() {
    $this->stdout("Guardar usuario soporte");
    $this->actionInsertarUsuario();
    $this->stdout("Proceso terminado");
  }

  public function actionInsertarUsuario() {
    $transaccion = Yii::$app->db->beginTransaction();
    try {
      $usuario = Usuario::find()
        ->andWhere(['correo' => 'soporte@edesarrollos.info'])
        ->andWhere(['eliminado' => null])
        ->one();

      if ($usuario === null) {
        $usuario = new Usuario();
        $usuario->uuid();
        $usuario->correo = 'soporte@edesarrollos.info';
        $usuario->agregarClave('Soporte@2026');
        $usuario->nombre = 'Soporte';
        $usuario->apellidos = 'Técnico';
        $usuario->rol = 'admin';
        $usuario->telefono = '1234567890';
        $usuario->creado = new Expression('now()');
        if (!$usuario->save()) {
          throw new \RuntimeException(json_encode($usuario->getFirstErrors()));
        }
      }

      $grupo = Grupo::find()
        ->andWhere(['nombre' => Grupo::ADMINISTRADORES])
        ->andWhere(['eliminado' => null])
        ->one();
      if ($grupo === null) {
        throw new \RuntimeException('Falta el grupo semilla ' . Grupo::ADMINISTRADORES . '.');
      }

      $asignado = GrupoUsuario::find()
        ->andWhere(['idUsuario' => $usuario->id, 'idGrupo' => $grupo->id])
        ->andWhere(['eliminado' => null])
        ->exists();
      if (!$asignado) {
        $asignacion = new GrupoUsuario();
        $asignacion->uuid();
        $asignacion->idUsuario = $usuario->id;
        $asignacion->idGrupo = $grupo->id;
        $asignacion->asignado = new Expression('now()');
        $asignacion->creado = new Expression('now()');
        if (!$asignacion->save()) {
          throw new \RuntimeException(json_encode($asignacion->getFirstErrors()));
        }
      }

      PermisoUsuario::updateAll([
        'eliminado' => new Expression('now()'),
      ], [
        'idUsuario' => $usuario->id,
        'idPermiso' => Permiso::ADMINISTRACION,
        'eliminado' => null,
      ]);

      $transaccion->commit();
      $this->stdout("Usuario soporte listo en el grupo " . Grupo::ADMINISTRADORES . ".");
    } catch (\Throwable $e) {
      $transaccion->rollBack();
      $this->stderr($e->getMessage() . "\n");
      return ExitCode::UNSPECIFIED_ERROR;
    }

    return ExitCode::OK;
  }
}
