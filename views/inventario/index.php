<div class="panel">
    <div class="panel-header">
        <h2>Gestión de Inventario</h2>
        <?php if (isset($total_alertas) && $total_alertas > 0): ?>
            <span class="badge badge-cancelled" style="font-size: 1rem; padding: 10px;">⚠️ <?= $total_alertas ?> alerta(s) de stock bajo</span>
        <?php else: ?>
            <span class="badge badge-completed" style="font-size: 1rem; padding: 10px;">✅ Todo en orden</span>
        <?php endif; ?>
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
        <form method="GET" action="<?= BASE_URL ?>inventario">
            <input type="text" name="busqueda" placeholder="Buscar producto..." value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>" class="form-control" style="width: 300px; display: inline-block;">
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Marca / Categoría</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Estado</th>
                    <th>Acción Rápida</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $p): ?>
                        <tr style="<?= $p['alerta'] ? 'background-color: #fef2f2;' : '' ?>">
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($p['marca_producto']) ?> <br> <small style="color: #666;"><?= htmlspecialchars($p['categoria']) ?></small></td>
                            <td style="font-size: 1.2rem; font-weight: bold;"><?= $p['stock'] ?></td>
                            <td><?= $p['stock_minimo'] ?></td>
                            <td>
                                <?php if ($p['alerta']): ?>
                                    <span class="badge badge-cancelled">Bajo</span>
                                <?php else: ?>
                                    <span class="badge badge-completed">OK</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>inventario/actualizar" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="id" value="<?= $p['id_producto'] ?>">
                                    <input type="number" name="stock" value="<?= $p['stock'] ?>" class="form-control" style="width: 80px; padding: 5px;" min="0">
                                    <input type="hidden" name="stock_minimo" value="<?= $p['stock_minimo'] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>