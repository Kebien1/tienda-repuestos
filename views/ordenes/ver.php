<?php

/** 
 * @var array $orden 
 * @var array $detalles 
 */
?>
<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Detalle y Seguimiento de Orden de Compra #<?= $orden['id_orden'] ?></h2>
        <a href="<?= BASE_URL ?>ordenes" class="btn btn-secondary btn-sm">Volver al listado</a>
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

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Información de la Orden -->
        <div style="background: rgba(15, 23, 42, 0.2); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--panel-border);">
            <h3 style="color: var(--accent-blue); font-size: 1.1rem; margin-bottom: 1rem;">Resumen de la Orden</h3>
            <p style="margin-bottom: 0.5rem;"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($orden['fecha'])) ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Total de la Orden:</strong> Bs. <?= number_format($orden['total'], 2) ?></p>
            <p style="margin-bottom: 0.5rem;">
                <strong>Estado actual:</strong>
                <?php if ($orden['estado'] === 'pendiente'): ?>
                    <span class="badge badge-pending">Pendiente de Entrega</span>
                <?php elseif ($orden['estado'] === 'recibida'): ?>
                    <span class="badge badge-completed">Recibida / Stock Incrementado</span>
                <?php else: ?>
                    <span class="badge badge-cancelled">Cancelada</span>
                <?php endif; ?>
            </p>
        </div>

        <!-- Información del Proveedor -->
        <div style="background: rgba(15, 23, 42, 0.2); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--panel-border);">
            <h3 style="color: var(--accent-purple); font-size: 1.1rem; margin-bottom: 1rem;">Datos del Proveedor</h3>
            <p style="margin-bottom: 0.5rem;"><strong>Empresa:</strong> <?= htmlspecialchars($orden['proveedor_nombre']) ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Contacto:</strong> <?= htmlspecialchars($orden['proveedor_contacto'] ?? 'N/A') ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Teléfono:</strong> <?= htmlspecialchars($orden['proveedor_telefono'] ?? 'N/A') ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Dirección:</strong> <?= htmlspecialchars($orden['proveedor_direccion'] ?? 'N/A') ?></p>
        </div>
    </div>

    <!-- Actualización de Estado (Seguimiento de Estado) -->
    <div style="background: rgba(255, 255, 255, 0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--panel-border); margin-bottom: 2rem;">
        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Actualizar Estado de Seguimiento</h3>

        <?php if ($orden['estado'] === 'recibida'): ?>
            <div class="alert alert-success" style="margin-bottom: 0;">
                <span>✓</span> Esta orden ya ha sido <strong>Recibida</strong>. El stock de los repuestos fue incrementado automáticamente en el inventario y no se permiten más cambios de estado.
            </div>
        <?php elseif ($orden['estado'] === 'cancelada'): ?>
            <div class="alert alert-warning" style="margin-bottom: 0;">
                <span>⚠</span> Esta orden ha sido <strong>Cancelada</strong> y no se puede modificar.
            </div>
        <?php else: ?>
            <form action="<?= BASE_URL ?>ordenes/estado/<?= $orden['id_orden'] ?>" method="POST" style="display: flex; gap: 1rem; align-items: flex-end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="estado">Seleccionar nuevo estado de seguimiento</label>
                    <select id="estado" name="estado" class="form-control" required>
                        <option value="pendiente" <?= $orden['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente (En tránsito / En espera)</option>
                        <option value="recibida">Recibida (Cargar productos a inventario)</option>
                        <option value="cancelada">Cancelar orden de compra</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Estado</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Detalles de los Productos Solicitados -->
    <h3>Productos Solicitados en la Orden</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Descripción del Repuesto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad Pedida</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['producto_marca'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($item['producto_nombre']) ?></td>
                        <td>Bs. <?= number_format($item['precio_unit'], 2) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td><strong>Bs. <?= number_format($item['cantidad'] * $item['precio_unit'], 2) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="background: rgba(255, 255, 255, 0.05); font-size: 1.1rem;">
                    <td colspan="4" style="text-align: right; font-weight: bold; padding: 1.25rem;">Total de la Orden:</td>
                    <td style="padding: 1.25rem; color: var(--accent-blue);"><strong>Bs. <?= number_format($orden['total'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>