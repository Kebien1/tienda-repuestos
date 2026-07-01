<?php

class ReporteController
{
    private $reporteModel;

    public function __construct()
    {
        $this->reporteModel = new Reporte();
    }

    /**
     * Redirige la ruta base /reportes hacia el dashboard
     */
    public function index()
    {
        header("Location: " . BASE_URL . "reportes/dashboard");
        exit;
    }


    /**
     * Carga el Dashboard analítico (Combina tus SP con la lógica del integrante)
     */
    public function dashboard()
    {
        // Rango por defecto: últimos 30 días para el historial gráfico o de tabla
        $inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fin = $_GET['fecha_fin'] ?? date('Y-m-d');

        // Consumo de datos unificado
        $resumen = $this->reporteModel->obtenerResumenGeneral();
        $topProductos = $this->reporteModel->obtenerProductosMasVendidos();
        $ventasPeriodo = $this->reporteModel->obtenerVentasPorFecha($inicio, $fin);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/reportes/dashboard.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Carga el reporte detallado y filtrado de ventas
     */
    public function ventas()
    {
        $inicio = $_GET['fecha_inicio'] ?? '';
        $fin = $_GET['fecha_fin'] ?? '';
        $estado = $_GET['estado'] ?? '';

        $ventas = $this->reporteModel->getVentasFiltradas($inicio, $fin, $estado);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/reportes/ventas.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
