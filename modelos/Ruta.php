<?php

namespace app\modelos;

use Yii;

/**
* Clase modelo para la tabla "Ruta".
*
* @property string $id
* @property string|null $idPadre
* @property string $nombre
* @property string $urlAbsoluta
* @property string|null $icono
* @property int|null $orden
* @property bool $visible Indica si la ruta debe ser visible en el menu lateral
* @property string|null $creado
* @property string|null $modificado
* @property string|null $eliminado
*
* @property Ruta $padre
* @property RutaUsuario[] $rutaUsuarios
* @property Ruta[] $rutas
*/
class Ruta extends ModeloBase {
  /**
  * {@inheritdoc}
  */
  public static function tableName() {
    return 'Ruta';
  }

  /**
  * {@inheritdoc}
  */
  public function rules() {
    return [
      [['idPadre', 'icono', 'orden', 'creado', 'modificado', 'eliminado'], 'default', 'value' => null],
      [['visible'], 'default', 'value' => 1],
      [['id', 'nombre', 'urlAbsoluta'], 'required'],
      [['orden'], 'default', 'value' => null],
      [['orden'], 'integer'],
      [['visible'], 'boolean'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idPadre'], 'string', 'max' => 50],
      [['nombre', 'urlAbsoluta', 'icono'], 'string', 'max' => 255],
      [['id'], 'unique'],
      [['idPadre'], 'exist', 'skipOnError' => true, 'targetClass' => Ruta::class, 'targetAttribute' => ['idPadre' => 'id']],
    ];
  }

  /**
  * {@inheritdoc}
  */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'idPadre' => 'Id Padre',
      'nombre' => 'Nombre',
      'urlAbsoluta' => 'Url Absoluta',
      'icono' => 'Icono',
      'orden' => 'Orden',
      'visible' => 'Visible',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields () {
    return [
      'id',
      'idPadre',
      'nombre',
      'urlAbsoluta',
      'icono',
      'orden',
      'visible',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields() {
    return [
      'padre',
      'rutaUsuarios',
      'rutas',
    ];
  }


  public function getPadre() {
    return $this->hasOne(Ruta::class, ['id' => 'idPadre']);
  }

  public function getRutaUsuarios() {
    return $this->hasMany(RutaUsuario::class, ['idRuta' => 'id']);
  }

  public function getRutas() {
    return $this->hasMany(Ruta::class, ['idPadre' => 'id']);
  }
}