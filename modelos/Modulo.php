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
 * @property Permiso[] $permisos
 */
class Modulo extends ModeloBase {

  public const MENU = 'MENU';
  public const USUARIO = 'USUARIO';
  public const PERMISO = 'PERMISO';
  public const GRUPO = 'GRUPO';
  public const CONFIGURACION = 'CONFIGURACION';

  /**
   * {@inheritdoc}
   */

  public static function tableName() {
    return 'Modulo';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
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
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'nombre' => 'Nombre',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields() {
    return [
      'id',
      'nombre',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields() {
    return [
      'permisos',
    ];
  }


  /**
   * Gets query for [[Permisos]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getPermisos() {
    return $this->hasMany(Permiso::class, ['idModulo' => 'id'])->where(["eliminado" => null]);
  }
}
