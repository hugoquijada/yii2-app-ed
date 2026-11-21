<?php

use yii\db\Migration;

class m261121_120000_agrega_usuarios_auditoria_entidades extends Migration {

  private const TABLAS = [
    'Usuario',
    'Media',
    'Modulo',
    'Permiso',
    'Grupo',
    'Configuracion',
  ];

  public function safeUp() {
    foreach (self::TABLAS as $tabla) {
      $this->addColumn($tabla, 'idUsuarioCreador', $this->string(36)->null());
      $this->addColumn($tabla, 'idUsuarioModificador', $this->string(36)->null());
      $this->createIndex("idx-{$tabla}-creador", $tabla, 'idUsuarioCreador');
      $this->createIndex("idx-{$tabla}-modificador", $tabla, 'idUsuarioModificador');
      $this->addForeignKey("{$tabla}IdUsuarioCreadorFK", $tabla, 'idUsuarioCreador', 'Usuario', 'id');
      $this->addForeignKey("{$tabla}IdUsuarioModificadorFK", $tabla, 'idUsuarioModificador', 'Usuario', 'id');
    }
  }

  public function safeDown() {
    foreach (array_reverse(self::TABLAS) as $tabla) {
      $this->dropForeignKey("{$tabla}IdUsuarioModificadorFK", $tabla);
      $this->dropForeignKey("{$tabla}IdUsuarioCreadorFK", $tabla);
      $this->dropIndex("idx-{$tabla}-modificador", $tabla);
      $this->dropIndex("idx-{$tabla}-creador", $tabla);
      $this->dropColumn($tabla, 'idUsuarioModificador');
      $this->dropColumn($tabla, 'idUsuarioCreador');
    }
  }
}
