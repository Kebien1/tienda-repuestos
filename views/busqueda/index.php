<div class="panel">
    <div class="panel-header">
        <h2>Buscar Repuestos por Compatibilidad Vehicular</h2>
    </div>

    <form method="GET" action="<?= BASE_URL ?>busqueda" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <div class="form-group" style="margin-bottom: 0;">
            <label>Tipo de Vehículo</label>
            <select name="id_tipo_vehiculo" id="filtro_tipo" class="form-control">
                <option value="">Seleccione...</option>
                <?php if (!empty($tipos)): ?>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t['id_tipo_vehiculo'] ?>" <?= (isset($id_tipo) && $id_tipo == $t['id_tipo_vehiculo']) ? 'selected' : '' ?>><?= htmlspecialchars($t['nombre']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Marca</label>
            <select name="id_marca_vehiculo" id="filtro_marca" class="form-control">
                <option value="">Seleccione tipo primero...</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Modelo</label>
            <select name="id_modelo_vehiculo" id="filtro_modelo" class="form-control">
                <option value="">Seleccione marca primero...</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label>Año (Opcional)</label>
            <select name="anio" id="filtro_anio" class="form-control">
                <option value="">Cualquier año</option>
                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                    <option value="<?= $y ?>" <?= (isset($anio) && $anio == $y) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">Buscar Repuestos</button>
        </div>
    </form>

    <?php if (isset($id_modelo) && $id_modelo): ?>
        <?php if (empty($resultados)): ?>
            <div class="alert alert-warning">No se encontraron repuestos compatibles para este vehículo.</div>
        <?php else: ?>
            <h3><?= count($resultados) ?> repuestos encontrados</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php foreach ($resultados as $p): ?>
                    <div class="card" style="border: 1px solid var(--border-color); border-radius: 8px; padding: 15px; background: white;">
                        <?php if ($p['imagen']): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= $p['imagen'] ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
                        <?php endif; ?>
                        <h4 style="margin-bottom: 10px; color: var(--accent-primary); font-size: 1.2rem;"><?= htmlspecialchars($p['nombre']) ?></h4>
                        <p style="font-size: 0.9rem; margin-bottom: 5px;"><strong>Marca:</strong> <?= htmlspecialchars($p['marca_producto']) ?></p>
                        <p style="font-size: 0.9rem; margin-bottom: 5px;"><strong>Categoría:</strong> <?= htmlspecialchars($p['categoria']) ?></p>
                        <p style="font-size: 1.2rem; font-weight: bold; color: var(--accent-success-text); margin-top: 10px; margin-bottom: 10px;">Bs. <?= number_format($p['precio'], 2) ?></p>
                        <p style="font-size: 0.9rem;">
                            <?= $p['stock'] > 0 ? '<span style="color: green; font-weight: bold;">✓ Disponible (' . $p['stock'] . ')</span>' : '<span style="color: red; font-weight: bold;">✗ Sin stock</span>' ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    // Lógica AJAX para el buscador en cascada
    const selMarca = '<?= isset($id_marca) ? $id_marca : '' ?>';
    const selModelo = '<?= isset($id_modelo) ? $id_modelo : '' ?>';

    function cargarMarcas(idTipo) {
        const marcaSelect = document.getElementById('filtro_marca');
        fetch('<?= BASE_URL ?>ajax/marcas?id_tipo=' + idTipo)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">Seleccione...</option>';
                data.forEach(m => {
                    html += `<option value="${m.id_marca_vehiculo}" ${m.id_marca_vehiculo == selMarca ? 'selected' : ''}>${m.nombre}</option>`;
                });
                marcaSelect.innerHTML = html;
                if (selMarca) cargarModelos(selMarca);
            });
    }

    function cargarModelos(idMarca) {
        const modeloSelect = document.getElementById('filtro_modelo');
        fetch('<?= BASE_URL ?>ajax/modelos?id_marca=' + idMarca)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">Seleccione...</option>';
                data.forEach(m => {
                    html += `<option value="${m.id_modelo_vehiculo}" ${m.id_modelo_vehiculo == selModelo ? 'selected' : ''}>${m.nombre}</option>`;
                });
                modeloSelect.innerHTML = html;
            });
    }

    document.getElementById('filtro_tipo').addEventListener('change', function() {
        if (this.value) cargarMarcas(this.value);
    });

    document.getElementById('filtro_marca').addEventListener('change', function() {
        if (this.value) cargarModelos(this.value);
    });

    const tipoActual = document.getElementById('filtro_tipo').value;
    if (tipoActual) cargarMarcas(tipoActual);
</script>