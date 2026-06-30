<?php

class Venta
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT v.*, c.nombre AS cliente_nombre, e.nombre AS empleado_nombre
            FROM venta v
            LEFT JOIN usuario c ON v.id_cliente = c.id_usuario
            LEFT JOIN usuario e ON v.id_empleado = e.id_usuario
            ORDER BY v.fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT v.*, c.nombre AS cliente_nombre, c.correo AS cliente_correo, 
                   e.nombre AS empleado_nombre
            FROM venta v
            LEFT JOIN usuario c ON v.id_cliente = c.id_usuario
            LEFT JOIN usuario e ON v.id_empleado = e.id_usuario
            WHERE v.id_venta = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getDetalles($id_venta)
    {
        $stmt = $this->db->prepare("
            SELECT dv.*, p.nombre AS producto_nombre, mp.nombre AS producto_marca
            FROM detalle_venta dv
            JOIN producto p ON dv.id_producto = p.id_producto
            LEFT JOIN marcas_producto mp ON p.id_marca_producto = mp.id_marca_producto
            WHERE dv.id_venta = ?
        ");
        $stmt->execute([$id_venta]);
        return $stmt->fetchAll();
    }

    public function create($id_cliente, $id_empleado, $items)
    {
        try {
            $this->db->beginTransaction();
            $total = 0;

            // Calcular total exacto
            foreach ($items as $item) {
                $total += $item['cantidad'] * $item['precio_unit'];
            }

            // Crear cabecera
            $stmt = $this->db->prepare("INSERT INTO venta (id_cliente, id_empleado, total, estado) VALUES (?, ?, ?, 'completada')");
            $stmt->execute([$id_cliente, $id_empleado, $total]);
            $venta_id = $this->db->lastInsertId();

            // Insertar detalles y restar stock
            $stmtDetalle = $this->db->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unit, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmtStock = $this->db->prepare("UPDATE producto SET stock = stock - ? WHERE id_producto = ?");

            foreach ($items as $item) {
                $subtotal = $item['cantidad'] * $item['precio_unit'];
                $stmtDetalle->execute([$venta_id, $item['id_producto'], $item['cantidad'], $item['precio_unit'], $subtotal]);
                $stmtStock->execute([$item['cantidad'], $item['id_producto']]);
            }

            $this->db->commit();
            return $venta_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function anular($id_venta)
    {
        // Llamamos al procedimiento almacenado que preparé en la Fase 1
        $stmt = $this->db->prepare("CALL sp_ventas_anular(?)");
        return $stmt->execute([$id_venta]);
    }
}
