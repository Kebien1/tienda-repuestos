<?php

/**
 * @var array $marcas Lista de marcas
 * @var array $tipos Lista de tipos de vehículo
 * @var array|null $edit Datos de la marca a editar
 */
?>


<div class="panel">
    <div class="panel-header" style="border-bottom: none; margin-bottom: 0;">
        <h2>Gestión de Vehículos</h2>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
        <a href="<?= BASE_URL ?>vehiculos" class="btn btn-primary">Gestión de Marcas</a>
        <a href="<?= BASE_URL ?>vehiculos/modelos" class="btn btn-secondary">Gestión de Modelos</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><span>✓</span> <?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning"><span>⚠</span> <?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px;"><?= $edit ? 'Editar Marca' : 'Registrar Nueva Marca' ?></h3>
        <form method="POST" action="<?= BASE_URL ?>vehiculos/guardarMarca" style="display: flex; gap: 15px; align-items: flex-end;">
            <input type="hidden" name="edit_id" value="<?= $edit ? $edit['id_marca_vehiculo'] : 0 ?>">

            <div class="form-group" style="flex: 2; margin-bottom: 0;">
                <label>Nombre de la Marca</label>
                <input type="text" name="nombre" value="<?= $edit ? htmlspecialchars($edit['nombre']) : '' ?>" required class="form-control">
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label>Tipo de Vehículo</label>
                <select name="id_tipo_vehiculo" required class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t['id_tipo_vehiculo'] ?>" <?= ($edit && $edit['id_tipo_vehiculo'] == $t['id_tipo_vehiculo']) ? 'selected' : '' ?>><?= htmlspecialchars($t['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><?= $edit ? 'Actualizar' : 'Guardar' ?></button>
                <?php if ($edit): ?>
                    <a href="<?= BASE_URL ?>vehiculos" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Marca</th>
                    <th>Tipo (Auto/Moto)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($marcas as $m): ?>
                    <tr>
                        <td><?= $m['id_marca_vehiculo'] ?></td>
                        <td><strong><?= htmlspecialchars($m['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($m['tipo_vehiculo']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>vehiculos?editar=<?= $m['id_marca_vehiculo'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                            <a href="<?= BASE_URL ?>vehiculos/eliminarMarca/<?= $m['id_marca_vehiculo'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta marca?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>