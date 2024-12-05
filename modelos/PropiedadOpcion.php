<?php

namespace app\modelos;

use Yii;

/**
 * Clase modelo para la tabla "PropiedadOpcion".
 *
 * @property string $id
 * @property string $idPropiedad
 * @property string $valor
 * @property string $etiqueta
 * @property string|null $creado
 * @property string|null $modificado
 * @property string|null $eliminado
 *
 * @property Propiedad $propiedad
 */
class PropiedadOpcion extends ModeloBase
{

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'PropiedadOpcion';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'idPropiedad', 'valor', 'etiqueta'], 'required'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['id', 'idPropiedad'], 'string', 'max' => 50],
      [['valor', 'etiqueta'], 'string', 'max' => 255],
      [['id'], 'unique'],
      [['idPropiedad'], 'exist', 'skipOnError' => true, 'targetClass' => Propiedad::class, 'targetAttribute' => ['idPropiedad' => 'id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'idPropiedad' => 'Id Propiedad',
      'valor' => 'Valor',
      'etiqueta' => 'Etiqueta',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields()
  {
    return [
      'id',
      'idPropiedad',
      'valor',
      'etiqueta',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields()
  {
    return [
      'propiedad',
    ];
  }


  public function getPropiedad()
  {
    return $this->hasOne(Propiedad::class, ['id' => 'idPropiedad']);
  }
}
