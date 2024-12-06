<?php

namespace app\modelos;

use Yii;

/**
 * This is the model class for table "Modulo".
 *
 * @property string $id
 * @property string|null $nombre
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property ModuloPermiso[] $permisos
 */
class Modulo extends ModeloBase
{
  /**
   * {@inheritdoc}
   */

  public static function tableName()
  {
    return 'Modulo';
  }
  
  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['nombre'], 'string', 'max' => 200],
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
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'permisos',
    ];
  }


  /**
   * Gets query for [[ModuloPermisos]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getPermisos()
  {
    return $this->hasMany(ModuloPermiso::class, ['idModulo' => 'id']);
  }
}
