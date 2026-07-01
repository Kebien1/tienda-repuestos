<?php

/** @var array $diagnosticos */ ?>
<div class="panel">
    <div class="panel-header">
        <h2>Historial de Diagnósticos IA</h2>
        <a href="<?= BASE_URL ?>diagnostico" class="btn btn-primary">+ Nuevo Análisis</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Vehículo</th>
                    <th>Síntoma Principal</th>
                    <th>Prioridad IA</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($diagnosticos as $d): ?>
                    <tr>
                        <td><strong>#<?= $d['id_diagnostico'] ?></strong></td>
                        <td><?= htmlspecialchars($d['marca_vehiculo'] . ' ' . $d['modelo_vehiculo']) ?></td>
                        <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($d['sintoma']) ?>
                        </td>
                        <td>
                            <span class="badge <?= strtolower($d['prioridad']) == 'urgente' || strtolower($d['prioridad']) == 'alta' ? 'badge-cancelled' : 'badge-pending' ?>">
                                <?= htmlspecialchars($d['prioridad']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($d['estado']) ?></td>
                        <td><?= date('d/m/Y', strtotime($d['fecha_registro'])) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>diagnostico/resultado/<?= $d['id_diagnostico'] ?>" class="btn btn-secondary btn-sm">Ver Detalles</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>