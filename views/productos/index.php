<?php

/**
 * @var array $productos Lista de productos desde el controlador
 */
?>
<div class="panel">
    <div class="panel-header">
        <h2>Gestión de Productos</h2>
        <a href="<?= BASE_URL ?>productos/crear" class="btn btn-primary">+ Nuevo Producto</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><span>✓</span> <?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <form method="GET" action="<?= BASE_URL ?>productos">
            <input type="text" name="busqueda" placeholder="Buscar por nombre, marca o categoría..." value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>" class="form-control" style="width: 300px; display: inline-block;">
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>IMG</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td>
                            <?php if ($p['imagen']): ?>
                                <img src="<?= BASE_URL ?>public/uploads/<?= $p['imagen'] ?>" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #f3f4f6; text-align: center; line-height: 50px; font-size: 10px; color: var(--text-secondary); border-radius: 5px;">Sin img</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($p['marca_producto']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td>Bs. <?= number_format($p['precio'], 2) ?></td>
                        <td>
                            <?php if ($p['stock'] <= $p['stock_minimo']): ?>
                                <span class="badge badge-cancelled"><?= $p['stock'] ?> (Bajo)</span>
                            <?php else: ?>
                                <span class="badge badge-completed"><?= $p['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="<?= BASE_URL ?>productos/editar/<?= $p['id_producto'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                                <a href="<?= BASE_URL ?>productos/estado/<?= $p['id_producto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea desactivar este producto?');">Borrar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No se encontraron productos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>