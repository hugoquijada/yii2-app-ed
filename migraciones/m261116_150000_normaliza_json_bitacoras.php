<?php

use yii\db\Migration;

/**
 * Convierte a objetos JSON las entradas almacenadas como cadenas JSON por las
 * primeras versiones de la bitácora.
 */
class m261116_150000_normaliza_json_bitacoras extends Migration {

  public function safeUp() {
    if ($this->db->driverName !== 'pgsql') {
      return;
    }

    $this->normalizar('BitacoraCambio', 'antes');
    $this->normalizar('BitacoraCambio', 'despues');
    $this->normalizar('BitacoraPeticion', 'parametros');
    $this->normalizar('BitacoraPeticion', 'cuerpo');
  }

  public function safeDown() {
    // La conversión es intencionalmente irreversible: evita volver a guardar
    // JSON serializado dos veces.
  }

  private function normalizar(string $tabla, string $columna): void {
    $tabla = $this->db->quoteTableName($tabla);
    $columna = $this->db->quoteColumnName($columna);
    $this->execute("UPDATE {$tabla} SET {$columna} = ({$columna} #>> '{}')::jsonb WHERE jsonb_typeof({$columna}) = 'string'");
  }
}
