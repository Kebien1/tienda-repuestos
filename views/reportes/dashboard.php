<?php

/** @var array $resumen (viene del SP general)
 * @var array $topProductos (viene del SP top productos)
 */
?>
<div class="panel">
    <div class="panel-header">
        <h2>Dashboard de Rendimiento</h2>
        <a href="<?= BASE_URL ?>reportes/ventas" class="btn btn-secondary btn-sm">Ver Historial de Ventas</a>
    </div>

    <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px;">
        <div class="form-group" style="margin: 0;">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'))) ?>">
        </div>
        <div class="form-group" style="margin: 0;">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($_GET['fecha_fin'] ?? date('Y-m-d')) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Filtrar Periodo</button>
    </form>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
        <div class="panel" style="text-align: center; border-top: 4px solid #28a745;">
            <h3 style="color: var(--text-secondary); font-size: 14px;">Total Ventas</h3>
            <p style="font-size: 24px; font-weight: bold;"><?= $resumen['total_ventas'] ?? '0' ?></p>
        </div>
        <div class="panel" style="text-align: center; border-top: 4px solid #007bff;">
            <h3 style="color: var(--text-secondary); font-size: 14px;">Ingresos (Bs)</h3>
            <p style="font-size: 24px; font-weight: bold;"><?= number_format($resumen['ingresos_totales'] ?? 0, 2) ?></p>
        </div>
        <div class="panel" style="text-align: center; border-top: 4px solid #17a2b8;">
            <h3 style="color: var(--text-secondary); font-size: 14px;">Productos en Stock</h3>
            <p style="font-size: 24px; font-weight: bold;"><?= $resumen['productos_stock'] ?? '0' ?></p>
        </div>
    </div>

    <h3>Top 5 Repuestos Más Vendidos</h3>
    <div class="table-container" style="margin-top: 15px;">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Cantidad Vendida</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($topProductos)): foreach ($topProductos as $tp):
                        // Mapeo dinámico: buscamos las llaves más comunes que pueda devolver tu SP
                        $nombre = $tp['nombre'] ?? $tp['producto'] ?? 'Desconocido';
                        $categoria = $tp['categoria'] ?? 'Repuestos';
                        $cantidad = $tp['cantidad'] ?? $tp['cantidad_vendida'] ?? $tp['total_vendido'] ?? $tp['total'] ?? 0;
                ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($nombre) ?></strong></td>
                            <td><?= htmlspecialchars($categoria) ?></td>
                            <td><span class="badge badge-completed"><?= htmlspecialchars($cantidad) ?> uds</span></td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center; color: var(--text-secondary);">No hay datos en este periodo.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>