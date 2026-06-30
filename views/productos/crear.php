<?php

/**
 * @var array $categorias Categorías disponibles
 * @var array $marcas_producto Marcas de producto disponibles
 * @var array $tipos_vehiculo Tipos de vehículo disponibles
 * @var array $ids_actuales IDs de vehículos compatibles (vacío en creación)
 */
?>
<div class="panel">
    <div class="panel-header">
        <h2>Crear Nuevo Producto</h2>
    </div>

    <form action="<?= BASE_URL ?>productos/guardar" method="POST" enctype="multipart/form-data">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Nombre del Producto *</label>
                <input type="text" name="nombre" required class="form-control">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Precio (Bs.) *</label>
                <input type="number" step="0.01" name="precio" required class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Marca del Repuesto *</label>
                <select name="id_marca_producto" required class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($marcas_producto as $m): ?>
                        <option value="<?= $m['id_marca_producto'] ?>"><?= $m['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Categoría *</label>
                <select name="id_categoria" required class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label>Stock Inicial *</label>
                <input type="number" name="stock" required class="form-control" value="0">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Imagen del Producto</label>
                <input type="file" name="imagen" accept="image/*" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3" class="form-control"></textarea>
        </div>

        <hr style="margin: 25px 0; border-color: var(--border-color);">
        <h3 style="margin-bottom: 5px;">Compatibilidad Vehicular</h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 15px;">Seleccione los vehículos compatibles con este repuesto.</p>

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
            <!-- Aquí se agregarán los vehículos compatibles -->
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
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