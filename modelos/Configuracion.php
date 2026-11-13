<?php

namespace app\modelos;

use Yii;

/**
* Clase modelo para la tabla "Configuracion".
*
* @property string|null $idUsuario
* @property string $clave
* @property string|null $valor
* @property string|null $nombre
* @property string|null $descripcion
* @property string|null $creado
* @property string|null $modificado
* @property string|null $eliminado
*
* @property Usuario $usuario
*/
class Configuracion extends ModeloBase {

  public const CLAVE_FORMATO_FLEXIBLE = 'FEX';
  public const CLAVE_TITULO_SISTEMA = 'TITULO';
  public const CLAVE_ZONA_HORARIA = 'ZH';
  public const CLAVE_LOGO = 'LOGO';

  public const CLAVES_SISTEMA = [
    self::CLAVE_FORMATO_FLEXIBLE,
    self::CLAVE_TITULO_SISTEMA,
    self::CLAVE_ZONA_HORARIA,
    self::CLAVE_LOGO,
  ];

  /**
  * {@inheritdoc}
  */
  public static function tableName() {
    return 'Configuracion';
  }

  /**
  * {@inheritdoc}
  */
  public function rules() {
    return [
      [['idUsuario', 'valor', 'nombre', 'descripcion', 'creado', 'modificado', 'eliminado'], 'default', 'value' => null],
      [['clave'], 'required'],
      [['valor', 'descripcion'], 'string'],
      [['creado', 'modificado', 'eliminado'], 'safe'],
      [['idUsuario'], 'string', 'max' => 36],
      [['clave'], 'string', 'max' => 50],
      [['nombre'], 'string', 'max' => 255],
      [['clave'], 'unique'],
      ['valor', 'validarZonaHoraria'],
      [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idUsuario' => 'id']],
    ];
  }

  public function validarZonaHoraria($attribute): void {
    if ($this->clave !== self::CLAVE_ZONA_HORARIA || $this->$attribute === null) {
      return;
    }

    $zonasHorarias = \DateTimeZone::listIdentifiers(\DateTimeZone::AMERICA);
    if (!in_array($this->$attribute, $zonasHorarias, true)) {
      $this->addError($attribute, 'La zona horaria debe pertenecer a América.');
    }
  }

  /**
  * {@inheritdoc}
  */
  public function attributeLabels() {
    return [
      'idUsuario' => 'Id Usuario',
      'clave' => 'Clave',
      'valor' => 'Valor',
      'nombre' => 'Nombre',
      'descripcion' => 'Descripcion',
      'creado' => 'Creado',
      'modificado' => 'Modificado',
      'eliminado' => 'Eliminado',
    ];
  }

  public function fields () {
    return [
      'idUsuario',
      'clave',
      'valor',
      'nombre',
      'descripcion',
      'creado',
      'modificado',
      'eliminado',
    ];
  }

  public function extraFields() {
    return [
      'usuario',
    ];
  }


  public function getUsuario() {
    return $this->hasOne(Usuario::class, ['id' => 'idUsuario']);
  }
}
