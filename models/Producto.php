<?php

class Producto
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- MÉTODOS COMPATIBLES CON TUS ÓRDENES DE COMPRA (NO BORRAR) ---
    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT p.*, mp.nombre AS marca 
            FROM producto p
            LEFT JOIN marcas_producto mp ON p.id_marca_producto = mp.id_marca_producto
            WHERE p.estado = 'activo' ORDER BY p.nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, mp.nombre AS marca 
            FROM producto p
            LEFT JOIN marcas_producto mp ON p.id_marca_producto = mp.id_marca_producto
            WHERE p.id_producto = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getLowStockProducts()
    {
        $stmt = $this->db->prepare("
            SELECT p.*, mp.nombre AS marca 
            FROM producto p
            LEFT JOIN marcas_producto mp ON p.id_marca_producto = mp.id_marca_producto 
            WHERE p.stock <= p.stock_minimo AND p.estado = 'activo' ORDER BY p.stock ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addStock($id, $cantidad)
    {
        $stmt = $this->db->prepare("UPDATE producto SET stock = stock + ? WHERE id_producto = ?");
        return $stmt->execute([$cantidad, $id]);
    }

    // --- NUEVOS MÉTODOS DEL MÓDULO DE EDUAR (Usando Procedimientos) ---
    public function listarConFiltro($busqueda = '')
    {
        $stmt = $this->db->prepare("CALL sp_productos_listar(?)");
        $stmt->execute([$busqueda]);
        $resultados = $stmt->fetchAll();
        $stmt->closeCursor(); // Necesario al usar Procedimientos
        return $resultados;
    }

    public function obtenerDetalleCompleto($id)
    {
        $stmt = $this->db->prepare("CALL sp_productos_obtener(?)");
        $stmt->execute([$id]);
        $producto = $stmt->fetch();
        $stmt->closeCursor();
        return $producto;
    }

    public function obtenerVehiculosCompatibles($id)
    {
        $stmt = $this->db->prepare("CALL sp_productos_obtener_vehiculos(?)");
        $stmt->execute([$id]);
        $vehiculos = $stmt->fetchAll();
        $stmt->closeCursor();
        return array_column($vehiculos, 'id_modelo_vehiculo');
    }

    public function crear($data, $vehiculos_ids)
    {
        $stmt = $this->db->prepare("CALL sp_productos_insertar(?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['id_marca_producto'],
            $data['id_categoria'],
            $data['precio'],
            $data['stock'],
            $data['descripcion'],
            $data['imagen'],
            $vehiculos_ids
        ]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res ? $res['id_producto'] : false;
    }

    public function actualizar($id, $data, $vehiculos_ids)
    {
        $stmt = $this->db->prepare("CALL sp_productos_actualizar(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id,
            $data['nombre'],
            $data['id_marca_producto'],
            $data['id_categoria'],
            $data['precio'],
            $data['stock'],
            $data['descripcion'],
            $data['imagen'],
            $vehiculos_ids
        ]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res ? true : false;
    }

    public function eliminarLogico($id)
    {
        $stmt = $this->db->prepare("CALL sp_productos_eliminar(?)");
        $stmt->execute([$id]);
        $stmt->closeCursor();
        return true;
    }
}
