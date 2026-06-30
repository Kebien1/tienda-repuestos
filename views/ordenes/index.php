<div class="panel">
    <!-- Sección de Alertas de Stock Bajo -->
    <?php if (!empty($lowStockProducts)): ?>
        <div class="stock-alerts-card">
            <h4>⚠ Alerta de Repuestos con Stock Bajo</h4>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.75rem;">
                Los siguientes productos requieren reabastecimiento urgente mediante una orden de compra:
            </p>
            <?php foreach ($lowStockProducts as $prod): ?>
                <div class="stock-alert-item">
                    <span>• <?= htmlspecialchars($prod['nombre']) ?> (<?= htmlspecialchars($prod['marca'] ?? '') ?>)</span>
                    <span>Stock: <strong class="stock-alert-badge"><?= $prod['stock'] ?></strong> / Mínimo: <strong><?= $prod['stock_minimo'] ?></strong></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="panel-header">
        <h2>Órdenes de Compra</h2>
        <a href="<?= BASE_URL ?>ordenes/crear" class="btn btn-primary">+ Generar Orden de Compra</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <span>✓</span> <?= $_SESSION['success'];
                            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning">
            <span>⚠</span> <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nº Orden</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Estado de Seguimiento</th>
                    <th>Fecha de Generación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ordenes)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary);">No se han generado órdenes de compra aún.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ordenes as $orden): ?>
                        <tr>
                            <td><strong>#<?= $orden['id_orden'] ?></strong></td>
                            <td><?= htmlspecialchars($orden['proveedor_nombre']) ?></td>
                            <td><strong>Bs. <?= number_format($orden['total'], 2) ?></strong></td>
                            <td>
                                <?php if ($orden['estado'] === 'pendiente'): ?>
                                    <span class="badge badge-pending">Pendiente</span>
                                <?php elseif ($orden['estado'] === 'recibida'): ?>
                                    <span class="badge badge-completed">Recibida</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Cancelada</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($orden['fecha'])) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>ordenes/ver/<?= $orden['id_orden'] ?>" class="btn btn-secondary btn-sm">Seguimiento / Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>