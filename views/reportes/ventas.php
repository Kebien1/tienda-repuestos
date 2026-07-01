<?php

/** @var array $ventas */ ?>
<div class="panel">
    <div class="panel-header">
        <h2>Reporte Detallado de Ventas</h2>
        <a href="<?= BASE_URL ?>reportes/dashboard" class="btn btn-secondary btn-sm">Volver al Dashboard</a>
    </div>

    <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px;">
        <div class="form-group" style="margin: 0;">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="">Todos</option>
                <option value="completada" <?= ($_GET['estado'] ?? '') == 'completada' ? 'selected' : '' ?>>Completada</option>
                <option value="pendiente" <?= ($_GET['estado'] ?? '') == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="cancelada" <?= ($_GET['estado'] ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
        <a href="<?= BASE_URL ?>reportes/ventas" class="btn btn-secondary">Limpiar</a>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Fecha</th>
                    <th>Total (Bs)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalRecaudado = 0; ?>
                <?php foreach ($ventas as $v): ?>
                    <?php if ($v['estado'] == 'completada') $totalRecaudado += $v['total']; ?>
                    <tr>
                        <td><strong>#<?= $v['id_venta'] ?></strong></td>
                        <td><?= htmlspecialchars($v['cliente_nombre'] ?? 'Mostrador') ?></td>
                        <td><?= htmlspecialchars($v['empleado_nombre'] ?? 'Sistema') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td><?= number_format($v['total'], 2) ?></td>
                        <td>
                            <span class="badge <?= $v['estado'] == 'completada' ? 'badge-completed' : ($v['estado'] == 'cancelada' ? 'badge-cancelled' : 'badge-pending') ?>">
                                <?= ucfirst($v['estado']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No se encontraron ventas con los filtros actuales.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($ventas)): ?>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total Recaudado (Completadas):</strong></td>
                        <td colspan="2"><strong>Bs. <?= number_format($totalRecaudado, 2) ?></strong></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>