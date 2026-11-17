<?php

namespace v1\controladores;

use app\modelos\Permiso;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\AuthController;
use yii\db\Query;

class BitacoraController extends AuthController {

  public $accionesPermitidas = ['cambios', 'peticiones'];
  public $permisosAccion = [
    'cambios' => Permiso::BITACORA_VER,
    'peticiones' => Permiso::BITACORA_VER,
  ];

  public function actionCambios() {
    $query = (new Query())->from('BitacoraCambio');
    $this->filtrar($query, ['tabla', 'accion']);
    return new Respuesta($query, $this->limite, $this->pagina, $this->ordenar ?: 'fecha-desc');
  }

  public function actionPeticiones() {
    $query = (new Query())->from('BitacoraPeticion');
    $this->filtrar($query, ['ruta', 'metodo', 'codigo']);
    return new Respuesta($query, $this->limite, $this->pagina, $this->ordenar ?: 'fecha-desc');
  }

  private function filtrar(Query $query, array $campos): void {
    $usuario = trim((string)$this->req->get('idUsuario', ''));
    if ($usuario !== '') {
      $query->andWhere(['idUsuario' => $usuario]);
    }

    $buscar = trim((string)$this->req->get('buscar', ''));
    if ($buscar === '') {
      return;
    }

    $condiciones = ['OR'];
    foreach ($campos as $campo) {
      if ($campo === 'codigo') {
        if (ctype_digit($buscar)) {
          $condiciones[] = [$campo => (int)$buscar];
        }
        continue;
      }
      $condiciones[] = ['ilike', $campo, $buscar];
    }
    if (count($condiciones) > 1) {
      $query->andWhere($condiciones);
    }
  }
}
