<?php

namespace app\modelos;

class Sesion extends Usuario {

  public function fields() {
    return [
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
      'token' => function ($model) {
        return $model->getAuthKey();
      },
      'refreshToken' => function ($model) {
        return $model->addRefreshToken();
      }
    ];
  }
}
