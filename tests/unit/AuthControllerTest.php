<?php

namespace tests\unit;

use eDesarrollos\rest\AuthController;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase {

  public function testPuedePermiteAccionSinPermisoAdicional(): void {
    $controlador = $this->crearControlador([]);

    $this->assertTrue($controlador->puede(null));
    $this->assertTrue($controlador->puede(''));
  }

  public function testPuedeValidaTodosLosPermisosSolicitados(): void {
    $controlador = $this->crearControlador(['USUARIO_CREAR', 'USUARIO_EDITAR']);

    $this->assertTrue($controlador->puede('USUARIO_CREAR'));
    $this->assertTrue($controlador->puede(['USUARIO_CREAR', 'USUARIO_EDITAR']));
    $this->assertFalse($controlador->puede(['USUARIO_CREAR', 'USUARIO_ELIMINAR']));
  }

  private function crearControlador(array $permisos): AuthController {
    $usuario = new class($permisos) {
      private $permisos;

      public function __construct(array $permisos) {
        $this->permisos = $permisos;
      }

      public function tienePermiso(string $permiso): bool {
        return in_array($permiso, $this->permisos, true);
      }
    };

    $reflexion = new \ReflectionClass(AuthController::class);
    /** @var AuthController $controlador */
    $controlador = $reflexion->newInstanceWithoutConstructor();
    $controlador->usuario = $usuario;

    return $controlador;
  }
}
