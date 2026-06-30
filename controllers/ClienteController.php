<?php

class ClienteController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    // Listado de clientes
    public function index()
    {
        $clientes = $this->usuarioModel->getAllClientes();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Mostrar formulario de creación
    public function crear()
    {
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Registrar nuevo cliente con validación de correo único
    public function guardar()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        // Validación simple
        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            $_SESSION['error'] = "Todos los campos obligatorios son requeridos.";
            header("Location: " . BASE_URL . "clientes/crear");
            exit;
        }

        // Validar correo electrónico único
        $exists = $this->usuarioModel->findByEmail($correo);
        if ($exists) {
            $_SESSION['error'] = "El correo electrónico ya se encuentra registrado.";
            header("Location: " . BASE_URL . "clientes/crear");
            exit;
        }

        $result = $this->usuarioModel->create([
            'nombre' => $nombre,
            'correo' => $correo,
            'contrasena' => $contrasena,
            'id_rol' => 5, // 5 = cliente
            'telefono' => $telefono,
            'direccion' => $direccion
        ]);

        if ($result) {
            $_SESSION['success'] = "Cliente registrado correctamente.";
            header("Location: " . BASE_URL . "clientes");
        } else {
            $_SESSION['error'] = "Error al intentar registrar el cliente.";
            header("Location: " . BASE_URL . "clientes/crear");
        }
        exit;
    }

    // Mostrar formulario de edición
    public function editar($id)
    {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: " . BASE_URL . "clientes");
            exit;
        }

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/editar.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Actualizar cliente
    public function actualizar($id)
    {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: " . BASE_URL . "clientes");
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($nombre) || empty($correo)) {
            $_SESSION['error'] = "Nombre y Correo Electrónico son obligatorios.";
            header("Location: " . BASE_URL . "clientes/editar/" . $id);
            exit;
        }

        // Validar que el correo no esté ocupado por otro usuario
        $exists = $this->usuarioModel->findByEmail($correo);
        if ($exists && $exists['id_usuario'] != $id) {
            $_SESSION['error'] = "El correo electrónico ya está en uso por otro usuario.";
            header("Location: " . BASE_URL . "clientes/editar/" . $id);
            exit;
        }

        $result = $this->usuarioModel->update($id, [
            'nombre' => $nombre,
            'correo' => $correo,
            'contrasena' => $contrasena,
            'telefono' => $telefono,
            'direccion' => $direccion
        ]);

        if ($result) {
            $_SESSION['success'] = "Cliente actualizado correctamente.";
            header("Location: " . BASE_URL . "clientes");
        } else {
            $_SESSION['error'] = "Error al intentar actualizar el cliente.";
            header("Location: " . BASE_URL . "clientes/editar/" . $id);
        }
        exit;
    }

    // Cambiar estado del cliente (activar/desactivar)
    public function cambiarEstado($id)
    {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: " . BASE_URL . "clientes");
            exit;
        }

        if ($this->usuarioModel->cambiarEstado($id)) {
            $nuevoEstado = $cliente['estado'] === 'activo' ? 'desactivado' : 'activado';
            $_SESSION['success'] = "Cliente $nuevoEstado correctamente.";
        } else {
            $_SESSION['error'] = "No se pudo cambiar el estado del cliente.";
        }
        header("Location: " . BASE_URL . "clientes");
        exit;
    }

    // Ver detalles del cliente y su historial
    public function ver($id)
    {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: " . BASE_URL . "clientes");
            exit;
        }

        // Usamos el nuevo método del modelo para obtener las ventas
        $historial = $this->usuarioModel->getHistorialCompras($id);

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/ver.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
