<?php

class Reporte
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getResumenGeneral()
    {
        $stmt = $this->db->prepare("CALL sp_reportes_resumen_general()");
        $stmt->execute();
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res;
    }

    public function getProductosVendidos()
    {
        $stmt = $this->db->prepare("CALL sp_reportes_productos_vendidos()");
        $stmt->execute();
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function getVentasPorFecha($inicio, $fin)
    {
        $stmt = $this->db->prepare("CALL sp_reportes_ventas_fecha(?, ?)");
        $stmt->execute([$inicio, $fin]);
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }
}
