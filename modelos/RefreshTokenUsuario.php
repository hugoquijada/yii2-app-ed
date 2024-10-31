<?php

namespace app\modelos;

use app\modelos\Usuario;
use Yii;

/**
 * This is the model class for table "RefreshTokenUsuario".
 *
 * @property string $token
 * @property string|null $idUsuario
 * @property string|null $expiracion Fecha en que el token expira
 * @property string|null $creado Fecha de creacion del token
 * @property string|null $eliminado Eliminar validez del token
 *
 * @property Usuario $usuario
 */
class RefreshTokenUsuario extends \yii\db\ActiveRecord
{
  /**
   * {@inheritdoc}
   */

  public static function tableName()
  {
    return 'RefreshTokenUsuario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['token'], 'required'],
      [['expiracion', 'creado', 'eliminado'], 'safe'],
      [['token', 'idUsuario'], 'string', 'max' => 36],
      [['token'], 'unique'],
      [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuario' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'token' => 'Token',
      'idUsuario' => 'Id Usuario',
      'expiracion' => 'Expiracion',
      'creado' => 'Creado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'token',
      'idUsuario',
      'expiracion',
      'creado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'usuario',
    ];
  }


  /**
   * Gets query for [[Usuario]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getUsuario()
  {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuario']);
  }
}
