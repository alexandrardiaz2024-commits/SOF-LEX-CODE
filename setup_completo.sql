-- ================================================================
-- SOF-LEX CODE v2 — Setup completo de BD
-- Ejecutar en: phpMyAdmin > soflex > pestaña SQL
-- ================================================================

USE soflex;

-- ── 1. TABLA PAGOS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS pagos (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  solicitud_id   INT NOT NULL DEFAULT 0,
  nombre         VARCHAR(120) NOT NULL,
  correo         VARCHAR(120) NOT NULL,
  servicio       VARCHAR(100) NOT NULL,
  metodo_pago    VARCHAR(50)  NOT NULL,
  referencia     VARCHAR(150) DEFAULT '',
  monto          DECIMAL(12,2) DEFAULT 0.00,
  estado_pago    ENUM('pendiente','verificado','rechazado') DEFAULT 'pendiente',
  fecha          DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 2. TABLA MENSAJES (admin → cliente) ─────────────────────────
CREATE TABLE IF NOT EXISTS mensajes (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  para_correo VARCHAR(120) NOT NULL,
  para_nombre VARCHAR(120) NOT NULL,
  asunto      VARCHAR(200) NOT NULL,
  cuerpo      TEXT NOT NULL,
  leido       TINYINT(1) DEFAULT 0,
  fecha       DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 3. USUARIO ADMIN EXCLUSIVO ───────────────────────────────────
-- usuario: soflex_admin
-- password: Soflex2025*
INSERT INTO usuarios (usuario, correo, password, rol)
VALUES ('soflex_admin', 'admin@soflex.com', 'Soflex2025*', 'admin')
ON DUPLICATE KEY UPDATE
  password = 'Soflex2025*',
  rol      = 'admin';

-- ================================================================
-- LISTO. Tablas creadas y admin configurado.
-- ================================================================
