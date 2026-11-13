<?php

namespace v1\controladores;

use app\modelos\PermisoUsuario;
use app\modelos\GrupoUsuario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use Yii;
use yii\db\Expression;

class UsuarioController extends AuthController {

  public $modelClass = \app\modelos\Usuario::class;

  /**
   * @param \yii\db\ActiveQuery $query
   * @param \yii\web\Request $request
   */
  public function buscador(&$query, $request) {
    $id = $request->get($this->modeloID, "");
    $buscar = trim($request->get("buscar", ""));
    $estatus = trim($request->get("estatus", ""));

    if ($id !== "") {
      $query->andWhere([$this->modeloID => $id]);
    }

    if ($estatus !== "") {
      $estatus = intval($estatus) === 1;
      $query->andWhere(["estatus" => $estatus]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
        ["ilike", "apellidos", $buscar],
        ["ilike", "correo", $buscar],
      ]);
    }
  }

  public function actionPost() {
    $id = trim($this->req->getBodyParam("id", ""));
    $modelo = null;
    $pwd = trim($this->req->getBodyParam("pwd", ""));
    $clave = '';

    $permisos = $this->req->getBodyParam("moduloPermisos", []);
    $grupos = $this->req->getBodyParam("grupos", []);

    if ($id !== "") {
      $modelo = $this->modelClass::findOne($id);
      $clave = $modelo->clave;
    }
    if ($modelo === null) {
      $modelo = new $this->modelClass();
      $modelo->uuid();
      $modelo->creado = new Expression('now()');
    } else {
      $modelo->modificado = new Expression('now()');
    }

    $transaccion = Yii::$app->db->beginTransaction();
    try {

      $modelo->load($this->req->getBodyParams(), '');

      if ($pwd !== '') {
        $modelo->agregarClave($pwd);
      } else {
        $modelo->clave = $clave;
      }

      if (!$modelo->save()) {
        $transaccion->rollBack();
        return (new Respuesta($modelo))
          ->esError()
          ->mensaje("Hubo un problema al guardar el registro");
      }

      PermisoUsuario::updateAll(['eliminado' => new Expression('now()')], ["idUsuario" => $modelo->id]);
      foreach ($permisos as $permisoData) {
        $permiso = new PermisoUsuario();
        $permiso->id = $permiso->uuid();
        $permiso->idUsuario = $modelo->id;
        $permiso->idPermiso = $permisoData["id"];
        $permiso->asignado = new Expression('now()');
        $permiso->creado = new Expression('now()');
        $permiso->eliminado = null;

        if (!$permiso->save()) {
          $transaccion->rollBack();
          return (new Respuesta($permisoData))
            ->esError()
            ->mensaje("Hubo un problema al guardar permisos");
        }
      }

      GrupoUsuario::updateAll(['eliminado' => new Expression('now()')], ["idUsuario" => $modelo->id]);
      $idsGrupos = [];
      foreach ($grupos as $grupoData) {
        $idGrupo = is_array($grupoData) ? ($grupoData['id'] ?? '') : $grupoData;
        if ($idGrupo !== '') {
          $idsGrupos[] = $idGrupo;
        }
      }

      foreach (array_unique($idsGrupos) as $idGrupo) {
        $grupoUsuario = new GrupoUsuario();
        $grupoUsuario->id = $grupoUsuario->uuid();
        $grupoUsuario->idUsuario = $modelo->id;
        $grupoUsuario->idGrupo = $idGrupo;
        $grupoUsuario->asignado = new Expression('now()');
        $grupoUsuario->creado = new Expression('now()');
        if (!$grupoUsuario->save()) {
          $transaccion->rollBack();
          return (new Respuesta($grupoUsuario))
            ->esError()
            ->mensaje("Hubo un problema al guardar grupos");
        }
      }

      $transaccion->commit();
    } catch (\Throwable $e) {
      $transaccion->rollBack();
      return (new Respuesta($e))
        ->esError()
        ->mensaje($e->getMessage());
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("Usuario guardado");
  }
}
