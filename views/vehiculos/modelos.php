<?php

/**
 * @var array $modelos Lista de modelos
 * @var array $marcas_vehiculo Lista de marcas para el select
 * @var array|null $edit Datos del modelo a editar
 * @var int $id_marca_filtro ID de la marca filtrada
 */
?>


<div class="panel">
    <div class="panel-header" style="border-bottom: none; margin-bottom: 0;">
        <h2>Gestión de Vehículos</h2>
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid var(--border-color); padding-bottom: 10px;">
        <a href="<?= BASE_URL ?>vehiculos" class="btn btn-secondary">Gestión de Marcas</a>
        <a href="<?= BASE_URL ?>vehiculos/modelos" class="btn btn-primary">Gestión de Modelos</a>
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
        <h3 style="margin-bottom: 15px;"><?= $edit ? 'Editar Modelo' : 'Registrar Nuevo Modelo' ?></h3>
        <form method="POST" action="<?= BASE_URL ?>vehiculos/guardarModelo" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="edit_id" value="<?= $edit ? $edit['id_modelo_vehiculo'] : 0 ?>">

            <div class="form-group" style="flex: 2; margin-bottom: 0;">
                <label>Nombre del Modelo</label>
                <input type="text" name="nombre" value="<?= $edit ? htmlspecialchars($edit['nombre']) : '' ?>" required class="form-control">
            </div>
            <div class="form-group" style="flex: 2; margin-bottom: 0;">
                <label>Marca</label>
                <select name="id_marca_vehiculo" required class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($marcas_vehiculo as $m): ?>
                        <option value="<?= $m['id_marca_vehiculo'] ?>" <?= ($edit && $edit['id_marca_vehiculo'] == $m['id_marca_vehiculo']) ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre']) ?> (<?= htmlspecialchars($m['tipo_vehiculo']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label>Año Inicio (Opc)</label>
                <input type="number" name="anio_inicio" value="<?= $edit ? $edit['anio_inicio'] : '' ?>" class="form-control" placeholder="Ej. 2015">
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label>Año Fin (Opc)</label>
                <input type="number" name="anio_fin" value="<?= $edit ? $edit['anio_fin'] : '' ?>" class="form-control" placeholder="Ej. 2024">
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><?= $edit ? 'Actualizar' : 'Guardar' ?></button>
                <?php if ($edit): ?>
                    <a href="<?= BASE_URL ?>vehiculos/modelos" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div style="margin-bottom: 15px;">
        <label><strong>Filtrar por marca:</strong></label>
        <select onchange="window.location.href='<?= BASE_URL ?>vehiculos/modelos?marca=' + this.value" class="form-control" style="width: auto; display: inline-block; margin-left: 10px;">
            <option value="0">Todas las marcas</option>
            <?php foreach ($marcas_vehiculo as $m): ?>
                <option value="<?= $m['id_marca_vehiculo'] ?>" <?= (isset($_GET['marca']) && $_GET['marca'] == $m['id_marca_vehiculo']) ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre Modelo</th>
                    <th>Marca</th>
                    <th>Tipo</th>
                    <th>Años (Rango)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modelos as $m): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($m['nombre']) ?></strong></td>
                        <td><?= htmlspecialchars($m['marca_vehiculo']) ?></td>
                        <td><?= htmlspecialchars($m['tipo_vehiculo']) ?></td>
                        <td><?= $m['anio_inicio'] ? $m['anio_inicio'] . ' - ' . ($m['anio_fin'] ?? 'Actual') : 'N/A' ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>vehiculos/modelos?editar=<?= $m['id_modelo_vehiculo'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                            <a href="<?= BASE_URL ?>vehiculos/eliminarModelo/<?= $m['id_modelo_vehiculo'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este modelo?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($modelos)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No hay modelos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>