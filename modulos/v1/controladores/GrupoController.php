<?php

namespace v1\controladores;

use app\modelos\Grupo;
use app\modelos\PermisoGrupo;
use app\modelos\GrupoUsuario;
use app\modelos\Permiso;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use Yii;
use yii\db\Expression;

class GrupoController extends AuthController {

  public $modelClass = Grupo::class;
  public $importacionHabilitada = true;
  public $accionesPermitidas = [
    'index',
    'post',
    'put',
    'delete',
    'importar-vista-previa',
    'importar-aplicar',
  ];

  public $permisosAccion = [
    'index' => Permiso::MENU_ADMINISTRACION_GRUPO,
    'post' => [
      'permiso' => Permiso::GRUPO_CREAR,
      'escenario' => Grupo::ESCENARIO_CREAR,
    ],
    'put' => [
      'permiso' => Permiso::GRUPO_EDITAR,
      'escenario' => Grupo::ESCENARIO_ACTUALIZAR,
    ],
    'delete' => Permiso::GRUPO_ELIMINAR,
    'importar-vista-previa' => [Permiso::GRUPO_CREAR, Permiso::GRUPO_EDITAR],
    'importar-aplicar' => [Permiso::GRUPO_CREAR, Permiso::GRUPO_EDITAR],
  ];

  public function buscador(&$query, $request) {
    $id = $request->get($this->modeloID, "");
    $buscar = trim($request->get("buscar", ""));

    if ($id !== "") {
      $query->andWhere([$this->modeloID => $id]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
        ["ilike", "descripcion", $buscar],
      ]);
    }
  }

  public function actionPost() {
    $id = trim($this->req->getBodyParam("id", ""));
    $permisos = $this->req->getBodyParam("permisos", []);
    $usuarios = $this->req->getBodyParam("usuarios", []);
    $modelo = $id !== "" ? Grupo::findOne($id) : null;

    if ($modelo === null) {
      $modelo = new Grupo();
      $modelo->scenario = Grupo::ESCENARIO_CREAR;
      $modelo->uuid();
      $modelo->creado = new Expression("now()");
    } else {
      $modelo->modificado = new Expression("now()");
    }

    $transaccion = Yii::$app->db->beginTransaction();
    try {
      $this->prepararModeloAccionActual($modelo);
      $modelo->load($this->req->getBodyParams(), "");
      if (!$modelo->save()) {
        $transaccion->rollBack();
        return (new Respuesta($modelo))
          ->esError()
          ->mensaje("Hubo un problema al guardar el grupo");
      }

      PermisoGrupo::updateAll([
        "eliminado" => new Expression("now()"),
      ], ["idGrupo" => $modelo->id]);

      foreach ($permisos as $permisoData) {
        $idPermiso = is_array($permisoData) ? ($permisoData["id"] ?? "") : $permisoData;
        if ($idPermiso === "") {
          continue;
        }

        $permisoGrupo = new PermisoGrupo();
        $permisoGrupo->id = $permisoGrupo->uuid();
        $permisoGrupo->idGrupo = $modelo->id;
        $permisoGrupo->idPermiso = $idPermiso;
        $permisoGrupo->asignado = new Expression("now()");
        $permisoGrupo->creado = new Expression("now()");
        if (!$permisoGrupo->save()) {
          $transaccion->rollBack();
          return (new Respuesta($permisoGrupo))
            ->esError()
            ->mensaje("Hubo un problema al guardar permisos del grupo");
        }
      }

      GrupoUsuario::updateAll([
        "eliminado" => new Expression("now()"),
      ], ["idGrupo" => $modelo->id]);

      $idsUsuarios = [];
      foreach ($usuarios as $usuarioData) {
        $idUsuario = is_array($usuarioData) ? ($usuarioData["id"] ?? "") : $usuarioData;
        if ($idUsuario !== "") {
          $idsUsuarios[] = $idUsuario;
        }
      }

      foreach (array_unique($idsUsuarios) as $idUsuario) {
        $grupoUsuario = new GrupoUsuario();
        $grupoUsuario->uuid();
        $grupoUsuario->idGrupo = $modelo->id;
        $grupoUsuario->idUsuario = $idUsuario;
        $grupoUsuario->asignado = new Expression("now()");
        $grupoUsuario->creado = new Expression("now()");
        if (!$grupoUsuario->save()) {
          $transaccion->rollBack();
          return (new Respuesta($grupoUsuario))
            ->esError()
            ->mensaje("Hubo un problema al guardar usuarios del grupo");
        }
      }

      $transaccion->commit();
    } catch (\Throwable $e) {
      $transaccion->rollBack();
      return (new Respuesta())
        ->esError()
        ->mensaje($e->getMessage());
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("Grupo guardado");
  }
}
