<?php

use hqsoft\reportkit\document\CellStyle;

return [
  'titulo' => [
    'mostrar' => false,
    'texto' => '',
    'estilo' => CellStyle::title(),
  ],
  'logo' => [
    'mostrar' => false,
    'ruta' => '@app/publico/recursos/logo.png',
    'width' => 120,
    'height' => null,
    'span' => 24,
    'align' => 'left',
    'estilo' => null,
  ],
  'encabezado' => [
    'mostrar' => true,
    'estilo' => CellStyle::header(),
  ],
  'fila' => [
    'estilo' => null,
    'estiloPar' => null,
    'estiloImpar' => null,
  ],
  'formatos' => [
    'pdf' => [
      'titulo' => [
        'mostrar' => false,
      ],
    ],
  ],
];
