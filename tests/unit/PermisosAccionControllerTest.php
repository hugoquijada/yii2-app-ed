<?php

namespace tests\unit;

use app\modelos\Configuracion;
use app\modelos\Permiso;
use app\modelos\Usuario;
use PHPUnit\Framework\TestCase;
use v1\controladores\ConfiguracionController;
use v1\controladores\UsuarioController;

class PermisosAccionControllerTest extends TestCase {

  public function testUsuarioDeclaraPermisoYEscenarioPorOperacion(): void {
    $controlador = $this->crearControlador(UsuarioController::class);

    $this->assertSame(Permiso::MENU_ADMINISTRACION_USUARIO, $controlador->permisosAccion['index']);
    $this->assertSame(Permiso::USUARIO_CREAR, $controlador->permisosAccion['post']['permiso']);
    $this->assertSame(Usuario::ESCENARIO_CREAR, $controlador->permisosAccion['post']['escenario']);
    $this->assertSame(Permiso::USUARIO_EDITAR, $controlador->permisosAccion['put']['permiso']);
  }

  public function testConfiguracionExigePermisoDeEdicion(): void {
    $controlador = $this->crearControlador(ConfiguracionController::class);

    $this->assertSame(Permiso::CONFIGURACION_EDITAR, $controlador->permisosAccion['put']['permiso']);
    $this->assertSame(
      Configuracion::ESCENARIO_ACTUALIZAR_SISTEMA,
      $controlador->permisosAccion['put']['escenario']
    );
  }

  private function crearControlador(string $clase) {
    return (new \ReflectionClass($clase))->newInstanceWithoutConstructor();
  }
}
