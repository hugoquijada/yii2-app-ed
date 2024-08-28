<?php

namespace v1\controladores;

use eDesarrollos\data\Respuesta;
use app\rest\JsonController;

class DefaultController extends JsonController {


  /*
  public function actionIndex() {
    $formato = $this->req->get("formato");
    if($formato === "json") {
      return $this->actionConsulta();
    } elseif($formato === "pdf") {
      return $this->actionPdf();
    } elseif($formato === "xlsx") {
      return $this->actionExcel();
    }
  }

  public function actionConsulta() {
    return new Respuesta(["accion" => "Index"]);
  }

  public function actionGuardar() {
    $formato = $this->req->get("formato");
    return new Respuesta(["accion" => "Guardar", "formato" => $formato]);
  }

  public function actionEliminar() {
    $formato = $this->req->get("formato");
    return new Respuesta(["accion" => "Eliminar", "formato" => $formato]);
  }

  public function actionPdf() {
    return new Respuesta(["accion" => "Pdf"]);
  }

  public function actionExcel() {
    return new Respuesta(["accion" => "Excel"]);
  }
  */
}
