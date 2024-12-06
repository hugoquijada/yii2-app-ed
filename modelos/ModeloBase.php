<?php

namespace app\modelos;

use Ramsey\Uuid\Uuid;

class ModeloBase extends \yii\db\ActiveRecord {

  public function load($data, $nombreFormulario = '') {
    return parent::load($data, $nombreFormulario);
  }

  public static function nombreSingular() {
    $nombre = new \ReflectionClass(static::class);
    $nombre = $nombre->getShortName();
    return $nombre;
  }

  public static function nombrePlural() {
    $nombre = new \ReflectionClass(static::class);
    $nombre = $nombre->getShortName() . 's';
    return $nombre;
  }

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