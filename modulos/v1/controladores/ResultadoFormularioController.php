<?php

namespace v1\controladores;

use app\modelos\Propiedad;
use app\modelos\ResultadoFormularioValor;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;
use yii\db\Expression;

class ResultadoFormularioController extends JsonController
{

  public $modelClass = '\app\modelos\ResultadoFormulario';

  public function buscador(&$query, $request)
  {
    $id = $request->get($this->modeloID, "");
    $buscar = trim($request->get("buscar", ""));

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

  public function actionGuardar()
  {
    $id = trim($this->req->getBodyParam("id", ""));
    $modelo = null;

    $valores = $this->req->getBodyParam("valores", []);

    if ($id !== "") {
      $modelo = $this->modelClass::findOne($id);
    }
    if ($modelo === null) {
      $modelo = new $this->modelClass();
      $modelo->uuid();
      $modelo->creado = new Expression('now()');
    } else {
      $modelo->modificado = new Expression('now()');
    }

    $transaccion = Yii::$app->db->beginTransaction();
    try {

      $modelo->load($this->req->getBodyParams(), '');
      if (!$modelo->save()) {
        $transaccion->rollBack();
        return (new Respuesta($modelo))
          ->esError()
          ->mensaje("Hubo un problema al guardar el formulario");
      }

      foreach ($valores as $valor) {
        $resultadoValor = new ResultadoFormularioValor();
        $resultadoValor->id = $resultadoValor->uuid();
        $resultadoValor->idResultadoFormulario = $modelo->id;
        $propiedad = Propiedad::findOne($valor["idPropiedad"]);

        $resultadoValor->idPropiedad = $propiedad->id;
        $resultadoValor->etiqueta = $propiedad->nombre;
        
        $resultadoValor->valor = $valor["valor"];
        if (!$resultadoValor->save()) {
          $transaccion->rollBack();
          return (new Respuesta($resultadoValor))
            ->esError()
            ->mensaje("Hubo un problema al guardar el valor de la propiedad", $propiedad->nombre);
        }
      }

      $transaccion->commit();
    } catch (\Exception $e) {
      $transaccion->rollBack();
      return (new Respuesta($e))
        ->esError()
        ->mensaje($e->getMessage());
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("ResultadoFormulario guardado");
  }
}
