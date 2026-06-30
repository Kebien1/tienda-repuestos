<?php

class OrdenCompra
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener todas las órdenes de compra con el nombre de su proveedor
    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT oc.*, p.nombre AS proveedor_nombre
            FROM orden_compra oc
            JOIN proveedor p ON oc.id_proveedor = p.id_proveedor
            ORDER BY oc.fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar una orden de compra por su ID con datos del proveedor
    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT oc.*, p.nombre AS proveedor_nombre, p.contacto AS proveedor_contacto,
                    p.telefono AS proveedor_telefono, p.direccion AS proveedor_direccion
            FROM orden_compra oc
            JOIN proveedor p ON oc.id_proveedor = p.id_proveedor
            WHERE oc.id_orden = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear una nueva orden de compra con sus detalles en una transacción
    public function create($id_proveedor, $items, $id_empleado = 1)
    {
        try {
            $this->db->beginTransaction();

            // Calcular el total de la orden
            $total = 0;
            foreach ($items as $item) {
                $total += $item['cantidad'] * $item['precio_unit'];
            }

            // Insertar la cabecera de la orden
            $stmt = $this->db->prepare("INSERT INTO orden_compra (id_proveedor, id_empleado, total, estado) VALUES (?, ?, ?, 'pendiente')");
            $stmt->execute([$id_proveedor, $id_empleado, $total]);
            $orden_id = $this->db->lastInsertId();

            // Insertar los detalles de la orden
            $stmtDetalle = $this->db->prepare("INSERT INTO detalle_orden (id_orden, id_producto, cantidad, precio_unit) VALUES (?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmtDetalle->execute([
                    $orden_id,
                    $item['id_producto'],
                    $item['cantidad'],
                    $item['precio_unit']
                ]);
            }

            $this->db->commit();
            return $orden_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Actualizar estado de la orden y sumar stock a productos si es 'recibida'
    public function updateStatus($id, $nuevoEstado)
    {
        try {
            $this->db->beginTransaction();

            // Obtener el estado actual de la orden
            $orden = $this->find($id);
            if (!$orden) {
                throw new Exception("Orden de compra no encontrada.");
            }

            $estadoAnterior = $orden['estado'];

            // Actualizar el estado en la base de datos
            $stmt = $this->db->prepare("UPDATE orden_compra SET estado = ? WHERE id_orden = ?");
            $stmt->execute([$nuevoEstado, $id]);

            // Si pasa a 'recibida' y antes no lo estaba, sumamos el stock
            if ($nuevoEstado === 'recibida' && $estadoAnterior !== 'recibida') {
                $detalleModel = new DetalleOrden();
                $detalles = $detalleModel->getByOrdenId($id);
                $productoModel = new Producto();

                foreach ($detalles as $detalle) {
                    $productoModel->addStock($detalle['id_producto'], $detalle['cantidad']);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
