<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "Formulario".
 *
 * @property string $id
 * @property string $nombre
 * @property string $tabla
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property MenuFormulario[] $menuFormularios
 * @property Propiedad[] $propiedades
 */
class Formulario extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'Formulario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'nombre', 'tabla'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['nombre', 'tabla'], 'string', 'max' => 255],
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
      'nombre' => 'Nombre',
      'tabla' => 'Tabla',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'nombre',
      'tabla',
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
    return $this->hasMany(Propiedad::class, ['idFormulario' => 'id']);
  }

}
