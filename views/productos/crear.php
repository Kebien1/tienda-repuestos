<?php

/**
 * @var array $categorias Categorías disponibles
 * @var array $marcas_producto Marcas de producto disponibles
 * @var array $tipos_vehiculo Tipos de vehículo disponibles
 * @var array $ids_actuales IDs de vehículos compatibles (vacío en creación)
 */
?>
<div class="container">
    <h2>Crear Nuevo Producto</h2>
    <div class="card" style="padding: 20px; background: #fff; border: 1px solid #ddd; border-radius: 5px;">
        <form action="<?= BASE_URL ?>productos/guardar" method="POST" enctype="multipart/form-data">

            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Nombre del Producto *</label><br>
                    <input type="text" name="nombre" required style="width: 100%; padding: 8px;">
                </div>
                <div style="flex: 1;">
                    <label>Precio (Bs.) *</label><br>
                    <input type="number" step="0.01" name="precio" required style="width: 100%; padding: 8px;">
                </div>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Marca del Repuesto *</label><br>
                    <select name="id_marca_producto" required style="width: 100%; padding: 8px;">
                        <option value="">Seleccione...</option>
                        <?php foreach ($marcas_producto as $m): ?>
                            <option value="<?= $m['id_marca_producto'] ?>"><?= $m['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label>Categoría *</label><br>
                    <select name="id_categoria" required style="width: 100%; padding: 8px;">
                        <option value="">Seleccione...</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label>Stock Inicial *</label><br>
                    <input type="number" name="stock" required style="width: 100%; padding: 8px;" value="0">
                </div>
                <div style="flex: 1;">
                    <label>Imagen del Producto</label><br>
                    <input type="file" name="imagen" accept="image/*" style="width: 100%; padding: 8px;">
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Descripción</label><br>
                <textarea name="descripcion" rows="3" style="width: 100%; padding: 8px;"></textarea>
            </div>

            <hr style="margin: 20px 0;">
            <h3>Compatibilidad Vehicular</h3>
            <p style="font-size: 12px; color: #666;">Seleccione los vehículos compatibles con este repuesto.</p>

            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <select id="tipo_vehiculo" style="padding: 8px;">
                    <option value="">1. Tipo...</option>
                    <?php foreach ($tipos_vehiculo as $t): ?>
                        <option value="<?= $t['id_tipo_vehiculo'] ?>"><?= $t['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="marca_vehiculo" style="padding: 8px;">
                    <option value="">2. Marca...</option>
                </select>

                <select id="modelo_vehiculo" style="padding: 8px;">
                    <option value="">3. Modelo...</option>
                </select>

                <button type="button" onclick="agregarVehiculo()" style="padding: 8px 15px; background: #17a2b8; color: white; border: none; cursor: pointer;">Añadir</button>
            </div>

            <div id="vehiculos_agregados" style="padding: 10px; border: 1px dashed #ccc; min-height: 50px; background: #fafafa; margin-bottom: 20px;">
                <!-- Aquí se agregarán los vehículos compatibles -->
            </div>

            <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; font-size: 16px;">Guardar Producto</button>
            <a href="<?= BASE_URL ?>productos" style="margin-left: 10px; text-decoration: none; color: #555;">Cancelar</a>
        </form>
    </div>
</div>

<script>
    // AJAX para cargar Marcas según el Tipo
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

    // AJAX para cargar Modelos según la Marca
    document.getElementById('marca_vehiculo').addEventListener('change', function() {
        fetch('<?= BASE_URL ?>ajax/modelos?id_marca=' + this.value)
            .then(res => res.json())
            .then(data => {
                let html = '<option value="">3. Modelo...</option>';
                data.forEach(m => html += `<option value="${m.id_modelo_vehiculo}">${m.nombre}</option>`);
                document.getElementById('modelo_vehiculo').innerHTML = html;
            });
    });

    // Función para añadir el vehículo visualmente al formulario
    function agregarVehiculo() {
        const select = document.getElementById('modelo_vehiculo');
        const id = select.value;
        const texto = select.options[select.selectedIndex].text;

        if (!id) return alert('Seleccione un modelo primero');
        if (document.getElementById('vehiculo_' + id)) return alert('Este vehículo ya está agregado');

        const container = document.getElementById('vehiculos_agregados');
        const div = document.createElement('div');
        div.id = 'vehiculo_' + id;
        div.style = "display: inline-block; background: #e2e3e5; color: #383d41; padding: 5px 10px; margin: 5px; border-radius: 15px; font-size: 14px;";
        div.innerHTML = `
            ${texto}
            <input type="hidden" name="vehiculos[]" value="${id}">
            <button type="button" onclick="this.parentElement.remove()" style="margin-left: 5px; color: red; border: none; background: none; cursor: pointer; font-weight: bold;">X</button>
        `;
        container.appendChild(div);
    }
</script>