CREATE DATABASE IF NOT EXISTS pasteleriadb;

USE pasteleriadb;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    numero INT NOT NULL UNIQUE,
    precio DECIMAL(10, 2) NOT NULL,
    tipo ENUM('Tarta', 'Bollo', 'Chocolate') NOT NULL,
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
    num_pedidos_efectuados INT DEFAULT 0
);

-- Tabla de compras
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    producto_id INT,
    fecha_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Tabla de valoraciones
CREATE TABLE IF NOT EXISTS valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    producto_id INT,
    comentario TEXT,
    fecha_valoracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);