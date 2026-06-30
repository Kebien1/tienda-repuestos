<?php

/** @var array $clientes */
/** @var array $productos */
?>
<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Registrar Nueva Venta</h2>
        <a href="<?= BASE_URL ?>ventas" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>ventas/guardar" method="POST">
        <div class="form-group">
            <label for="id_cliente">Seleccionar Cliente *</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <option value="">-- Seleccione un cliente --</option>
                <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['id_usuario'] ?>"><?= htmlspecialchars($c['nombre']) ?> (<?= htmlspecialchars($c['correo']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Selección de Repuestos</h3>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1rem;">Seleccione los productos e indique la cantidad a vender.</p>

        <div class="table-container" style="margin-bottom: 2rem;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">Sel.</th>
                        <th>Producto</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock Disp.</th>
                        <th style="width: 120px;">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="productos[]" value="<?= $prod['id_producto'] ?>" style="width: 18px; height: 18px; cursor: pointer;" <?= $prod['stock'] <= 0 ? 'disabled' : '' ?>>
                            </td>
                            <td><strong><?= htmlspecialchars($prod['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($prod['marca'] ?? 'N/A') ?></td>
                            <td>Bs. <?= number_format($prod['precio'], 2) ?></td>
                            <td>
                                <span style="color: <?= $prod['stock'] <= 0 ? 'var(--accent-red)' : 'var(--accent-green)' ?>; font-weight: bold;">
                                    <?= $prod['stock'] ?>
                                </span>
                            </td>
                            <td>
                                <input type="number" name="cantidades[<?= $prod['id_producto'] ?>]" class="form-control" min="1" max="<?= $prod['stock'] ?>" placeholder="0" <?= $prod['stock'] <= 0 ? 'disabled' : '' ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Procesar Venta</button>
    </form>
</div>