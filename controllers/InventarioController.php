<?php

class InventarioController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        $busqueda = trim($_GET['busqueda'] ?? '');

        // Llamar a los Procedimientos Almacenados de Inventario
        $stmt = $this->db->prepare("CALL sp_inventario_listar(?)");
        $stmt->execute([$busqueda]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $stmt2 = $this->db->query("CALL sp_inventario_contar_alertas()");
        $conteo = $stmt2->fetch(PDO::FETCH_ASSOC);
        $total_alertas = $conteo['total'] ?? 0;
        $stmt2->closeCursor();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/inventario/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function actualizar()
    {
        $id = (int)($_POST['id'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $stock_minimo = (int)($_POST['stock_minimo'] ?? 5);

        $stmt = $this->db->prepare("CALL sp_inventario_actualizar(?, ?, ?)");
        if ($stmt->execute([$id, $stock, $stock_minimo])) {
            $_SESSION['success'] = "Stock actualizado correctamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar el stock.";
        }
        $stmt->closeCursor();

        header("Location: " . BASE_URL . "inventario");
        exit;
    }
}
