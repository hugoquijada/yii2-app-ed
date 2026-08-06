<?php

namespace v1\controladores;

use app\modelos\Media;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class SubirArchivoController extends AuthController {

  public function actionPost() {
    if (!$this->req->isPost) {
      throw new NotFoundHttpException();
    }

    $usuario = $this->usuario;

    $this->res->format = Response::FORMAT_JSON;
    $archivo = UploadedFile::getInstanceByName('archivo');
    if ($archivo === null) {
      return (new Respuesta())
        ->esError()
        ->mensaje("No se recibió el archivo");
    }

    $sec = Yii::$app->getSecurity();
    $base = Yii::getAlias("@recursos");

    $ruta = "/";
    if (!is_dir($base . $ruta)) {
      mkdir($base . $ruta);
    }

    $ruta .= date("Y/");
    if (!is_dir($base . $ruta)) {
      mkdir($base . $ruta);
    }

    $ruta .= date("m/");
    if (!is_dir($base . $ruta)) {
      mkdir($base . $ruta);
    }

    do {
      $nombreArchivo = str_replace("-", "", $ruta . $sec->generateRandomString());
      if ($archivo->extension) {
        $nombreArchivo .= "." . $archivo->extension;
      }
    } while (is_file($base . $nombreArchivo));
    if (!$archivo->saveAs($base . $nombreArchivo)) {
      return (new Respuesta())
        ->mensaje("Ocurrió un problema al guardar el archivo");
    }

    $uuid = Uuid::uuid1();

    $modelo = new Media();

    $modelo->creado = new Expression('now()');
    $modelo->uuid();
    $modelo->idUsuario = $usuario->id;
    $modelo->uuid = $uuid->toString();
    $modelo->nombre = $archivo->name;
    $modelo->extension = $archivo->extension;
    $modelo->ruta = $nombreArchivo;
    $modelo->peso = $archivo->size;
    $modelo->mimetype = $archivo->type;

    $modelo->load($this->req->getBodyParams(), '');
    if (!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("Hubo un problema al guardar Media");
    }

    $modelo->refresh();
    // $modelo->save();

    return (new Respuesta())
      ->mensaje("Archivo subido correctamente")
      ->detalle($modelo);
    // ->detalle(["idUsuario" => $usuario->id, "uuid" => $sec->generateRandomString(), "nombre" => $archivo->name, "extension" => $archivo->extension, "ruta" => $dominio . $nombreArchivo ]);
    // ->detalle(["ruta" => $dominio . $nombreArchivo]);
  }
}
