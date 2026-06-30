<?php

/** @var array $ventas */
?>
<div class="panel">
    <div class="panel-header">
        <h2>Historial de Ventas</h2>
        <a href="<?= BASE_URL ?>ventas/crear" class="btn btn-primary">+ Nueva Venta</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><span>✓</span> <?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nº Venta</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Atendido por</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No hay ventas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td><strong>#<?= $v['id_venta'] ?></strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td><?= htmlspecialchars($v['cliente_nombre'] ?? 'Consumidor Final') ?></td>
                            <td><?= htmlspecialchars($v['empleado_nombre'] ?? 'Sistema') ?></td>
                            <td><strong>Bs. <?= number_format($v['total'], 2) ?></strong></td>
                            <td>
                                <?php if ($v['estado'] === 'completada'): ?>
                                    <span class="badge badge-completed">Completada</span>
                                <?php elseif ($v['estado'] === 'pendiente'): ?>
                                    <span class="badge badge-pending">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Anulada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>ventas/ver/<?= $v['id_venta'] ?>" class="btn btn-secondary btn-sm">Ver Detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>