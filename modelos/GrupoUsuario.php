<?php

namespace app\modelos;

class GrupoUsuario extends ModeloBase {

  public static function tableName() {
    return "GrupoUsuario";
  }

  public function rules() {
    return [
      [["id", "idGrupo", "idUsuario"], "required"],
      [["asignado", "creado", "modificado", "eliminado"], "safe"],
      [["id", "idGrupo", "idUsuario"], "string", "max" => 36],
      [["id"], "unique"],
      [["idGrupo"], "exist", "skipOnError" => true, "targetClass" => Grupo::class, "targetAttribute" => ["idGrupo" => "id"]],
      [["idUsuario"], "exist", "skipOnError" => true, "targetClass" => Usuario::class, "targetAttribute" => ["idUsuario" => "id"]],
    ];
  }
}
