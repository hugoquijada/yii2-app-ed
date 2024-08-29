<?php

namespace app\modelos;

use Yii;

/**
 * This is the model class for table "ModuloPermisoUsuario".
 *
 * @property string $id
 * @property string|null $idPermiso
 * @property string|null $idUsuario
 * @property string|null $asignado
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property ModuloPermiso $permiso
 * @property Usuario $usuario
 */
class ModuloPermisoUsuario extends ModeloBase {
  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'ModuloPermisoUsuario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['id'], 'required'],
      [['asignado', 'creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idPermiso', 'idUsuario'], 'string', 'max' => 36],
      [['id'], 'unique'],
      [['idPermiso'], 'exist', 'skipOnError' => true, 'targetClass' => ModuloPermiso::class, 'targetAttribute' => ['idPermiso' => 'id']],
      [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuario' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'idPermiso' => 'Id Permiso',
      'idUsuario' => 'Id Usuario',
      'asignado' => 'Asignado',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  /**
   * Gets query for [[permiso]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getPermiso() {
    return $this->hasOne(ModuloPermiso::class, ['id' => 'idPermiso']);
  }

  /**
   * Gets query for [[usuario]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getUsuario() {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuario']);
  }
}