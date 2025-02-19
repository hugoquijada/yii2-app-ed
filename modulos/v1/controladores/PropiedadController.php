<?php

namespace v1\controladores;

use eDesarrollos\rest\JsonController;

class PropiedadController extends JsonController
{

  public $modelClass = '\app\modelos\Propiedad';

  public function buscador(&$query, $request)
  {
    $id = $request->get($this->modeloID, "");
    $idFormulario = $request->get("idFormulario", "");
    $buscar = $request->get("buscar", "");

    if ($id !== "") {
      $query->andWhere([$this->modeloID => $id]);
    }

    if ($idFormulario !== "") {
      $query->andWhere(["idFormulario" => $idFormulario]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
      ]);
    }
  }
}
