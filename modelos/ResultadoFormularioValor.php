<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "ResultadoFormularioValor".
 *
 * @property string $id
 * @property string $idResultadoFormulario
 * @property string $idPropiedad
 * @property string $etiqueta
 * @property string|null $valor
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Propiedad $propiedad
 * @property ResultadoFormulario $resultado
 */
class ResultadoFormularioValor extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'ResultadoFormularioValor';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'idResultadoFormulario', 'idPropiedad'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idResultadoFormulario', 'idPropiedad'], 'string', 'max' => 50],
      [['etiqueta'], 'string', 'max' => 255],
      [['valor'], 'string', 'max' => 1023],
      [['id'], 'unique'],
      [['idPropiedad'], 'exist', 'skipOnError' => true, 'targetClass' => Propiedad::class, 'targetAttribute' => ['idPropiedad' => 'id']],
      [['idResultadoFormulario'], 'exist', 'skipOnError' => true, 'targetClass' => ResultadoFormulario::class, 'targetAttribute' => ['idResultadoFormulario' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'idResultadoFormulario' => 'Id Resultado Formulario',
      'idPropiedad' => 'Id Propiedad',
      'etiqueta' => 'Etiqueta',
      'valor' => 'Valor',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'idResultadoFormulario',
      'idPropiedad',
      'etiqueta',
      'valor',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'propiedad',
      'resultado',
    ];
  }


  public function getPropiedad()
  {
    return $this->hasOne(Propiedad::class, ['id' => 'idPropiedad']);
  }

  public function getResultado()
  {
    return $this->hasOne(ResultadoFormulario::class, ['id' => 'idResultadoFormulario']);
  }
}
