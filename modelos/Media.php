<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "Media".
 *
 * @property string $id
 * @property string|null $idUsuario
 * @property string $nombre
 * @property string|null $uuid
 * @property int|null $peso
 * @property string|null $extension
 * @property string|null $mimetype
 * @property string|null $ruta
 * @property string|null $descripcion
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 */
class Media extends ModeloBase {

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'Media';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['idUsuario', 'uuid', 'peso', 'extension', 'mimetype', 'ruta', 'descripcion', 'creado', 'modificado', 'eliminado'], 'default', 'value' => null],
      [['id', 'nombre'], 'required'],
      [['peso'], 'default', 'value' => null],
      [['peso'], 'integer'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id'], 'string', 'max' => 50],
      [['idUsuario'], 'string', 'max' => 36],
      [['nombre', 'uuid', 'mimetype'], 'string', 'max' => 100],
      [['extension'], 'string', 'max' => 5],
      [['ruta', 'descripcion'], 'string', 'max' => 500],
      [['id'], 'unique'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'idUsuario' => 'Id Usuario',
      'nombre' => 'Nombre',
      'uuid' => 'Uuid',
      'peso' => 'Peso',
      'extension' => 'Extension',
      'mimetype' => 'Mimetype',
      'ruta' => 'Ruta',
      'descripcion' => 'Descripcion',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields() {
    return [
      'id',
      'idUsuario',
      'nombre',
      'uuid',
      'peso',
      'extension',
      'mimetype',
      'ruta',
      'descripcion',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields() {
    return [];
  }
}
