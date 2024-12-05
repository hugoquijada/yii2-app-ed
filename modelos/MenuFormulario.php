<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "MenuFormulario".
 *
 * @property string $id
 * @property string $idMenu
 * @property string $idFormulario
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Formulario $formulario
 * @property Menu $menu
 */
class MenuFormulario extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'MenuFormulario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'idMenu', 'idFormulario'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idMenu', 'idFormulario'], 'string', 'max' => 50],
      [['idFormulario'], 'exist', 'skipOnError' => true, 'targetClass' => Formulario::class, 'targetAttribute' => ['idFormulario' => 'id']],
      [['idMenu'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['idMenu' => 'id']],
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
      'idFormulario' => 'Id Formulario',
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
      'idFormulario',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'formulario',
      'menu',
    ];
  }


  public function getFormulario()
  {
    return $this->hasOne(Formulario::class, ['id' => 'idFormulario']);
  }

  public function getMenu()
  {
    return $this->hasOne(Menu::class, ['id' => 'idMenu']);
  }
}
