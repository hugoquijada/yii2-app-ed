<?php

namespace v1\controladores;

use app\modelos\MenuFormulario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use Yii;
use yii\db\Expression;

class FormularioController extends AuthController
{

  public $modelClass = '\app\modelos\Formulario';

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

      $transaccion->commit();
    } catch (\Exception $e) {
      $transaccion->rollBack();
      return (new Respuesta($e))
        ->esError()
        ->mensaje($e->getMessage());
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("Formulario guardado");
  }

  public function actionListarTablas() {
    $buscar = trim($this->req->getQueryParam("buscar", ""));
    $tablas = Yii::$app->db->createCommand("SELECT * FROM pg_tables WHERE schemaname = 'public' AND tablename ILIKE '%{$buscar}%'")->queryAll();
    
    $tablas = array_map(function($tabla) {
      return [
        "id" => $tabla["tablename"],
        "nombre" => $tabla["tablename"],
      ];
    }, $tablas);

    return (new Respuesta($tablas))
      ->mensaje("Tablas obtenidas");
  }

  public function actionListarCampos() {
    $tabla = trim($this->req->getQueryParam("tabla", ""));
    $campos = new \yii\db\Query();
    $campos->select("column_name")
      ->from("information_schema.columns")
      ->where(["table_name" => $tabla])
      ->andWhere(["column_name" => ["creado", "modificado", "eliminado", "id"]]);
    
    $campos = $campos->all();
      $campos = array_map(function($campo) {
      return [
        "id" => $campo["column_name"],
        "nombre" => $campo["column_name"],
      ];
    }, $campos);

    return (new Respuesta($campos))
      ->mensaje("Campos obtenidos");
  }
}
