<?php

namespace v1\controladores;

use eDesarrollos\rest\JsonController;

class PropiedadController extends JsonController
{

  public $modelClass = '\app\modelos\Propiedad';

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
