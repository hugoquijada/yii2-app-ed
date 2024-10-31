<?php

namespace app\modelos;

use Yii;

/**
 * This is the model class for table "Media".
 *
 * @property string $id
 * @property int|null $idUsuario
 * @property string $nombre
 * @property string|null $uuid
 * @property string|null $size
 * @property string|null $extension
 * @property string|null $mimetype
 * @property string|null $ruta
 * @property string|null $descripcion
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 */
class Media extends ModeloBase
{
  /**
   * {@inheritdoc}
   */

  public static function tableName()
  {
    return 'Media';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'nombre'], 'required'],
      [['idUsuario'], 'default', 'value' => null],
      [['idUsuario'], 'integer'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['nombre', 'uuid', 'size', 'mimetype'], 'string', 'max' => 100],
      [['extension'], 'string', 'max' => 5],
      [['ruta', 'descripcion'], 'string', 'max' => 500],
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
      'idUsuario' => 'Id Usuario',
      'nombre' => 'Nombre',
      'uuid' => 'Uuid',
      'size' => 'Size',
      'extension' => 'Extension',
      'mimetype' => 'Mimetype',
      'ruta' => 'Ruta',
      'descripcion' => 'Descripcion',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'idUsuario',
      'nombre',
      'uuid',
      'size',
      'extension',
      'mimetype',
      'ruta',
      'descripcion',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [];
  }
}
