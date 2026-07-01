<?php

/** * @var array $cita 
 * @var array $clientes 
 * @var array $mecanicos 
 */
?>
<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Editar Cita #<?= $cita['id_cita'] ?></h2>
        <a href="<?= BASE_URL ?>citas" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <form action="<?= BASE_URL ?>citas/actualizar/<?= $cita['id_cita'] ?>" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Cliente *</label>
                <select name="id_cliente" required class="form-control">
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id_usuario'] ?>" <?= $c['id_usuario'] == $cita['id_cliente'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mecánico Asignado</label>
                <select name="id_mecanico" class="form-control">
                    <option value="">Sin asignar aún...</option>
                    <?php foreach ($mecanicos as $m): ?>
                        <option value="<?= $m['id_usuario'] ?>" <?= $m['id_usuario'] == $cita['id_mecanico'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Marca *</label>
                <input type="text" name="marca_vehiculo" class="form-control" value="<?= htmlspecialchars($cita['marca_vehiculo']) ?>" required>
            </div>
            <div class="form-group">
                <label>Modelo *</label>
                <input type="text" name="modelo_vehiculo" class="form-control" value="<?= htmlspecialchars($cita['modelo_vehiculo']) ?>" required>
            </div>
            <div class="form-group">
                <label>Año</label>
                <input type="number" name="anio_vehiculo" class="form-control" value="<?= htmlspecialchars($cita['anio_vehiculo']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Placa *</label>
                <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($cita['placa']) ?>" required>
            </div>
            <div class="form-group">
                <label>Estado de Cita *</label>
                <select name="estado" class="form-control" required>
                    <?php $estados = ['Pendiente', 'Confirmada', 'En proceso', 'Finalizada', 'Cancelada']; ?>
                    <?php foreach ($estados as $e): ?>
                        <option value="<?= $e ?>" <?= $cita['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Fecha *</label>
                <input type="date" name="fecha_cita" class="form-control" value="<?= $cita['fecha_cita'] ?>" required>
            </div>
            <div class="form-group">
                <label>Hora *</label>
                <input type="time" name="hora_cita" class="form-control" value="<?= $cita['hora_cita'] ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Tipo de Servicio *</label>
            <input type="text" name="tipo_servicio" class="form-control" value="<?= htmlspecialchars($cita['tipo_servicio']) ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción / Síntomas</label>
            <textarea name="descripcion" rows="4" class="form-control"><?= htmlspecialchars($cita['descripcion']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar Cita</button>
    </form>
</div>