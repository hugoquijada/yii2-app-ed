<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "RutaUsuario".
 *
 * @property string $id
 * @property string $idRuta
 * @property string $idUsuario
 * @property string|null $asignado
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Ruta $ruta
 * @property Usuario $usuario
 */
class RutaUsuario extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'RutaUsuario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['asignado', 'creado', 'modificado', 'eliminado'], 'default', 'value' => null],
      [['id', 'idRuta', 'idUsuario'], 'required'],
      [['asignado', 'creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idRuta', 'idUsuario'], 'string', 'max' => 50],
      [['id'], 'unique'],
      [['idRuta'], 'exist', 'skipOnError' => true, 'targetClass' => Ruta::class, 'targetAttribute' => ['idRuta' => 'id']],
      [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuario' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'idRuta' => 'Id Ruta',
      'idUsuario' => 'Id Usuario',
      'asignado' => 'Asignado',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'idRuta',
      'idUsuario',
      'asignado',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'ruta',
      'usuario',
    ];
  }


  public function getRuta()
  {
    return $this->hasOne(Ruta::class, ['id' => 'idRuta']);
  }

  public function getUsuario()
  {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuario']);
  }
}
