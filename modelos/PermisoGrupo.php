<?php

namespace app\modelos;

class PermisoGrupo extends ModeloBase {

  public static function tableName() {
    return "PermisoGrupo";
  }

  public function rules() {
    return [
      [["id", "idPermiso", "idGrupo"], "required"],
      [["asignado", "creado", "modificado", "eliminado"], "safe"],
      [["id", "idPermiso", "idGrupo"], "string", "max" => 36],
      [["id"], "unique"],
      [["idPermiso"], "exist", "skipOnError" => true, "targetClass" => Permiso::class, "targetAttribute" => ["idPermiso" => "id"]],
      [["idGrupo"], "exist", "skipOnError" => true, "targetClass" => Grupo::class, "targetAttribute" => ["idGrupo" => "id"]],
    ];
  }

  public function getPermiso() {
    return $this->hasOne(Permiso::class, ["id" => "idPermiso"]);
  }

  public function getGrupo() {
    return $this->hasOne(Grupo::class, ["id" => "idGrupo"]);
  }
}
