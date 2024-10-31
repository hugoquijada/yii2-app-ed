<?php

namespace app\modelos;

class Sesion extends Usuario
{

  public function fields()
  {
    return [
      'id',
      'correo',
      'nombre',
      'apellidos',
      'estatus',
      'foto',
      'rol',
      'token' => function ($model) {
        return $model->getAuthKey();
      },
      'refreshToken' => function ($model) {
        return $model->addRefreshToken();
      }
    ];
  }
}
