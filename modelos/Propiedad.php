<?php

namespace app\modelos;

use Yii;

/**
* Clase modelo para la tabla "Propiedad".
*
* @property string $id
* @property string $idFormulario
* @property string $idTipoCampo
* @property string $nombre
* @property string|null $configuracion
* @property bool $requerido
* @property bool $mostrar
* @property string|null $creado
* @property string|null $modificado
* @property string|null $eliminado
*
* @property Formulario $formulario0
* @property TipoCampo $tipoCampo0
* @property PropiedadOpcion[] $propiedadOpcions
* @property ResultadoFormularioValor[] $resultadoFormularioValors
*/
class Propiedad extends ModeloBase {

  /**
  * {@inheritdoc}
  */
  public static function tableName() {
    return 'Propiedad';
  }

  /**
  * {@inheritdoc}
  */
  public function rules() {
    return [
      [['id', 'idFormulario', 'idTipoCampo', 'nombre'], 'required'],
      [['configuracion', 'creado', 'modificado', 'eliminado'], 'safe'],
      [['requerido', 'mostrar'], 'boolean'],
      [['id', 'idFormulario', 'idTipoCampo'], 'string', 'max' => 50],
      [['nombre'], 'string', 'max' => 255],
      [['id'], 'unique'],
      [['idFormulario'], 'exist', 'skipOnError' => true, 'targetClass' => Formulario::class, 'targetAttribute' => ['idFormulario' => 'id']],
      [['idTipoCampo'], 'exist', 'skipOnError' => true, 'targetClass' => TipoCampo::class, 'targetAttribute' => ['idTipoCampo' => 'id']],
    ];
  }

  /**
  * {@inheritdoc}
  */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'idFormulario' => 'Id Formulario',
      'idTipoCampo' => 'Id Tipo Campo',
      'nombre' => 'Nombre',
      'configuracion' => 'Configuracion',
      'requerido' => 'Requerido',
      'mostrar' => 'Mostrar',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields () {
    return [
      'id',
      'idFormulario',
      'idTipoCampo',
      'nombre',
      'configuracion',
      'requerido',
      'mostrar',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields() {
    return [
      'formulario0',
      'tipoCampo0',
      'propiedadOpcions',
      'resultadoFormularioValors',
    ];
  }


  public function getFormulario0() {
    return $this->hasOne(Formulario::class, ['id' => 'idFormulario']);
  }

  public function getTipoCampo0() {
    return $this->hasOne(TipoCampo::class, ['id' => 'idTipoCampo']);
  }

  public function getPropiedadOpcions() {
    return $this->hasMany(PropiedadOpcion::class, ['idPropiedad' => 'id']);
  }

  public function getResultadoFormularioValors() {
    return $this->hasMany(ResultadoFormularioValor::class, ['idPropiedad' => 'id']);
  }
}