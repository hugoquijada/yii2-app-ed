<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "MenuUsuario".
 *
 * @property string $id
 * @property string $idMenu
 * @property string $idUsuario
 * @property string|null $asignado
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Menu $menu
 * @property Usuario $usuario
 */
class MenuUsuario extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'MenuUsuario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'idMenu', 'idUsuario'], 'required'],
      [['asignado','creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idMenu', 'idUsuario'], 'string', 'max' => 50],
      [['id'], 'unique'],
      [['idMenu'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['idMenu' => 'id']],
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
      'idMenu' => 'Id Menu',
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
      'idMenu',
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
      'menu',
      'usuario',
    ];
  }

  public function getMenu()
  {
    return $this->hasOne(Menu::class, ['id' => 'idMenu']);
  }

  public function getUsuario()
  {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuario']);
  }
}
