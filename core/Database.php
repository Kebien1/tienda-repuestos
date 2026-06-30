<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}";
        
        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['pass'], $config['options']);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    // Patrón Singleton para obtener una única instancia de conexión
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
