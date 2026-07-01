<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Nuevo Diagnóstico con IA</h2>
        <a href="<?= BASE_URL ?>diagnostico/historial" class="btn btn-secondary btn-sm">Ver Historial</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <p style="margin-bottom: 20px; color: var(--text-secondary);">Describe los síntomas de tu vehículo y nuestra IA especializada analizará el problema para sugerirte el mejor curso de acción.</p>

    <form action="<?= BASE_URL ?>diagnostico/analizar" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Marca del Vehículo *</label>
                <input type="text" name="marca_vehiculo" class="form-control" required placeholder="Ej: Toyota">
            </div>
            <div class="form-group">
                <label>Modelo del Vehículo *</label>
                <input type="text" name="modelo_vehiculo" class="form-control" required placeholder="Ej: Corolla">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Año</label>
                <input type="number" name="anio_vehiculo" class="form-control" placeholder="Ej: 2018">
            </div>
            <div class="form-group">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control" placeholder="Ej: 1234ABC">
            </div>
        </div>

        <div class="form-group">
            <label>Descripción detallada del Síntoma *</label>
            <textarea name="sintoma" rows="5" class="form-control" required placeholder="Describe los ruidos, fallas, o comportamientos extraños del vehículo..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">⚙️ Analizar con Inteligencia Artificial</button>
    </form>
</div>