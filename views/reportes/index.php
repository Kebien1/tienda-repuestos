<?php

/** @var array $resumen */
?>
<div class="panel">
    <div class="panel-header" style="border-bottom: none; margin-bottom: 0;">
        <h2>Dashboard de Reportes</h2>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
        <a href="<?= BASE_URL ?>reportes" class="btn btn-primary">Resumen General</a>
        <a href="<?= BASE_URL ?>reportes/productos" class="btn btn-secondary">Productos más Vendidos</a>
        <a href="<?= BASE_URL ?>reportes/fechas" class="btn btn-secondary">Ventas por Fecha</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: var(--accent-success-bg); border: 1px solid #a7f3d0; padding: 25px; border-radius: 12px; text-align: center;">
            <h3 style="color: var(--accent-success-text); font-size: 1.2rem; margin-bottom: 10px;">Ingresos Totales</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--text-primary);">Bs. <?= number_format($resumen['total'] ?? 0, 2) ?></p>
        </div>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 25px; border-radius: 12px; text-align: center;">
            <h3 style="color: #1d4ed8; font-size: 1.2rem; margin-bottom: 10px;">Ventas Completadas</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--text-primary);"><?= $resumen['cantidad'] ?? 0 ?></p>
        </div>

        <div style="background: #fef3c7; border: 1px solid #fde68a; padding: 25px; border-radius: 12px; text-align: center;">
            <h3 style="color: #b45309; font-size: 1.2rem; margin-bottom: 10px;">Venta Más Alta</h3>
            <p style="font-size: 2.5rem; font-weight: bold; color: var(--text-primary);">Bs. <?= number_format($resumen['maximo'] ?? 0, 2) ?></p>
        </div>
    </div>
</div>