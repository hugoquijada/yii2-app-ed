<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "Menu".
 *
 * @property string $id
 * @property string $idPadre
 * @property string $nombre
 * @property string $url
 * @property string|null $icono
 * @property int $orden
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Menu $padre
 * @property MenuFormulario[] $menuFormularios
 * @property MenuUsuario[] $menuUsuarios
 * @property Menu[] $subMenus
 */
class Menu extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'Menu';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'idPadre', 'url'], 'required'],
      [['orden'], 'default', 'value' => null],
      [['orden'], 'integer'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idPadre'], 'string', 'max' => 50],
      [['url', 'icono', 'nombre'], 'string', 'max' => 255],
      [['id'], 'unique'],
      [['idPadre'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::class, 'targetAttribute' => ['idPadre' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'idPadre' => 'Id Padre',
      'nombre' => 'Nombre',
      'url' => 'Url',
      'icono' => 'Icono',
      'orden' => 'Orden',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'idPadre',
      'nombre',
      'url',
      'icono',
      'orden',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'padre',
      'subMenus',
    ];
  }


  public function getPadre()
  {
    return $this->hasOne(Menu::class, ['id' => 'idPadre']);
  }

  public function getSubMenus()
  {
    return $this->hasMany(Menu::class, ['idPadre' => 'id']);
  }


  public function getMenuFormularios()
  {
    return $this->hasMany(MenuFormulario::class, ['idMenu' => 'id']);
  }

  public function getMenuUsuarios()
  {
    return $this->hasMany(MenuUsuario::class, ['idMenu' => 'id']);
  }
}
