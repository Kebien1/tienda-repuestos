<?php
session_start();

// Definir constante BASE_URL
define('BASE_URL', '/tienda-repuestos/');

// Autocarga de clases básica
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../core/',
        __DIR__ . '/../models/',
        __DIR__ . '/../controllers/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Inicializar Ruteador
$router = new Router();

// Definir rutas para Clientes (Usuarios)
$router->get('clientes', 'ClienteController@index');
$router->get('clientes/crear', 'ClienteController@crear');
$router->post('clientes/guardar', 'ClienteController@guardar');
$router->get('clientes/editar/{id}', 'ClienteController@editar');
$router->post('clientes/actualizar/{id}', 'ClienteController@actualizar');
$router->get('clientes/estado/{id}', 'ClienteController@cambiarEstado');
$router->get('clientes/ver/{id}', 'ClienteController@ver');

// Definir rutas para Proveedores
$router->get('proveedores', 'ProveedorController@index');
$router->get('proveedores/crear', 'ProveedorController@crear');
$router->post('proveedores/guardar', 'ProveedorController@guardar');
$router->get('proveedores/editar/{id}', 'ProveedorController@editar');
$router->post('proveedores/actualizar/{id}', 'ProveedorController@actualizar');
$router->get('proveedores/estado/{id}', 'ProveedorController@cambiarEstado');

// Definir rutas para Órdenes de Compra
$router->get('ordenes', 'OrdenCompraController@index');
$router->get('ordenes/crear', 'OrdenCompraController@crear');
$router->post('ordenes/guardar', 'OrdenCompraController@guardar');
$router->get('ordenes/ver/{id}', 'OrdenCompraController@ver');
$router->post('ordenes/estado/{id}', 'OrdenCompraController@actualizarEstado');

// Definir rutas para Productos (Catálogo)
$router->get('productos', 'ProductoController@index');
$router->get('productos/crear', 'ProductoController@crear');
$router->post('productos/guardar', 'ProductoController@guardar');
$router->get('productos/editar/{id}', 'ProductoController@editar');
$router->post('productos/actualizar/{id}', 'ProductoController@actualizar');
$router->get('productos/estado/{id}', 'ProductoController@cambiarEstado');

// Definir rutas para Inventario y Alertas
$router->get('inventario', 'InventarioController@index');
$router->post('inventario/actualizar', 'InventarioController@actualizar');

// Definir rutas para Búsqueda por Compatibilidad Vehicular
$router->get('busqueda', 'BusquedaController@index');
$router->get('ajax/marcas', 'BusquedaController@ajaxMarcas');
$router->get('ajax/modelos', 'BusquedaController@ajaxModelos');

// Definir rutas para Vehículos (Marcas y Modelos)
$router->get('vehiculos', 'VehiculoController@marcas');
$router->post('vehiculos/guardarMarca', 'VehiculoController@guardarMarca');
$router->get('vehiculos/eliminarMarca/{id}', 'VehiculoController@eliminarMarca');

$router->get('vehiculos/modelos', 'VehiculoController@modelos');
$router->post('vehiculos/guardarModelo', 'VehiculoController@guardarModelo');
$router->get('vehiculos/eliminarModelo/{id}', 'VehiculoController@eliminarModelo');

// Página de inicio (redirige a proveedores por defecto o muestra un panel de control)
$router->get('', 'ProveedorController@index');

// Despachar la ruta solicitada
$url = isset($_GET['url']) ? $_GET['url'] : '';
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($url, $method);
