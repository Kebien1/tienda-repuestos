<?php

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener todos los productos activos
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE estado = 'activo' ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar producto por ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Obtener productos con stock bajo (alerta de stock bajo)
    public function getLowStockProducts() {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE stock <= stock_minimo AND estado = 'activo' ORDER BY stock ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Incrementar stock de producto (cuando se recibe la orden de compra)
    public function addStock($id, $cantidad) {
        $stmt = $this->db->prepare("UPDATE producto SET stock = stock + ? WHERE id_producto = ?");
        return $stmt->execute([$cantidad, $id]);
    }
}
