<?php

use app\modelos\Modulo;
use app\modelos\Permiso;
use app\modelos\Grupo;
use yii\db\Migration;
use yii\db\Query;

class m261112_120000_agrega_grupos_permisos extends Migration {

  public function safeUp() {
    $this->createTable("Grupo", [
      "id" => $this->string(36)->notNull(),
      "nombre" => $this->string(200)->notNull(),
      "descripcion" => $this->string(500),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);
    $this->addPrimaryKey("GrupoPK", "Grupo", "id");

    $this->createTable("GrupoUsuario", [
      "id" => $this->string(36)->notNull(),
      "idGrupo" => $this->string(36)->notNull(),
      "idUsuario" => $this->string(36)->notNull(),
      "asignado" => $this->timestamp()->append("with time zone"),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);
    $this->addPrimaryKey("GrupoUsuarioPK", "GrupoUsuario", "id");
    $this->addForeignKey("GrupoUsuarioIdGrupoFK", "GrupoUsuario", "idGrupo", "Grupo", "id");
    $this->addForeignKey("GrupoUsuarioIdUsuarioFK", "GrupoUsuario", "idUsuario", "Usuario", "id");
    $this->execute('CREATE UNIQUE INDEX "GrupoUsuarioUnico" ON "GrupoUsuario" ("idGrupo", "idUsuario") WHERE "eliminado" IS NULL');

    $this->createTable("PermisoGrupo", [
      "id" => $this->string(36)->notNull(),
      "idPermiso" => $this->string(36)->notNull(),
      "idGrupo" => $this->string(36)->notNull(),
      "asignado" => $this->timestamp()->append("with time zone"),
      "creado" => $this->timestamp()->append("with time zone"),
      "modificado" => $this->timestamp()->append("with time zone"),
      "eliminado" => $this->timestamp()->append("with time zone"),
    ]);
    $this->addPrimaryKey("PermisoGrupoPK", "PermisoGrupo", "id");
    $this->addForeignKey("PermisoGrupoIdPermisoFK", "PermisoGrupo", "idPermiso", "Permiso", "id");
    $this->addForeignKey("PermisoGrupoIdGrupoFK", "PermisoGrupo", "idGrupo", "Grupo", "id");
    $this->execute('CREATE UNIQUE INDEX "PermisoGrupoUnico" ON "PermisoGrupo" ("idPermiso", "idGrupo") WHERE "eliminado" IS NULL');

    $ahora = new \yii\db\Expression('now()');
    $modulos = [
      Modulo::MENU => 'Menú',
      Modulo::USUARIO => 'Usuario',
      Modulo::PERMISO => 'Permiso',
      Modulo::GRUPO => 'Grupo',
      Modulo::CONFIGURACION => 'Configuración',
    ];
    foreach ($modulos as $id => $nombre) {
      if (!(new Query())->from('Modulo')->where(['id' => $id])->exists()) {
        $this->insert('Modulo', [
          'id' => $id,
          'nombre' => $nombre,
          'creado' => $ahora,
        ]);
      }
    }

    $permisos = [
      Permiso::MENU_ADMINISTRACION => [Modulo::MENU, 'Ver Administración', 'Permite ver el menú Administración.'],
      Permiso::MENU_ADMINISTRACION_USUARIO => [Modulo::MENU, 'Ver Usuarios', 'Permite ver Usuarios dentro de Administración.'],
      Permiso::MENU_ADMINISTRACION_PERMISO => [Modulo::MENU, 'Ver Permisos', 'Permite ver Permisos dentro de Administración.'],
      Permiso::MENU_ADMINISTRACION_GRUPO => [Modulo::MENU, 'Ver Grupos', 'Permite ver Grupos dentro de Administración.'],
      Permiso::MENU_ADMINISTRACION_CONFIGURACION => [Modulo::MENU, 'Ver Configuración', 'Permite ver Configuración dentro de Administración.'],
      Permiso::USUARIO_CREAR => [Modulo::USUARIO, 'Crear usuario', 'Permite crear usuarios.'],
      Permiso::USUARIO_EDITAR => [Modulo::USUARIO, 'Editar usuario', 'Permite actualizar usuarios.'],
      Permiso::USUARIO_ELIMINAR => [Modulo::USUARIO, 'Eliminar usuario', 'Permite eliminar usuarios.'],
      Permiso::PERMISO_CREAR => [Modulo::PERMISO, 'Crear permiso', 'Permite crear permisos.'],
      Permiso::PERMISO_EDITAR => [Modulo::PERMISO, 'Editar permiso', 'Permite actualizar permisos.'],
      Permiso::PERMISO_ELIMINAR => [Modulo::PERMISO, 'Eliminar permiso', 'Permite eliminar permisos.'],
      Permiso::GRUPO_CREAR => [Modulo::GRUPO, 'Crear grupo', 'Permite crear grupos.'],
      Permiso::GRUPO_EDITAR => [Modulo::GRUPO, 'Editar grupo', 'Permite actualizar grupos.'],
      Permiso::GRUPO_ELIMINAR => [Modulo::GRUPO, 'Eliminar grupo', 'Permite eliminar grupos.'],
      Permiso::CONFIGURACION_EDITAR => [Modulo::CONFIGURACION, 'Editar configuración', 'Permite actualizar la configuración del sistema.'],
    ];
    foreach ($permisos as $id => [$idModulo, $nombre, $descripcion]) {
      if (!(new Query())->from('Permiso')->where(['id' => $id])->exists()) {
        $this->insert('Permiso', [
          'id' => $id,
          'idModulo' => $idModulo,
          'nombre' => $nombre,
          'descripcion' => $descripcion,
          'creado' => $ahora,
        ]);
      }
    }

    $grupoAdministradores = (new Query())
      ->from('Grupo')
      ->select('id')
      ->where(['nombre' => Grupo::ADMINISTRADORES, 'eliminado' => null])
      ->scalar();
    if ($grupoAdministradores === false) {
      $grupoAdministradores = \Ramsey\Uuid\Uuid::uuid4()->toString();
      $this->insert('Grupo', [
        'id' => $grupoAdministradores,
        'nombre' => Grupo::ADMINISTRADORES,
        'descripcion' => 'Usuarios con permisos de administración.',
        'creado' => $ahora,
      ]);
    }

    foreach (Permiso::ADMINISTRACION as $idPermiso) {
      $asignado = (new Query())
        ->from('PermisoGrupo')
        ->where([
          'idGrupo' => $grupoAdministradores,
          'idPermiso' => $idPermiso,
          'eliminado' => null,
        ])
        ->exists();
      if (!$asignado) {
        $this->insert('PermisoGrupo', [
          'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
          'idGrupo' => $grupoAdministradores,
          'idPermiso' => $idPermiso,
          'asignado' => $ahora,
          'creado' => $ahora,
        ]);
      }
    }
  }

  public function safeDown() {
    $this->dropForeignKey("PermisoGrupoIdGrupoFK", "PermisoGrupo");
    $this->dropForeignKey("PermisoGrupoIdPermisoFK", "PermisoGrupo");
    $this->dropTable("PermisoGrupo");
    $this->dropForeignKey("GrupoUsuarioIdUsuarioFK", "GrupoUsuario");
    $this->dropForeignKey("GrupoUsuarioIdGrupoFK", "GrupoUsuario");
    $this->dropTable("GrupoUsuario");
    $this->dropTable("Grupo");
  }
}
