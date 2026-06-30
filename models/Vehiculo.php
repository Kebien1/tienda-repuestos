<?php

class Vehiculo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- MÉTODOS EXISTENTES ---
    public function getCategorias()
    {
        $stmt = $this->db->query("CALL sp_obtener_categorias()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function getMarcasProducto()
    {
        $stmt = $this->db->query("CALL sp_obtener_marcas_producto()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function getTiposVehiculo()
    {
        $stmt = $this->db->query("CALL sp_obtener_tipos_vehiculo()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    // --- NUEVOS MÉTODOS PARA CRUD DE VEHÍCULOS ---
    public function getMarcasVehiculo()
    {
        $stmt = $this->db->query("CALL sp_listar_marcas_vehiculo()");
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function getModelosVehiculo($id_marca = 0)
    {
        $stmt = $this->db->prepare("CALL sp_listar_modelos_vehiculo(?)");
        $stmt->execute([$id_marca]);
        $res = $stmt->fetchAll();
        $stmt->closeCursor();
        return $res;
    }

    public function obtenerMarca($id)
    {
        $stmt = $this->db->prepare("CALL sp_obtener_marca_vehiculo(?)");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res;
    }

    public function obtenerModelo($id)
    {
        $stmt = $this->db->prepare("CALL sp_obtener_modelo_vehiculo(?)");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res;
    }

    public function crearMarca($nombre, $id_tipo)
    {
        $stmt = $this->db->prepare("CALL sp_marca_vehiculo_insertar(?, ?)");
        $stmt->execute([$nombre, $id_tipo]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res ? true : false;
    }

    public function actualizarMarca($id, $nombre, $id_tipo)
    {
        $stmt = $this->db->prepare("CALL sp_marca_vehiculo_actualizar(?, ?, ?)");
        $stmt->execute([$id, $nombre, $id_tipo]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res ? true : false;
    }

    public function eliminarMarca($id)
    {
        $stmt = $this->db->prepare("CALL sp_marca_vehiculo_eliminar(?)");
        $stmt->execute([$id]);
        $stmt->closeCursor();
        return true;
    }

    public function crearModelo($nombre, $id_marca, $anio_inicio, $anio_fin)
    {
        $ai = empty($anio_inicio) ? null : $anio_inicio;
        $af = empty($anio_fin) ? null : $anio_fin;
        $stmt = $this->db->prepare("CALL sp_modelo_vehiculo_insertar(?, ?, ?, ?)");
        $stmt->execute([$nombre, $id_marca, $ai, $af]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res ? true : false;
    }

    public function actualizarModelo($id, $nombre, $id_marca, $anio_inicio, $anio_fin)
    {
        $ai = empty($anio_inicio) ? null : $anio_inicio;
        $af = empty($anio_fin) ? null : $anio_fin;
        $stmt = $this->db->prepare("CALL sp_modelo_vehiculo_actualizar(?, ?, ?, ?, ?)");
        $stmt->execute([$id, $nombre, $id_marca, $ai, $af]);
        $res = $stmt->fetch();
        $stmt->closeCursor();
        return $res ? true : false;
    }

    public function eliminarModelo($id)
    {
        $stmt = $this->db->prepare("CALL sp_modelo_vehiculo_eliminar(?)");
        $stmt->execute([$id]);
        $stmt->closeCursor();
        return true;
    }
}
