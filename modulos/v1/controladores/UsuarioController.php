<?php

namespace v1\controladores;

use app\modelos\MenuUsuario;
use app\modelos\PermisoUsuario;
use app\modelos\RutaUsuario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use Yii;
use yii\db\Expression;

class UsuarioController extends AuthController {

  public $modelClass = '\app\modelos\Usuario';

  public function actionModelo(){
    $modelo = new $this->modelClass();
    $atributos = $modelo->attributeLabels();
    $columnas = [];

    foreach ($atributos as $key => $value) {
      $columnas[] = [
        'title' => $value,
        'dataIndex' => $key,
      ];
    }

    $defaultEndpoint = "/v1/{$this->id}";

    return [
      'nombrePlural' => $modelo::nombrePlural(),
      'nombreSingular' => $modelo::nombreSingular(),
      'endpoints' => [
        'default' => $defaultEndpoint . '.json',
      ],
      'columnas' => $columnas,
    ];
  }

  public function buscador(&$query, $request) {
    $id = $request->get($this->modeloID, "");
    $buscar = trim($request->get("buscar", ""));
    $estatus = trim($request->get("estatus", ""));

    if ($id !== "") {
      $query->andWhere([$this->modeloID => $id]);
    }

    if ($estatus !== "") {
      $estatus = intval($estatus) === 1;
      $query->andWhere(["estatus" => $estatus]);
    }

    if ($buscar !== "") {
      $query->andWhere([
        "OR",
        ["ilike", "nombre", $buscar],
        ["ilike", "apellidos", $buscar],
        ["ilike", "correo", $buscar],
      ]);
    }
  }

  public function actionGuardar()
  {
    $id = trim($this->req->getBodyParam("id", ""));
    $modelo = null;
    $pwd = trim($this->req->getBodyParam("pwd", ""));
    $clave = '';

    $permisos = $this->req->getBodyParam("permisos", []);
    $menus = $this->req->getBodyParam("menus", []);

    if ($id !== "") {
      $modelo = $this->modelClass::findOne($id);
      $clave = $modelo->clave;
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
      
      if ($pwd !== '') {
        $modelo->agregarClave($pwd);
      } else {
        $modelo->clave = $clave;
      }
      
      if (!$modelo->save()) {
        $transaccion->rollBack();
        return (new Respuesta($modelo))
          ->esError()
          ->mensaje("Hubo un problema al guardar el registro");
      }

      PermisoUsuario::deleteAll(["idUsuario" => $modelo->id]);
      foreach ($permisos as $permisoData) {
        $permiso = new PermisoUsuario();
        $permiso->id = $permiso->uuid();
        $permiso->idUsuario = $modelo->id;
        $permiso->idPermiso = $permisoData["id"];
        $permiso->asignado = new Expression('now()');
        $permiso->creado = $permisoData["creado"];

        if (!$permiso->save()) {
          $transaccion->rollBack();
          return (new Respuesta($permisoData))
            ->esError()
            ->mensaje("Hubo un problema al guardar permisos");
        }
      }

      RutaUsuario::deleteAll(["idUsuario" => $modelo->id]);
      foreach ($menus as $menuData) {
        $rutaUsuario = new RutaUsuario();
        $rutaUsuario->id = $rutaUsuario->uuid();
        $rutaUsuario->idRuta = $menuData["idRuta"];
        $rutaUsuario->idUsuario = $modelo->id;
        $rutaUsuario->asignado = new Expression('now()');
        $rutaUsuario->creado = $menuData["creado"];

        if (!$rutaUsuario->save()) {
          $transaccion->rollBack();
          return (new Respuesta($rutaUsuario))
            ->esError()
            ->mensaje("Hubo un problema al guardar rutas");
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
      ->mensaje("Usuario guardado");
  }
}
