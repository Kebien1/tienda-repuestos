<?php

class ReporteController
{
    private $reporteModel;

    public function __construct()
    {
        $this->reporteModel = new Reporte();
    }

    public function index()
    {
        $resumen = $this->reporteModel->getResumenGeneral();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/reportes/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function productosVendidos()
    {
        $productos = $this->reporteModel->getProductosVendidos();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/reportes/productos.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function ventasPorFecha()
    {
        $fecha_inicio = $_GET['inicio'] ?? date('Y-m-01'); // Primer día del mes por defecto
        $fecha_fin = $_GET['fin'] ?? date('Y-m-t'); // Último día del mes por defecto

        $ventas = $this->reporteModel->getVentasPorFecha($fecha_inicio, $fecha_fin);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/reportes/fechas.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
