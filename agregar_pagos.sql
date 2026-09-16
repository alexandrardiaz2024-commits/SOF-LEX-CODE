-- Ejecutar en phpMyAdmin > BD soflex > pestaña SQL
USE soflex;

CREATE TABLE IF NOT EXISTS pagos (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  solicitud_id    INT NOT NULL,
  nombre          VARCHAR(120) NOT NULL,
  correo          VARCHAR(120) NOT NULL,
  servicio        VARCHAR(100) NOT NULL,
  metodo_pago     VARCHAR(50)  NOT NULL,
  referencia      VARCHAR(100),
  monto           DECIMAL(10,2),
  comprobante     VARCHAR(255),
  estado_pago     ENUM('pendiente','verificado','rechazado') DEFAULT 'pendiente',
  fecha           DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
