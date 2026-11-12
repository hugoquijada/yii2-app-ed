<?php

namespace v1\controladores;

use app\modelos\Configuracion;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use yii\db\Expression;

class ConfiguracionController extends AuthController {

  public $modelClass = Configuracion::class;

  public function actionPost() {
    $clave = trim($this->req->getBodyParam("clave", ""));
    $modelo = $clave !== "" ? Configuracion::findOne($clave) : null;

    if ($modelo === null) {
      $modelo = new Configuracion();
      $modelo->creado = new Expression('now()');
    } else {
      $modelo->modificado = new Expression('now()');
    }

    $modelo->load($this->req->getBodyParams(), '');
    if (!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("Hubo un problema al guardar la {$this->nombreSingular}");
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("{$this->nombreSingular} guardada");
  }

  public function buscador(&$query, $request) {
    $id = $request->get("clave", "");
    $buscar = $request->get("buscar", "");
    $idUsuario = $request->get("idUsuario", "");

    if ($id !== "") {
      $query->andWhere(["clave" => $id]);
    }

    if ($idUsuario !== "") {
      $query->andWhere(["idUsuario" => $idUsuario]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
        ["ilike", "clave", $buscar],
        ["ilike", "valor", $buscar],
        ["ilike", "descripcion", $buscar],
      ]);
    }
  }
}
