<?php

namespace v1\controladores;

use app\modelos\Configuracion;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;

class ConfiguracionPublicaController extends JsonController {

  public $accionesPermitidas = ['index'];

  public function actionIndex() {
    $configuraciones = Configuracion::find()
      ->select(['clave', 'valor'])
      ->where([
        'clave' => [
          Configuracion::CLAVE_TITULO_SISTEMA,
          Configuracion::CLAVE_LOGO,
          Configuracion::CLAVE_COLOR_PRIMARIO,
          Configuracion::CLAVE_COLOR_SECUNDARIO,
        ],
        'eliminado' => null,
      ])
      ->asArray()
      ->all();

    return new Respuesta($configuraciones);
  }
}
