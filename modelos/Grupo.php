<?php

namespace app\modelos;

class Grupo extends ModeloBase {

  public const ADMINISTRADORES = 'Administradores';
  public const ESCENARIO_CREAR = 'crear';
  public const ESCENARIO_ACTUALIZAR = 'actualizar';

  public static function tableName() {
    return "Grupo";
  }

  public function rules() {
    return [
      [["id", "nombre"], "required"],
      [["nombre"], "required", "on" => self::ESCENARIO_CREAR],
      [["creado", "modificado", "eliminado"], "safe"],
      [["id"], "string", "max" => 36],
      [["idUsuarioCreador", "idUsuarioModificador"], "string", "max" => 36],
      [["nombre"], "string", "max" => 200],
      [["descripcion"], "string", "max" => 500],
      [["id"], "unique"],
      [['idUsuarioCreador'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuarioCreador' => 'id']],
      [['idUsuarioModificador'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuarioModificador' => 'id']],
    ];
  }

  public function scenarios() {
    $scenarios = parent::scenarios();
    $scenarios[self::ESCENARIO_CREAR] = ['nombre', 'descripcion'];
    $scenarios[self::ESCENARIO_ACTUALIZAR] = ['nombre', 'descripcion'];
    return $scenarios;
  }

  public function attributeLabels() {
    return [
      "id" => "ID",
      "nombre" => "Nombre",
      "descripcion" => "Descripción",
      "idUsuarioCreador" => "Usuario creador",
      "idUsuarioModificador" => "Usuario modificador",
      "creado" => "Creado",
      "modificado" => "Modificado",
      "eliminado" => "Eliminado",
    ];
  }

  public function fields() {
    return ["id", "nombre", "descripcion", "idUsuarioCreador", "idUsuarioModificador", "creado", "modificado"];
  }

  public function extraFields() {
    return ["permisos", "usuarios", "creador", "modificador"];
  }

  public function getPermisos() {
    return $this->hasMany(Permiso::class, ["id" => "idPermiso"])
      ->viaTable("PermisoGrupo", ["idGrupo" => "id"], function ($query) {
        $query->andWhere(["{{PermisoGrupo}}.[[eliminado]]" => null]);
      });
  }

  public function getUsuarios() {
    return $this->hasMany(Usuario::class, ["id" => "idUsuario"])
      ->viaTable("GrupoUsuario", ["idGrupo" => "id"], function ($query) {
        $query->andWhere(["{{GrupoUsuario}}.[[eliminado]]" => null]);
      });
  }
}
