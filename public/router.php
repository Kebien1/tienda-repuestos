<?php
/**
 * Router para el servidor integrado de PHP (php -S)
 * Ejecutar con: php -S localhost:8000 -t public public/router.php
 */

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // Deja que PHP sirva el archivo estático directamente desde la carpeta public
}

// Para todo lo demás, redirigir a index.php asignando el parámetro url
$_GET['url'] = ltrim($path, '/');
require_once __DIR__ . '/index.php';
