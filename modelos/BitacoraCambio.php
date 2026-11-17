<?php

namespace app\modelos;

/**
 * Registro de auditoría de entidades.
 *
 * Los campos JSON se leen mediante ActiveRecord para que el driver PostgreSQL
 * los convierta de forma nativa a arreglos, sin decodificación manual.
 *
 * @property string $id
 * @property string|null $idUsuario
 * @property string $tabla
 * @property string|null $idRegistro
 * @property string $accion
 * @property array|null $antes
 * @property array|null $despues
 * @property string|null $ruta
 * @property string $fecha
 */
class BitacoraCambio extends ModeloBase {

  public static function tableName() {
    return 'BitacoraCambio';
  }

  public static function bitacoraHabilitada(): bool {
    return false;
  }

  public function rules() {
    return [
      [['id', 'tabla', 'accion', 'fecha'], 'required'],
      [['antes', 'despues'], 'safe'],
      [['id', 'idUsuario', 'tabla', 'idRegistro', 'accion', 'ruta', 'fecha'], 'safe'],
    ];
  }

  public function fields() {
    return [
      'id',
      'idUsuario',
      'tabla',
      'idRegistro',
      'accion',
      'antes',
      'despues',
      'ruta',
      'fecha',
    ];
  }
}
