<?php

use yii\db\Expression;
use yii\db\Migration;

class m261125_120000_agrega_colores_fondo_configuracion extends Migration {

  public function safeUp() {
    $this->batchInsert('Configuracion', ['clave', 'valor', 'nombre', 'descripcion', 'creado'], [
      ['COLOR_FONDO_MENU', '#FFFFFF', 'Color de fondo del menú', 'Color de fondo del menú lateral.', new Expression('now()')],
      ['COLOR_FONDO_CONTENIDO', '#F8FAFC', 'Color de fondo del contenido', 'Color de fondo del área principal de contenido.', new Expression('now()')],
    ]);
  }

  public function safeDown() {
    $this->delete('Configuracion', [
      'clave' => ['COLOR_FONDO_MENU', 'COLOR_FONDO_CONTENIDO'],
    ]);
  }
}
