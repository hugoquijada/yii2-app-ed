<?php

namespace app\modelos;

class Sesion extends Usuario {

  public $requiereTotp = false;

  public function fields() {
    $campos = [
      'id',
      'correo',
      'nombre',
      'apellidos',
      'estatus',
      'foto',
      'rol',
      'permisos' => function ($model) {
        /** @var self $model */
        return array_keys(array_filter($model->cargarPermisos()));
      },
      'totpActivo',
    ];

    if ($this->requiereTotp) {
      $campos['requiereTotp'] = function () {
        return true;
      };
      return $campos;
    }

    $campos['token'] = function ($model) {
      return $model->getAuthKey();
    };
    $campos['refreshToken'] = function ($model) {
      return $model->addRefreshToken();
    };

    return $campos;
  }
}
