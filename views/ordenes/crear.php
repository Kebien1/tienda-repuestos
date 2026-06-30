<?php 
/** 
 * @var array $proveedores 
 * @var array $productos 
 */ 
?>
<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Generar Orden de Compra</h2>
        <a href="<?= BASE_URL ?>ordenes" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning">
            <span>⚠</span> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>ordenes/guardar" method="POST">
        <!-- Selección de Proveedor -->
        <div class="form-group">
            <label for="id_proveedor">Seleccionar Proveedor *</label>
            <select id="id_proveedor" name="id_proveedor" class="form-control" required>
                <option value="">-- Seleccione un proveedor --</option>
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov['id_proveedor'] ?>"><?= htmlspecialchars($prov['nombre']) ?> (Contacto: <?= htmlspecialchars($prov['contacto'] ?? '') ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Selección de Repuestos</h3>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1rem;">
            Selecciona los productos y define la cantidad a solicitar. Los repuestos marcados con <strong style="color: var(--accent-red);">⚠</strong> tienen stock bajo.
        </p>

        <!-- Selección de Productos -->
        <div class="table-container" style="margin-bottom: 2rem;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">Solicitar</th>
                        <th>Producto</th>
                        <th>Marca</th>
                        <th>Precio Unitario</th>
                        <th>Stock Actual / Mínimo</th>
                        <th style="width: 120px;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-secondary);">No hay productos registrados en el sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $prod): ?>
                            <?php
                            $isLowStock = ($prod['stock'] <= $prod['stock_minimo']);
                            ?>
                            <tr style="<?= $isLowStock ? 'background: rgba(248, 113, 113, 0.03);' : '' ?>">
                                <td style="text-align: center;">
                                    <input type="checkbox" name="productos[]" value="<?= $prod['id_producto'] ?>" style="width: 18px; height: 18px; cursor: pointer;">
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($prod['nombre']) ?></strong>
                                    <?php if ($isLowStock): ?>
                                        <span style="color: var(--accent-red); margin-left: 5px;" title="Stock bajo">⚠</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($prod['marca'] ?? 'N/A') ?></td>
                                <td>Bs. <?= number_format($prod['precio'], 2) ?></td>
                                <td>
                                    <span style="color: <?= $isLowStock ? 'var(--accent-red)' : 'var(--accent-green)' ?>; font-weight: bold;">
                                        <?= $prod['stock'] ?>
                                    </span>
                                    / <?= $prod['stock_minimo'] ?>
                                </td>
                                <td>
                                    <input type="number" name="cantidades[<?= $prod['id_producto'] ?>]" class="form-control" min="1" placeholder="0" style="padding: 0.4rem;">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Generar Orden de Compra (Pendiente)</button>
    </form>
</div>
