<?php

class ClienteController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Listado de clientes
    public function index() {
        $clientes = $this->usuarioModel->getAllClientes();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Mostrar formulario de creación
    public function crear() {
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Registrar nuevo cliente con validación de correo único
    public function guardar() {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        // Validación simple
        if (empty($nombre) || empty($correo) || empty($contrasena)) {
            $_SESSION['error'] = "Todos los campos obligatorios son requeridos.";
            header("Location: /clientes/crear");
            exit;
        }

        // Validar correo electrónico único
        $exists = $this->usuarioModel->findByEmail($correo);
        if ($exists) {
            $_SESSION['error'] = "El correo electrónico ya se encuentra registrado.";
            header("Location: /clientes/crear");
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
            header("Location: /clientes");
        } else {
            $_SESSION['error'] = "Error al intentar registrar el cliente.";
            header("Location: /clientes/crear");
        }
        exit;
    }

    // Mostrar formulario de edición
    public function editar($id) {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: /clientes");
            exit;
        }

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/clientes/editar.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    // Actualizar cliente
    public function actualizar($id) {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: /clientes");
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if (empty($nombre) || empty($correo)) {
            $_SESSION['error'] = "Nombre y Correo Electrónico son obligatorios.";
            header("Location: /clientes/editar/" . $id);
            exit;
        }

        // Validar que el correo no esté ocupado por otro usuario
        $exists = $this->usuarioModel->findByEmail($correo);
        if ($exists && $exists['id_usuario'] != $id) {
            $_SESSION['error'] = "El correo electrónico ya está en uso por otro usuario.";
            header("Location: /clientes/editar/" . $id);
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
            header("Location: /clientes");
        } else {
            $_SESSION['error'] = "Error al intentar actualizar el cliente.";
            header("Location: /clientes/editar/" . $id);
        }
        exit;
    }

    // Eliminar cliente
    public function eliminar($id) {
        $cliente = $this->usuarioModel->find($id);
        if (!$cliente || (int)$cliente['id_rol'] !== 5) {
            $_SESSION['error'] = "Cliente no encontrado.";
            header("Location: /clientes");
            exit;
        }

        if ($this->usuarioModel->delete($id)) {
            $_SESSION['success'] = "Cliente eliminado correctamente.";
        } else {
            $_SESSION['error'] = "No se pudo eliminar el cliente.";
        }
        header("Location: /clientes");
        exit;
    }
}
