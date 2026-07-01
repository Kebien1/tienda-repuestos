<?php
class Diagnostico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT d.*, u.nombre AS cliente_nombre 
            FROM diagnosticos_ia d 
            LEFT JOIN usuario u ON d.id_cliente = u.id_usuario 
            ORDER BY d.fecha_registro DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM diagnosticos_ia WHERE id_diagnostico = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO diagnosticos_ia 
            (id_cliente, marca_vehiculo, modelo_vehiculo, anio_vehiculo, placa, sintoma, componente_detectado, posible_falla, prioridad, especialidad_recomendada, recomendacion, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['id_cliente'] ?: null,
            $data['marca_vehiculo'],
            $data['modelo_vehiculo'],
            $data['anio_vehiculo'],
            $data['placa'],
            $data['sintoma'],
            $data['componente_detectado'],
            $data['posible_falla'],
            $data['prioridad'],
            $data['especialidad_recomendada'],
            $data['recomendacion'],
            $data['estado']
        ]);
        return $this->db->lastInsertId();
    }

    public function actualizarEstado($id, $estado)
    {
        $stmt = $this->db->prepare("UPDATE diagnosticos_ia SET estado = ? WHERE id_diagnostico = ?");
        return $stmt->execute([$estado, $id]);
    }
}
