<?php

class Vehiculo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCategorias()
    {
        $stmt = $this->db->query("CALL sp_obtener_categorias()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function getMarcasProducto()
    {
        $stmt = $this->db->query("CALL sp_obtener_marcas_producto()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function getTiposVehiculo()
    {
        $stmt = $this->db->query("CALL sp_obtener_tipos_vehiculo()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }
}
