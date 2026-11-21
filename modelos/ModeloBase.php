<?php

namespace app\modelos;

class ModeloBase extends \eDesarrollos\models\ModeloBase {

  public function getCreador() {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuarioCreador']);
  }

  public function getModificador() {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuarioModificador']);
  }
}
