<?php

/** * @var array $cliente 
 * @var array $historial 
 */
?>
<div class="panel" style="max-width: 900px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Perfil del Cliente: <?= htmlspecialchars($cliente['nombre'] ?? '') ?></h2>
        <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary btn-sm">Volver a Clientes</a>
    </div>

    <div style="background: rgba(15, 23, 42, 0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
            <h3 style="color: var(--accent-primary); font-size: 1.1rem; margin-bottom: 1rem;">Datos de Contacto</h3>
            <p style="margin-bottom: 0.5rem;"><strong>Correo Electrónico:</strong> <?= htmlspecialchars($cliente['correo'] ?? '') ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Teléfono:</strong> <?= htmlspecialchars($cliente['telefono'] ?? 'No registrado') ?></p>
            <p style="margin-bottom: 0.5rem;"><strong>Dirección:</strong> <?= htmlspecialchars($cliente['direccion'] ?? 'No registrada') ?></p>
        </div>
        <div>
            <h3 style="color: var(--accent-primary); font-size: 1.1rem; margin-bottom: 1rem;">Estado de la Cuenta</h3>
            <p style="margin-bottom: 0.5rem;">
                <strong>Estado:</strong>
                <span class="badge <?= ($cliente['estado'] ?? '') === 'activo' ? 'badge-completed' : 'badge-cancelled' ?>">
                    <?= ucfirst($cliente['estado'] ?? 'desconocido') ?>
                </span>
            </p>
            <p style="margin-bottom: 0.5rem;"><strong>Fecha de Registro:</strong> <?= !empty($cliente['fecha_registro']) ? date('d/m/Y', strtotime($cliente['fecha_registro'])) : 'No registrada' ?></p>
        </div>
    </div>

    <h3>Historial de Compras</h3>
    <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1rem;">Registro de todas las compras realizadas por este cliente en el sistema.</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nº Venta</th>
                    <th>Fecha</th>
                    <th>Atendido por</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($historial)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-secondary);">Este cliente aún no tiene compras registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($historial as $venta): ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($venta['id_venta'] ?? '') ?></strong></td>
                            <td><?= !empty($venta['fecha']) ? date('d/m/Y H:i', strtotime($venta['fecha'])) : 'N/A' ?></td>
                            <td><?= htmlspecialchars($venta['vendedor_nombre'] ?? 'Sistema') ?></td>
                            <td><strong>Bs. <?= number_format($venta['total'] ?? 0, 2) ?></strong></td>
                            <td>
                                <?php if (($venta['estado'] ?? '') === 'completada'): ?>
                                    <span class="badge badge-completed">Completada</span>
                                <?php elseif (($venta['estado'] ?? '') === 'pendiente'): ?>
                                    <span class="badge badge-pending">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Anulada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>