<?php

namespace v1\controladores;

use eDesarrollos\rest\AuthController;

class RutaController extends AuthController {

  public static function mostrarEnOpenapi(): bool {
    return false;
  }

  public $modelClass = \app\modelos\Ruta::class;

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
