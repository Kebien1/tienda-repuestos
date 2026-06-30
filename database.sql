-- =========================================================
-- BASE DE DATOS: Tienda de Repuestos
-- Universidad - Proyecto de Ventas de Repuestos
-- =========================================================

CREATE DATABASE IF NOT EXISTS tienda_repuestos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE tienda_repuestos;

-- =========================================================
-- TABLA: rol
-- Define los tipos de usuario del sistema
-- =========================================================
CREATE TABLE rol (
    id_rol      INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol  VARCHAR(30) NOT NULL UNIQUE
);

-- =========================================================
-- TABLA: usuario
-- Unifica clientes, vendedores, mecánicos, repartidores y admin
-- =========================================================
CREATE TABLE usuario (
    id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    correo          VARCHAR(100) NOT NULL UNIQUE,
    contrasena      VARCHAR(255) NOT NULL,
    telefono        VARCHAR(20),
    direccion       VARCHAR(200),
    id_rol          INT NOT NULL,
    estado          ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_registro  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
);

-- =========================================================
-- TABLA: producto
-- Catálogo + inventario (RF001, RF002, RF010)
-- =========================================================
CREATE TABLE producto (
    id_producto         INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(150) NOT NULL,
    marca               VARCHAR(80),
    categoria           VARCHAR(80),
    descripcion         VARCHAR(255),
    precio              DECIMAL(10,2) NOT NULL,
    stock               INT NOT NULL DEFAULT 0,
    stock_minimo        INT NOT NULL DEFAULT 5,
    vehiculo_compatible VARCHAR(150),
    estado              ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_registro      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- TABLA: venta (RF003, RF004)
-- =========================================================
CREATE TABLE venta (
    id_venta     INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente   INT NOT NULL,
    id_empleado  INT NOT NULL,
    fecha        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total        DECIMAL(10,2) NOT NULL DEFAULT 0,
    estado       ENUM('pendiente', 'completada', 'anulada') NOT NULL DEFAULT 'pendiente',
    FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_empleado) REFERENCES usuario(id_usuario)
);

-- =========================================================
-- TABLA: detalle_venta
-- Productos incluidos en cada venta
-- =========================================================
CREATE TABLE detalle_venta (
    id_detalle    INT AUTO_INCREMENT PRIMARY KEY,
    id_venta      INT NOT NULL,
    id_producto   INT NOT NULL,
    cantidad      INT NOT NULL,
    precio_unit   DECIMAL(10,2) NOT NULL,
    subtotal      DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES venta(id_venta) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

-- =========================================================
-- TABLA: delivery (RF011)
-- =========================================================
CREATE TABLE delivery (
    id_delivery        INT AUTO_INCREMENT PRIMARY KEY,
    id_venta           INT NOT NULL UNIQUE,
    id_cliente         INT NOT NULL,
    id_repartidor      INT,
    direccion_entrega  VARCHAR(200) NOT NULL,
    costo_envio        DECIMAL(10,2) NOT NULL DEFAULT 0,
    estado             ENUM('pendiente', 'en_camino', 'entregado') NOT NULL DEFAULT 'pendiente',
    fecha_asignacion   DATETIME,
    fecha_entrega      DATETIME,
    FOREIGN KEY (id_venta) REFERENCES venta(id_venta),
    FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_repartidor) REFERENCES usuario(id_usuario)
);

-- =========================================================
-- TABLA: proveedor (RF006)
-- =========================================================
CREATE TABLE proveedor (
    id_proveedor  INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(150) NOT NULL,
    contacto      VARCHAR(100),
    telefono      VARCHAR(20),
    correo        VARCHAR(100),
    direccion     VARCHAR(200),
    estado        ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
);

-- =========================================================
-- TABLA: orden_compra (RF006)
-- =========================================================
CREATE TABLE orden_compra (
    id_orden       INT AUTO_INCREMENT PRIMARY KEY,
    id_proveedor   INT NOT NULL,
    id_empleado    INT NOT NULL,
    fecha          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado         ENUM('pendiente', 'recibida', 'cancelada') NOT NULL DEFAULT 'pendiente',
    total          DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (id_proveedor) REFERENCES proveedor(id_proveedor),
    FOREIGN KEY (id_empleado) REFERENCES usuario(id_usuario)
);

-- =========================================================
-- TABLA: detalle_orden
-- Productos incluidos en cada orden de compra
-- =========================================================
CREATE TABLE detalle_orden (
    id_detalle_orden  INT AUTO_INCREMENT PRIMARY KEY,
    id_orden          INT NOT NULL,
    id_producto       INT NOT NULL,
    cantidad          INT NOT NULL,
    precio_unit       DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_orden) REFERENCES orden_compra(id_orden) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

-- =========================================================
-- TABLA: asignacion_trabajo (RF012)
-- Trabajos asignados a mecánicos
-- =========================================================
CREATE TABLE asignacion_trabajo (
    id_asignacion      INT AUTO_INCREMENT PRIMARY KEY,
    id_mecanico        INT NOT NULL,
    id_venta           INT,
    descripcion        VARCHAR(255) NOT NULL,
    estado             ENUM('pendiente', 'en_proceso', 'finalizado') NOT NULL DEFAULT 'pendiente',
    fecha_asignacion   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_finalizacion DATETIME,
    FOREIGN KEY (id_mecanico) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_venta) REFERENCES venta(id_venta)
);

-- =========================================================
-- DATOS INICIALES: roles del sistema
-- =========================================================
INSERT INTO rol (nombre_rol) VALUES
    ('admin'),
    ('vendedor'),
    ('mecanico'),
    ('repartidor'),
    ('cliente');

-- =========================================================
-- ÍNDICES recomendados para mejorar el rendimiento
-- =========================================================
CREATE INDEX idx_producto_categoria ON producto(categoria);
CREATE INDEX idx_venta_cliente ON venta(id_cliente);
CREATE INDEX idx_venta_fecha ON venta(fecha);
CREATE INDEX idx_usuario_rol ON usuario(id_rol);








-- =========================================================
-- DATOS DE PRUEBA: Tienda de Repuestos
-- Ejecutar DESPUÉS de tienda_repuestos.sql
-- =========================================================

USE tienda_repuestos;

-- =========================================================
-- USUARIOS (contraseña de todos: "123456" hasheada con SHA2)
-- =========================================================
INSERT INTO usuario (nombre, correo, contrasena, telefono, direccion, id_rol) VALUES
-- Admins
('Carlos Mamani',      'admin@tienda.com',      SHA2('123456', 256), '71234567', 'Av. Arce 123, La Paz',         1),
-- Vendedores
('Ana Quispe',         'vendedor1@tienda.com',  SHA2('123456', 256), '72345678', 'Calle Loayza 45, La Paz',      2),
('Pedro Flores',       'vendedor2@tienda.com',  SHA2('123456', 256), '73456789', 'Av. 6 de Agosto 78, La Paz',   2),
-- Mecánicos
('Juan Condori',       'mecanico1@tienda.com',  SHA2('123456', 256), '74567890', 'Calle Murillo 12, La Paz',     3),
('Luis Apaza',         'mecanico2@tienda.com',  SHA2('123456', 256), '75678901', 'Av. Illimani 34, La Paz',      3),
-- Repartidores
('Roberto Choque',     'repartidor1@tienda.com',SHA2('123456', 256), '76789012', 'Calle Potosí 56, La Paz',      4),
('Mario Huanca',       'repartidor2@tienda.com',SHA2('123456', 256), '77890123', 'Av. Busch 89, La Paz',         4),
-- Clientes
('María López',        'maria@gmail.com',       SHA2('123456', 256), '78901234', 'Av. Ballivián 101, La Paz',    5),
('Jorge Ramos',        'jorge@gmail.com',        SHA2('123456', 256), '79012345', 'Calle Ingavi 202, La Paz',     5),
('Patricia Salinas',   'patricia@gmail.com',    SHA2('123456', 256), '70123456', 'Av. Camacho 303, La Paz',      5),
('Diego Vargas',       'diego@gmail.com',        SHA2('123456', 256), '71111222', 'Calle Comercio 404, La Paz',   5),
('Rosa Mendoza',       'rosa@gmail.com',          SHA2('123456', 256), '72222333', 'Av. Mcal. Santa Cruz 505',    5);

-- =========================================================
-- PRODUCTOS (catálogo + inventario)
-- =========================================================
INSERT INTO producto (nombre, marca, categoria, descripcion, precio, stock, stock_minimo, vehiculo_compatible) VALUES
('Filtro de aceite',          'Bosch',      'Filtros',    'Filtro de aceite para motor 1.6L',           35.00,  45, 5,  'Toyota Corolla 2015-2022, Toyota Yaris 2016-2022'),
('Filtro de aire',            'Mann',       'Filtros',    'Filtro de aire de alto flujo',                28.50,  38, 5,  'Hyundai Tucson 2018-2023, Hyundai Santa Fe 2019-2023'),
('Pastillas de freno delant.','Brembo',     'Frenos',     'Juego de pastillas de freno delanteras',      85.00,  22, 4,  'Nissan Sentra 2017-2023, Nissan Versa 2018-2023'),
('Pastillas de freno traseras','Brembo',    'Frenos',     'Juego de pastillas de freno traseras',        75.00,  18, 4,  'Nissan Sentra 2017-2023, Nissan Versa 2018-2023'),
('Bujías NGK (juego x4)',     'NGK',        'Encendido',  'Bujías de iridio larga duración',             65.00,  30, 5,  'Toyota Corolla 2015-2022, Honda Civic 2016-2022'),
('Correa de distribución',    'Gates',      'Motor',      'Kit completo con tensores',                  120.00,   8, 3,  'Kia Sportage 2016-2021, Kia Cerato 2017-2022'),
('Amortiguador delantero',    'Monroe',     'Suspensión', 'Par de amortiguadores delanteros',           180.00,  12, 3,  'Chevrolet Spark 2018-2023, Chevrolet Aveo 2016-2022'),
('Batería 60Ah',              'Bosch',      'Eléctrico',  'Batería de 60Ah libre de mantenimiento',     210.00,  10, 2,  'Universal (todos los modelos)'),
('Aceite de motor 5W-30 4L',  'Mobil',      'Lubricantes','Aceite sintético 5W-30 para motor',           55.00,  60, 10, 'Universal (todos los modelos)'),
('Disco de freno delantero',  'Brembo',     'Frenos',     'Par de discos de freno delanteros ventilados',140.00, 15, 3,  'Toyota RAV4 2019-2023, Toyota Corolla Cross 2021-2023'),
('Termostato de motor',       'Wahler',     'Motor',      'Termostato 82°C con empaque incluido',        42.00,  20, 4,  'Hyundai Accent 2017-2022, Kia Rio 2018-2023'),
('Bomba de agua',             'Dayco',      'Motor',      'Bomba de agua con empaque',                   95.00,   9, 3,  'Toyota Hilux 2018-2023, Toyota SW4 2019-2023'),
('Alternador remanufacturado','Bosch',      'Eléctrico',  'Alternador 90A remanufacturado garantía 1yr',350.00,   4, 2,  'Ford Ranger 2018-2023, Ford Ecosport 2019-2022'),
('Sensor de oxígeno',         'Bosch',      'Sensores',   'Sensor O2 sonda lambda upstream',             88.00,  14, 3,  'Honda CRV 2018-2022, Honda HRV 2019-2023'),
('Cable bujía (juego x4)',    'NGK',        'Encendido',  'Juego de cables de bujía premium',            48.00,  25, 5,  'Chevrolet Cruze 2017-2022, Chevrolet Tracker 2020-2023');

-- =========================================================
-- PROVEEDORES
-- =========================================================
INSERT INTO proveedor (nombre, contacto, telefono, correo, direccion) VALUES
('Bosch Bolivia S.A.',        'Ing. Ramiro Torres',   '22334455', 'ventas@bosch-bo.com',      'Av. Montes 234, La Paz'),
('AutoPartes del Sur Ltda.',  'Lic. Carmen Vidal',    '22445566', 'contacto@autoparsur.com',  'Calle Yanacocha 78, La Paz'),
('NGK Distribuidores',        'Sr. Félix Romero',     '22556677', 'pedidos@ngk-dist.com',     'Av. Kollasuyo 456, El Alto'),
('Importadora Brembo',        'Ing. Diana Soto',      '22667788', 'ventas@brembo-bo.com',     'Zona Fría Industrial, El Alto'),
('Lubricantes Mobil Bolivia', 'Sr. Ricardo Pinto',    '22778899', 'pedidos@mobilbol.com',     'Av. Argentina 890, La Paz');

-- =========================================================
-- VENTAS
-- =========================================================
INSERT INTO venta (id_cliente, id_empleado, fecha, total, estado) VALUES
(8,  2, '2025-06-01 10:30:00', 175.00, 'completada'),
(9,  2, '2025-06-02 11:00:00', 265.00, 'completada'),
(10, 3, '2025-06-03 09:15:00',  55.00, 'completada'),
(11, 2, '2025-06-04 14:00:00', 350.00, 'completada'),
(12, 3, '2025-06-05 16:30:00', 208.50, 'completada'),
(8,  2, '2025-06-08 10:00:00', 120.00, 'completada'),
(9,  3, '2025-06-09 11:45:00',  85.00, 'completada'),
(10, 2, '2025-06-10 13:00:00', 465.00, 'completada'),
(11, 3, '2025-06-11 15:30:00', 140.00, 'pendiente'),
(12, 2, '2025-06-12 09:00:00',  65.00, 'pendiente');

-- =========================================================
-- DETALLE DE VENTAS
-- =========================================================
INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unit, subtotal) VALUES
(1,  1, 1,  35.00,  35.00),
(1,  5, 1,  65.00,  65.00),
(1,  9, 1,  55.00,  55.00),
(2,  3, 1,  85.00,  85.00),
(2,  4, 1,  75.00,  75.00),
(2,  9, 1,  55.00,  55.00),
(3,  9, 1,  55.00,  55.00),
(4,  8, 1, 210.00, 210.00),
(4,  9, 2,  55.00, 110.00),
(5,  2, 1,  28.50,  28.50),
(5,  9, 2,  55.00, 110.00),
(5,  1, 2,  35.00,  70.00),
(6,  6, 1, 120.00, 120.00),
(7,  3, 1,  85.00,  85.00),
(8, 13, 1, 350.00, 350.00),
(8,  9, 2,  55.00, 110.00),
(9, 10, 1, 140.00, 140.00),
(10, 5, 1,  65.00,  65.00);

-- =========================================================
-- DELIVERY
-- =========================================================
INSERT INTO delivery (id_venta, id_cliente, id_repartidor, direccion_entrega, costo_envio, estado, fecha_asignacion, fecha_entrega) VALUES
(1,  8,  6, 'Av. Ballivián 101, La Paz',      15.00, 'entregado',  '2025-06-01 12:00:00', '2025-06-01 14:30:00'),
(2,  9,  6, 'Calle Ingavi 202, La Paz',         15.00, 'entregado',  '2025-06-02 13:00:00', '2025-06-02 15:00:00'),
(4, 11,  7, 'Calle Comercio 404, La Paz',       20.00, 'entregado',  '2025-06-04 15:00:00', '2025-06-04 17:00:00'),
(8, 10,  6, 'Av. Camacho 303, La Paz',          15.00, 'en_camino',  '2025-06-10 14:00:00', NULL),
(9, 11,  7, 'Calle Comercio 404, La Paz',       20.00, 'pendiente',  NULL,                  NULL);

-- =========================================================
-- ÓRDENES DE COMPRA
-- =========================================================
INSERT INTO orden_compra (id_proveedor, id_empleado, fecha, estado, total) VALUES
(1, 1, '2025-05-15 09:00:00', 'recibida',  1750.00),
(2, 1, '2025-05-20 10:00:00', 'recibida',  2100.00),
(3, 1, '2025-06-01 09:00:00', 'pendiente', 1300.00),
(4, 1, '2025-06-05 11:00:00', 'pendiente',  850.00),
(5, 1, '2025-06-10 08:00:00', 'pendiente', 1650.00);

-- =========================================================
-- DETALLE DE ÓRDENES DE COMPRA
-- =========================================================
INSERT INTO detalle_orden (id_orden, id_producto, cantidad, precio_unit) VALUES
(1, 1, 20, 25.00),
(1, 8,  5, 150.00),
(2, 3, 10,  60.00),
(2, 4, 10,  55.00),
(2, 9, 20,  40.00),
(3, 5, 10,  45.00),
(3,15, 10,  35.00),
(4, 3, 10,  60.00),
(5, 9, 30,  40.00),
(5, 1, 15,  25.00);

-- =========================================================
-- ASIGNACIONES DE TRABAJO A MECÁNICOS
-- =========================================================
INSERT INTO asignacion_trabajo (id_mecanico, id_venta, descripcion, estado, fecha_asignacion, fecha_finalizacion) VALUES
(4, 1, 'Instalación de filtro de aceite y bujías',           'finalizado',   '2025-06-01 15:00:00', '2025-06-01 17:00:00'),
(5, 2, 'Cambio de pastillas de freno delantera y trasera',   'finalizado',   '2025-06-02 12:00:00', '2025-06-02 14:30:00'),
(4, 4, 'Instalación de batería y cambio de aceite',          'finalizado',   '2025-06-04 09:00:00', '2025-06-04 11:00:00'),
(5, 6, 'Instalación de correa de distribución',              'en_proceso',   '2025-06-08 10:00:00', NULL),
(4, 9, 'Instalación de disco de freno delantero',            'pendiente',    '2025-06-11 08:00:00', NULL);