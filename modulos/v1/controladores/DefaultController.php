<?php

namespace v1\controladores;

use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use yii\web\Response;

class DefaultController extends JsonController {

  public function actions() {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
    ];
  }

  public function beforeAction($action) {
    $resultado = parent::beforeAction($action);
    if ($action->id === 'error') {
      \Yii::$app->response->format = Response::FORMAT_HTML;
    }

    return $resultado;
  }

  public function actionIndex() {
    $formato = $this->req->get("formato");

    throw new \yii\web\ForbiddenHttpException("mensaje");

    return new Respuesta(["Hola" => "mundo"]);
  }
}
