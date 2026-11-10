CREATE DATABASE IF NOT EXISTS botica_saludesencia CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE botica_saludesencia;

CREATE TABLE usuarios (
 id_usuario INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL,
 usuario VARCHAR(50) NOT NULL UNIQUE,
 clave VARCHAR(255) NOT NULL,
 rol VARCHAR(30) NOT NULL
);

CREATE TABLE medicamentos (
 id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 descripcion TEXT,
 precio DECIMAL(10,2) NOT NULL,
 stock_minimo INT NOT NULL DEFAULT 0
);

CREATE TABLE lotes (
 id_lote INT AUTO_INCREMENT PRIMARY KEY,
 id_medicamento INT NOT NULL,
 codigo_lote VARCHAR(50) NOT NULL,
 cantidad INT NOT NULL,
 fecha_vencimiento DATE NOT NULL,
 FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
);

CREATE TABLE ventas (
 id_venta INT AUTO_INCREMENT PRIMARY KEY,
 id_usuario INT NOT NULL,
 fecha_venta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 total DECIMAL(10,2) NOT NULL DEFAULT 0,
 FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE detalle_ventas (
 id_detalle INT AUTO_INCREMENT PRIMARY KEY,
 id_venta INT NOT NULL,
 id_medicamento INT NOT NULL,
 cantidad INT NOT NULL,
 precio_unitario DECIMAL(10,2) NOT NULL,
 subtotal DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (id_venta) REFERENCES ventas(id_venta),
 FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
);

INSERT INTO usuarios(nombre, usuario, clave, rol) VALUES
('Administrador de prueba','admin','cambiar_esta_clave','Administrador');
INSERT INTO medicamentos(nombre, descripcion, precio, stock_minimo) VALUES
('Paracetamol','Tabletas 500 mg',0.50,20);
INSERT INTO lotes(id_medicamento,codigo_lote,cantidad,fecha_vencimiento) VALUES
(1,'LOT-001',100,'2027-12-31');
