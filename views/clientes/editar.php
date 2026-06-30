<?php

/** @var array $cliente */ ?>
<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Editar Cliente</h2>
        <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning">
            <span>⚠</span> <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>clientes/actualizar/<?= $cliente['id_usuario'] ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre Completo *</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico *</label>
            <input type="email" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($cliente['correo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña (Dejar en blanco para no cambiar)</label>
            <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Nueva contraseña">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <textarea id="direccion" name="direccion" class="form-control" rows="3"><?= htmlspecialchars($cliente['direccion'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar Cliente</button>
    </form>
</div>