<?php

/** @var array $citas */ ?>
<div class="panel">
    <div class="panel-header">
        <h2>Gestión de Citas de Servicio</h2>
        <a href="<?= BASE_URL ?>citas/crear" class="btn btn-primary">+ Nueva Cita</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><span>✓</span> <?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nº Cita</th>
                    <th>Cliente</th>
                    <th>Vehículo / Placa</th>
                    <th>Fecha y Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><strong>#<?= $cita['id_cita'] ?></strong></td>
                        <td><?= htmlspecialchars($cita['cliente_nombre'] ?? 'Sin asignar') ?></td>
                        <td>
                            <?= htmlspecialchars($cita['marca_vehiculo'] . ' ' . $cita['modelo_vehiculo']) ?><br>
                            <small style="color: var(--text-secondary);"><?= htmlspecialchars($cita['placa']) ?></small>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($cita['fecha_cita'])) ?><br>
                            <small style="color: var(--text-secondary);"><?= date('H:i', strtotime($cita['hora_cita'])) ?></small>
                        </td>
                        <td>
                            <?php
                            $badge = 'badge-pending';
                            if ($cita['estado'] === 'Confirmada' || $cita['estado'] === 'Finalizada') $badge = 'badge-completed';
                            if ($cita['estado'] === 'Cancelada') $badge = 'badge-cancelled';
                            ?>
                            <span class="badge <?= $badge ?>"><?= $cita['estado'] ?></span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>citas/editar/<?= $cita['id_cita'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                <a href="<?= BASE_URL ?>citas/eliminar/<?= $cita['id_cita'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta cita?');">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($citas)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary);">No hay citas registradas en el sistema.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>