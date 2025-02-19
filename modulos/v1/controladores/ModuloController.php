<?php

namespace v1\controladores;

use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;

class ModuloController extends AuthController
{

  public $modelClass = '\app\modelos\Modulo';

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

  public function actionSelector(){
    $query = $this->queryInicial;
    
    $query->select(['valor' => 'id', 'etiqueta' => 'nombre']);

    $this->buscador($query, $this->req);

    return new Respuesta($query, $this->limite, $this->pagina, $this->ordenar);
  }
}
