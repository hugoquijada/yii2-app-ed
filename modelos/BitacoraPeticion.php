<?php

namespace app\modelos;

/**
 * Registro de peticiones HTTP que terminaron con error.
 *
 * Los campos JSON se leen mediante ActiveRecord para que el driver PostgreSQL
 * los convierta de forma nativa a arreglos, sin decodificación manual.
 *
 * @property string $id
 * @property string|null $idUsuario
 * @property string $metodo
 * @property string $ruta
 * @property int $codigo
 * @property string|null $ip
 * @property string|null $agente
 * @property array|null $parametros
 * @property array|null $cuerpo
 * @property string|null $error
 * @property int|null $duracionMs
 * @property string $fecha
 */
class BitacoraPeticion extends ModeloBase {

  public static function tableName() {
    return 'BitacoraPeticion';
  }

  public static function bitacoraHabilitada(): bool {
    return false;
  }

  public function rules() {
    return [
      [['id', 'metodo', 'ruta', 'codigo', 'fecha'], 'required'],
      [['codigo', 'duracionMs'], 'integer'],
      [['parametros', 'cuerpo'], 'safe'],
      [['id', 'idUsuario', 'metodo', 'ruta', 'ip', 'agente', 'error', 'fecha'], 'safe'],
    ];
  }

  public function fields() {
    return [
      'id',
      'idUsuario',
      'metodo',
      'ruta',
      'codigo',
      'ip',
      'agente',
      'parametros',
      'cuerpo',
      'error',
      'duracionMs',
      'fecha',
    ];
  }
}
