<?php

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener todos los clientes (id_rol = 5 es 'cliente')
    public function getAllClientes() {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_rol = 5 ORDER BY fecha_registro DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar usuario por id
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Buscar usuario por correo electrónico (para validación de correo único)
    public function findByEmail($correo) {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }

    // Crear nuevo usuario (cliente)
    public function create($data) {
        $contrasenaHash = hash('sha256', $data['contrasena']);

        $stmt = $this->db->prepare("INSERT INTO usuario (nombre, correo, contrasena, telefono, direccion, id_rol, estado) VALUES (?, ?, ?, ?, ?, ?, 'activo')");
        return $stmt->execute([
            $data['nombre'],
            $data['correo'],
            $contrasenaHash,
            $data['telefono'] ?? null,
            $data['direccion'] ?? null,
            $data['id_rol'] ?? 5  // 5 = cliente por defecto
        ]);
    }

    // Actualizar usuario
    public function update($id, $data) {
        if (!empty($data['contrasena'])) {
            $contrasenaHash = hash('sha256', $data['contrasena']);
            $stmt = $this->db->prepare("UPDATE usuario SET nombre = ?, correo = ?, contrasena = ?, telefono = ?, direccion = ? WHERE id_usuario = ?");
            return $stmt->execute([
                $data['nombre'],
                $data['correo'],
                $contrasenaHash,
                $data['telefono'] ?? null,
                $data['direccion'] ?? null,
                $id
            ]);
        } else {
            $stmt = $this->db->prepare("UPDATE usuario SET nombre = ?, correo = ?, telefono = ?, direccion = ? WHERE id_usuario = ?");
            return $stmt->execute([
                $data['nombre'],
                $data['correo'],
                $data['telefono'] ?? null,
                $data['direccion'] ?? null,
                $id
            ]);
        }
    }

    // Eliminar usuario
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    // Cambiar estado de usuario (Activar/Desactivar)
    public function cambiarEstado($id) {
        $stmt = $this->db->prepare("UPDATE usuario SET estado = IF(estado = 'activo', 'inactivo', 'activo') WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }
}
