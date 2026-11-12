<?php

use yii\db\Migration;

class m261110_224509_tabla_configuracion extends Migration {
  /**
   * {@inheritdoc}
   */
  public function safeUp() {
    $this->createTable("Configuracion", [
      "idUsuario" => $this->string(36),
      "clave" => $this->string(50),
      "valor" => $this->text(),
      "nombre" => $this->string(),
      "descripcion" => $this->text(),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);

    $this->addPrimaryKey("ConfiguracionPK", "Configuracion", "clave");
    $this->addForeignKey("ConfiguracionFK", "Configuracion", "idUsuario", "Usuario", "id");
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown() {
    $this->dropForeignKey("ConfiguracionFK", "Configuracion");
    $this->dropTable("Configuracion");
  }
}
