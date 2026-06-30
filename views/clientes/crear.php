<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Registrar Nuevo Cliente</h2>
        <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning">
            <span>⚠</span> <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>clientes/guardar" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre Completo *</label>
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico *</label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña *</label>
            <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña de la cuenta" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Ej. 78901234">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea id="direccion" name="direccion" class="form-control" rows="3" placeholder="Ej. Av. Ballivián 101, La Paz"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Registrar Cliente</button>
    </form>
</div>