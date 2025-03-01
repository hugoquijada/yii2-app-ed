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
    $ordenar = $this->ordenar;

    $this->buscador($query, $this->req);

    if ($ordenar !== false && ($campo = trim($ordenar)) !== "") { //TODO: IMPLEMENTAR ESTO EN EL SERIALIZADOR
      $separar = explode(",", $ordenar);
      $ordenamiento = [];
      foreach ($separar as $segmento) {
        $exp = explode("-", trim($segmento));
        $desc = false;
        if (count($exp) > 1) {
          $campo = $exp[0];
          $desc = $exp[1] === 'desc';
        }
        $ordenamiento[$campo] = $desc ? SORT_DESC : SORT_ASC;
      }
      if (!empty($ordenamiento)) {
        $query->orderBy($ordenamiento);
      }
    }

    $total = $query->count();

    $result = $query->select(['valor' => 'id', 'etiqueta' => 'nombre'])
    ->limit($this->limite)
    ->offset(($this->pagina - 1) * $this->limite)
    ->asArray()
    ->all();

    $respuesta = new Respuesta($result);

    return $respuesta;
  }
}
