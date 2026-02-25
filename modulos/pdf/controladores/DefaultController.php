<?php

namespace pdf\controladores;

use hqsoft\reportkit\document\CellStyle;
use hqsoft\reportkit\document\Document;
use hqsoft\reportkit\document\Row;

class DefaultController extends \eDesarrollos\rest\JsonController {

  public function actionIndex() {
    $documento = new Document();
    $documento->row(function ($row) {
      /** @var Row $row */
      $estilo = (new CellStyle)
        ->bold()
        ->fontSize(20);

      $row->col(12)
        ->style($estilo)
        ->text("Hola");
      $row->col(12)
        ->text("Mundo");
    });
    return $documento;
  }
}
