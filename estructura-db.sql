-- Elimina la base de datos si existe
DROP DATABASE IF EXISTS parqueadero_system;

-- Crea la base de datos
CREATE DATABASE parqueadero_system;
USE parqueadero_system;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de configuración del parqueadero
CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_cupos INT NOT NULL DEFAULT 10,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de reservas (sin restricción UNIQUE)
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    numero_espacio INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    placa_vehiculo VARCHAR(10) NOT NULL,
    estado ENUM('activa', 'completada', 'cancelada') DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Insertar configuración inicial
INSERT INTO configuracion (total_cupos) VALUES (10);

-- Insertar usuario administrador
INSERT INTO usuarios (nombre, email, password, rol)
VALUES (
    'Administrador',
    'admin@parquedero',
    '$2y$10$qG4UkmEJSvP82mMPWQ6hb.jQ6t.HUpfs2eAJMXM6hnwTesia3o2YK',
    'admin'
);

ALTER TABLE reservas ADD COLUMN tipo_vehiculo ENUM('moto', 'carro') NOT NULL DEFAULT 'carro';

