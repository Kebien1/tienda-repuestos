<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Repuestos - Integrante 3</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="<?= BASE_URL ?>" class="logo">Repuestos Center</a>
            <nav>
                <?php
                // Detectar la sección activa en la URL para resaltar la pestaña
                $current_uri = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
                $is_clientes = (strpos($current_uri, 'clientes') === 0);
                $is_proveedores = (strpos($current_uri, 'proveedores') === 0 || $current_uri === '');
                $is_ordenes = (strpos($current_uri, 'ordenes') === 0);
                ?>
                <ul>
                    <li><a href="<?= BASE_URL ?>proveedores" class="<?= $is_proveedores ? 'active' : '' ?>">Proveedores</a></li>
                    <li><a href="<?= BASE_URL ?>clientes" class="<?= $is_clientes ? 'active' : '' ?>">Clientes</a></li>
                    <li><a href="<?= BASE_URL ?>ordenes" class="<?= $is_ordenes ? 'active' : '' ?>">Órdenes de Compra</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
