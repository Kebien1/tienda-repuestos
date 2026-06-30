<div class="panel">
    <div class="panel-header">
        <h2>Gestión de Clientes</h2>
        <a href="<?= BASE_URL ?>clientes/crear" class="btn btn-primary">+ Registrar Cliente</a>
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
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary);">No hay clientes registrados en el sistema.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($cliente['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($cliente['correo']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefono'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($cliente['direccion'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge <?= $cliente['estado'] === 'activo' ? 'badge-completed' : 'badge-cancelled' ?>">
                                    <?= ucfirst($cliente['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?= BASE_URL ?>clientes/editar/<?= $cliente['id_usuario'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="<?= BASE_URL ?>clientes/eliminar/<?= $cliente['id_usuario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>