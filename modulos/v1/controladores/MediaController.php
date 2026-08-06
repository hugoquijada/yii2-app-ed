<?php

namespace v1\controladores;

use eDesarrollos\rest\JsonController;

class MediaController extends JsonController {

  public $modelClass = \app\modelos\Media::class;

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
