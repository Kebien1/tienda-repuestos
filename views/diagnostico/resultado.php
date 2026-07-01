<?php

/** @var array $diagnostico */ ?>
<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Resultado IA: Diagnóstico #<?= $diagnostico['id_diagnostico'] ?></h2>
        <a href="<?= BASE_URL ?>diagnostico/historial" class="btn btn-secondary btn-sm">Volver al Historial</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><span>✓</span> <?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="panel" style="box-shadow: none; border: 1px solid var(--border-color);">
            <h3 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px;">Datos del Vehículo</h3>
            <p><strong>Marca/Modelo:</strong> <?= htmlspecialchars($diagnostico['marca_vehiculo'] . ' ' . $diagnostico['modelo_vehiculo']) ?></p>
            <p><strong>Año:</strong> <?= htmlspecialchars($diagnostico['anio_vehiculo'] ?? 'N/A') ?></p>
            <p><strong>Placa:</strong> <?= htmlspecialchars($diagnostico['placa'] ?? 'N/A') ?></p>
            <p style="margin-top: 10px;"><strong>Síntoma Reportado:</strong><br><?= nl2br(htmlspecialchars($diagnostico['sintoma'])) ?></p>
        </div>

        <div class="panel" style="box-shadow: none; border: 1px solid var(--border-color); background-color: #f9fbfd;">
            <h3 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; color: #0056b3;">Análisis de la IA</h3>
            <p><strong>Componente Afectado:</strong> <span style="color: #d9534f; font-weight: bold;"><?= htmlspecialchars($diagnostico['componente_detectado']) ?></span></p>
            <p><strong>Posible Falla:</strong> <?= htmlspecialchars($diagnostico['posible_falla']) ?></p>
            <p><strong>Prioridad:</strong> <?= htmlspecialchars($diagnostico['prioridad']) ?></p>
            <p><strong>Especialidad Req.:</strong> <?= htmlspecialchars($diagnostico['especialidad_recomendada']) ?></p>
        </div>
    </div>

    <div class="panel" style="box-shadow: none; border: 1px solid var(--border-color); margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px;">Recomendación Técnica</h3>
        <p><?= nl2br(htmlspecialchars($diagnostico['recomendacion'])) ?></p>
    </div>

    <div style="text-align: right; padding-top: 15px; border-top: 1px solid var(--border-color);">
        <a href="<?= BASE_URL ?>citas/crear?diagnostico=<?= $diagnostico['id_diagnostico'] ?>" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">
            📅 Agendar Revisión para este Diagnóstico
        </a>
    </div>
</div>