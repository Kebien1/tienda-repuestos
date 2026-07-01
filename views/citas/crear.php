<?php

/** * @var array $clientes 
 * @var array $mecanicos 
 * @var array|null $diagnostico 
 */
?>
<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Agendar Nueva Cita</h2>
        <a href="<?= BASE_URL ?>citas" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($diagnostico)): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            <span>✓</span> Vinculando esta cita con el Diagnóstico IA #<?= $diagnostico['id_diagnostico'] ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>citas/guardar" method="POST">
        <input type="hidden" name="id_diagnostico" value="<?= isset($diagnostico) ? $diagnostico['id_diagnostico'] : '' ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Cliente *</label>
                <select name="id_cliente" required class="form-control">
                    <option value="">Seleccione un cliente...</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id_usuario'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mecánico Asignado</label>
                <select name="id_mecanico" class="form-control">
                    <option value="">Sin asignar aún...</option>
                    <?php foreach ($mecanicos as $m): ?>
                        <option value="<?= $m['id_usuario'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Marca Vehículo *</label>
                <input type="text" name="marca_vehiculo" class="form-control" value="<?= isset($diagnostico) ? htmlspecialchars($diagnostico['marca_vehiculo']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Modelo Vehículo *</label>
                <input type="text" name="modelo_vehiculo" class="form-control" value="<?= isset($diagnostico) ? htmlspecialchars($diagnostico['modelo_vehiculo']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Año</label>
                <input type="number" name="anio_vehiculo" class="form-control" value="<?= isset($diagnostico) ? htmlspecialchars($diagnostico['anio_vehiculo']) : '' ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Placa / Matrícula *</label>
                <input type="text" name="placa" class="form-control" value="<?= isset($diagnostico) ? htmlspecialchars($diagnostico['placa']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Tipo de Servicio *</label>
                <select name="tipo_servicio" class="form-control" required>
                    <option value="Mantenimiento general">Mantenimiento general</option>
                    <option value="Reparación de motor">Reparación de motor</option>
                    <option value="Revisión eléctrica">Revisión eléctrica</option>
                    <option value="Frenos y suspensión">Frenos y suspensión</option>
                    <option value="Diagnóstico IA" <?= isset($diagnostico) ? 'selected' : '' ?>>Revisión post Diagnóstico IA</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Fecha de Cita *</label>
                <input type="date" name="fecha_cita" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Hora de Cita *</label>
                <input type="time" name="hora_cita" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label>Descripción / Síntomas</label>
            <textarea name="descripcion" rows="4" class="form-control" placeholder="Describa el problema del vehículo..."><?= isset($diagnostico) ? "SÍNTOMA REPORTADO: " . htmlspecialchars($diagnostico['sintoma']) . "\n\nRECOMENDACIÓN IA: " . htmlspecialchars($diagnostico['recomendacion']) : '' ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Registrar Cita</button>
    </form>
</div>