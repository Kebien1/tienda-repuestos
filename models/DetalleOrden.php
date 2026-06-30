<?php

class DetalleOrden {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener los detalles de una orden de compra específica junto a los nombres de producto
    public function getByOrdenId($orden_id) {
        $stmt = $this->db->prepare("
            SELECT do.*, p.nombre AS producto_nombre, p.marca AS producto_marca
            FROM detalle_orden do
            JOIN producto p ON do.id_producto = p.id_producto
            WHERE do.id_orden = ?
        ");
        $stmt->execute([$orden_id]);
        return $stmt->fetchAll();
    }
}
