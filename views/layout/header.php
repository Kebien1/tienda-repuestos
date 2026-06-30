<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Repuestos - Integrante 3</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?= BASE_URL ?>" class="logo">Repuestos Center</a>
            </div>

            <nav class="sidebar-nav">
                <?php
                // Detectar la sección activa
                $current_uri = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
                $is_clientes = (strpos($current_uri, 'clientes') === 0);
                $is_proveedores = (strpos($current_uri, 'proveedores') === 0 || $current_uri === '');
                $is_ordenes = (strpos($current_uri, 'ordenes') === 0);
                $is_productos = (strpos($current_uri, 'productos') === 0);
                $is_inventario = (strpos($current_uri, 'inventario') === 0);
                $is_busqueda = (strpos($current_uri, 'busqueda') === 0);
                $is_vehiculos = (strpos($current_uri, 'vehiculos') === 0);
                ?>
                <ul>
                    <li><a href="<?= BASE_URL ?>proveedores" class="<?= $is_proveedores ? 'active' : '' ?>"> Proveedores</a></li>
                    <li><a href="<?= BASE_URL ?>clientes" class="<?= $is_clientes ? 'active' : '' ?>"> Clientes</a></li>
                    <li><a href="<?= BASE_URL ?>ordenes" class="<?= $is_ordenes ? 'active' : '' ?>"> Órdenes</a></li>
                    <li><a href="<?= BASE_URL ?>productos" class="<?= $is_productos ? 'active' : '' ?>"> Productos</a></li>
                    <li><a href="<?= BASE_URL ?>inventario" class="<?= $is_inventario ? 'active' : '' ?>"> Inventario</a></li>
                    <li><a href="<?= BASE_URL ?>busqueda" class="<?= $is_busqueda ? 'active' : '' ?>"> Búsqueda</a></li>
                    <li><a href="<?= BASE_URL ?>vehiculos" class="<?= $is_vehiculos ? 'active' : '' ?>"> Vehículos</a></li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <main>