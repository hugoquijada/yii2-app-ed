<?php

use app\modelos\Grupo;
use app\modelos\Modulo;
use app\modelos\Permiso;
use Ramsey\Uuid\Uuid;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;

class m261116_120000_agrega_bitacoras extends Migration {

  public function safeUp() {
    $this->createTable('BitacoraCambio', [
      'id' => $this->string(36)->notNull(),
      'idUsuario' => $this->string(36)->null(),
      'tabla' => $this->string(128)->notNull(),
      'idRegistro' => $this->text()->notNull(),
      'accion' => $this->string(20)->notNull(),
      'antes' => $this->json()->null(),
      'despues' => $this->json()->null(),
      'ruta' => $this->string(500)->null(),
      'fecha' => $this->timestamp()->notNull(),
      'PRIMARY KEY ([[id]])',
    ]);
    $this->createIndex('idx-BitacoraCambio-fecha', 'BitacoraCambio', 'fecha');
    $this->createIndex('idx-BitacoraCambio-registro', 'BitacoraCambio', ['tabla', 'idRegistro']);
    $this->createIndex('idx-BitacoraCambio-usuario', 'BitacoraCambio', 'idUsuario');

    $this->createTable('BitacoraPeticion', [
      'id' => $this->string(36)->notNull(),
      'idUsuario' => $this->string(36)->null(),
      'metodo' => $this->string(10)->notNull(),
      'ruta' => $this->string(500)->notNull(),
      'codigo' => $this->integer()->notNull(),
      'ip' => $this->string(64)->null(),
      'agente' => $this->text()->null(),
      'parametros' => $this->json()->null(),
      'cuerpo' => $this->json()->null(),
      'error' => $this->text()->null(),
      'duracionMs' => $this->integer()->null(),
      'fecha' => $this->timestamp()->notNull(),
      'PRIMARY KEY ([[id]])',
    ]);
    $this->createIndex('idx-BitacoraPeticion-fecha', 'BitacoraPeticion', 'fecha');
    $this->createIndex('idx-BitacoraPeticion-codigo', 'BitacoraPeticion', 'codigo');
    $this->createIndex('idx-BitacoraPeticion-usuario', 'BitacoraPeticion', 'idUsuario');

    $ahora = new Expression('now()');
    if (!(new Query())->from('Modulo')->where(['id' => Modulo::BITACORA])->exists()) {
      $this->insert('Modulo', [
        'id' => Modulo::BITACORA,
        'nombre' => 'Bitácoras',
        'creado' => $ahora,
      ]);
    }

    $permisos = [
      Permiso::MENU_ADMINISTRACION_BITACORA => [Modulo::MENU, 'Ver Bitácoras', 'Permite abrir Bitácoras dentro de Administración.'],
      Permiso::BITACORA_VER => [Modulo::BITACORA, 'Consultar bitácoras', 'Permite consultar cambios y peticiones registradas.'],
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

    $idGrupo = (new Query())->from('Grupo')->select('id')
      ->where(['nombre' => Grupo::ADMINISTRADORES, 'eliminado' => null])->scalar();
    if ($idGrupo === false) {
      return;
    }
    foreach (array_keys($permisos) as $idPermiso) {
      if (!(new Query())->from('PermisoGrupo')->where([
        'idGrupo' => $idGrupo,
        'idPermiso' => $idPermiso,
        'eliminado' => null,
      ])->exists()) {
        $this->insert('PermisoGrupo', [
          'id' => Uuid::uuid4()->toString(),
          'idGrupo' => $idGrupo,
          'idPermiso' => $idPermiso,
          'asignado' => $ahora,
          'creado' => $ahora,
        ]);
      }
    }
  }

  public function safeDown() {
    $this->delete('PermisoGrupo', ['idPermiso' => [Permiso::MENU_ADMINISTRACION_BITACORA, Permiso::BITACORA_VER]]);
    $this->delete('Permiso', ['id' => [Permiso::MENU_ADMINISTRACION_BITACORA, Permiso::BITACORA_VER]]);
    $this->delete('Modulo', ['id' => Modulo::BITACORA]);
    $this->dropTable('BitacoraPeticion');
    $this->dropTable('BitacoraCambio');
  }
}
