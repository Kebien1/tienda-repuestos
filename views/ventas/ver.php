<?php

/** @var array $venta */
/** @var array $detalles */
?>
<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Detalle de la Venta #<?= $venta['id_venta'] ?></h2>
        <div>
            <?php if ($venta['estado'] !== 'anulada'): ?>
                <a href="<?= BASE_URL ?>ventas/anular/<?= $venta['id_venta'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de anular esta venta? El stock será devuelto al inventario.');">Anular Venta</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>ventas" class="btn btn-secondary btn-sm">Volver</a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><span>✓</span> <?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div style="background: rgba(15, 23, 42, 0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
            <h3 style="color: var(--accent-primary); font-size: 1.1rem; margin-bottom: 1rem;">Información General</h3>
            <p style="margin-bottom: 0.5rem;"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Atendido por:</strong> <?= htmlspecialchars($venta['empleado_nombre'] ?? 'N/A') ?></p>
            <p style="margin-bottom: 0.5rem;">
                <strong>Estado:</strong>
                <?php if ($venta['estado'] === 'completada'): ?>
                    <span class="badge badge-completed">Completada</span>
                <?php else: ?>
                    <span class="badge badge-cancelled">Anulada</span>
                <?php endif; ?>
            </p>
        </div>
        <div style="background: rgba(15, 23, 42, 0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
            <h3 style="color: var(--accent-primary); font-size: 1.1rem; margin-bottom: 1rem;">Datos del Cliente</h3>
            <p style="margin-bottom: 0.5rem;"><strong>Nombre:</strong> <?= htmlspecialchars($venta['cliente_nombre'] ?? 'N/A') ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Correo:</strong> <?= htmlspecialchars($venta['cliente_correo'] ?? 'N/A') ?></p>
        </div>
    </div>

    <h3>Productos Vendidos</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Producto</th>
                    <th>Precio Unit.</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $item): ?>
                    <tr <?= $venta['estado'] === 'anulada' ? 'style="opacity: 0.6; text-decoration: line-through;"' : '' ?>>
                        <td><?= htmlspecialchars($item['producto_marca'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($item['producto_nombre']) ?></td>
                        <td>Bs. <?= number_format($item['precio_unit'], 2) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td><strong>Bs. <?= number_format($item['subtotal'], 2) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="background: #f9fafb; font-size: 1.1rem;">
                    <td colspan="4" style="text-align: right; font-weight: bold; padding: 1.25rem;">TOTAL DE LA VENTA:</td>
                    <td style="padding: 1.25rem; color: var(--accent-primary);"><strong>Bs. <?= number_format($venta['total'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>