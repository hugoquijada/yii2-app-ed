<?php

namespace v1\controladores;

use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use yii\db\Expression;

class TipoCampoController extends JsonController
{

  public $modelClass = '\app\modelos\TipoCampo';

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
