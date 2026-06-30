<?php

class Proveedor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener todos los proveedores
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM proveedor ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener todos los proveedores (incluyendo inactivos)
    public function getAllIncludingInactive() {
        $stmt = $this->db->prepare("SELECT * FROM proveedor ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar proveedor por id
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM proveedor WHERE id_proveedor = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear proveedor
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO proveedor (nombre, contacto, telefono, correo, direccion, estado) VALUES (?, ?, ?, ?, ?, 'activo')");
        return $stmt->execute([
            $data['nombre'],
            $data['contacto'],
            $data['telefono'],
            $data['correo'] ?? null,
            $data['direccion']
        ]);
    }

    // Actualizar proveedor
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE proveedor SET nombre = ?, contacto = ?, telefono = ?, correo = ?, direccion = ? WHERE id_proveedor = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['contacto'],
            $data['telefono'],
            $data['correo'] ?? null,
            $data['direccion'],
            $id
        ]);
    }

    // Cambiar estado de proveedor (Activar/Desactivar)
    public function cambiarEstado($id) {
        $stmt = $this->db->prepare("UPDATE proveedor SET estado = IF(estado = 'activo', 'inactivo', 'activo') WHERE id_proveedor = ?");
        return $stmt->execute([$id]);
    }
}
