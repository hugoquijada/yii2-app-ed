<?php

namespace app\modelos;

use Ramsey\Uuid\Uuid;

class ModeloBase extends \yii\db\ActiveRecord {

  public function uuid() {
    $pk = static::primaryKey();
    if (is_array($pk) && count($pk) > 1) {
      return null;
    }
    $pk = $pk[0];
    do {
      $uuid = (Uuid::uuid4())
        ->toString();

      $modelo = static::find()
        ->andWhere([$pk => $uuid]);
    } while ($modelo->exists());
    $this->{$pk} = $uuid;
    return $uuid;
  }

  public function validarUnico($atributo, $parametros) {
    $query = static::find()
      ->andWhere([$atributo => $this->{$atributo}]);
    
    if($this->hasProperty("eliminado")) {
      $query->andWhere(["eliminado" => null]);
    }

    if(!$this->isNewRecord) {
      $llaves = $this->primaryKey();
      foreach($llaves as $llave) {
        $query->andWhere(["!=", $llave, $this->{$llave}]);
      }
    }

    $existe = $query->exists();
    if($existe) {
      $this->addError($atributo, "La {$atributo} ya ha sido utilizada.");
    }

  }
}