<?php

class ProveedorController
{
    private $proveedorModel;

    public function __construct()
    {
        $this->proveedorModel = new Proveedor();
    }

    // Listado de proveedores
    public function index()
    {
        $proveedores = $this->proveedorModel->getAll();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/proveedores/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Formulario de creación
    public function crear()
    {
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/proveedores/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Guardar nuevo proveedor
    public function guardar()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $contacto = trim($_POST['contacto'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($nombre) || empty($contacto) || empty($telefono) || empty($direccion)) {
            $_SESSION['error'] = "Todos los campos obligatorios son requeridos.";
            header("Location: " . BASE_URL . "proveedores/crear");
            exit;
        }

        $result = $this->proveedorModel->create([
            'nombre' => $nombre,
            'contacto' => $contacto,
            'telefono' => $telefono,
            'correo' => $correo,
            'direccion' => $direccion
        ]);

        if ($result) {
            $_SESSION['success'] = "Proveedor registrado correctamente.";
            header("Location: " . BASE_URL . "proveedores");
        } else {
            $_SESSION['error'] = "Error al registrar el proveedor.";
            header("Location: " . BASE_URL . "proveedores/crear");
        }
        exit;
    }

    // Formulario de edición
    public function editar($id)
    {
        $proveedor = $this->proveedorModel->find($id);
        if (!$proveedor) {
            $_SESSION['error'] = "Proveedor no encontrado.";
            header("Location: " . BASE_URL . "proveedores");
            exit;
        }

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/proveedores/editar.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Actualizar proveedor
    public function actualizar($id)
    {
        $proveedor = $this->proveedorModel->find($id);
        if (!$proveedor) {
            $_SESSION['error'] = "Proveedor no encontrado.";
            header("Location: " . BASE_URL . "proveedores");
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $contacto = trim($_POST['contacto'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($nombre) || empty($contacto) || empty($telefono) || empty($direccion)) {
            $_SESSION['error'] = "Todos los campos obligatorios son requeridos.";
            header("Location: " . BASE_URL . "proveedores/editar/" . $id);
            exit;
        }

        $result = $this->proveedorModel->update($id, [
            'nombre' => $nombre,
            'contacto' => $contacto,
            'telefono' => $telefono,
            'correo' => $correo,
            'direccion' => $direccion
        ]);

        if ($result) {
            $_SESSION['success'] = "Proveedor actualizado correctamente.";
            header("Location: " . BASE_URL . "proveedores");
        } else {
            $_SESSION['error'] = "Error al actualizar el proveedor.";
            header("Location: " . BASE_URL . "proveedores/editar/" . $id);
        }
        exit;
    }

    // Cambiar estado del proveedor (activar/desactivar)
    public function cambiarEstado($id)
    {
        $proveedor = $this->proveedorModel->find($id);
        if (!$proveedor) {
            $_SESSION['error'] = "Proveedor no encontrado.";
            header("Location: " . BASE_URL . "proveedores");
            exit;
        }

        if ($this->proveedorModel->cambiarEstado($id)) {
            $nuevoEstado = $proveedor['estado'] === 'activo' ? 'desactivado' : 'activado';
            $_SESSION['success'] = "Proveedor $nuevoEstado correctamente.";
        } else {
            $_SESSION['error'] = "No se pudo cambiar el estado del proveedor.";
        }
        header("Location: " . BASE_URL . "proveedores");
        exit;
    }
}
