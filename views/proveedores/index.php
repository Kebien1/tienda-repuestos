<div class="panel">
    <div class="panel-header">
        <h2>Gestión de Proveedores</h2>
        <a href="<?= BASE_URL ?>proveedores/crear" class="btn btn-primary">+ Registrar Proveedor</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <span>✓</span> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning">
            <span>⚠</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proveedores)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary);">No hay proveedores registrados en el sistema.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($proveedor['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($proveedor['contacto'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($proveedor['telefono'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($proveedor['correo'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($proveedor['direccion'] ?? 'N/A') ?></td>
                            <td>
                                <div class="actions">
                                    <a href="<?= BASE_URL ?>proveedores/editar/<?= $proveedor['id_proveedor'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="<?= BASE_URL ?>proveedores/eliminar/<?= $proveedor['id_proveedor'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
