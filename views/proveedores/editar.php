<?php

/** @var array $proveedor */ ?>
<div class="panel" style="max-width: 600px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Editar Proveedor</h2>
        <a href="<?= BASE_URL ?>proveedores" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-warning">
            <span>⚠</span> <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>proveedores/actualizar/<?= $proveedor['id_proveedor'] ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de la Empresa *</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
        </div>

        <div class="form-group">
            <label for="contacto">Persona de Contacto *</label>
            <input type="text" id="contacto" name="contacto" class="form-control" value="<?= htmlspecialchars($proveedor['contacto'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono *</label>
            <input type="text" id="telefono" name="telefono" class="form-control" value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($proveedor['correo'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección Física *</label>
            <input type="text" id="direccion" name="direccion" class="form-control" value="<?= htmlspecialchars($proveedor['direccion'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar Proveedor</button>
    </form>
</div>