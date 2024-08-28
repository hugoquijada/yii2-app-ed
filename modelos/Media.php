<?php

namespace app\modelos;

use Yii;

/**
 * This is the model class for table "Media".
 *
 * @property string $id
 * @property string|null $idUsuario
 * @property string $nombre
 * @property string|null $uuid
 * @property string|null $size
 * @property string|null $extension
 * @property string|null $mimetype
 * @property string|null $ruta
 * @property string|null $descripcion
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $ubicacionFisica
 * @property string|null $eliminado
 * 
 * @property Usuario $usuario
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
      [['nombre'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['idUsuario'], 'string', 'max' => 50],
      [['ubicacionFisica'], 'string', 'max' => 200],
      [['extension'], 'string', 'max' => 5],
      [['nombre', 'uuid', 'size', 'mimetype', 'ruta'], 'string', 'max' => 100],
      [['descripcion'], 'string', 'max' => 500],
      [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuario' => 'id']],
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
      'size' => 'Size',
      'extension' => 'Extension',
      'mimetype' => 'Mimetype',
      'ubicacionFisica' => 'Ubicación Fisica',
      'ruta' => 'Ruta',
      'descripcion' => 'Descripcion',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  /**
   * Gets query for [[usuario]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getUsuario() {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuario']);
  }

}