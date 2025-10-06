-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-10-2025 a las 07:17:16
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `instituto_dos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id_agenda` int(11) NOT NULL,
  `id_taller` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `variacion` varchar(100) DEFAULT NULL,
  `cant_inscritas` int(11) NOT NULL,
  `cant_interesadas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id_agenda`, `id_taller`, `fecha`, `hora_inicio`, `hora_fin`, `ubicacion`, `variacion`, `cant_inscritas`, `cant_interesadas`) VALUES
(1, 1, '2025-09-10', '10:00:00', '13:00:00', 'Aula 101', 'Matutino', 3, 0),
(2, 2, '2025-09-15', '15:00:00', '18:00:00', 'Sala Principal', 'Vespertino', 3, 0),
(3, 3, '2025-09-20', '09:00:00', '12:00:00', 'Salón Creativo', 'Fin de semana', 3, 0),
(4, 3, '2025-12-02', '15:00:00', '18:00:00', 'Victoria', '', 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda_cursos`
--

CREATE TABLE `agenda_cursos` (
  `id_agenda_curso` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `variacion` varchar(100) DEFAULT NULL,
  `cant_inscritas` int(11) NOT NULL,
  `cant_interesadas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda_cursos`
--

INSERT INTO `agenda_cursos` (`id_agenda_curso`, `id_curso`, `fecha`, `hora_inicio`, `hora_fin`, `ubicacion`, `variacion`, `cant_inscritas`, `cant_interesadas`) VALUES
(1, 1, '2025-09-25', '09:00:00', '12:00:00', 'Aula 201', 'Matutino', 2, 0),
(2, 2, '2025-09-30', '16:00:00', '19:00:00', 'Laboratorio A', 'Vespertino', 2, 0),
(3, 3, '2025-10-05', '10:00:00', '13:00:00', 'Sala B', 'Fin de semana', 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnas`
--

CREATE TABLE `alumnas` (
  `id_alumna` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apat` varchar(30) NOT NULL,
  `amat` varchar(30) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `descuento_aplicado` tinyint(1) DEFAULT 0,
  `tipo_descuento` varchar(100) DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnas`
--

INSERT INTO `alumnas` (`id_alumna`, `nombre`, `apat`, `amat`, `telefono`, `correo`, `direccion`, `descuento_aplicado`, `tipo_descuento`, `estatus`) VALUES
(1, 'Laura', 'Sánchez', 'Perez', '555-123-4567', 'laura@example.com', 'Calle 1, Colonia Centro', 1, 'Estudiante', 1),
(2, 'Mariana', 'Díaz', 'Martinez', '555-987-6543', 'mariana@example.com', 'Calle 2, Colonia Norte', 0, NULL, 1),
(3, 'Fernanda', 'Gómez', 'Farias', '555-321-7890', 'fernanda@example.com', 'Calle 3, Colonia Sur', 1, 'Estudiante', 1),
(4, 'Carlos', 'Mendez', 'Reyes', '413241', 'carlos@gmail.com', 'SIN NOMBRE Mza12, LT13', 0, '12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apat` varchar(30) NOT NULL,
  `amat` varchar(30) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apat`, `amat`, `correo`, `telefono`, `direccion`, `estatus`) VALUES
(1, 'Juan', 'Perez', 'Hernandez', 'juan.perez@gmail.com', '5512345678', 'Av. Reforma #123, CDMX', 1),
(2, 'María', 'Lopez', 'Marquez', 'maria.lopez@yahoo.com', '3322233344', 'Calle Hidalgo #45, Guadalajara', 1),
(3, 'Carlos', 'Ramirez', 'Valdez', 'carlos.ramirez@hotmail.com', '8111122233', 'Col. Centro, Monterrey', 1),
(4, 'Ana Maria', 'Torres', 'Rubio', 'ana.torres@outlook.com', '2223344556', 'Blvd. Díaz Ordaz #890, Puebla', 1),
(5, 'Ezequiel', 'Torres', 'Desilos', 'cheque@gmail.com', '123441', 'SIN NOMBRE Mza12, LT13', 1),
(6, 'Cesar', 'Hernandez', 'Medina', 'cesar@gmail.com', '412342', 'rarara', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `id_maestra` int(11) NOT NULL,
  `costo_base` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `ingreso_bruto` decimal(10,2) DEFAULT NULL,
  `gastos` decimal(10,2) DEFAULT NULL,
  `precio_preferencial` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre`, `id_maestra`, `costo_base`, `status`, `ingreso_bruto`, `gastos`, `precio_preferencial`) VALUES
(1, 'Curso de Inglés', 0, 2500.00, 'activo', 7500.00, 2000.00, 0),
(2, 'Curso de Programación', 0, 3000.00, 'activo', 9000.00, 3000.00, 1),
(3, 'Curso de Fotografía', 0, 2000.00, 'pendiente', 0.00, 0.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE `descuentos` (
  `id_descuento` int(11) NOT NULL,
  `tipo_descuento` varchar(100) DEFAULT NULL,
  `porcentaje` decimal(5,2) DEFAULT NULL,
  `id_alumna` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `descuentos`
--

INSERT INTO `descuentos` (`id_descuento`, `tipo_descuento`, `porcentaje`, `id_alumna`) VALUES
(1, '10% Descuento', 10.00, 1),
(2, 'Estudiante', 20.00, 3),
(3, 'Promoción especial', 15.00, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `idDetalle` int(11) NOT NULL,
  `idVenta` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_kit` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`idDetalle`, `idVenta`, `id_producto`, `id_kit`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 1, NULL, 1, 500.00, 500.00),
(2, 1, 2, NULL, 1, 800.00, 800.00),
(3, 1, 3, NULL, 1, 300.00, 300.00),
(4, 2, 1, NULL, 1, 500.00, 500.00),
(5, 2, 3, NULL, 1, 300.00, 300.00),
(6, 5, 1, NULL, 1, 500.00, 500.00),
(7, 5, 2, NULL, 2, 800.00, 1600.00),
(8, 5, 3, NULL, 1, 300.00, 300.00),
(9, 5, NULL, 2, 1, 1500.00, 1500.00),
(10, 6, 3, NULL, 1, 300.00, 300.00),
(11, 6, 2, NULL, 1, 800.00, 800.00),
(12, 6, 1, NULL, 5, 500.00, 2500.00),
(13, 7, 1, NULL, 3, 500.00, 1500.00),
(14, 8, 2, NULL, 1, 800.00, 800.00),
(15, 8, 1, NULL, 1, 500.00, 500.00),
(16, 8, NULL, 2, 1, 1500.00, 1500.00),
(17, 8, NULL, 3, 1, 1000.00, 1000.00),
(18, 9, 1, NULL, 1, 500.00, 500.00),
(19, 9, 2, NULL, 1, 800.00, 800.00),
(20, 9, 3, NULL, 1, 300.00, 300.00),
(23, 10, 1, NULL, 1, 500.00, 500.00),
(24, 10, NULL, 1, 1, 1200.00, 1200.00),
(25, 10, NULL, 2, 1, 1500.00, 1500.00),
(26, 10, NULL, 3, 1, 1000.00, 1000.00),
(27, 11, 1, NULL, 3, 500.00, 1500.00),
(28, 11, 3, NULL, 1, 300.00, 300.00),
(29, 11, NULL, 3, 1, 1000.00, 1000.00),
(30, 11, NULL, 1, 1, 1200.00, 1200.00),
(31, 12, 2, NULL, 1, 800.00, 800.00),
(58, 13, 1, NULL, 1, 500.00, 500.00),
(59, 13, 2, NULL, 2, 800.00, 1600.00),
(60, 14, 3, NULL, 1, 300.00, 300.00),
(61, 14, 1, NULL, 1, 500.00, 500.00),
(62, 15, 2, NULL, 1, 800.00, 800.00),
(63, 15, 1, NULL, 1, 500.00, 500.00),
(64, 16, 3, NULL, 1, 300.00, 300.00),
(65, 16, 2, NULL, 2, 800.00, 1600.00),
(66, 17, 1, NULL, 1, 500.00, 500.00),
(67, 17, 3, NULL, 1, 300.00, 300.00),
(68, 17, 2, NULL, 1, 800.00, 800.00),
(69, 18, 1, NULL, 1, 500.00, 500.00),
(70, 18, 2, NULL, 1, 800.00, 800.00),
(71, 18, 3, NULL, 1, 300.00, 300.00),
(72, 19, NULL, 3, 1, 1000.00, 1000.00),
(73, 19, NULL, 2, 1, 1500.00, 1500.00),
(74, 19, 3, NULL, 1, 300.00, 300.00),
(75, 20, NULL, 2, 3, 1500.00, 4500.00),
(76, 21, 2, NULL, 2, 800.00, 1600.00),
(77, 22, 1, NULL, 1, 500.00, 500.00),
(78, 23, 1, NULL, 1, 500.00, 500.00),
(79, 24, 2, NULL, 1, 800.00, 800.00),
(80, 24, 1, NULL, 1, 500.00, 500.00),
(81, 24, 3, NULL, 1, 300.00, 300.00),
(85, 25, 3, NULL, 1, 300.00, 300.00),
(86, 26, 3, NULL, 1, 300.00, 300.00),
(87, 27, 2, NULL, 1, 800.00, 800.00),
(88, 28, 2, NULL, 1, 800.00, 800.00),
(89, 29, 2, NULL, 1, 800.00, 800.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pagos`
--

CREATE TABLE `historial_pagos` (
  `id_pago` int(11) NOT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `id_intermedia` int(11) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metodo_pago` enum('efectivo','tarjeta','transferencia','depósito','otros') DEFAULT 'efectivo',
  `tipo_servicio` varchar(30) DEFAULT 'servicio',
  `comprobante` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_pagos`
--

INSERT INTO `historial_pagos` (`id_pago`, `idVenta`, `id_intermedia`, `monto_pagado`, `saldo_pendiente`, `fecha_pago`, `metodo_pago`, `tipo_servicio`, `comprobante`) VALUES
(4, 1, NULL, 500.00, 1100.00, '2025-09-17 05:20:26', 'transferencia', 'venta', ''),
(5, 1, NULL, 300.00, 800.00, '2025-09-18 05:20:26', 'transferencia', 'venta', ''),
(6, 2, NULL, 300.00, 500.00, '2025-09-10 05:25:32', 'transferencia', 'venta', ''),
(7, NULL, 1, 300.00, 200.00, '2025-09-25 07:05:51', 'tarjeta', 'inscripcion', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(8, NULL, 2, 300.00, 300.00, '2025-09-25 07:33:16', 'tarjeta', 'inscripcion', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(10, NULL, 2, 200.00, 100.00, '2025-09-25 08:01:57', 'tarjeta', 'inscripcion', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(12, NULL, 2, 100.00, 0.00, '2025-09-25 08:10:51', 'tarjeta', 'inscripcion', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(13, 1, NULL, 200.00, 600.00, '2025-09-26 06:58:24', 'tarjeta', 'Taller', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(14, 1, NULL, 200.00, 400.00, '2025-09-26 07:01:26', 'efectivo', 'Taller', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(15, NULL, 1, 200.00, 0.00, '2025-09-26 07:02:25', 'tarjeta', 'inscripcion', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link'),
(16, 25, NULL, 200.00, 100.00, '2025-09-26 08:59:03', 'tarjeta', 'venta', 'https://drive.google.com/file/d/1uXPuC1KvnEo0InWHSKKz-0CHWfRSCIbp/view?usp=drive_link');

--
-- Disparadores `historial_pagos`
--
DELIMITER $$
CREATE TRIGGER `tr_pago_completo` AFTER INSERT ON `historial_pagos` FOR EACH ROW BEGIN
    -- Si el saldo pendiente es 0
    IF NEW.saldo_pendiente = 0 THEN
        -- Caso: Venta
        IF NEW.idVenta IS NOT NULL THEN
            UPDATE venta
            SET estado = 'Pagado'
            WHERE idVenta = NEW.idVenta;
        END IF;

        -- Caso: Inscripción (intermedia_a)
        IF NEW.id_intermedia IS NOT NULL THEN
            UPDATE intermedia_a
            SET estado = 'Pagado'
            WHERE id_intermedia = NEW.id_intermedia;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institutos`
--

CREATE TABLE `institutos` (
  `id_instituto` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `institutos`
--

INSERT INTO `institutos` (`id_instituto`, `nombre`) VALUES
(1, 'Instituto Central'),
(2, 'Instituto del Norte'),
(3, 'Instituto del Sur');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interesadas`
--

CREATE TABLE `interesadas` (
  `id_interesada` int(11) NOT NULL,
  `id_alumna` int(11) NOT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `id_agenda_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `interesadas`
--

INSERT INTO `interesadas` (`id_interesada`, `id_alumna`, `id_agenda`, `id_agenda_curso`) VALUES
(3, 2, 4, NULL),
(4, 1, 4, NULL);

--
-- Disparadores `interesadas`
--
DELIMITER $$
CREATE TRIGGER `trg_update_cant_interesadas_after_insert` AFTER INSERT ON `interesadas` FOR EACH ROW BEGIN
  DECLARE cnt INT DEFAULT 0;

  IF NEW.id_agenda IS NOT NULL AND NEW.id_agenda <> 0 THEN
    SELECT COUNT(*) INTO cnt FROM interesadas WHERE id_agenda = NEW.id_agenda;
    UPDATE agenda
      SET cant_interesadas = cnt
      WHERE id_agenda = NEW.id_agenda;
  ELSEIF NEW.id_agenda_curso IS NOT NULL AND NEW.id_agenda_curso <> 0 THEN
    SELECT COUNT(*) INTO cnt FROM interesadas WHERE id_agenda_curso = NEW.id_agenda_curso;
    UPDATE agenda_cursos
      SET cant_interesadas = cnt
      WHERE id_agenda_curso = NEW.id_agenda_curso;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intermedia_a`
--

CREATE TABLE `intermedia_a` (
  `id_intermedia` int(11) NOT NULL,
  `id_alumna` int(11) NOT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `id_agenda_curso` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intermedia_a`
--

INSERT INTO `intermedia_a` (`id_intermedia`, `id_alumna`, `id_agenda`, `id_agenda_curso`, `fecha`, `estado`, `total`) VALUES
(1, 1, 3, NULL, '2025-09-26 07:02:25', 'Pagado', 500.00),
(2, 2, NULL, 3, '2025-09-25 08:10:51', 'Pagado', 600.00),
(3, 2, 3, NULL, '2025-09-25 07:27:45', '', 800.00),
(4, 2, 2, NULL, '2025-09-22 01:01:57', '', 0.00),
(5, 3, 1, NULL, '2025-09-22 01:02:01', '', 0.00),
(6, 3, 3, NULL, '2025-09-22 01:04:15', '', 0.00),
(7, 1, 4, NULL, '2025-09-22 01:07:07', '', 0.00),
(8, 3, NULL, 1, '2025-09-22 01:08:26', '', 0.00),
(9, 3, NULL, 3, '2025-09-22 01:08:30', '', 0.00),
(10, 2, NULL, 2, '2025-09-22 01:08:33', '', 0.00),
(11, 2, 4, NULL, '2025-09-22 01:58:19', '', 0.00),
(12, 3, 4, NULL, '2025-09-22 02:17:52', '', 0.00),
(13, 3, NULL, 2, '2025-09-26 21:21:31', 'Pendiente', 0.00),
(14, 2, 1, NULL, '2025-09-26 21:24:07', 'Pendiente', 0.00),
(15, 2, NULL, 1, '2025-09-26 21:24:15', 'Pendiente', 0.00),
(16, 3, 2, NULL, '2025-09-30 20:55:16', 'Pendiente', 0.00),
(17, 1, 2, NULL, '2025-09-30 20:55:24', 'Pendiente', 0.00),
(21, 4, 1, NULL, '2025-10-06 05:16:53', 'Pendiente', 0.00);

--
-- Disparadores `intermedia_a`
--
DELIMITER $$
CREATE TRIGGER `trg_intermedia_a_after_insert` AFTER INSERT ON `intermedia_a` FOR EACH ROW BEGIN
  DECLARE cnt INT DEFAULT 0;

  -- Si se insertó con id_agenda (taller)
  IF NEW.id_agenda IS NOT NULL AND NEW.id_agenda <> 0 THEN
    SELECT COUNT(*) INTO cnt FROM intermedia_a WHERE id_agenda = NEW.id_agenda;
    UPDATE agenda
      SET cant_inscritas = cnt
      WHERE id_agenda = NEW.id_agenda;
  -- Si se insertó con id_agenda_curso (curso)
  ELSEIF NEW.id_agenda_curso IS NOT NULL AND NEW.id_agenda_curso <> 0 THEN
    SELECT COUNT(*) INTO cnt FROM intermedia_a WHERE id_agenda_curso = NEW.id_agenda_curso;
    UPDATE agenda_cursos
      SET cant_inscritas = cnt
      WHERE id_agenda_curso = NEW.id_agenda_curso;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_inventario` int(11) NOT NULL,
  `id_instituto` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad_actual` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_inventario`, `id_instituto`, `id_producto`, `cantidad_actual`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 5),
(3, 2, 3, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_stock`
--

CREATE TABLE `inventario_stock` (
  `id` int(11) NOT NULL,
  `id_instituto` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `movimiento` enum('entrada','salida','ajuste') NOT NULL DEFAULT 'entrada',
  `cantidad` int(11) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario_stock`
--

INSERT INTO `inventario_stock` (`id`, `id_instituto`, `id_producto`, `movimiento`, `cantidad`, `motivo`, `fecha`) VALUES
(1, 1, 1, 'entrada', 10, 'Compra inicial', '2025-09-01 10:00:00'),
(2, 1, 2, 'salida', 2, 'Venta producto', '2025-09-05 12:00:00'),
(3, 2, 3, 'ajuste', 5, 'Revisión de stock', '2025-09-07 15:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kits_productos`
--

CREATE TABLE `kits_productos` (
  `id_kit` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `Stock` int(11) NOT NULL,
  `Activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `kits_productos`
--

INSERT INTO `kits_productos` (`id_kit`, `nombre`, `precio_unitario`, `Stock`, `Activo`) VALUES
(1, 'Kit Repostería', 1200.00, 3, 1),
(2, 'Kit Maquillaje', 1500.00, 90, 1),
(3, 'Kit Manualidades', 1000.00, 0, 0);

--
-- Disparadores `kits_productos`
--
DELIMITER $$
CREATE TRIGGER `trg_kits_bi` BEFORE INSERT ON `kits_productos` FOR EACH ROW BEGIN
  IF NEW.Stock IS NULL THEN SET NEW.Stock = 0; END IF;
  IF NEW.Stock <= 0 THEN
    SET NEW.Stock = 0, NEW.Activo = 0;
  ELSE
    SET NEW.Activo = 1;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_kits_bu` BEFORE UPDATE ON `kits_productos` FOR EACH ROW BEGIN
  IF NEW.Stock IS NULL THEN SET NEW.Stock = 0; END IF;
  IF NEW.Stock <= 0 THEN
    SET NEW.Stock = 0, NEW.Activo = 0;
  ELSE
    SET NEW.Activo = 1;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestras`
--

CREATE TABLE `maestras` (
  `id_maestra` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `base` varchar(100) DEFAULT NULL,
  `acuerdo` varchar(100) DEFAULT NULL,
  `gastos` decimal(10,2) DEFAULT NULL,
  `porcentaje_ganancia` decimal(5,2) DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestras`
--

INSERT INTO `maestras` (`id_maestra`, `nombre`, `base`, `acuerdo`, `gastos`, `porcentaje_ganancia`, `estatus`) VALUES
(1, 'María López', 'Base A', '50/50', 1200.50, 40.00, 1),
(2, 'Ana Martínez', 'Base B', '60/40', 800.00, 35.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `Stock` int(11) NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `Activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `precio_unitario`, `Stock`, `imagen`, `Activo`) VALUES
(1, 'Libro de repostería', 500.00, 7, NULL, 1),
(2, 'Set de brochas', 800.00, 6, 0xffd8ffe000104a46494600010100000100010000ffdb00840009060713121115121312161515151718151817151617151617171717161a18181515181d2820181d251d181521312125292b2e2e2e171f3338332c37282d2e2b010a0a0a0e0d0e1b10101b2d201d252e2d2d2d2e2d2d2d2d2d2d2f2d2d322d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2dffc000110800e100e103012200021101031101ffc4001c0000010501010100000000000000000000000203040506070108ffc4005a10000102030305090a0a04090c0300000001020300041105122106314151b11322326171728191a10714153334525393c1d12342627392a2b2c2d2f0162454e125354382a3a4b3b4b52644456374758494d3d4e2f16485c4ffc400190100030101010000000000000000000000000102030405ffc4002b110002020102040504030100000000000000010211032131123241610422425171136281f00591a123ffda000c03010002110311003f00ee04d313105cb55b0682aae4cdd662b328ad1a2b720700013c64e61d519c72d400e7fce88df1e24d5b11b416b27cd3d912589d42f0071d4708c2a2d319eb87ef31259b401c6b1a3c117b0ad9b98220d9139ba233d48c0f1ea3f9d513a395aa74ca08210a79233a80e5223d42c1cc41e4358402a082080020822aad2b5421571271d2757108718b6e9016b0467dab5955cf5e58bb967c2d3787fea2a58dc77158ec115f6cda8897415ac8000ce639e5a1dd3d90a20289a6acc48ae10946c67538239bd83dd29975d0d92535cc55883c5c51d192aa8ae6c2b09aa1b4d0a8425e49340a04f2c55650dac96a59e712aa9426f1a684d4051e804c616cfcb565453757895048d77891414e9115185efa08ea5040208800820820008212e2c24549a01a4c218984ac5526bf9d500e9d58ec104100820820800e47dd46d25cace0246f1d48524f345d23a0807f9c239fda1948a2b14cc4e38e8a69e3ad7aa3b0f764b0fbe2cf53a9155cb1dd871a333a3e8efbf9823e7958d31a29ba2922f53948e02a15c3138717e444e90ca95209aebad396bb2327761f95614b5a5081794b504a52339528d1207293029b45709debb9e5bd7a554ea86721b48ae752412aea0a4e3c7171336b2959d541a86023119603c112f28c0c53b9285e198bc0de755c552b04710e28c14e658beb0a00d02861d98c5a9addee4559d5ad1ca665ae12b1a56915f6465e36b986da41355ad094d3495148a1eb8e3b3536b71454a274f51d11abee5567976d04ae95dc1b71fe94a6ea3eb2927a20795b1f023e8399b410834ce750d1ca6237858f99dbfba39f2f2cd8456f3830ad7a3f3db02f2f25824a82eb4a9a0e215d90d421d49a674645a6085614212554ce0d056302e5a38d49aa8935d753fbf6c79901942a9f9c71376eb6db6a24eb2aa2523a8abaa30394369a9a79492685b5a90a1c9507b4424e316e828e84c5a24e8e43ac1fc98d164bda179776b5aa7b47e4c71a4e50e02a68460298e39abda6369dca27cb8ebef2cd1b692057415b84d0275d129387ca11529a71a0a361dd1b27953b26a4375dd51bf401f1e8314748cdc6071c709c97c8f7e7d4e0694846e21255ba5f4f0af602ea4e3bd381a47d107281aad28aa569534d958c15aac225ed39d5a0ad15965ccdd42d48429d6ee554a4834557750483861c6639da71dcd31bbd0a6ee4591bbb39df8f27e09b551b07f947069e34a7b4e1a088e8b97f6f093610b556e2d770d3cea1207628f445ed912e1b61a6d200096d22830cc91147dd26c8efab39e6aa02b78a413982d2b4d2b81a038a4f128c09d3b25be2661724f284da534f4a9146572ef24d739bc2ee3ab027aa33190b930e77f4ba9d28b88994214028925c4b2261200a622972a6353dcc2492d392d746f9489e0b579c5b792d83c975030e5d661592e3f5947fbc53fe1c881c9b65d51d7608208464109716120a89a018930a8a2b7664a9619198514afba3dbd509ba4698e1c72a12f3e5d554d6efc54fb48d7b217420d53811f9e981a401b6b4fcfe4c3ed1198e63ab1883a9cab44b426494d0706a50ce224c6794e169616330c0f1a6340935c62d3b39f2e3e176b667b0410433112b4050208a822841cc41ce0c7cbb959611939c7a5a8688555bce6adab1463a77a403c60c7d491cd728d644fcfe399894dafc05c3574711665d46a42490339009a66ce74688e8bdc63277769a334b1bc971bdae62ea8103e8a6a794a63dee34a22cd9da79edfd911a2ee0eaab139fed469ead10c6de85d7760b1fbe2cc71405572e43e9e44d439f50afa408f9f5f945b54dd1b5b75ad3744a915a52b4bc056951d71f50e584bee967cda06754b3c91ca5b553b63857754dff007abba14827e936cabdf05845795b33b2164bcf85299656ea51c22849504d73548cd9a3ad7708b32eb3313073ad69647106c5e5759707d18a8ee4e829b3271ccc14e048e54a535fb42351dc313fc1855e73ee2bb109fbb0584b94e3b95964ad89c996421452d3aa15009010adf22a746f0a4c563128e292541b5940ad5410a291caa02823ace559c2d7f9d3fdc9115b914aff0027a63e79cfecd10594a3b1a5ee1365dc947a608c5e72e8e63430faca7231fdd9ac95376815a524a1f425dc01202d3542c76255fce8de770e3fc149f9e77ed439978afd6529d726f1ea7e587de82e888f9a47036a5dc29bf7145030bd74dd1c4554a4747b41876cfb0e4d6d9297261fdd5640c68e34e1457f989474d62b2c5557261c1aa617f610637c0ff06591f308fee0b82c7bd2f739659d6acd38eb614e2ca54e2106b5a6f9634ebc63a1e5e9a4e4d1d721363a9126633960ae962363ff009cafed1bf7c69fba100262609200ef69a49273632ec1cffcc84d951dce8f313adb29485aa95180d26948876dbe972516a49a8defdb4c603baab8f09a6cb6f1424265db22ed6a5f7dc4dece294bb9b4f1458e4f2d69979f616e17370984202aedda821a39aa6989d70f4aee428bd195fdcf7c64b725a3fde950c64af9423fde09ff000e6a1eee7ca01d964de49fe30cc71f2827311519fb218c95f286f8ed04ff0087379faa116fa9d76082080c4438e848aa88033549a465e595ba38a73ce52883a870468d5171945e24f38453d988a369e2a768a63d75e88ce6f5a3bbc3c12c6e5f82c47bf6c7b5e58f00f7edfcf4c293b612258ccd22a33683f93d7d86265833054ddd39d1bde8d1ed88cf9ecf6e3eda7442f2753e30fca03b2bed86b71cd5e1d4b88208234388239a6537974ff1cbca1e8bcf88e971ce72bdba5a2f7fac916cf4b730b1b1c101a63e631fdc6d74b3a741f3dafb31a3ee067f579be39807fa244647b942aed9f3c3529ad86367dc05aa48cc2bce9a5538c0658f6d61fb84b951d2271abedad1e72549eb0447cf396b5559922e690db20f434a41ed408fa32382658cad2ce71bf413130df204cda8a7ea2c421e3d9aec5be4aba1bc9c2ad6b749e85abd8911aaee28d14d8ecd7e32de3fd32c7b23052ce5dc9947ca2ff6b8e08ea5dcd58b964c98cd5610e7acdffde8053d9185cab18dae9d21d07e948a2914f912e8fd1f991febdcfec5b8d1e5835fad5a49a70da967396adbed7dc118bc9376961cd7cfb9fddda80d16b5f0ce91dc387f058f9e77688772f07eb8cf1ca4c8ea7e48c2fb8c3376c760f9ca795fd338076011ee5ea3f59963ad89a4fd69557dd819963e6472dc9f57f934f8d530a1fd1347db1d0bfd17647ccb7fe1eec735b1154c9c98ff006a23fa19711d42d06ae59f64a3cd4a11d5674c7ba07d4b5bc4c15906962b235cfabfb56e3a3652589df334ea774b956968e0def1ad045738cd4af1d744736b3ff8a6553aed223adf408ec0ef9639cc46c852d8705aaf8317dd5bc78ffebcff005a988b3b207f1b9d5309572d10d9a74d29157dd63c6f459ffdea622e2cb185adf3df70401e95fbee3f92993bb8a9a74bb78a04c135450a83ebbf426f1e0e038ee8cd10325727157db78ad184c898a50d69dea962e83aea2f578e35963f013cd3b22264b78a6fa226d94e2b534b0410459cc479d950ea6e9240a8387145059a08174e74d527941a469e2b676cf378ad19ce71aceb11138dea74e0cb49c5ec321bc3af569ac7a3f3a73c45efb03027371c78a9e4eb1d711c46ff0049b1c9a550449c9d6e8d951f8ea24720a0f61884d4aa9fd694573d738d204681b40480902800a01c51505d4cf3cd28f02dfa8a820823438c230597944cf4b9240dd2566918fc85cb285359c4e11bd8ca65c300aa5d75354a9c4d3410e3774f4e622132f1f323976403213276904a8e0b4f090466bf9ae935cda699a37fdc49ba59493e73af1ea594fdd862c5c8b69b6a650975ea4d2af2ea504a4efb046f701be39eb9846af242c544949b72cda94a4a0ac852e978df716b35ba00cea3a204d32b245a48b98e39972cd3c26d9069ba075206150ecb344907370db5f4831d8e30794f6734f4e3c87124a5d69b42c0511782774a7260e28614d103162dce796a9b993aca37c920b86868462f93c3181e88ee3614bee72ac3776edc69b45dd5750914e8a464ddc8992725512aa6d6596eb751baaf0a92a3bead4e24e731b94e6813b1e44d51ce72e10913ce54d375946c7296de7783acd1cc7554473bc9d653e029929730dd9628514515161acd45114a534c75acb39649986546b52d38d9a1ce92b695d7540c78cc55d9b9072a8945ca256f869c5ee845f49378a529c0dccd448838922e09b8a7f25e772f6eed932828455bbd8fca52955e9ad7a62365f901c9551d3bba33139dabd4c34ef2bd0634961c8a18966586eb71a6d2da6a6a689000a9d2708a5cb864284b935052e2a94f94d2d04752cf50819943991c501422c19a6c158226c9df20d0d12c0e1817466cd5ac75fca86e92b222845d581c9fa94ca71eb8ab6720255526b952a7434eba5f500b4d6f9081828a701bc4e11a5cb168161ad175d4d3a5b713b0982ecb69a9239149a8091b3d02b555a83029a2b19849cdd1ae3afbde58e7311b0c67acec886372956f7476ecb4c89a4628a972fdfa2f7b8a6ba050f1c685ef2b73988d8625bb45c5352fc189eeb3e315c499139c0cd3331af6698b7b2975f0b8a667474d5b07375c2f2bec56e6e64b6b52d20b6c54a0a6b569c7569a541d2b35e4d11626c94b2d4f3a14a52a628e2af52e820528800540e5261d935e55fbd4b3b1b809e69d910f25fc537d1132c6e0279a76444c97f148e889f6347bc8d2425c7024549a0e384cc3e9424ad46800a98cdaa754eaaf1c0681a87be3430c78dcd9a7063d8a9919bbb81e09ecfdd13e76692d36b755c14254b3c89049d900a78dc5d0ccdcf32d9a2d4907552a7a8084b13ec2f00a4f48bbb408e55665bce2ca94e6fafa8aceb0546a424eac7345eb534956634e5847a11f049c7776746023d8c6d956a29b5245eaa2b420e200d635523650ce2cd85e274c2082080c4233396bfc873cec11a68cce5a7f21ce56c109ec5e3e6448b3f8222e24f803a7698a7b3f8316f27c01d3b4c443736cdb0fc632d8f2e57353b236718cb63cb95cd4ec8a96c678798bb6b8316cde61c9150d7062ddbcc390428159ba195caff1ecf355b445849f062bf2bbc7b3cd56d116127c18996e698f90b795e00e48a0cb4cccf3cec8bf95e027922832d382cf3cec8d1ec611e71f92e04232bbc437f3a9fb2a85497021395de21af9d4fd95c4476369f321c90e0a7946d86dff002b73988d861c90e0a7a21b98f2b73988d861741be721da3e5c7e6d1ed89f697933fccf7c40b43cb8f311ed89f697933fccf7c3ea2f421cb1b829e69d910f25cfc1a3a225d8bc14f34ec88592de2d3ca217b0def223e57da557532e0e09016ae535ba3a054f4886251519db5666b3cfa8fa429fa1bc1d89117326e468cd3146a25db6a88d965347c1731ac04a3a14b427612216cae1ab6a5cbb2932d81525a2a03596c8501d9022b224e9bf7394d94fe88d2ca3b1916b7a6b17b20f423d086c69595c745925d5b41d6949eb023984bb91d4655bba84a7cd481d400868e0fe41f28ec10410cf3423339699d8e72b62634d199cb3cec7395b1309ec5e3e644890e088b793e00e9da62a2438222de4f803a7698881b66d87e3196c7972b9a9d91b38c65b1e5cae6a76454b633c3cc5d35c18b76f30e41150d7062ddbcc390428159ba196cacf28679a76889f29c18afcacf286b9a7ed458caf06265b9a63e445b4af013c9143967c1679e7ec98be95e00e48a1cb3e0b3cff00ba6347b18479c764b8109caef10d7cea7ec2e3d92e04272b7c4b5f3a9fb0b888ec6d3e6439679de8e51087fcadce6a3618559fc11ca36c25ff002b739a9d9096c3f590ed13fae9e623db13ed1f267f99ef8afb4bcb0f311ed8b0b4bc99ee60da61f517a10e58fc14f34ec88792e3e0d1d1132c7e0a79a76444c99f168e884ba0dfa8c5e50cb144e3df3855d0bdf0db126426235b9536097a8eb62ab48a11e70d14e318c648c8ad2714a81e304468698a69c4bb977845cd878ad47404d3ac8f718cc4a4b3ca3442147a081d27308db59525b936124d54715119aba871081119a6b868e3d95761195995200de2b7cd9d1709cdd19ba06b8af953431da7282c444db571782862858ce93ed0748fdd1cf1bc8d99dd772b9400f0ff0093a6b074f26781a3abc3f898b8799d344dc8b912f3c0d378dd14ad55f8a3af1e4063a5440b16ca44b341b472a9473a95a4989f0ce0f1197ea4efa041041018046672cf3b1cabfbb1a68cc6597098e55fdc852d8d3173224c87062de4b803a7698a890e0c5bc970074ed311035cdb0fc636d8f2e57353b236518db67cb55cd4ec8a96c678798b96b8316ede61c8229d9e0c5bb5c11c836428179fa196cabf296b99f78c584a1dec576559fd65be67de316127c18996e698f90b895e027922872cf82cf3fee98be95e027922872cf82d73fee98b7b1cf1e71c92e04272b7c4b5f389fb0b8548f021195a7e01af9c4fd85c4c76379f321e90e08e51b61a7fcadce6a764392077a9e8db0d3c7f5c779a8fb30ba0df3916d01fae9e623db13ad2f267b9836c419ef2d3cc46c89f697933dcd1b61f517a10bb23829e69d9117267c5a3a224d93c01cd3b222e4c9f83474425d06fd5f068dc5802a610d3b5cf106d37b7e8471151d83db0fb2ac23539d43cb64c8c85ab961766373680521b3459f388c0a5274535eb1ab3dc653da465e49f7c7090da8a79e704fd62239158ee6f463d7099e97f17e0e19a5294d5a5a1dba4e652ea12e20d52a151ee3c703af50d067d9198c839bdeb8d9380a2c74e07608b4937af12a39c9ac33933f87fa59a50f62dd0aac2a186d50fc072b541041040208ce659b04a1b700a84135e20aa63d9db1a38a8b4f2965185043efa10559af56e9d78d29d7055a2a2e9d95166daaddda15539634f2437838f1c78cd62ae5672cf242db72549ce0a56d579450c5a2275a399c41e4524fb62631a2f264e21f8c6e5324b7321c237aa48a1e31811cb9bae35e1d49cca1d623c79a4ac5d524281d04023a8c36ac984b85d99b92b4d0a0120d5470034931a66c5001a84312d20d366a86d293ac000f5c48ac28c68793271195cb268a5c6dda55205d3c46b51d753d50990b55b200ae39a9a6ba808d5ac02284020e70711d50c3124d20d50da127584807ac4271b65c33546a8765d242120e7a45165a34a2d21605421753c40822bd74eb8d0563c34381cd15464a54ecc959b6a20809c6f1c00d24ea113f2bd951974102b716951e4baa1b488b76249a41bc86d093ac2403d7120d0e109468d2596da6662c79d42aea41df1230d31ecdaeeceb95c2a94915d3852340c4ab6824a1094939ca5206c8f5d976d4429494a8a73120123909cd0b8741fd6f35d199b4c14cd851c014268746150626cf2af4b3d771de8cd8e6353d9176eb2958a2921438c0315b69dacd4b5d4141df02404800534d606ab508cdc928a5a91ac6702920035374e1d1113259cde2069a8c212de53b0ddd286427743414a0af19a0cc206ade6c4c5c4b080b22a54295c6b860339a189b46ce3377a7fa5cdad2e4a82c68143c58c2187e99e2bdbca5529f535713bda0ae3c222f53aab0946502b77535b9a0014df635a915036c571a2638b2254d1332a6cc54cc8bcca784b4d523354a541407156e81d31c5e49c5346eac104604114208d041cc63b01ca173762ddc4d0531c74d29a75910dbf69053b4530d1cdbe526a730a627960e3476f82cf93c35a71b4f5dc83dced852838f104208b893e7635246b030c797545c36da9b374e88f45b4a0abb71341867ea8745af5342d8ebfdd071c4c33cb2e4caf235bf7254b39788113a2b116aa7cc23929162daea011988af5c52699c592324f5542a0820866621d1bd34d46383775642973684a5249bb800093d40477c8c5f740b61c9761d5b6b285246f48a0a619f1c0f4c5c754d0d3d4c3e4789f6a4cb4dd9f7af385414fa540fc4a8dccb75292052b5d275458b9674eaf856759e3fe166d7da008aac9bcb19c9866f2e66714a0b29bd2ed489452893456e801bd8eaa5088b6194335e9ed3f5366fbe20d971093934f1cf67487fcacd7e28697920e9ff47c8693e4933aa99f751d3af087ce51ccfa6b4bd459df8e10bca49ac2931690e2dc2cdcdc75569803cdfb66a2c26de625d0d77a04dcae0cb6a4362aa277a95a8a867d273d627f7d3ffb32fa87be21d819480cba4b8eba575554be9690e70d54bc96aa8185294d14ae358b0fd216fd27688cdd1a2e3ae837df2ffeccbea1ef8f7be5ff00d997d43df0bfd216fd276883f485bf49da21683f3f611df0ff00eccbea1ef83be1ff00d997d43df0bfd216fd26c8f3f4811e93b441a079fb09ef87bf665f50f7c1bbbdfb3afa87be17fa408f49da20fd2047a4d90681e7ec37bbbdfb3afa8425530f7eccbfa30f787d1e936424e5023d26c8341f9fb1e48eeeb751f04a6d2955545586143871d62a7ba456fb74f317da445c4b5bb7dd42106f5e5508cfbdc6a7a33c53f7453f08cf21db0fd24c6feaab31abad41ae09490388923f7c59174a57bb0cea712ae84a1351d655152b51dcd5c44f6507b62d5692a61207090e049fe781edd9199dba1613abdcd5bb274bd7fa00bbb2bd70fda6a290a74672e850e44e0361eb86a611ba4b5139d2edcfac46c20c4974872596919d2bb9f5f0ec84c11eda2778e3a9f8c50472008221c9a5de429c1f1823626bdb1e2082cb8de9414a7a37b4f6c2d9a6e6b6f4a2ef684fb6b0862de7494858d374f50f7c3c5ddf24f4f5c34c8052a479a4768f7d61d6e8524f9b51d50c43a9733f38757e4469a48fc1a39a9d8233252303ac57aa91a693f168e6a7608d319c7e2b643d041046a718455db813741284a8e38a929566e708b48aaca19a086cd50178134209ad345045477039f4c6594b0deaa6dc42ef5c212d4ba929c7e202ca89c298424655a7e24cccb9c66cbafd94260b2b2925ae296b44a30a4921482c3a4e2681454951143ab3e06252b2b25b5d92ae73d70f529b24427b9ad69b0da728ded0a74f2d96a1f78429594733850ebe1598e1c29a4076b5ac24e584b0f8964744d23fe947a72bd834f81b248c7fce93980a923e03ae10abb1a6b16d70a610a74a37437af7c0963e31a7c12ca8a70a6738e7d3137c26d6b475262358b352ef308737195df03e2aeb8de0a237abba2f66d59eb1368c7a167e827dd10df7354b4e511e136b5a3a841e136b5a3a842fe03d0b5f453ee8f6ac7a26be8a7dd0afb8f87ed1bf0a37ad1d420f0a37ad1d421654c7a26be8a7dd05f63d135f453ee82fb870fda23c28deb475083c26deb4750872ac7a26be8a7dd0558f42d7d14fba0bee15f68d7849bf91f44424da4d7c8fa2987aac7a16be8a7dd09258f42cfd14fba15f71d7da372d6ba7744250126faae9a000d2871c2297ba39a2dae6afb088d04b4db2871212d212566ed50002302740cd8467fba3f0da1ad0b1d6530fd228e99569462d0a05684e8567e9a93165623854fad07ce1f485ea456aa95bde6829eba53b2b1394f5ca3c33adc4afa52948da55d7199da4fb05477471b3e93b42570f596921f750735f1d78910dda0eee652ea452f3b7fd9b2245ace5d4eea9f8ce0575603676c03152ed94ccba0f04ddebaa290f2105330bae62003f569db4845a4bab6a753f18a0f2707db0b9b70a9a2e0cea08fbb0863cd208755c74da290e32d101435c34fba4a12b19cddaf281ef87cb86f24e8cfd700872e1a720a75ffee35327e2d1cd4ec11960e1c78d40f47e691a992f168e6a7608d319c7e2b643d041046a7184565ba1b28a397b31e052b4e9c22ce33b97369265a54bab6377482014855c201ce6f004c547703072f25642ef2fbd5e74de502a757295bc0e228eb80d3a298c59b6f48a46f64cff00cc4923ec3d15b67db3661652e779b6d859268a79f2526f5d254a0d914c01ad74c4955ab650ce996e89d6362d40c266b544c368c98cf2c07fc7b0363f0855b12229f009c491fc62ce815af8fcd117c3164e8435d13525ff00561d45a567102eb24835cd33254a0d27e1a94e3e4841fd9a6b2989575a4b81aa055701305c18288c1685949cda0c4af06cafa33eb5cfc511a464657734148525252140074505ec7e21ba73e71843fde12ff2fd6abdf19b66897c8bf074afa33eb5cfc50783a5bd19f58bfc509ef097f97eb55ef83bc65fe5fad57be0b1d7c8a367cb7a33eb17f8a0ef096f467d62ff001427bc65f52fd6abdf009197d4af5aaf7c1615f22bc1f2de8cfac5fe283bc25bd19f58bfc509ef197f95eb55ef83bc98f95eb5505857cfefe457784b7a33eb17f8a1264257d19f5abfc51e19363e57ad5420c94bfcbf5a610ebe7f7f23f28dcb36e20a5ba2946ea4de52e8483e71c396293ba326ab6790f61116f26d4ba1d41014545544d56554274d0c5477473be6b9abfbb0fa0a2bfea8c3adbde2c692491d14ff00ca2d12c071adcab8a1c4539a400761315c82438dd7304d4f51aed8b1b1d25332b0acd7c23a48553d9d710761629bafb1734a5ea74124ecaf543edb81d65c6f4a5da7415d47b621d8cd943cb4ab3176e0e5baafddd70fc933b9bee54ef4b8074a813ed891925a74292eb5e6a934e4de8fcf2c38cac1dd1ad5748faa0fb2184b41130e2b45500f29298794ddc796ad02ed7a6ec031e616095a3cd229c9400fe78e1c69d050a3a89a7b21b0ddd71475903ac8875b6c0c35fb2001d2b141cd2635329e2d1cd4ec1196b9d9846a24fc5a39a9d8234c671f8ad90f410411a9c61187ee859572f2e3bde625d4ea5c1880bb9d4463d4446e229b28326256740130ddebb98852907ad24561a1a39959b69d87753faaa5071de3ab7d4a4ef8f09650514d3c338119b30d04be5059806f1a6e9f226e5b617c1ec8b17f21acb974826554ba66a36ecc2ba6e851239708f1c167354a49bb9bd0b8df63974f640cb4c89fa5367fa1feb527ed998f4654d9f51fab8e5ef8923ffe987156b488cd2ce7495276130daad9931fe6cafa4f9fb2930875f268586251694ac3428a4850c466201ce951073e704885f794afa21d67df10251c925a12b2909a8cc5c70118d310aa11d2043b76478bd6aff14416976649ef295f443e91f7c1de32be8beb1f7c46bb23c5eb97f8a0bb23c5eb97f8a158ebb324f794afa2fac7df009295f45f58fe288d764b8bd72ff141764b57f4abfc5058576649ef296f45f58fe283bca57d17d63ef88f764bf2f2ff00141764b8bd72ff0014163aecc91de72de887d23ef8499495f443e91f7c31764b50f5abfc51e14c96a1eb57f8a0b0e1ecc99282590e26eb60289ba0e7a13cb9a287ba31dfb3c6950eb298b8905ca25c4ee6917c9a0df15904e9a13872c54f744a5e679147a8a61f4082ac88c4a9cc16ad554f5d0fb044b98748690e0cea5249e509401b22316c1051e92a7a4d29f662c65025cbcd1cc95a143900c475244667612ad7594a10e0d2e93d353ee8916c5772be34b978f591ee86a41d0ea14d915baedee8c55b476c3b22fee8971a3a1caf45e24eced84343b6802a654b19d4524f2ef61c98054cded26e57a0261a957ef29e68f9c08eb483ec875873e11d6f41ba47152e8d9b210c5ba0a9093a70af426241a95057e78e1896737cb49cd5047450438cb86e2869aedfc980192024f6de8d4c9f8b47353b04656f9c39b48d5ca7011cd1b04698ce3f15b21d820823538c20820800cfe58da4fcbb6db8c80a1ba00e0b8566e1ce404e3519f4c6765f2b67d44812ae2c57052194ad2452bc15bcd2876c74282029355b18636eda0acf26b3cb289ffbc309369cefec3fd4d3ff00751bb8200b5ec67ecfb45cdcd37e59695635018290313f1429407d23123c227d02fd52a2e208543e25ec53f844fa05faa541e113e817ea9517104141c6bd8a8f08abd02fd52a3cf08abd02fd52a2e2082838d7b151e113e817ea951e7840fa05faa545c41050f8d7b14a6d03fb3afd4aa1267cfecebf52af745e4105071af629a52794569025d42a68496d48a0d26f18a0ee89c26f890bda23710c4d49b6e0a3884aa99af0ad3935426b4a1c7225252a38e0410b41f3124f50a7b445859a8b8fad5a0b8948e45249fbc98d9650e4e2773acbb22fdec684e2920d70275dd8c54dcacfa4d3bd16520d4908273002a286a70023371676c32c64ac9f20d6e4f2ab994f14f45d57b4887da6c36f2d7a14e84f5835ed222b1e9e79405585920def14e56bd11e99f9870dd5cb2c0adea965e4e3ce3844d1a5a2ddc4dc75c5fca40eb29ac3af0bae2d7a8a7b6efb223bbbb2d2429acf4277aaae14e3e2880f4f4d9aa7bd1c29c31dc1ed1c7d10a98f89178a012b27e501d07187922840d75ecfc98a313136b4dd4cab855f14169c40a818554aa01d262c2cd91b41646eac86f8c96f62544f643e164b9c56ecb0ae7e5a46a657809e68d821a459ed0cc81d353b62546b18d1c39b2a9d504104116601041040010410400104104001041040010410400104104001041040010410400104104001041040010410400104104001041040010410400104104007ffd9, 1),
(3, 'Caja de pinturas', 300.00, 96, NULL, 1);

--
-- Disparadores `productos`
--
DELIMITER $$
CREATE TRIGGER `trg_productos_bi` BEFORE INSERT ON `productos` FOR EACH ROW BEGIN
  IF NEW.Stock IS NULL THEN SET NEW.Stock = 0; END IF;
  IF NEW.Stock <= 0 THEN
    SET NEW.Stock = 0, NEW.Activo = 0;
  ELSE
    SET NEW.Activo = 1;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_productos_bu` BEFORE UPDATE ON `productos` FOR EACH ROW BEGIN
  IF NEW.Stock IS NULL THEN SET NEW.Stock = 0; END IF;
  IF NEW.Stock <= 0 THEN
    SET NEW.Stock = 0, NEW.Activo = 0;
  ELSE
    SET NEW.Activo = 1;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_kits`
--

CREATE TABLE `productos_kits` (
  `id_kit` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_kits`
--

INSERT INTO `productos_kits` (`id_kit`, `id_producto`, `cantidad`) VALUES
(3, 3, 0),
(2, 2, 4),
(1, 3, 1),
(1, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `id_taller` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `id_maestra` int(11) DEFAULT NULL,
  `costo_base` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `ingreso_bruto` decimal(10,2) DEFAULT NULL,
  `gastos` decimal(10,2) DEFAULT NULL,
  `porcentaje_delia` decimal(5,2) DEFAULT NULL,
  `porcentaje_caro` decimal(5,2) DEFAULT NULL,
  `precio_preferencial` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`id_taller`, `nombre`, `id_maestra`, `costo_base`, `status`, `ingreso_bruto`, `gastos`, `porcentaje_delia`, `porcentaje_caro`, `precio_preferencial`) VALUES
(1, 'Taller de Repostería', 1, 1500.00, 'activo', 5000.00, 2000.00, 40.00, 60.00, 0),
(2, 'Taller de Maquillaje', 2, 2000.00, 'activo', 6000.00, 2500.00, 50.00, 50.00, 1),
(3, 'Taller de Manualidades', NULL, 1000.00, 'pendiente', 0.00, 0.00, 30.00, 70.00, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id_Usuario` int(11) NOT NULL,
  `Nombre` varchar(60) NOT NULL,
  `Apat` varchar(30) NOT NULL,
  `Amat` varchar(30) NOT NULL,
  `Correo` varchar(60) NOT NULL,
  `User` varchar(15) NOT NULL,
  `Pass` varchar(255) NOT NULL,
  `Cargo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id_Usuario`, `Nombre`, `Apat`, `Amat`, `Correo`, `User`, `Pass`, `Cargo`) VALUES
(1, 'Cesar Alejandro', 'Hernandez', 'Medina', 'cesarflow4k@gmail.com', 'Magik', '$2y$10$sY.jyXrN6oWtszl401DfbePxrwrtUYNSB4X9D/TXEZs.UrOzEPLr2', 'Admin'),
(2, 'salvador', 'de la garza', 'cvazos', 'salba3@gmail.com', 'salba', '$2y$10$l6LT13XF/PdNnnut897bUO0fGz5SGMditVAiVkh/c.vAeX.JGBwye', 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idVenta` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `comprador_tipo` varchar(30) NOT NULL,
  `id_alumna` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_taller` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idVenta`, `fecha`, `comprador_tipo`, `id_alumna`, `id_cliente`, `id_taller`, `total`, `estado`) VALUES
(1, '2025-09-17 23:28:46', 'Cliente', NULL, 1, 2, 1600.00, 'Pendiente'),
(2, '2025-09-18 04:00:31', 'Alumna', 1, NULL, 1, 800.00, 'Pendiente'),
(5, '2025-09-19 15:25:27', 'Alumna', 3, NULL, NULL, 3900.00, 'Pendiente'),
(6, '2025-09-19 16:07:47', 'alumna', 2, NULL, NULL, 3600.00, 'Pendiente'),
(7, '2025-09-19 16:08:46', 'alumna', 1, NULL, NULL, 1500.00, 'Pendiente'),
(8, '2025-09-19 16:09:34', 'alumna', 1, NULL, NULL, 3800.00, 'Pendiente'),
(9, '2025-09-19 16:10:53', 'alumna', 2, NULL, NULL, 1600.00, 'Pendiente'),
(10, '2025-09-19 16:14:14', 'alumna', 1, NULL, NULL, 4200.00, 'Pendiente'),
(11, '2025-09-19 17:38:20', 'alumna', 3, NULL, NULL, 4000.00, 'Pendiente'),
(12, '2025-09-19 18:08:25', 'alumna', 2, NULL, NULL, 800.00, 'Pendiente'),
(13, '2025-09-21 01:56:11', 'Alumna', 3, NULL, NULL, 2100.00, 'Pendiente'),
(14, '2025-09-21 01:56:19', 'Cliente', NULL, 2, NULL, 800.00, 'Pendiente'),
(15, '2025-09-21 02:20:00', 'Cliente', NULL, 2, NULL, 1300.00, 'Pendiente'),
(16, '2025-09-21 02:20:17', 'Cliente', NULL, 1, NULL, 1900.00, 'Pendiente'),
(17, '2025-09-21 02:20:35', 'Cliente', NULL, 1, NULL, 1600.00, 'Pendiente'),
(18, '2025-09-21 02:20:52', 'Alumna', 2, NULL, NULL, 1600.00, 'Pendiente'),
(19, '2025-09-21 02:21:06', 'Cliente', NULL, 1, NULL, 2800.00, 'Pendiente'),
(20, '2025-09-21 07:04:33', 'Alumna', 1, NULL, NULL, 4500.00, 'Pendiente'),
(21, '2025-09-21 07:05:18', 'Cliente', NULL, 1, NULL, 1600.00, 'Pendiente'),
(22, '2025-09-21 07:14:52', 'cliente', NULL, 3, NULL, 500.00, 'Pendiente'),
(23, '2025-09-22 02:02:00', 'alumna', 2, NULL, NULL, 500.00, 'Pendiente'),
(24, '2025-09-22 07:41:14', 'alumna', 2, NULL, NULL, 1600.00, 'Pendiente'),
(25, '2025-09-26 08:58:16', 'alumna', 3, NULL, NULL, 300.00, 'Pendiente'),
(26, '2025-09-26 21:02:39', 'alumna', 1, NULL, NULL, 300.00, 'Pendiente'),
(27, '2025-10-01 01:30:52', 'cliente', NULL, 2, NULL, 800.00, 'Pendiente'),
(28, '2025-10-02 23:22:00', 'alumna', 3, NULL, NULL, 800.00, 'Pendiente'),
(29, '2025-10-03 06:24:53', 'cliente', NULL, 5, NULL, 800.00, 'Pendiente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id_agenda`),
  ADD KEY `id_taller` (`id_taller`);

--
-- Indices de la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  ADD PRIMARY KEY (`id_agenda_curso`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `alumnas`
--
ALTER TABLE `alumnas`
  ADD PRIMARY KEY (`id_alumna`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD PRIMARY KEY (`id_descuento`),
  ADD KEY `id_alumna` (`id_alumna`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`idDetalle`),
  ADD KEY `fk_detalle_venta_venta` (`idVenta`),
  ADD KEY `fk_detalle_venta_producto` (`id_producto`),
  ADD KEY `fk_detalle_venta_kit` (`id_kit`);

--
-- Indices de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `idVenta` (`idVenta`),
  ADD KEY `fk_historial_pagos_intermedia` (`id_intermedia`);

--
-- Indices de la tabla `institutos`
--
ALTER TABLE `institutos`
  ADD PRIMARY KEY (`id_instituto`);

--
-- Indices de la tabla `interesadas`
--
ALTER TABLE `interesadas`
  ADD PRIMARY KEY (`id_interesada`),
  ADD KEY `id_alumna` (`id_alumna`),
  ADD KEY `id_agenda` (`id_agenda`),
  ADD KEY `id_agenda_curso` (`id_agenda_curso`);

--
-- Indices de la tabla `intermedia_a`
--
ALTER TABLE `intermedia_a`
  ADD PRIMARY KEY (`id_intermedia`),
  ADD KEY `idx_intermedia_alumna` (`id_alumna`),
  ADD KEY `idx_intermedia_agenda` (`id_agenda`),
  ADD KEY `idx_intermedia_curso` (`id_agenda_curso`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_inventario`),
  ADD UNIQUE KEY `uq_inventario_instituto_producto` (`id_instituto`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_instituto` (`id_instituto`);

--
-- Indices de la tabla `inventario_stock`
--
ALTER TABLE `inventario_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_instituto` (`id_instituto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `kits_productos`
--
ALTER TABLE `kits_productos`
  ADD PRIMARY KEY (`id_kit`),
  ADD KEY `idx_kits_activo_stock` (`Activo`,`Stock`);

--
-- Indices de la tabla `maestras`
--
ALTER TABLE `maestras`
  ADD PRIMARY KEY (`id_maestra`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `idx_productos_activo_stock` (`Activo`,`Stock`);

--
-- Indices de la tabla `productos_kits`
--
ALTER TABLE `productos_kits`
  ADD KEY `id_kit` (`id_kit`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`id_taller`),
  ADD KEY `id_maestra` (`id_maestra`),
  ADD KEY `idx_talleres_id_maestra` (`id_maestra`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id_Usuario`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idVenta`),
  ADD KEY `fk_venta_taller` (`id_taller`),
  ADD KEY `id_alumna` (`id_alumna`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id_agenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  MODIFY `id_agenda_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alumnas`
--
ALTER TABLE `alumnas`
  MODIFY `id_alumna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  MODIFY `id_descuento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `institutos`
--
ALTER TABLE `institutos`
  MODIFY `id_instituto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `interesadas`
--
ALTER TABLE `interesadas`
  MODIFY `id_interesada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `intermedia_a`
--
ALTER TABLE `intermedia_a`
  MODIFY `id_intermedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario_stock`
--
ALTER TABLE `inventario_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `kits_productos`
--
ALTER TABLE `kits_productos`
  MODIFY `id_kit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `maestras`
--
ALTER TABLE `maestras`
  MODIFY `id_maestra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id_taller` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`);

--
-- Filtros para la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  ADD CONSTRAINT `fk_agenda_cursos__cursos` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta_kit` FOREIGN KEY (`id_kit`) REFERENCES `kits_productos` (`id_kit`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_venta` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD CONSTRAINT `fk_historial_pagos_intermedia` FOREIGN KEY (`id_intermedia`) REFERENCES `intermedia_a` (`id_intermedia`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `historial_pagos_ibfk_3` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`);

--
-- Filtros para la tabla `interesadas`
--
ALTER TABLE `interesadas`
  ADD CONSTRAINT `interesadas_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`),
  ADD CONSTRAINT `interesadas_ibfk_2` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  ADD CONSTRAINT `interesadas_ibfk_3` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`);

--
-- Filtros para la tabla `intermedia_a`
--
ALTER TABLE `intermedia_a`
  ADD CONSTRAINT `fk_intermediaA_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_intermediaA_alumna` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_intermediaA_curso` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_inventario__institutos` FOREIGN KEY (`id_instituto`) REFERENCES `institutos` (`id_instituto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `inventario_stock`
--
ALTER TABLE `inventario_stock`
  ADD CONSTRAINT `fk_stock__institutos` FOREIGN KEY (`id_instituto`) REFERENCES `institutos` (`id_instituto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock__productos` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_kits`
--
ALTER TABLE `productos_kits`
  ADD CONSTRAINT `productos_kits_ibfk_1` FOREIGN KEY (`id_kit`) REFERENCES `kits_productos` (`id_kit`),
  ADD CONSTRAINT `productos_kits_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD CONSTRAINT `fk_talleres_maestras` FOREIGN KEY (`id_maestra`) REFERENCES `maestras` (`id_maestra`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_taller` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
