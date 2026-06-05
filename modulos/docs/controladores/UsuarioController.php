<?php

namespace docs\controladores;

use app\modelos\Usuario;
use eDesarrollos\data\Respuesta;
use eDesarrollos\rest\JsonController;
use hqsoft\reportkit\document\CellStyle;
use hqsoft\reportkit\document\Document;
use hqsoft\reportkit\document\DocumentConfig;

class UsuarioController extends JsonController {

  public function actionIndex() {
    $id = trim((string)$this->req->get('id', ''));

    $query = Usuario::find()
      ->andWhere(['eliminado' => null]);

    if ($id !== '') {
      $query->andWhere(['id' => $id]);
    }

    /** @var Usuario|null $usuario */
    $usuario = $query
      ->orderBy(['creado' => SORT_ASC])
      ->one();

    if ($usuario === null) {
      return (new Respuesta())
        ->esError()
        ->mensaje('No se encontró un usuario para generar el ejemplo');
    }

    $documento = Document::create(new DocumentConfig(24));

    $documento->row(function ($row) {
      $row->col(24)
        ->style(CellStyle::create()->bold()->fontSize(18)->padding(8))
        ->text('Detalle de usuario');
    });

    $documento->row(function ($row) use ($usuario) {
      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('ID');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text((string)$usuario->id);
    });

    $documento->row(function ($row) use ($usuario) {
      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('Nombre completo');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text(trim($usuario->nombre . ' ' . $usuario->apellidos));
    });

    $documento->row(function ($row) use ($usuario) {
      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('Correo');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text((string)$usuario->correo);
    });

    $documento->row(function ($row) use ($usuario) {
      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('Telefono');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text((string)$usuario->telefono);
    });

    $documento->row(function ($row) use ($usuario) {
      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('Rol');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text((string)$usuario->rol);
    });

    $documento->row(function ($row) use ($usuario) {
      $estatus = intval($usuario->estatus) === Usuario::ACTIVO ? 'Activo' : 'Inactivo';

      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('Estatus');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text($estatus);
    });

    $documento->row(function ($row) use ($usuario) {
      $row->col(8)
        ->style(CellStyle::create()->bold()->background('#E5E7EB')->padding(6))
        ->text('Creado');
      $row->col(16)
        ->style(CellStyle::create()->padding(6))
        ->text((string)$usuario->creado);
    });

    return (new Respuesta())
      ->documento($documento)
      ->mensaje('Documento de ejemplo generado');
  }

  public function actionCuadricula() {
    $filas = max(1, intval($this->req->get('filas', 24)));
    $columnas = max(1, intval($this->req->get('columnas', 16)));
    $maxColumnas = max(24, $columnas);
    $spanBase = max(1, intdiv($maxColumnas, $columnas));
    $restante = $maxColumnas % $columnas;

    $spans = [];
    for ($i = 0; $i < $columnas; $i++) {
      $spans[] = $spanBase + ($i < $restante ? 1 : 0);
    }

    $documento = Document::create(new DocumentConfig($maxColumnas));
    $estiloCelda = CellStyle::create()
      ->background('#FFFFFF')
      ->valign('middle');

    $documento->row(function ($row) use ($columnas, $spans) {
      for ($columna = 1; $columna <= $columnas; $columna++) {
        $row->col($spans[$columna - 1])
          ->style(CellStyle::create()->bold()->background('#E5E7EB'))
          ->border('all', '#94A3B8', 1);
      }
    });

    for ($fila = 1; $fila <= $filas; $fila++) {
      $documento->row(function ($row) use ($columnas, $fila, $spans, $estiloCelda) {
        for ($columna = 1; $columna <= $columnas; $columna++) {
          $row->col($spans[$columna - 1])
            ->style($estiloCelda)
            ->border('all', '#CBD5E1', 1);
          // ->text("F{$fila} C{$columna}");
        }
      });
    }

    return (new Respuesta())
      ->documento($documento)
      ->mensaje('Cuadricula de ejemplo generada');
  }
}
