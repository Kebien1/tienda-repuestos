<?php

/** * @var array $ventas 
 * @var string $fecha_inicio
 * @var string $fecha_fin
 */
?>
<div class="panel">
    <div class="panel-header" style="border-bottom: none; margin-bottom: 0;">
        <h2>Reporte: Ventas por Rango de Fechas</h2>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
        <a href="<?= BASE_URL ?>reportes" class="btn btn-secondary">Resumen General</a>
        <a href="<?= BASE_URL ?>reportes/productos" class="btn btn-secondary">Productos más Vendidos</a>
        <a href="<?= BASE_URL ?>reportes/fechas" class="btn btn-primary">Ventas por Fecha</a>
    </div>

    <form method="GET" action="<?= BASE_URL ?>reportes/fechas" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 30px; align-items: flex-end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Desde Fecha:</label>
            <input type="date" name="inicio" value="<?= htmlspecialchars($fecha_inicio ?? '') ?>" class="form-control" required>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Hasta Fecha:</label>
            <input type="date" name="fin" value="<?= htmlspecialchars($fecha_fin ?? '') ?>" class="form-control" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Generar Reporte</button>
        </div>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nº Venta</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Atendido por</th>
                    <th>Estado</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No se encontraron ventas en este rango de fechas.</td>
                    </tr>
                <?php else: ?>
                    <?php $gran_total = 0;
                    foreach ($ventas as $v): ?>
                        <?php if ($v['estado'] === 'completada') $gran_total += $v['total']; ?>
                        <tr <?= $v['estado'] === 'anulada' ? 'style="opacity: 0.6;"' : '' ?>>
                            <td><a href="<?= BASE_URL ?>ventas/ver/<?= $v['id_venta'] ?>">#<?= $v['id_venta'] ?></a></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td><?= htmlspecialchars($v['cliente_nombre'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($v['empleado_nombre'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge <?= $v['estado'] === 'completada' ? 'badge-completed' : 'badge-cancelled' ?>">
                                    <?= ucfirst($v['estado']) ?>
                                </span>
                            </td>
                            <td>Bs. <?= number_format($v['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background: #f9fafb;">
                        <td colspan="5" style="text-align: right; font-weight: bold; padding: 15px;">TOTAL VENTAS COMPLETADAS EN EL PERIODO:</td>
                        <td style="color: var(--accent-success-text); font-weight: bold; padding: 15px; font-size: 1.1rem;">Bs. <?= number_format($gran_total, 2) ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>