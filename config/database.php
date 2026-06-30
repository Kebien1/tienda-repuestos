<?php
// Configuración de la base de datos MySQL

return [
    'host' => 'localhost',
    'db'   => 'tienda_repuestos',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];
