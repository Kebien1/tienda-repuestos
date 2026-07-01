<?php

class Reporte
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene el resumen general utilizando tu procedimiento almacenado nativo
     */
    public function obtenerResumenGeneral()
    {
        $stmt = $this->db->query("CALL sp_reportes_resumen_general()");
        $res = $stmt->fetch();
        $stmt->closeCursor(); // Libera la conexión para el próximo procedimiento
        return $res;
    }

    /**
     * Obtiene el top de productos utilizando tu procedimiento almacenado nativo
     */
    public function obtenerProductosMasVendidos()
    {
        $stmt = $this->db->query("CALL sp_reportes_productos_vendidos()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    /**
     * Obtiene el historial de ventas utilizando tu procedimiento almacenado nativo
     */
    public function obtenerVentasPorFecha($inicio, $fin)
    {
        $stmt = $this->db->prepare("CALL sp_reportes_ventas_fecha(?, ?)");
        $stmt->execute([$inicio, $fin]);
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    /**
     * Consulta flexible para los reportes con filtros dinámicos del integrante
     */
    public function getVentasFiltradas($inicio, $fin, $estado)
    {
        $sql = "SELECT v.*, u.nombre as cliente_nombre, e.nombre as empleado_nombre 
                FROM venta v 
                LEFT JOIN usuario u ON v.id_cliente = u.id_usuario 
                LEFT JOIN usuario e ON v.id_empleado = e.id_usuario 
                WHERE 1=1";
        $params = [];

        if (!empty($inicio) && !empty($fin)) {
            $sql .= " AND DATE(v.fecha) BETWEEN ? AND ?";
            $params[] = $inicio;
            $params[] = $fin;
        }
        if (!empty($estado)) {
            $sql .= " AND v.estado = ?";
            $params[] = $estado;
        }

        $sql .= " ORDER BY v.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
