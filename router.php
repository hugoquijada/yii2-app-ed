<?php
// Ruta al directorio de documentos públicos
$docroot = __DIR__ . '/publico';
$uri = $_SERVER['REQUEST_URI'];
$file = $docroot . $uri;

// Redirige a index.php si el archivo no existe
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // Sirve el archivo directamente si existe
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $docroot . '/index.php';
$_SERVER['PATH_INFO'] = parse_url($uri, PHP_URL_PATH);

require  $docroot . '/index.php';