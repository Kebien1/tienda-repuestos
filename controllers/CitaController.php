<?php
class CitaController
{
    private $citaModel;
    private $usuarioModel;
    private $diagnosticoModel;

    public function __construct()
    {
        $this->citaModel = new Cita();
        $this->usuarioModel = new Usuario();
        $this->diagnosticoModel = new Diagnostico();
    }

    public function index()
    {
        $citas = $this->citaModel->getAll();
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/citas/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function crear()
    {
        // Obtener clientes (rol 5) y mecánicos (rol 3) de tu BD real
        $db = Database::getInstance()->getConnection();
        $clientes = $db->query("SELECT id_usuario, nombre FROM usuario WHERE id_rol = 5")->fetchAll();
        $mecanicos = $db->query("SELECT id_usuario, nombre FROM usuario WHERE id_rol = 3")->fetchAll();

        $id_diagnostico = $_GET['diagnostico'] ?? null;
        $diagnostico = $id_diagnostico ? $this->diagnosticoModel->find($id_diagnostico) : null;

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/citas/crear.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function guardar()
    {
        $data = [
            'id_diagnostico' => $_POST['id_diagnostico'] ?? null,
            'id_cliente' => $_POST['id_cliente'] ?? null,
            'id_mecanico' => $_POST['id_mecanico'] ?? null,
            'marca_vehiculo' => $_POST['marca_vehiculo'] ?? '',
            'modelo_vehiculo' => $_POST['modelo_vehiculo'] ?? '',
            'anio_vehiculo' => $_POST['anio_vehiculo'] ?? null,
            'placa' => $_POST['placa'] ?? '',
            'tipo_servicio' => $_POST['tipo_servicio'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'fecha_cita' => $_POST['fecha_cita'] ?? '',
            'hora_cita' => $_POST['hora_cita'] ?? ''
        ];

        if (empty($data['id_cliente']) || empty($data['fecha_cita']) || empty($data['hora_cita'])) {
            $_SESSION['error'] = "Debe completar cliente, fecha y hora obligatoriamente.";
            header("Location: " . BASE_URL . "citas/crear");
            exit;
        }

        $this->citaModel->create($data);
        if ($data['id_diagnostico']) {
            $this->diagnosticoModel->actualizarEstado($data['id_diagnostico'], 'Pendiente de revisión');
        }

        $_SESSION['success'] = "Cita registrada correctamente.";
        header("Location: " . BASE_URL . "citas");
    }

    public function editar($id)
    {
        $cita = $this->citaModel->find($id);
        $db = Database::getInstance()->getConnection();
        $clientes = $db->query("SELECT id_usuario, nombre FROM usuario WHERE id_rol = 5")->fetchAll();
        $mecanicos = $db->query("SELECT id_usuario, nombre FROM usuario WHERE id_rol = 3")->fetchAll();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/citas/editar.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function actualizar($id)
    {
        $data = $_POST; // En un entorno real sanitizar
        $this->citaModel->update($id, $data);
        $_SESSION['success'] = "Cita actualizada correctamente.";
        header("Location: " . BASE_URL . "citas");
    }

    public function eliminar($id)
    {
        $this->citaModel->delete($id);
        $_SESSION['success'] = "Cita eliminada correctamente.";
        header("Location: " . BASE_URL . "citas");
    }
}
