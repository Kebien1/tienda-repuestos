<?php

/** @var array $productos */
?>
<div class="panel">
    <div class="panel-header" style="border-bottom: none; margin-bottom: 0;">
        <h2>Reporte: Productos Más Vendidos</h2>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
        <a href="<?= BASE_URL ?>reportes" class="btn btn-secondary">Resumen General</a>
        <a href="<?= BASE_URL ?>reportes/productos" class="btn btn-primary">Productos más Vendidos</a>
        <a href="<?= BASE_URL ?>reportes/fechas" class="btn btn-secondary">Ventas por Fecha</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th style="text-align: center;">Unidades Vendidas</th>
                    <th style="text-align: right;">Ingreso Generado (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay datos para mostrar.</td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1;
                    foreach ($productos as $p): ?>
                        <tr>
                            <td><strong>#<?= $i++ ?></strong></td>
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($p['marca'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($p['categoria'] ?? 'N/A') ?></td>
                            <td style="text-align: center;">
                                <span class="badge badge-completed" style="font-size: 1rem;"><?= $p['total_vendido'] ?></span>
                            </td>
                            <td style="text-align: right;"><strong><?= number_format($p['total_dinero'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>