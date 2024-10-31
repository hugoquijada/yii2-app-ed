<?php

namespace v1\controladores;

use app\modelos\ModuloPermisoUsuario;
use app\modelos\PermisoUsuario;
use app\modelos\UsuarioPuerta;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;
use yii\db\Expression;

class UsuarioController extends JsonController {

  public $modelClass = '\app\modelos\Usuario';

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

  public function actionGuardar()
  {
    $id = trim($this->req->getBodyParam("id", ""));
    $modelo = null;

    $permisos = $this->req->getBodyParam("permisos", []);

    if ($id !== "") {
      $modelo = $this->modelClass::findOne($id);
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
      if (!$modelo->save()) {
        $transaccion->rollBack();
        return (new Respuesta($modelo))
          ->esError()
          ->mensaje("Hubo un problema al guardar el registro");
      }

      PermisoUsuario::deleteAll(["idUsuario" => $modelo->id]);
      foreach ($permisos as $permisoData) {
        $permiso = new PermisoUsuario();
        $permiso->id = $permiso->uuid();
        $permiso->idUsuario = $modelo->id;
        $permiso->idPermiso = $permisoData["id"];
        $permiso->asignado = new Expression('now()');
        $permiso->creado = $permisoData["creado"];

        if (!$permiso->save()) {
          $transaccion->rollBack();
          return (new Respuesta($permisoData))
            ->esError()
            ->mensaje("Hubo un problema al guardar permisos");
        }
      }

      $transaccion->commit();
    } catch (\Exception $e) {
      $transaccion->rollBack();
      return (new Respuesta($e))
        ->esError()
        ->mensaje($e->getMessage());
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("Registro guardado");
  }
}
