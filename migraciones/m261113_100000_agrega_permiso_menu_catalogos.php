<?php

use app\modelos\Grupo;
use app\modelos\Modulo;
use app\modelos\Permiso;
use Ramsey\Uuid\Uuid;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;

class m261113_100000_agrega_permiso_menu_catalogos extends Migration {

  public function safeUp() {
    $ahora = new Expression('now()');

    if (!(new Query())->from('Permiso')->where(['id' => Permiso::MENU_CATALOGOS])->exists()) {
      $this->insert('Permiso', [
        'id' => Permiso::MENU_CATALOGOS,
        'idModulo' => Modulo::MENU,
        'nombre' => 'Ver Catálogos',
        'descripcion' => 'Permite ver el menú Catálogos.',
        'creado' => $ahora,
      ]);
    }

    $idGrupo = (new Query())
      ->from('Grupo')
      ->select('id')
      ->where(['nombre' => Grupo::ADMINISTRADORES, 'eliminado' => null])
      ->scalar();

    if ($idGrupo === false || (new Query())
      ->from('PermisoGrupo')
      ->where([
        'idGrupo' => $idGrupo,
        'idPermiso' => Permiso::MENU_CATALOGOS,
        'eliminado' => null,
      ])
      ->exists()) {
      return;
    }

    $this->insert('PermisoGrupo', [
      'id' => Uuid::uuid4()->toString(),
      'idPermiso' => Permiso::MENU_CATALOGOS,
      'idGrupo' => $idGrupo,
      'asignado' => $ahora,
      'creado' => $ahora,
    ]);
  }

  public function safeDown() {
    $this->delete('PermisoGrupo', ['idPermiso' => Permiso::MENU_CATALOGOS]);
    $this->delete('Permiso', ['id' => Permiso::MENU_CATALOGOS]);
  }
}
