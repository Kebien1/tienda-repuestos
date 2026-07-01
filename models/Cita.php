<?php
class Cita
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nombre AS cliente_nombre, m.nombre AS mecanico_nombre 
            FROM citas_servicio c
            LEFT JOIN usuario u ON c.id_cliente = u.id_usuario
            LEFT JOIN usuario m ON c.id_mecanico = m.id_usuario
            ORDER BY c.fecha_cita DESC, c.hora_cita DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM citas_servicio WHERE id_cita = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO citas_servicio 
            (id_diagnostico, id_cliente, id_mecanico, marca_vehiculo, modelo_vehiculo, anio_vehiculo, placa, tipo_servicio, descripcion, fecha_cita, hora_cita, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pendiente')
        ");
        return $stmt->execute([
            $data['id_diagnostico'] ?: null,
            $data['id_cliente'],
            $data['id_mecanico'] ?: null,
            $data['marca_vehiculo'],
            $data['modelo_vehiculo'],
            $data['anio_vehiculo'],
            $data['placa'],
            $data['tipo_servicio'],
            $data['descripcion'],
            $data['fecha_cita'],
            $data['hora_cita']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE citas_servicio SET 
            id_cliente=?, id_mecanico=?, marca_vehiculo=?, modelo_vehiculo=?, anio_vehiculo=?, 
            placa=?, tipo_servicio=?, descripcion=?, fecha_cita=?, hora_cita=?, estado=? 
            WHERE id_cita=?
        ");
        return $stmt->execute([
            $data['id_cliente'],
            $data['id_mecanico'] ?: null,
            $data['marca_vehiculo'],
            $data['modelo_vehiculo'],
            $data['anio_vehiculo'],
            $data['placa'],
            $data['tipo_servicio'],
            $data['descripcion'],
            $data['fecha_cita'],
            $data['hora_cita'],
            $data['estado'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM citas_servicio WHERE id_cita = ?");
        return $stmt->execute([$id]);
    }
}
