<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "Menu".
 *
 * @property string $id
 * @property string $idPadre
 * @property string $nombre
 * @property string $urlAbsoluta
 * @property string|null $icono
 * @property int $orden
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Menu $menuPadre
 * @property MenuUsuario[] $menuUsuarios
 * @property Menu[] $submenus
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
      [['id', 'idPadre', 'urlAbsoluta'], 'required'],
      [['orden'], 'default', 'value' => null],
      [['orden'], 'integer'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idPadre'], 'string', 'max' => 50],
      [['urlAbsoluta', 'icono', 'nombre'], 'string', 'max' => 255],
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
      'urlAbsoluta' => 'UrlAbsoluta',
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
      'urlAbsoluta',
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
      'menuPadre',
      'submenus',
    ];
  }


  public function getMenuPadre()
  {
    return $this->hasOne(Menu::class, ['id' => 'idPadre']);
  }

  public function getSubmenus()
  {
    return $this->hasMany(Menu::class, ['idPadre' => 'id']);
  }

  public function getMenuUsuarios()
  {
    return $this->hasMany(MenuUsuario::class, ['idMenu' => 'id']);
  }
}
