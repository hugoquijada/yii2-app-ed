<?php

namespace v1\controladores;

use app\modelos\Formulario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use yii\db\Expression;

class CrudController extends AuthController {
  const ELIMINADO = "eliminado";

  public function actionIndex() {
    $parametros = $this->req->get();
    $idFormulario = null;
    if(isset($parametros["idFormulario"])) {
      $idFormulario = $parametros["idFormulario"];
      unset($parametros["idFormulario"]);
    }

    try {
      $formulario = $this->obtenerFormulario($idFormulario);
    } catch(\Exception $e) {
      return (new Respuesta())
        ->esError()
        ->mensaje($e->getMessage());
    }

    $clase = "app\modelos\\" . $formulario->tabla;
    $instancia = new $clase;
    /** @var \app\modelos\ModeloBase $clase */
    $modelo = $clase::find();
    if($instancia->hasProperty(self::ELIMINADO)) {
      $modelo->andWhere([self::ELIMINADO => null]);
    }

    foreach($parametros as $campo => $valor) {
      if($modelo->hasProperty($campo)) {
        $modelo->andWhere([$campo => $valor]);
      }
    }

    return (new Respuesta($modelo, $this->limite, $this->pagina, $this->ordenar));
  }

  public function actionGuardar() {
    $parametros = $this->req->getBodyParams();
    $idFormulario = null;
    if(isset($parametros["idFormulario"])) {
      $idFormulario = $parametros["idFormulario"];
      unset($parametros["idFormulario"]);
    }

    try {
      $formulario = $this->obtenerFormulario($idFormulario);
    } catch(\Exception $e) {
      return (new Respuesta())
        ->esError()
        ->mensaje($e->getMessage());
    }

    $clase = "app\modelos\\" . $formulario->tabla;
    $instancia = new $clase;
    $modelo = $clase::find()
      ->andWhere(["id" => $parametros["id"]]);
    if($modelo->hasProperty(self::ELIMINADO)) {
      $modelo->andWhere([self::ELIMINADO => null]);
    }
    $modelo = $modelo->one();
    if($modelo === null) {
      $modelo = new $clase;
      $modelo->loadDefaultValues();
    }
    /** @var \app\modelos\ModeloBase $modelo */

    $nombre = $modelo->nombreSingular();
    $modelo->load($parametros);
    if(!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("Ocurrió un error al guardar el {$nombre}");
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("{$nombre} guardado.");
  }

  public function actionEliminar() {
    $parametros = $this->req->getBodyParams();
    $idFormulario = null;
    if(isset($parametros["idFormulario"])) {
      $idFormulario = $parametros["idFormulario"];
      unset($parametros["idFormulario"]);
    }

    try {
      $formulario = $this->obtenerFormulario($idFormulario);
    } catch(\Exception $e) {
      return (new Respuesta())
        ->esError()
        ->mensaje($e->getMessage());
    }

    $clase = "app\modelos\\" . $formulario->tabla;
    $instancia = new $clase;
    $modelo = $clase::find()
      ->andWhere(["id" => $parametros["id"]]);
    $tieneEliminado = false;
    if($instancia->hasProperty(self::ELIMINADO)) {
      $modelo->andWhere([self::ELIMINADO => null]);
      $tieneEliminado = true;
    }

    if(!$tieneEliminado) {
      return (new Respuesta())
        ->esError()
        ->detalle([
          "modelo" => $modelo,
          "tieneEliminado" => $tieneEliminado
        ])
        ->mensaje("La tabla no tiene campo eliminado");
    }
    
    $modelo = $modelo->one();
    if($modelo === null) {
      return (new Respuesta())
        ->esError()
        ->mensaje("No se encontró el registro");
    }
    /** @var \app\modelos\ModeloBase $modelo */

    $nombre = $modelo->nombreSingular();
    $modelo->eliminado = new Expression('now()');
    if(!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("No se pudo eliminar el {$nombre}");
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("{$nombre} eliminado.");
  }


  /**
   * @return Formulario
   */
  private function obtenerFormulario($idFormulario) {
    $formulario = Formulario::find()
      ->andWhere([
        "id" => $idFormulario,
        "eliminado" => null
      ])
      ->one();

    if($formulario === null) {
      throw new \Exception("No se encontró el Formulario");
    }

    return $formulario;
  }


}