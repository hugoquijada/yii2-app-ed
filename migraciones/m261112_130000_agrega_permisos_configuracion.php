<?php

use app\modelos\Grupo;
use app\modelos\Modulo;
use app\modelos\Permiso;
use Ramsey\Uuid\Uuid;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;

class m261112_130000_agrega_permisos_configuracion extends Migration {

  public function safeUp() {
    $ahora = new Expression('now()');

    if (!(new Query())->from('Modulo')->where(['id' => Modulo::CONFIGURACION])->exists()) {
      $this->insert('Modulo', [
        'id' => Modulo::CONFIGURACION,
        'nombre' => 'Configuración',
        'creado' => $ahora,
      ]);
    }

    $permisos = [
      Permiso::MENU_ADMINISTRACION_CONFIGURACION => [
        Modulo::MENU,
        'Ver Configuración',
        'Permite ver Configuración dentro de Administración.',
      ],
      Permiso::CONFIGURACION_EDITAR => [
        Modulo::CONFIGURACION,
        'Editar configuración',
        'Permite actualizar la configuración del sistema.',
      ],
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

    $idGrupo = (new Query())
      ->from('Grupo')
      ->select('id')
      ->where(['nombre' => Grupo::ADMINISTRADORES, 'eliminado' => null])
      ->scalar();

    if ($idGrupo === false) {
      return;
    }

    foreach ($permisos as $idPermiso => $_) {
      $asignado = (new Query())
        ->from('PermisoGrupo')
        ->where([
          'idGrupo' => $idGrupo,
          'idPermiso' => $idPermiso,
          'eliminado' => null,
        ])
        ->exists();

      if (!$asignado) {
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
    $this->delete('PermisoGrupo', [
      'idPermiso' => [
        Permiso::MENU_ADMINISTRACION_CONFIGURACION,
        Permiso::CONFIGURACION_EDITAR,
      ],
    ]);
    $this->delete('Permiso', [
      'id' => [
        Permiso::MENU_ADMINISTRACION_CONFIGURACION,
        Permiso::CONFIGURACION_EDITAR,
      ],
    ]);
    $this->delete('Modulo', ['id' => Modulo::CONFIGURACION]);
  }
}
