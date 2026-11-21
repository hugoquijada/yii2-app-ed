<?php

use app\modelos\Grupo;
use app\modelos\Modulo;
use app\modelos\Permiso;
use Ramsey\Uuid\Uuid;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;

class m261121_121000_agrega_permisos_importacion_exportacion_catalogos extends Migration {

  public function safeUp() {
    $ahora = new Expression('now()');
    $permisos = [
      Permiso::CONFIGURACION_IMPORTAR => ['Importar configuración', 'Permite importar registros de Configuración.'],
      Permiso::CONFIGURACION_EXPORTAR => ['Exportar configuración', 'Permite exportar registros de Configuración.'],
    ];

    foreach ($permisos as $id => [$nombre, $descripcion]) {
      if (!(new Query())->from('Permiso')->where(['id' => $id])->exists()) {
        $this->insert('Permiso', [
          'id' => $id,
          'idModulo' => Modulo::CONFIGURACION,
          'nombre' => $nombre,
          'descripcion' => $descripcion,
          'creado' => $ahora,
        ]);
      }
    }

    $idGrupo = (new Query())->from('Grupo')->select('id')
      ->where(['nombre' => Grupo::ADMINISTRADORES, 'eliminado' => null])
      ->scalar();
    if ($idGrupo === false) {
      return;
    }

    foreach (array_keys($permisos) as $idPermiso) {
      if ((new Query())->from('PermisoGrupo')->where([
        'idGrupo' => $idGrupo,
        'idPermiso' => $idPermiso,
        'eliminado' => null,
      ])->exists()) {
        continue;
      }

      $this->insert('PermisoGrupo', [
        'id' => Uuid::uuid4()->toString(),
        'idGrupo' => $idGrupo,
        'idPermiso' => $idPermiso,
        'asignado' => $ahora,
        'creado' => $ahora,
      ]);
    }
  }

  public function safeDown() {
    $permisos = [Permiso::CONFIGURACION_IMPORTAR, Permiso::CONFIGURACION_EXPORTAR];
    $this->delete('PermisoGrupo', ['idPermiso' => $permisos]);
    $this->delete('Permiso', ['id' => $permisos]);
  }
}
