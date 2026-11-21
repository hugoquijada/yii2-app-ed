<?php

namespace v1\controladores;

use app\modelos\Configuracion;
use app\modelos\Modulo;
use app\modelos\Permiso;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use yii\db\Expression;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Punto de entrada para catálogos simples configurados explícitamente.
 *
 * Nunca descubre tablas desde la base de datos: cada catálogo debe declarar
 * su modelo, llave primaria, campos editables y permisos.
 */
class CatalogoController extends AuthController {

  private $catalogoActual = null;

  public $accionesPermitidas = [
    'index',
    'post',
    'put',
    'delete',
    'importar-vista-previa',
    'importar-aplicar',
  ];
  public $importacionHabilitada = true;

  public $permisosAccion = [
    'index' => Permiso::MENU_CATALOGOS,
    'post' => Permiso::CONFIGURACION_EDITAR,
    'put' => Permiso::CONFIGURACION_EDITAR,
  ];

  public function beforeAction($action) {
    $identificador = trim((string)\Yii::$app->request->get('catalogo', ''));
    if ($identificador !== '') {
      $catalogo = $this->catalogosEditables()[$identificador] ?? null;
      if ($catalogo === null) {
        throw new NotFoundHttpException('El catálogo solicitado no está disponible.');
      }

      $this->catalogoActual = $catalogo;
      $this->modelClass = $catalogo['modelo'];
      $this->modeloID = $catalogo['llavePrimaria'];
      $this->nombreSingular = $catalogo['nombre'];
      $this->nombrePlural = $catalogo['nombre'];
      $this->permisosAccion = $this->permisosCatalogo($catalogo);
    }

    return parent::beforeAction($action);
  }

  public function actionIndex() {
    if ($this->catalogoActual === null) {
      return (new Respuesta())->detalle([
        'catalogos' => array_values(array_map(function ($catalogo) {
          return [
            'id' => $catalogo['id'],
            'nombre' => $catalogo['nombre'],
            'descripcion' => $catalogo['descripcion'],
            'llavePrimaria' => $catalogo['llavePrimaria'],
            'campos' => $catalogo['campos'],
            'permiteEliminar' => $catalogo['permiteEliminar'],
          ];
        }, $this->catalogosEditables()))]);
    }

    return parent::actionIndex();
  }

  public function actionPost() {
    return $this->guardarCatalogo();
  }

  public function actionPut() {
    return $this->guardarCatalogo();
  }

  public function actionDelete() {
    throw new ForbiddenHttpException('Este catálogo no permite eliminar registros.');
  }

  public function buscador(&$query, $request) {
    if ($this->catalogoActual === null) {
      return;
    }

    $buscar = trim((string)$request->get('buscar', ''));
    if ($buscar === '') {
      return;
    }

    $condiciones = ['OR'];
    foreach ($this->catalogoActual['campos'] as $campo) {
      if ($campo['editable'] ?? false) {
        $condiciones[] = ['ilike', $campo['id'], $buscar];
      }
    }
    if (count($condiciones) > 1) {
      $query->andWhere($condiciones);
    }
  }

  private function guardarCatalogo() {
    if ($this->catalogoActual === null) {
      throw new NotFoundHttpException('Debe seleccionar un catálogo.');
    }

    $llave = $this->catalogoActual['llavePrimaria'];
    $valorLlave = trim((string)$this->req->getBodyParam($llave, ''));
    $modelo = $valorLlave !== '' ? $this->modelClass::findOne([$llave => $valorLlave]) : null;

    if ($modelo === null) {
      $modelo = new $this->modelClass();
      $modelo->creado = new Expression('now()');
    } else {
      $modelo->modificado = new Expression('now()');
    }

    $datos = [];
    foreach ($this->catalogoActual['campos'] as $campo) {
      $id = $campo['id'];
      if (($campo['editable'] ?? false) && $this->req->getBodyParam($id, null) !== null) {
        $datos[$id] = $this->req->getBodyParam($id);
      }
    }

    $this->prepararModeloAccionActual($modelo);
    $modelo->load($datos, '');
    $modelo->idUsuario = $this->usuario->id;
    if (!$modelo->save()) {
      return (new Respuesta($modelo))
        ->esError()
        ->mensaje("Hubo un problema al guardar {$this->catalogoActual['nombre']}");
    }

    $modelo->refresh();
    return (new Respuesta($modelo))->mensaje("{$this->catalogoActual['nombre']} guardado");
  }

  private function catalogosEditables(): array {
    return [
      'configuracion' => [
        'id' => 'configuracion',
        'nombre' => 'Configuración de ejemplo',
        'descripcion' => 'Catálogo de prueba con edición directa en la tabla.',
        'modelo' => Configuracion::class,
        'recurso' => Modulo::CONFIGURACION,
        'llavePrimaria' => 'clave',
        'permiteEliminar' => false,
        'campos' => [
          ['id' => 'clave', 'titulo' => 'Clave', 'editable' => true],
          ['id' => 'valor', 'titulo' => 'Valor', 'editable' => true],
          ['id' => 'creado', 'titulo' => 'Creado', 'editable' => false],
          ['id' => 'modificado', 'titulo' => 'Modificado', 'editable' => false],
        ],
      ],
    ];
  }

  private function permisosCatalogo(array $catalogo): array {
    $recurso = $catalogo['recurso'] ?? strtoupper($catalogo['id']);
    $permisos = [
      'index' => Permiso::MENU_CATALOGOS,
      'post' => Permiso::CONFIGURACION_EDITAR,
      'put' => Permiso::CONFIGURACION_EDITAR,
      'importar-vista-previa' => [
        Permiso::MENU_CATALOGOS,
        Permiso::catalogoImportar($recurso),
      ],
      'importar-aplicar' => [
        Permiso::MENU_CATALOGOS,
        Permiso::catalogoImportar($recurso),
      ],
    ];

    if ($this->esExportacion()) {
      $permisos['index'] = [
        Permiso::MENU_CATALOGOS,
        Permiso::catalogoExportar($recurso),
      ];
    }

    return $permisos;
  }

  private function esExportacion(): bool {
    $formato = strtolower((string)\Yii::$app->request->get('formato', 'json'));
    return !in_array($formato, ['json', 'xml', 'html'], true);
  }
}
