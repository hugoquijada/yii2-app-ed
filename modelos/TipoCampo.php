<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "TipoCampo".
 *
 * @property string $id
 * @property string $clave
 * @property string $nombre
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Propiedad[] $propiedades
 */
class TipoCampo extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'TipoCampo';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'clave', 'nombre'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['clave', 'nombre'], 'string', 'max' => 255],
      [['clave'], 'unique'],
      [['id'], 'unique'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'clave' => 'Clave',
      'nombre' => 'Nombre',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'clave',
      'nombre',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'propiedades',
    ];
  }


  public function getPropiedades()
  {
    return $this->hasMany(Propiedad::class, ['idTipoCampo' => 'id']);
  }
}
