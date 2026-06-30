<?php
/**
 * @var array $productos Lista de productos desde el controlador
 */
?>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Gestión de Productos</h2>
        <div>
            <a href="<?= BASE_URL ?>productos/crear" class="btn btn-primary" style="background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">+ Nuevo Producto</a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <form method="GET" action="<?= BASE_URL ?>productos">
            <input type="text" name="busqueda" placeholder="Buscar por nombre, marca o categoría..." value="<?= isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : '' ?>" style="padding: 8px; width: 300px;">
            <button type="submit" class="btn" style="padding: 8px 15px;">Buscar</button>
        </form>
    </div>

    <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f4f4f4; border-bottom: 2px solid #ddd;">
                <th style="padding: 10px;">IMG</th>
                <th style="padding: 10px;">Nombre</th>
                <th style="padding: 10px;">Marca</th>
                <th style="padding: 10px;">Categoría</th>
                <th style="padding: 10px;">Precio</th>
                <th style="padding: 10px;">Stock</th>
                <th style="padding: 10px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;">
                        <?php if ($p['imagen']): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= $p['imagen'] ?>" width="50" height="50" style="object-fit:cover; border-radius: 5px;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #eee; text-align: center; line-height: 50px; font-size: 10px; color: #999;">Sin img</div>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 10px;"><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                    <td style="padding: 10px;"><?= htmlspecialchars($p['marca_producto']) ?></td>
                    <td style="padding: 10px;"><?= htmlspecialchars($p['categoria']) ?></td>
                    <td style="padding: 10px;">Bs. <?= number_format($p['precio'], 2) ?></td>
                    <td style="padding: 10px;">
                        <?php if ($p['stock'] <= $p['stock_minimo']): ?>
                            <span style="color: red; font-weight: bold;"><?= $p['stock'] ?> (Bajo)</span>
                        <?php else: ?>
                            <span style="color: green;"><?= $p['stock'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 10px;">
                        <a href="<?= BASE_URL ?>productos/editar/<?= $p['id_producto'] ?>" class="btn" style="background-color: #ffc107; padding: 5px 10px; text-decoration: none; color: black; border-radius: 3px;">Editar</a>
                        <a href="<?= BASE_URL ?>productos/estado/<?= $p['id_producto'] ?>" class="btn" style="background-color: #dc3545; padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px;" onclick="return confirm('¿Seguro que desea desactivar este producto?');">Borrar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($productos)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No se encontraron productos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>