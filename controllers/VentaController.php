<?php

class VentaController
{
    private $ventaModel;
    private $productoModel;
    private $usuarioModel;

    public function __construct()
    {
        $this->ventaModel = new Venta();
        $this->productoModel = new Producto();
        $this->usuarioModel = new Usuario();
    }

    public function index()
    {
        $ventas = $this->ventaModel->getAll();
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/ventas/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function crear()
    {
        $clientes = $this->usuarioModel->getAllClientes();
        $productos = $this->productoModel->getAll();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/ventas/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function guardar()
    {
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);
        $productos_seleccionados = $_POST['productos'] ?? [];
        $cantidades = $_POST['cantidades'] ?? [];

        if (empty($id_cliente) || empty($productos_seleccionados)) {
            $_SESSION['error'] = "Debe seleccionar un cliente y al menos un repuesto.";
            header("Location: " . BASE_URL . "ventas/crear");
            exit;
        }

        $items = [];
        foreach ($productos_seleccionados as $prod_id) {
            $cantidad = (int)($cantidades[$prod_id] ?? 0);
            if ($cantidad > 0) {
                $producto = $this->productoModel->find($prod_id);

                // Validación estricta de stock
                if ($producto && $producto['stock'] >= $cantidad) {
                    $items[] = [
                        'id_producto' => $prod_id,
                        'cantidad' => $cantidad,
                        'precio_unit' => $producto['precio']
                    ];
                } else {
                    $_SESSION['error'] = "Stock insuficiente para el repuesto: " . ($producto['nombre'] ?? 'Desconocido');
                    header("Location: " . BASE_URL . "ventas/crear");
                    exit;
                }
            }
        }

        if (empty($items)) {
            $_SESSION['error'] = "Las cantidades deben ser mayores a cero.";
            header("Location: " . BASE_URL . "ventas/crear");
            exit;
        }

        try {
            // id_empleado quemado a 1 (Admin) como tu diseño base
            $venta_id = $this->ventaModel->create($id_cliente, 1, $items);
            $_SESSION['success'] = "Venta #$venta_id procesada correctamente.";
            header("Location: " . BASE_URL . "ventas");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al procesar la venta: " . $e->getMessage();
            header("Location: " . BASE_URL . "ventas/crear");
        }
        exit;
    }

    public function ver($id)
    {
        $venta = $this->ventaModel->find($id);
        if (!$venta) {
            $_SESSION['error'] = "Venta no encontrada.";
            header("Location: " . BASE_URL . "ventas");
            exit;
        }
        $detalles = $this->ventaModel->getDetalles($id);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/ventas/ver.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function anular($id)
    {
        try {
            $this->ventaModel->anular($id);
            $_SESSION['success'] = "La venta ha sido anulada y el stock fue devuelto al inventario.";
        } catch (Exception $e) {
            $_SESSION['error'] = "No se pudo anular la venta.";
        }
        header("Location: " . BASE_URL . "ventas/ver/" . $id);
        exit;
    }
}
