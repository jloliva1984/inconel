-- ============================================================
-- Inconel Building - Database Schema
-- MySQL 5.7+ / 8.0+
-- ============================================================

CREATE DATABASE IF NOT EXISTS `inconel_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `inconel_db`;

-- ============================================================
-- Table: usuarios
-- ============================================================
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`       VARCHAR(100) NOT NULL,
  `apellido`     VARCHAR(100) NOT NULL,
  `email`        VARCHAR(150) NOT NULL UNIQUE,
  `password`     VARCHAR(255) NOT NULL,
  `rol`          ENUM('admin','tecnico') NOT NULL DEFAULT 'tecnico',
  `activo`       TINYINT(1) NOT NULL DEFAULT 1,
  `ultimo_login` DATETIME NULL,
  `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`   DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Table: viviendas
-- ============================================================
CREATE TABLE IF NOT EXISTS `viviendas` (
  `id`                    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `direccion`             VARCHAR(300) NOT NULL,
  `fecha_instalacion_ac`  DATE NOT NULL COMMENT 'Fecha instalación aire acondicionado',
  `serie_handler`         VARCHAR(100) NOT NULL COMMENT 'Número de serie Handler',
  `serie_condenser`       VARCHAR(100) NOT NULL COMMENT 'Número de serie Condenser',
  `fecha_venta`           DATE NOT NULL COMMENT 'Fecha de venta',
  `tecnico_id`            INT UNSIGNED NOT NULL COMMENT 'Técnico que registró',
  `notas`                 TEXT NULL,
  `created_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at`            DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_tecnico` (`tecnico_id`),
  INDEX `idx_fecha_venta` (`fecha_venta`),
  INDEX `idx_fecha_instalacion` (`fecha_instalacion_ac`),
  CONSTRAINT `fk_viviendas_tecnico`
    FOREIGN KEY (`tecnico_id`) REFERENCES `usuarios` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Default admin user
-- Password: Admin@123 (bcrypt hashed)
-- ============================================================
INSERT INTO `usuarios` (`nombre`, `apellido`, `email`, `password`, `rol`, `activo`) VALUES
('Administrador', 'Sistema', 'admin@inconel.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
 'admin', 1),
('Técnico', 'Demo', 'tecnico@inconel.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
 'tecnico', 1);

-- ============================================================
-- Sample data for viviendas
-- ============================================================
INSERT INTO `viviendas` (`direccion`, `fecha_instalacion_ac`, `serie_handler`, `serie_condenser`, `fecha_venta`, `tecnico_id`, `notas`) VALUES
('123 Palm Avenue, Miami, FL 33101', '2023-01-15', 'HDL-2023-001', 'CDS-2023-001', '2023-01-10', 1, 'Instalación estándar'),
('456 Ocean Drive, Miami Beach, FL 33139', '2022-06-20', 'HDL-2022-045', 'CDS-2022-045', '2022-06-15', 1, NULL),
('789 Brickell Ave, Miami, FL 33131', '2024-03-05', 'HDL-2024-012', 'CDS-2024-012', '2024-03-01', 2, 'Cliente VIP'),
('321 Coral Way, Coral Gables, FL 33134', '2021-11-10', 'HDL-2021-089', 'CDS-2021-089', '2021-11-05', 1, NULL),
('654 SW 8th St, Miami, FL 33130', '2024-08-22', 'HDL-2024-067', 'CDS-2024-067', '2024-08-20', 2, 'Instalación urgente'),
('987 NW 7th Ave, Miami, FL 33136', '2023-09-14', 'HDL-2023-134', 'CDS-2023-134', '2023-09-10', 2, NULL),
('147 Flagler St, Miami, FL 33130', '2022-12-03', 'HDL-2022-201', 'CDS-2022-201', '2022-11-28', 1, 'Garantía extendida'),
('258 NE 2nd Ave, Miami, FL 33132', '2024-01-18', 'HDL-2024-003', 'CDS-2024-003', '2024-01-15', 2, NULL),
('369 SW 27th Ave, Miami, FL 33135', '2021-05-25', 'HDL-2021-045', 'CDS-2021-045', '2021-05-20', 1, NULL),
('741 NE 125th St, North Miami, FL 33161', '2023-07-08', 'HDL-2023-098', 'CDS-2023-098', '2023-07-05', 2, NULL);
