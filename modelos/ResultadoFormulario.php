<?php

namespace app\modelos;

use Yii;

/**
* Clase modelo para la tabla "ResultadoFormulario".
*
  * @property string $id
  * @property string $idFormulario
  * @property string|null $creado
  * @property string|null $modificado
  * @property string|null $eliminado
  *
      * @property Formulario $formulario0
      * @property ResultadoFormularioValor[] $resultadoFormularioValors
  */
class ResultadoFormulario extends ModeloBase {

/**
* {@inheritdoc}
*/
public static function tableName() {
return 'ResultadoFormulario';
}

/**
* {@inheritdoc}
*/
public function rules() {
return [
      [['id', 'idFormulario'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idFormulario'], 'string', 'max' => 50],
      [['id'], 'unique'],
      [['idFormulario'], 'exist', 'skipOnError' => true, 'targetClass' => Formulario::class, 'targetAttribute' => ['idFormulario' => 'id']],
    ];
}

/**
* {@inheritdoc}
*/
public function attributeLabels() {
return [
 'id' => 'ID',
 'idFormulario' => 'Id Formulario',
 'creado' => 'Creado',
 'modificado' => 'Modificado',
 'eliminado' => 'Eliminado',
  ];
  }

  public function fields () {
  return [
   'id',
 'idFormulario',
 'creado',
 'modificado',
 'eliminado',
  ];
  }

  public function extraFields() {
  return [
   'formulario0',
 'resultadoFormularioValors',
    ];
    }

    
      public function getFormulario0() {
      return $this->hasOne(Formulario::class, ['id' => 'idFormulario']);
      }
    
      public function getResultadoFormularioValors() {
      return $this->hasMany(ResultadoFormularioValor::class, ['idResultadoFormulario' => 'id']);
      }
            }