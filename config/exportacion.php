<?php

use hqsoft\reportkit\document\CellStyle;

return [
  'titulo' => [
    'mostrar' => true,
    'texto' => '',
    'estilo' => CellStyle::create()
      ->bold()
      ->fontSize(18)
      ->color('#1F2937')
      ->padding(8),
  ],

  'logo' => [
    'mostrar' => false,
    'ruta' => '@app/publico/recursos/logo.png',
    'width' => 120,
    'height' => null,
    'span' => 24,
    'align' => 'left',
    'estilo' => CellStyle::create()->padding(4),
  ],
  'encabezado' => [
    'mostrar' => true, /*
    'estilo' => CellStyle::create()
      ->bold()
      ->background('#0F172A')
      ->color('#FFFFFF')
      ->padding(6), // */
  ],

  'fila' => [
    'estilo' => CellStyle::create()
      ->padding(5),
    'estiloPar' => CellStyle::create()
      ->background('#F8FAFC'),
    'estiloImpar' => CellStyle::create()
      ->background('#FFFFFF'),
  ],

  'formatos' => [
    'pdf' => [
      'titulo' => [
        'mostrar' => false,
        //'texto' => 'Reporte PDF',
      ],
    ],
    'xlsx' => [
      'titulo' => [
        'mostrar' => false,
        'texto' => 'Reporte Excel',
      ],
    ],
    'docx' => [
      'titulo' => [
        'mostrar' => false,
      ],
      'encabezado' => [
        'estilo' => CellStyle::create()
          ->bold()
          ->background('#1D4ED8')
          ->color('#FFFFFF')
          ->padding(6),
      ],
    ],
    'csv' => [
      'titulo' => [
        'mostrar' => false,
      ],
      'logo' => [
        'mostrar' => false,
      ],
    ],
  ],
];
