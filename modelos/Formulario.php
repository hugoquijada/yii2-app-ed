<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "Formulario".
 *
 * @property string $id
 * @property string $nombre
 * @property string $nombrePlural
 * @property bool $esCrud
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property MenuFormulario[] $menuFormularios
 * @property Propiedad[] $propiedades
 * @property ResultadoFormulario[] $resultados
 */
class Formulario extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'Formulario';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'nombre', 'nombrePlural'], 'required'],
      [['esCrud'], 'boolean'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['nombre'], 'string', 'max' => 124],
      [['nombrePlural'], 'string', 'max' => 127],
      [['id'], 'unique'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'nombre' => 'Nombre',
      'nombrePlural' => 'Nombre Plural',
      'esCrud' => 'Es Crud',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'nombre',
      'nombrePlural',
      'esCrud',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'menus',
      'propiedades',
      'resultados',
    ];
  }


  public function getMenus()
  {
    return $this->hasMany(Menu::class, ['idMenu' => 'id'])->viaTable('MenuFormulario', ['idFormulario' => 'id']);
  }

  public function getPropiedades()
  {
    return $this->hasMany(Propiedad::class, ['idFormulario' => 'id']);
  }

  public function getResultados()
  {
    return $this->hasMany(ResultadoFormulario::class, ['idFormulario' => 'id']);
  }
}
