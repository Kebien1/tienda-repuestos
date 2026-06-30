<?php

class OrdenCompraController {
    private $ordenModel;
    private $proveedorModel;
    private $productoModel;
    private $detalleModel;

    public function __construct() {
        $this->ordenModel = new OrdenCompra();
        $this->proveedorModel = new Proveedor();
        $this->productoModel = new Producto();
        $this->detalleModel = new DetalleOrden();
    }

    // Listado de órdenes de compra
    public function index() {
        $ordenes = $this->ordenModel->getAll();

        // Obtener productos con stock bajo para mostrar alertas en el panel de control
        $lowStockProducts = $this->productoModel->getLowStockProducts();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/ordenes/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Formulario de creación de orden
    public function crear() {
        $proveedores = $this->proveedorModel->getAll();
        $productos = $this->productoModel->getAll();
        $lowStockProducts = $this->productoModel->getLowStockProducts();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/ordenes/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Guardar orden de compra con sus detalles
    public function guardar() {
        $id_proveedor = $_POST['id_proveedor'] ?? '';
        $productos_seleccionados = $_POST['productos'] ?? []; // Array de IDs de producto
        $cantidades = $_POST['cantidades'] ?? []; // Array de cantidades indexado por id_producto

        if (empty($id_proveedor) || empty($productos_seleccionados)) {
            $_SESSION['error'] = "Debe seleccionar un proveedor y al menos un producto.";
            header("Location: /ordenes/crear");
            exit;
        }

        // Construir la lista de ítems para guardar
        $items = [];
        foreach ($productos_seleccionados as $prod_id) {
            $cantidad = (int)($cantidades[$prod_id] ?? 0);
            if ($cantidad <= 0) {
                continue;
            }

            // Buscar información del producto para obtener el precio
            $producto = $this->productoModel->find($prod_id);
            if ($producto) {
                $items[] = [
                    'id_producto' => $prod_id,
                    'cantidad' => $cantidad,
                    'precio_unit' => $producto['precio']
                ];
            }
        }

        if (empty($items)) {
            $_SESSION['error'] = "Las cantidades para los productos seleccionados deben ser mayores a 0.";
            header("Location: /ordenes/crear");
            exit;
        }

        try {
            // id_empleado = 1 (admin por defecto)
            $orden_id = $this->ordenModel->create($id_proveedor, $items, 1);
            $_SESSION['success'] = "Orden de compra #$orden_id registrada correctamente en estado Pendiente.";
            header("Location: /ordenes");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al registrar la orden de compra: " . $e->getMessage();
            header("Location: /ordenes/crear");
        }
        exit;
    }

    // Ver detalles de una orden de compra
    public function ver($id) {
        $orden = $this->ordenModel->find($id);
        if (!$orden) {
            $_SESSION['error'] = "Orden de compra no encontrada.";
            header("Location: /ordenes");
            exit;
        }

        $detalles = $this->detalleModel->getByOrdenId($id);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/ordenes/ver.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Actualizar el estado de la orden
    public function actualizarEstado($id) {
        $nuevoEstado = $_POST['estado'] ?? '';

        if (!in_array($nuevoEstado, ['pendiente', 'recibida', 'cancelada'])) {
            $_SESSION['error'] = "Estado no válido.";
            header("Location: /ordenes/ver/" . $id);
            exit;
        }

        try {
            $this->ordenModel->updateStatus($id, $nuevoEstado);
            $_SESSION['success'] = "Estado de orden actualizado a '$nuevoEstado' correctamente.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al actualizar estado: " . $e->getMessage();
        }

        header("Location: /ordenes/ver/" . $id);
        exit;
    }
}
