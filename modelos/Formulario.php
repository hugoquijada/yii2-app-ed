<?php

namespace app\modelos;

use Yii;

/**
* Clase modelo para la tabla "Formulario".
*
* @property string $id
* @property string $nombre
* @property string $endpoint //Endpoint al que se hará el post de los datos del formulario
* @property string|null $items //Array de FilaProps > ColumnaProps > IFormRendererItem en el frontend
* @property string|null $creado
* @property string|null $modificado
* @property string|null $eliminado
*/
class Formulario extends ModeloBase {

  /**
  * {@inheritdoc}
  */
  public static function tableName() {
    return 'Formulario';
  }

  /**
  * {@inheritdoc}
  */
  public function rules() {
    return [
      [['items', 'creado', 'modificado', 'eliminado'], 'default', 'value' => null],
      [['id', 'endpoint', 'nombre'], 'required'],
      [['items', 'creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['endpoint', 'nombre'], 'string', 'max' => 255],
    ];
  }

  /**
  * {@inheritdoc}
  */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'nombre' => 'Nombre',
      'endpoint' => 'Endpoint',
      'items' => 'Items',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields () {
    return [
      'id',
      'nombre',
      'endpoint',
      'items',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields() {
    return [
    ];
  }

}