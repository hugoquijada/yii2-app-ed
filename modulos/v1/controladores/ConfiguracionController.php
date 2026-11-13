<?php

namespace v1\controladores;

use app\modelos\Configuracion;
use app\modelos\Permiso;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use yii\db\Expression;

class ConfiguracionController extends AuthController {

  public $modelClass = Configuracion::class;

  public $permisosAccion = [
    'index' => Permiso::MENU_ADMINISTRACION_CONFIGURACION,
    'post' => [
      'permiso' => Permiso::CONFIGURACION_EDITAR,
      'escenario' => Configuracion::ESCENARIO_ACTUALIZAR_SISTEMA,
    ],
    'put' => [
      'permiso' => Permiso::CONFIGURACION_EDITAR,
      'escenario' => Configuracion::ESCENARIO_ACTUALIZAR_SISTEMA,
    ],
  ];

  public function actionPost() {
    $clave = trim($this->req->getBodyParam("clave", ""));
    $modelo = $clave !== "" ? Configuracion::findOne($clave) : null;

    if ($modelo === null) {
      $modelo = new Configuracion();
      $modelo->creado = new Expression('now()');
    } else {
      $modelo->modificado = new Expression('now()');
    }

    $this->prepararModeloAccionActual($modelo);
    $modelo->load($this->req->getBodyParams(), '');
    $modelo->idUsuario = $this->usuario->id;
    if (!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("Hubo un problema al guardar la {$this->nombreSingular}");
    }

    $modelo->refresh();
    if ($modelo->clave === Configuracion::CLAVE_ZONA_HORARIA) {
      \Yii::$app->timeZone = $modelo->valor;
    }
    return (new Respuesta($modelo))
      ->mensaje("{$this->nombreSingular} guardada");
  }

  public function buscador(&$query, $request) {
    $id = $request->get("clave", "");
    $buscar = $request->get("buscar", "");

    if ($id !== "") {
      $query->andWhere(["clave" => $id]);
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
