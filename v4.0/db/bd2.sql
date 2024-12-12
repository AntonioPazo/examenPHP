CREATE DATABASE IF NOT EXISTS pasteleriadb;

USE pasteleriadb;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    tipo ENUM('Tarta', 'Bollo', 'Chocolate') NOT NULL,
    descripcion TEXT,
    imagen LONGBLOB NOT NULL,           -- Contenido binario de la imagen
    num_pisos INT, 
    rellenos TEXT,
    min_comensales INT,
    max_comensales INT,
    relleno VARCHAR(100),
    porcentaje_cacao INT,
    peso INT
);

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    num_pedidos_efectuados INT DEFAULT 0,
    usuario VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);

-- Tabla de valoraciones
CREATE TABLE IF NOT EXISTS valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NULL,
    producto_id INT,
    comentario TEXT,
    fecha_valoracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Tabla compras
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NULL,
    producto_id INT,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Insertar el administrador
INSERT INTO clientes (nombre, usuario, password) 
VALUES ('Administrador', 'admin', 'admin');

-- Insertar un cliente con usuario normal
INSERT INTO clientes (nombre, usuario, password) 
VALUES ('Usuario Normal', 'usuario', 'usuario');