<?php

namespace tests\unit;

use app\modelos\Configuracion;
use app\modelos\Grupo;
use app\modelos\Usuario;
use PHPUnit\Framework\TestCase;

class ModeloEscenarioTest extends TestCase {

  public function testUsuarioNoPermiteCambiarClaveEnActualizacionGeneral(): void {
    $usuario = new Usuario();

    $this->assertContains('clave', $usuario->scenarios()[Usuario::ESCENARIO_CREAR]);
    $this->assertNotContains('clave', $usuario->scenarios()[Usuario::ESCENARIO_ACTUALIZAR]);
  }

  public function testGrupoSoloExponeSusAtributosEditables(): void {
    $grupo = new Grupo();

    $this->assertSame(
      ['nombre', 'descripcion'],
      $grupo->scenarios()[Grupo::ESCENARIO_ACTUALIZAR]
    );
  }

  public function testConfiguracionSoloPermiteClaveYValorParaElSistema(): void {
    $configuracion = new Configuracion();

    $this->assertSame(
      ['clave', 'valor'],
      $configuracion->scenarios()[Configuracion::ESCENARIO_ACTUALIZAR_SISTEMA]
    );
  }
}
