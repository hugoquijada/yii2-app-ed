<?php

namespace v1\controladores;

use eDesarrollos\rest\AuthController;

class RutaController extends AuthController
{

  public $modelClass = '\app\modelos\Ruta';

  public function buscador(&$query, $request)
  {
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
