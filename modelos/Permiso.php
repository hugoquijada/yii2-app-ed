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
 */
class Permiso extends ModeloBase {

  public const MENU_ADMINISTRACION = 'MENU_ADMINISTRACION';
  public const MENU_ADMINISTRACION_USUARIO = 'MENU_ADMINISTRACION_USUARIO';
  public const MENU_ADMINISTRACION_PERMISO = 'MENU_ADMINISTRACION_PERMISO';
  public const MENU_ADMINISTRACION_GRUPO = 'MENU_ADMINISTRACION_GRUPO';
  public const MENU_ADMINISTRACION_CONFIGURACION = 'MENU_ADMINISTRACION_CONFIGURACION';

  public const USUARIO_CREAR = 'USUARIO_CREAR';
  public const USUARIO_EDITAR = 'USUARIO_EDITAR';
  public const USUARIO_ELIMINAR = 'USUARIO_ELIMINAR';
  public const PERMISO_CREAR = 'PERMISO_CREAR';
  public const PERMISO_EDITAR = 'PERMISO_EDITAR';
  public const PERMISO_ELIMINAR = 'PERMISO_ELIMINAR';
  public const GRUPO_CREAR = 'GRUPO_CREAR';
  public const GRUPO_EDITAR = 'GRUPO_EDITAR';
  public const GRUPO_ELIMINAR = 'GRUPO_ELIMINAR';
  public const CONFIGURACION_EDITAR = 'CONFIGURACION_EDITAR';

  public const ADMINISTRACION = [
    self::MENU_ADMINISTRACION,
    self::MENU_ADMINISTRACION_USUARIO,
    self::MENU_ADMINISTRACION_PERMISO,
    self::MENU_ADMINISTRACION_GRUPO,
    self::MENU_ADMINISTRACION_CONFIGURACION,
    self::USUARIO_CREAR,
    self::USUARIO_EDITAR,
    self::USUARIO_ELIMINAR,
    self::PERMISO_CREAR,
    self::PERMISO_EDITAR,
    self::PERMISO_ELIMINAR,
    self::GRUPO_CREAR,
    self::GRUPO_EDITAR,
    self::GRUPO_ELIMINAR,
    self::CONFIGURACION_EDITAR,
  ];
  /**
   * {@inheritdoc}
   */

  public static function tableName() {
    return 'Permiso';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
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
  public function attributeLabels() {
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

  public function fields() {
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

  public function extraFields() {
    return [
      'modulo',
    ];
  }


  /**
   * Gets query for [[Modulo]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getModulo() {
    return $this->hasOne(Modulo::class, ['id' => 'idModulo']);
  }
}
