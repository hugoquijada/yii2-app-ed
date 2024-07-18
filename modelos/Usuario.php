<?php

namespace app\modelos;

use Yii;

/**
 * This is the model class for table "Usuario".
 *
 * @property string $id
 * @property string $correo
 * @property string $clave
 * @property string $nombre
 * @property string $apellidos
 * @property int|null $estatus 0:inactivo, 1:activo
 * @property string $telefono
 * @property string|null $foto
 * @property string $rol
 * @property string|null $login
 * @property string|null $modificado
 * @property string|null $eliminado
 * @property int|null $pin
 *
 * @property Media[] $media
 */
class Usuario extends ModeloBase {

  public const ACTIVO = 1;
  public const INACTIVO = 0;

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'Usuario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['id', 'correo', 'clave', 'nombre', 'telefono'], 'required'],
      [['estatus'], 'default', 'value' => null],
      [['estatus', 'pin'], 'integer'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['correo', 'clave', 'nombre', 'apellidos', 'telefono', 'rol', 'login'], 'string', 'max' => 100],
      [['foto'], 'string', 'max' => 300],
      [['id'], 'unique'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'correo' => 'Correo',
      'clave' => 'Clave',
      'nombre' => 'Nombre',
      'estatus' => 'Estatus',
      'telefono' => 'Telefono',
      'foto' => 'Foto',
      'rol' => 'Rol',
      'login' => 'Login',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  /**
   * Gets query for [[media]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getMedia() {
    return $this->hasMany(Media::class, ['idUsuario' => 'id']);
  }

  public function agregarClave($pwd) {
    $this->clave = Yii::$app->getSecurity()->generatePasswordHash($pwd);
  }

  public function validarClave($pwd) {
    return Yii::$app->getSecurity()->validatePassword($pwd, $this->clave);
  }

  public function getPermisos() {
    return $this->hasMany(ModuloPermisoUsuario::className(), ['idUsuario' => 'id'])
        ->andWhere(['eliminado' => null]);
  }

}