<?php

namespace v1\controladores;

use app\modelos\Permiso;
use eDesarrollos\rest\AuthController;

class MediaController extends AuthController {

  public $modelClass = \app\modelos\Media::class;

  public $permisosAccion = [
    'index' => Permiso::MENU_CATALOGOS,
  ];

  public function buscador(&$query, $request) {
    $id = $request->get($this->modeloID, "");
    $buscar = $request->get("buscar", "");
    $tipo = $request->get("tipo", "");

    if ($id !== "") {
      $query->andWhere([$this->modeloID => $id]);
    }

    if ($tipo !== "") {
      $query->andWhere(["{{Media}}.extension" => $tipo]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
      ]);
    }
  }
}
