<?php

namespace app\rest;

use eDesarrollos\data\Respuesta;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;
use Yii;
use yii\db\Expression;

/**
 * @property \yii\web\Application $app
 * @property \yii\web\Request $req
 * @property \yii\web\Response $res
 * @property \yii\db\ActiveQuery $queryInicial
 * @property int $limite
 * @property int $pagina
 * @property string $ordenar
 * @property string $modelClass
 */
class JsonController extends \eDesarrollos\rest\JsonController {

  const FORMATO_HTML = 'html';
  const FORMATO_JSON = 'json';
  const FORMATO_SQL = 'sql';
  const FORMATO_XML = 'xml';

  public $app = null;
  public $req = null;
  public $res = null;

  public $queryInicial = null;
  public $modelClass = null;

  public $limite = null;
  public $pagina = null;
  public $ordenar = null;

  public $serializer = 'eDesarrollos\rest\Serializer';

  public function behaviors() {
    $behavior = parent::behaviors();
    $behavior['contentNegotiator'] =  [
      'class' => ContentNegotiator::class,
      'formats' => [
        'application/json' => Response::FORMAT_JSON,
        'application/xml' => Response::FORMAT_XML,
      ],
    ];
    $behavior['corsFilter'] = [
      'class' => Cors::class,
      'cors' => [
        'Origin' => ['*'],
        'Access-Control-Request-Method' => [
          'GET', 'POST', 'PUT', 'PATCH', 
          'DELETE', 'HEAD', 'OPTIONS'
        ],
        'Access-Control-Request-Headers' => ['*'],
      ],
    ];
    $behavior["authenticator"]["except"] = ['options'];
    return $behavior;
  }

  public function beforeAction($action) {
    parent::beforeAction($action);
    $this->app = Yii::$app;
    $this->req = $this->app->getRequest();
    $this->res = $this->app->getResponse();
    if ($this->req->isGet) {
      $this->limite = $this->req->get("limite", 20);
      $this->pagina = $this->req->get("pagina", 0);
      $this->ordenar = $this->req->get("ordenar", "");
    }
    if ($this->modelClass !== null) {
      $model = new $this->modelClass;
      $tableName = $this->modelClass::tableName();
      $this->queryInicial = $this->modelClass::find();
      if ($model->hasProperty('eliminado')) {
        $this->queryInicial
          ->where(["{{{$tableName}}}.[[eliminado]]" => null]);
      }
    }
    $formato = $this->req->get("formato", "");
    if($formato === self::FORMATO_JSON) {
      $this->res->format = Response::FORMAT_JSON;
    } elseif($formato === self::FORMATO_XML) {
      $this->res->format = Response::FORMAT_XML;
    } elseif($formato === self::FORMATO_HTML) {
      $this->res->format = Response::FORMAT_HTML;
    } elseif($formato === self::FORMATO_SQL) {
      $this->res->format = Response::FORMAT_RAW;
    }
    return true;
  }

  public function actionOptions() {
    $headers = $this->res->getHeaders();
    $headers->set('Access-Control-Allow-Methods', 'POST, GET, DELETE, PUT, OPTIONS');
    $headers->set('Access-Control-Allow-Headers', 'Content-Type,Accept,Authorization,X-Requested-With');
    $headers->set('Access-Control-Allow-Origin', '*');
    $headers->set('Access-Control-Request-Method', 'POST, GET, DELETE, PUT, OPTIONS');
    $headers->set('Access-Control-Allow-Credentials', 'true');
    $headers->set('Access-Control-Max-Age', 86400);
    return "";
  }

  public function actionIndex() {
    if($this->modelClass === null) {
      return (new Respuesta())
        ->esError()
        ->mensaje("Debe especificar un modelo");
    }
    
    $query = $this->queryInicial;

    $this->buscador($query, $this->req);
    
    return new Respuesta($query, $this->limite, $this->pagina, $this->ordenar);
  }

  public function actionGuardar() {
    $id = trim($this->req->getBodyParam("id", ""));
    $modelo = null;

    if($id !== "") {
      $modelo = $this->modelClass::findOne($id);
    }
    if($modelo === null) {
      $modelo = new $this->modelClass();
      $modelo->uuid();
      $modelo->creado = new Expression('now()');
    } else {
      $modelo->modificado = new Expression('now()');
    }

    $modelo->load($this->req->getBodyParams(), '');
    if (!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("Hubo un problema al guardar el registro");
    }

    $modelo->refresh();
    return (new Respuesta($modelo))
      ->mensaje("Registro guardado");
  }

  public function actionEliminar() {
    $id = trim($this->req->getBodyParam("id", ""));
    $modelo = null;

    if($id !== "") {
      $modelo = $this->modelClass::findOne([
        "id" => $id,
        "eliminado" => null
      ]);
    }
    if($modelo === null) {
      return (new Respuesta())
        ->esError()
        ->mensaje("Registro no encontrado");
    }
    $modelo->eliminado = new Expression('now()');
    if(!$modelo->save()) {
      return (new Respuesta($modelo))
        ->mensaje("No se pudo eliminar el Registro");
    }

    return (new Respuesta())
      ->mensaje("Registro eliminado");
  }

  public function buscador(&$query, $request) {
    $id = $request->get("id", "");

    if($id !== "") {
      $query->andWhere(["id" => $id]);
    }
  }

}
