<?php

namespace app\modelos;

use Yii;

/**
 * This is the model class for table "ModuloPermiso".
 *
 * @property string $id
 * @property string|null $idModulo
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Modulo $modulo
 * @property ModuloPermisoUsuario[] $moduloPermisoUsuarios
 */
class ModuloPermiso extends ModeloBase
{
  /**
   * {@inheritdoc}
   */

  public static function tableName()
  {
    return 'ModuloPermiso';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 36],
      [['idModulo'], 'string', 'max' => 50],
      [['nombre'], 'string', 'max' => 200],
      [['descripcion'], 'string', 'max' => 500],
      [['id'], 'unique'],
      [['idModulo'], 'exist', 'skipOnError' => true, 'targetClass' => Modulo::class, 'targetAttribute' => ['idModulo' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'idModulo' => 'Id Modulo',
      'nombre' => 'Nombre',
      'descripcion' => 'Descripcion',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'idModulo',
      'nombre',
      'descripcion',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'modulo',
    ];
  }


  /**
   * Gets query for [[Modulo]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getModulo()
  {
    return $this->hasOne(Modulo::class, ['id' => 'idModulo']);
  }

}
