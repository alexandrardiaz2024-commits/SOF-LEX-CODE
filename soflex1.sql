-- =============================================
-- Base de datos: soflex
-- SOF-LEX CODE v2
-- =============================================

CREATE DATABASE IF NOT EXISTS soflex
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_spanish_ci;

USE soflex;

-- Tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  usuario  VARCHAR(60)  NOT NULL UNIQUE,
  correo   VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol      ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin por defecto (password: admin123)
INSERT INTO usuarios (usuario, correo, password, rol)
VALUES ('admin', 'admin@soflex.com', 'admin123', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Tabla solicitudes de servicio
CREATE TABLE IF NOT EXISTS solicitudes (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  nombre   VARCHAR(120) NOT NULL,
  correo   VARCHAR(120) NOT NULL,
  telefono VARCHAR(30),
  servicio VARCHAR(100) NOT NULL,
  detalle  TEXT,
  fecha    DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado   ENUM('pendiente','en proceso','resuelto') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla quejas y sugerencias
CREATE TABLE IF NOT EXISTS quejas (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  nombre  VARCHAR(120) NOT NULL,
  correo  VARCHAR(120) NOT NULL,
  tipo    ENUM('queja','sugerencia') NOT NULL DEFAULT 'queja',
  mensaje TEXT NOT NULL,
  fecha   DATETIME DEFAULT CURRENT_TIMESTAMP,
  leido   TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
