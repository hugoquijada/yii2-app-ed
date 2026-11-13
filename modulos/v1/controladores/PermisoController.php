<?php

namespace v1\controladores;

use app\modelos\Permiso;
use eDesarrollos\rest\AuthController;

class PermisoController extends AuthController {

  public $modelClass = Permiso::class;

  public $permisosAccion = [
    'index' => Permiso::MENU_ADMINISTRACION_PERMISO,
    'post' => Permiso::PERMISO_CREAR,
    'put' => Permiso::PERMISO_EDITAR,
    'delete' => Permiso::PERMISO_ELIMINAR,
  ];

  public function buscador(&$query, $request) {
    $id = $request->get($this->modeloID, "");
    $buscar = $request->get("buscar", "");

    if ($id !== "") {
      $query->andWhere([$this->modeloID => $id]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
      ]);
    }
  }
}
