<?php

/**
 * @var array $producto Datos del producto a editar
 * @var array $categorias Categorías disponibles
 * @var array $marcas_producto Marcas de producto disponibles
 * @var array $tipos_vehiculo Tipos de vehículo disponibles
 * @var array $ids_actuales IDs de vehículos compatibles actuales
 */
// Pequeña consulta rápida para cargar los nombres de los vehículos que ya tiene el producto
$vehiculos_actuales = [];
if (!empty($ids_actuales)) {
    $db = Database::getInstance()->getConnection();
    $in = str_repeat('?,', count($ids_actuales) - 1) . '?';
    $stmt = $db->prepare("SELECT id_modelo_vehiculo, nombre FROM modelos_vehiculo WHERE id_modelo_vehiculo IN ($in)");
    $stmt->execute($ids_actuales);
    $vehiculos_actuales = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="panel">
    <div class="panel-header">
        <h2>Editar Producto: <?= htmlspecialchars($producto['nombre']) ?></h2>
    </div>

    <form action="<?= BASE_URL ?>productos/actualizar/<?= $producto['id_producto'] ?>" method="POST" enctype="multipart/form-data">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Nombre del Producto *</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required class="form-control">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Precio (Bs.) *</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Marca del Repuesto *</label>
                <select name="id_marca_producto" required class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($marcas_producto as $m): ?>
                        <option value="<?= $m['id_marca_producto'] ?>" <?= $m['id_marca_producto'] == $producto['id_marca_producto'] ? 'selected' : '' ?>><?= $m['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Categoría *</label>
                <select name="id_categoria" required class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id_categoria'] ?>" <?= $c['id_categoria'] == $producto['id_categoria'] ? 'selected' : '' ?>><?= $c['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Stock Actual *</label>
                <input type="number" name="stock" value="<?= $producto['stock'] ?>" required class="form-control">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Actualizar Imagen (Opcional)</label>
                <input type="file" name="imagen" accept="image/*" class="form-control">
                <?php if ($producto['imagen']): ?>
                    <small style="color: var(--text-secondary); margin-top: 5px; display: block;">Imagen actual: <?= $producto['imagen'] ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3" class="form-control"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>

        <hr style="margin: 25px 0; border-color: var(--border-color);">
        <h3 style="margin-bottom: 5px;">Compatibilidad Vehicular</h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 15px;">Añade o quita vehículos compatibles con este repuesto.</p>

        <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
            <select id="tipo_vehiculo" class="form-control" style="width: auto;">
                <option value="">1. Tipo...</option>
                <?php foreach ($tipos_vehiculo as $t): ?>
                    <option value="<?= $t['id_tipo_vehiculo'] ?>"><?= $t['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

            <select id="marca_vehiculo" class="form-control" style="width: auto;">
                <option value="">2. Marca...</option>
            </select>

            <select id="modelo_vehiculo" class="form-control" style="width: auto;">
                <option value="">3. Modelo...</option>
            </select>

            <button type="button" onclick="agregarVehiculo()" class="btn btn-secondary">Añadir</button>
        </div>

        <div id="vehiculos_agregados" style="padding: 15px; border: 1px dashed var(--border-color); min-height: 50px; background: #f9fafb; margin-bottom: 25px; border-radius: 8px;">
            <!-- Vehículos cargados desde la base de datos -->
            <?php foreach ($vehiculos_actuales as $v): ?>
                <div id="vehiculo_<?= $v['id_modelo_vehiculo'] ?>" style="display: inline-flex; align-items: center; gap: 8px; background: #f3f4f6; color: var(--text-primary); padding: 6px 12px; margin: 4px; border-radius: 9999px; font-size: 0.85rem; border: 1px solid var(--border-color);">
                    <?= htmlspecialchars($v['nombre']) ?>
                    <input type="hidden" name="vehiculos[]" value="<?= $v['id_modelo_vehiculo'] ?>">
                    <button type="button" onclick="this.parentElement.remove()" style="color: var(--accent-danger-text); border: none; background: none; cursor: pointer; font-weight: bold; font-size: 1rem; line-height: 1;">×</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            <a href="<?= BASE_URL ?>productos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('tipo_vehiculo').addEventListener('change', function() {
        fetch('<?= BASE_URL ?>ajax/marcas?id_tipo=' + this.value)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">2. Marca...</option>';
                data.forEach(m => html += `<option value="${m.id_marca_vehiculo}">${m.nombre}</option>`);
                document.getElementById('marca_vehiculo').innerHTML = html;
                document.getElementById('modelo_vehiculo').innerHTML = '<option value="">3. Modelo...</option>';
            });
    });

    document.getElementById('marca_vehiculo').addEventListener('change', function() {
        fetch('<?= BASE_URL ?>ajax/modelos?id_marca=' + this.value)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">3. Modelo...</option>';
                data.forEach(m => html += `<option value="${m.id_modelo_vehiculo}">${m.nombre}</option>`);
                document.getElementById('modelo_vehiculo').innerHTML = html;
            });
    });

    function agregarVehiculo() {
        const select = document.getElementById('modelo_vehiculo');
        const id = select.value;
        const texto = select.options[select.selectedIndex].text;

        if (!id) return alert('Seleccione un modelo primero');
        if (document.getElementById('vehiculo_' + id)) return alert('Este vehículo ya está agregado');

        const container = document.getElementById('vehiculos_agregados');
        const div = document.createElement('div');
        div.id = 'vehiculo_' + id;
        div.style = "display: inline-flex; align-items: center; gap: 8px; background: #f3f4f6; color: var(--text-primary); padding: 6px 12px; margin: 4px; border-radius: 9999px; font-size: 0.85rem; border: 1px solid var(--border-color);";
        div.innerHTML = `
            ${texto}
            <input type="hidden" name="vehiculos[]" value="${id}">
            <button type="button" onclick="this.parentElement.remove()" style="color: var(--accent-danger-text); border: none; background: none; cursor: pointer; font-weight: bold; font-size: 1rem; line-height: 1;">×</button>
        `;
        container.appendChild(div);
    }
</script>