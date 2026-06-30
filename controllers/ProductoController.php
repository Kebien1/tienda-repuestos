<?php

class ProductoController
{
    private $productoModel;
    private $vehiculoModel;

    public function __construct()
    {
        $this->productoModel = new Producto();
        $this->vehiculoModel = new Vehiculo();
    }

    public function index()
    {
        $busqueda = trim($_GET['busqueda'] ?? '');
        $productos = $this->productoModel->listarConFiltro($busqueda);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/productos/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function crear()
    {
        $categorias = $this->vehiculoModel->getCategorias();
        $marcas_producto = $this->vehiculoModel->getMarcasProducto();
        $tipos_vehiculo = $this->vehiculoModel->getTiposVehiculo();

        $ids_actuales = []; // Vacío porque es nuevo

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/productos/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function editar($id)
    {
        $producto = $this->productoModel->obtenerDetalleCompleto($id);
        if (!$producto) {
            $_SESSION['error'] = "Producto no encontrado.";
            header("Location: " . BASE_URL . "productos");
            exit;
        }

        $categorias = $this->vehiculoModel->getCategorias();
        $marcas_producto = $this->vehiculoModel->getMarcasProducto();
        $tipos_vehiculo = $this->vehiculoModel->getTiposVehiculo();

        // Obtener IDs de vehículos compatibles
        $ids_actuales = $this->productoModel->obtenerVehiculosCompatibles($id);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/productos/editar.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function guardar()
    {
        $this->procesarFormulario();
    }

    public function actualizar($id)
    {
        $this->procesarFormulario($id);
    }

    private function procesarFormulario($id = null)
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $id_marca_producto = (int)($_POST['id_marca_producto'] ?? 0);
        $id_categoria = (int)($_POST['id_categoria'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $vehiculos_ids_array = $_POST['vehiculos'] ?? [];

        // Convertir array de vehiculos a string separado por comas
        $vehiculos_ids = implode(',', array_map('intval', $vehiculos_ids_array));

        if (empty($nombre) || empty($id_marca_producto) || empty($id_categoria) || $precio <= 0) {
            $_SESSION['error'] = "Complete todos los campos obligatorios.";
            $url = $id ? "productos/editar/$id" : "productos/crear";
            header("Location: " . BASE_URL . $url);
            exit;
        }

        // --- SUBIDA DE IMAGEN --- (Se guarda en public/uploads)
        $imagen = null;
        if ($id) {
            $productoActual = $this->productoModel->obtenerDetalleCompleto($id);
            $imagen = $productoActual['imagen']; // Conservar imagen vieja si no se sube una nueva
        }

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $dir_uploads = __DIR__ . '/../public/uploads/';
                if (!is_dir($dir_uploads)) mkdir($dir_uploads, 0777, true);

                $nombre_imagen = 'producto_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $dir_uploads . $nombre_imagen);
                $imagen = $nombre_imagen;
            }
        }

        $data = [
            'nombre' => $nombre,
            'id_marca_producto' => $id_marca_producto,
            'id_categoria' => $id_categoria,
            'precio' => $precio,
            'stock' => $stock,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        if ($id) {
            $result = $this->productoModel->actualizar($id, $data, $vehiculos_ids);
            $mensaje = "Producto actualizado correctamente.";
        } else {
            $result = $this->productoModel->crear($data, $vehiculos_ids);
            $mensaje = "Producto creado correctamente.";
        }

        if ($result) {
            $_SESSION['success'] = $mensaje;
        } else {
            $_SESSION['error'] = "Ocurrió un error al guardar el producto.";
        }

        header("Location: " . BASE_URL . "productos");
        exit;
    }

    public function cambiarEstado($id)
    {
        if ($this->productoModel->eliminarLogico($id)) {
            $_SESSION['success'] = "Producto desactivado correctamente.";
        }
        header("Location: " . BASE_URL . "productos");
        exit;
    }
}
