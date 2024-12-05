<?php

namespace v1\controladores;

use app\modelos\MenuFormulario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use Yii;
use yii\db\Expression;

class FormularioController extends JsonController
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

    $menus = $this->req->getBodyParam("menus", []);

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

      MenuFormulario::deleteAll(["idFormulario" => $modelo->id]);
      foreach ($menus as $menuData) {
        $menuFormulario = new MenuFormulario();
        $menuFormulario->id = $menuFormulario->uuid();
        $menuFormulario->idMenu = $menuData["idMenu"];
        $menuFormulario->idFormulario = $modelo->id;
        $menuFormulario->asignado = new Expression('now()');
        $menuFormulario->creado = $menuData["creado"];

        if (!$menuFormulario->save()) {
          $transaccion->rollBack();
          return (new Respuesta($menuData))
            ->esError()
            ->mensaje("Hubo un problema al guardar los menus del formulario");
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
      ->mensaje("Formulario guardado");
  }
}
