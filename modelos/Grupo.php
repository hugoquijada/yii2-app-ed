<?php

namespace app\modelos;

class Grupo extends ModeloBase {

  public const ADMINISTRADORES = 'Administradores';

  public static function tableName() {
    return "Grupo";
  }

  public function rules() {
    return [
      [["id", "nombre"], "required"],
      [["creado", "modificado", "eliminado"], "safe"],
      [["id"], "string", "max" => 36],
      [["nombre"], "string", "max" => 200],
      [["descripcion"], "string", "max" => 500],
      [["id"], "unique"],
    ];
  }

  public function attributeLabels() {
    return [
      "id" => "ID",
      "nombre" => "Nombre",
      "descripcion" => "Descripción",
      "creado" => "Creado",
      "modificado" => "Modificado",
      "eliminado" => "Eliminado",
    ];
  }

  public function fields() {
    return ["id", "nombre", "descripcion", "creado", "modificado", "eliminado"];
  }

  public function extraFields() {
    return ["permisos", "usuarios"];
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
