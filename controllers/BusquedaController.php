<?php

class BusquedaController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // El index lo dejaremos vacío por ahora, lo usaremos para la página de búsqueda del cliente
    public function index()
    {
        echo "Página de búsqueda en construcción...";
    }

    // Función que devuelve las marcas en formato JSON para el JavaScript
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

    // Función que devuelve los modelos en formato JSON para el JavaScript
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
