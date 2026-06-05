<?php

use yii\db\Migration;

/**
 * Class m240718_041517_init
 */
class m240718_041517_inicio extends Migration {

  public function safeUp() {

    $this->createTable('Usuario', [
      "id" => $this->string(50)->notNull(),
      "correo" => $this->string(128)->notNull(),
      "clave" => $this->string(100)->notNull(),
      "nombre" => $this->string(100)->notNull(),
      "apellidos" => $this->string(100)->notNull(),
      "estatus" => $this->smallInteger()->comment("0:inactivo, 1:activo"),
      "telefono" => $this->string(100),
      "alias" => $this->string(100),
      "foto" => $this->string(300),
      "rol" => $this->string(100)->notNull(),
      "login" => $this->string(100),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);

    $this->addPrimaryKey("UsuarioPK", "Usuario", "id");

    $this->createTable('Media', [
      "id" => $this->string(50)->notNull(),
      "idUsuario" => $this->integer(),
      "nombre" => $this->string(100)->notNull(),
      "uuid" => $this->string(100),
      "peso" => $this->integer(),
      "extension" => $this->string(5),
      "mimetype" => $this->string(100),
      "ruta" => $this->string(500),
      "descripcion" => $this->string(500),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);

    $this->addPrimaryKey("MediaPK", "Media", "id");

    $this->createTable("Modulo", [
      "id" => $this->string(50)->notNull(),
      "nombre" => $this->string(200),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);

    $this->addPrimaryKey("ModuloPK", 'Modulo', "id");

    $this->createTable("Permiso", [
      "id" => $this->string(36)->notNull(),
      "idModulo" => $this->string(50),
      "nombre" => $this->string(200),
      "descripcion" => $this->string(500),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);

    $this->addPrimaryKey("PermisoPK", "Permiso", "id");
    $this->addForeignKey("PermisoIdModuloFK", "Permiso", "idModulo", "Modulo", "id");

    $this->createTable("PermisoUsuario", [
      "id" => $this->string(36)->notNull(),
      "idPermiso" => $this->string(36),
      "idUsuario" => $this->string(36),
      "asignado" => $this->timestamp()->append("with time zone"),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);

    $this->addPrimaryKey("PUidPermisoIdUsuarioPK", "PermisoUsuario", "id");
    $this->addForeignKey("PUidPermisoFK", "PermisoUsuario", "idPermiso", "Permiso", "id");
    $this->addForeignKey("PUidUsuarioFK", "PermisoUsuario", "idUsuario", "Usuario", "id");
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown() {
    $this->dropForeignKey("PUidUsuarioFK", "PermisoUsuario");
    $this->dropForeignKey("PUidPermisoFK", "PermisoUsuario");
    $this->dropTable("PermisoUsuario");
    $this->dropForeignKey("PermisoIdModuloFK", "Permiso");
    $this->dropTable("Permiso");
    $this->dropTable("Modulo");

    $this->dropTable('Media');
    $this->dropTable('Usuario');
  }
}
