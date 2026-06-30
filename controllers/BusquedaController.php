<?php

class BusquedaController
{
    private $db;
    private $vehiculoModel;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->vehiculoModel = new Vehiculo();
    }

    public function index()
    {
        $tipos = $this->vehiculoModel->getTiposVehiculo();

        $id_tipo = $_GET['id_tipo_vehiculo'] ?? '';
        $id_marca = $_GET['id_marca_vehiculo'] ?? '';
        $id_modelo = $_GET['id_modelo_vehiculo'] ?? '';
        $anio = $_GET['anio'] ?? '';

        $resultados = [];
        if ($id_modelo) {
            $anio_int = $anio ? (int)$anio : 0;
            $stmt = $this->db->prepare("CALL sp_buscar_productos_por_vehiculo(?, ?)");
            $stmt->execute([$id_modelo, $anio_int]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/busqueda/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function ajaxMarcas()
    {
        $id_tipo = isset($_GET['id_tipo']) ? (int)$_GET['id_tipo'] : 0;
        $stmt = $this->db->prepare("CALL sp_obtener_marcas_por_tipo(?)");
        $stmt->execute([$id_tipo]);
        $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($marcas);
        exit;
    }

    public function ajaxModelos()
    {
        $id_marca = isset($_GET['id_marca']) ? (int)$_GET['id_marca'] : 0;
        $stmt = $this->db->prepare("CALL sp_obtener_modelos_por_marca(?)");
        $stmt->execute([$id_marca]);
        $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($modelos);
        exit;
    }
}
