<?php

class VehiculoController
{
    private $vehiculoModel;

    public function __construct()
    {
        $this->vehiculoModel = new Vehiculo();
    }

    // ==========================================
    // SECCIÓN MARCAS
    // ==========================================
    public function marcas()
    {
        $marcas = $this->vehiculoModel->getMarcasVehiculo();
        $tipos = $this->vehiculoModel->getTiposVehiculo();

        $edit = null;
        if (isset($_GET['editar'])) {
            $edit = $this->vehiculoModel->obtenerMarca($_GET['editar']);
        }

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/vehiculos/marcas.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function guardarMarca()
    {
        $id = (int)($_POST['edit_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $id_tipo = (int)($_POST['id_tipo_vehiculo'] ?? 0);

        if (empty($nombre) || empty($id_tipo)) {
            $_SESSION['error'] = "Complete todos los campos obligatorios.";
        } else {
            if ($id) {
                $this->vehiculoModel->actualizarMarca($id, $nombre, $id_tipo);
                $_SESSION['success'] = "Marca actualizada.";
            } else {
                $this->vehiculoModel->crearMarca($nombre, $id_tipo);
                $_SESSION['success'] = "Marca registrada.";
            }
        }
        header("Location: " . BASE_URL . "vehiculos");
        exit;
    }

    public function eliminarMarca($id)
    {
        $this->vehiculoModel->eliminarMarca($id);
        $_SESSION['success'] = "Marca eliminada correctamente.";
        header("Location: " . BASE_URL . "vehiculos");
        exit;
    }

    // ==========================================
    // SECCIÓN MODELOS
    // ==========================================
    public function modelos()
    {
        $id_marca_filtro = isset($_GET['marca']) ? (int)$_GET['marca'] : 0;
        $modelos = $this->vehiculoModel->getModelosVehiculo($id_marca_filtro);
        $marcas_vehiculo = $this->vehiculoModel->getMarcasVehiculo();

        $edit = null;
        if (isset($_GET['editar'])) {
            $edit = $this->vehiculoModel->obtenerModelo($_GET['editar']);
        }

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/vehiculos/modelos.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function guardarModelo()
    {
        $id = (int)($_POST['edit_id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $id_marca = (int)($_POST['id_marca_vehiculo'] ?? 0);
        $anio_inicio = trim($_POST['anio_inicio'] ?? '');
        $anio_fin = trim($_POST['anio_fin'] ?? '');

        if (empty($nombre) || empty($id_marca)) {
            $_SESSION['error'] = "Complete el nombre y seleccione la marca.";
        } else {
            if ($id) {
                $this->vehiculoModel->actualizarModelo($id, $nombre, $id_marca, $anio_inicio, $anio_fin);
                $_SESSION['success'] = "Modelo actualizado.";
            } else {
                $this->vehiculoModel->crearModelo($nombre, $id_marca, $anio_inicio, $anio_fin);
                $_SESSION['success'] = "Modelo registrado.";
            }
        }
        header("Location: " . BASE_URL . "vehiculos/modelos");
        exit;
    }

    public function eliminarModelo($id)
    {
        $this->vehiculoModel->eliminarModelo($id);
        $_SESSION['success'] = "Modelo eliminado correctamente.";
        header("Location: " . BASE_URL . "vehiculos/modelos");
        exit;
    }
}
