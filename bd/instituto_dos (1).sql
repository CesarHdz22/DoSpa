-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 07:22:28
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
  `cant_interesadas` int(11) NOT NULL,
  `ingreso_total` decimal(12,2) DEFAULT 0.00,
  `gastos_totales` decimal(12,2) DEFAULT 0.00,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id_agenda`, `id_taller`, `fecha`, `hora_inicio`, `hora_fin`, `ubicacion`, `variacion`, `cant_inscritas`, `cant_interesadas`, `ingreso_total`, `gastos_totales`, `id_sucursal`) VALUES
(1, 1, '2025-08-27', NULL, NULL, 'GUADALAJARA', NULL, 4, 0, 0.00, 0.00, 1),
(2, 2, '2025-04-16', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1),
(3, 3, '2025-04-09', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1),
(4, 4, '2025-04-04', NULL, NULL, 'GUADALAJARA', NULL, 5, 0, 0.00, 0.00, 1),
(5, 5, '2025-12-05', NULL, NULL, 'GUADALAJARA', NULL, 9, 0, 0.00, 0.00, 1),
(6, 6, '2025-05-15', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1),
(7, 6, '2025-08-28', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1),
(8, 7, '2025-06-25', NULL, NULL, 'GUADALAJARA', NULL, 10, 0, 0.00, 0.00, 1),
(9, 8, '2025-06-23', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1),
(10, 9, '2025-06-26', NULL, NULL, 'GUADALAJARA', NULL, 7, 0, 0.00, 0.00, 1),
(11, 10, '2025-07-17', NULL, NULL, 'GUADALAJARA', NULL, 8, 0, 0.00, 0.00, 1),
(12, 11, '2025-09-09', NULL, NULL, 'GUADALAJARA', NULL, 4, 0, 0.00, 0.00, 1),
(13, 12, '2025-07-15', NULL, NULL, 'GUADALAJARA', NULL, 3, 0, 0.00, 0.00, 1),
(14, 13, '2025-08-26', NULL, NULL, 'GUADALAJARA', NULL, 6, 0, 0.00, 0.00, 1),
(15, 14, '2025-09-21', NULL, NULL, 'GUADALAJARA', NULL, 7, 0, 0.00, 0.00, 1),
(16, 1, '2025-03-31', NULL, NULL, 'GUADALAJARA', NULL, 8, 0, 0.00, 0.00, 1),
(17, 14, '2025-09-23', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1),
(18, 13, '2025-09-24', NULL, NULL, 'GUADALAJARA', NULL, 0, 0, 0.00, 0.00, 1);

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
  `cant_interesadas` int(11) NOT NULL,
  `ingreso_total` decimal(12,2) DEFAULT 0.00,
  `gastos_totales` decimal(12,2) DEFAULT 0.00,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `imagen` blob NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnas`
--

INSERT INTO `alumnas` (`id_alumna`, `nombre`, `apat`, `amat`, `telefono`, `correo`, `direccion`, `descuento_aplicado`, `tipo_descuento`, `imagen`, `estatus`) VALUES
(1, 'Alumna 1', '', '', '7618411063', NULL, NULL, 0, NULL, '', 1),
(2, 'Alumna 2', '', '', '9098144518', NULL, NULL, 0, NULL, '', 1),
(3, 'Alumna 3', '', '', '3170906463', NULL, NULL, 0, NULL, '', 1),
(4, 'Alumna 4', '', '', '2460392687', NULL, NULL, 0, NULL, '', 1),
(5, 'Alumna 5', '', '', '5842647678', NULL, NULL, 0, NULL, '', 1),
(6, 'Alumna 6', '', '', '4744033851', NULL, NULL, 0, NULL, '', 1),
(7, 'Alumna 7', '', '', '3263595484', NULL, NULL, 0, NULL, '', 1),
(8, 'Alumna 8', '', '', '7550161801', NULL, NULL, 0, NULL, '', 1),
(9, 'Alumna 9', '', '', '2182547033', NULL, NULL, 0, NULL, '', 1),
(10, 'Alumna 10', '', '', '8043424775', NULL, NULL, 0, NULL, '', 1),
(11, 'Alumna 11', '', '', '5140259648', NULL, NULL, 0, NULL, '', 1),
(12, 'Alumna 12', '', '', '2434065261', NULL, NULL, 0, NULL, '', 1),
(13, 'Alumna 13', '', '', '4889696800', NULL, NULL, 0, NULL, '', 1),
(14, 'Alumna 14', '', '', '2758855526', NULL, NULL, 0, NULL, '', 1),
(15, 'Alumna 15', '', '', '8333093711', NULL, NULL, 0, NULL, '', 1),
(16, 'Alumna 16', '', '', '1707109391', NULL, NULL, 0, NULL, '', 1),
(17, 'Alumna 17', '', '', '9062852995', NULL, NULL, 0, NULL, '', 1),
(18, 'Alumna 18', '', '', '4869999090', NULL, NULL, 0, NULL, '', 1),
(19, 'Alumna 19', '', '', '4974857761', NULL, NULL, 0, NULL, '', 1),
(20, 'Alumna 20', '', '', '3703039244', NULL, NULL, 0, NULL, '', 1),
(21, 'Alumna 21', '', '', '8943837624', NULL, NULL, 0, NULL, '', 1),
(22, 'Alumna 22', '', '', '3078354945', NULL, NULL, 0, NULL, '', 1),
(23, 'Alumna 23', '', '', '4239254653', NULL, NULL, 0, NULL, '', 1),
(24, 'Alumna 24', '', '', '4038839106', NULL, NULL, 0, NULL, '', 1),
(25, 'Alumna 25', '', '', '3448235481', NULL, NULL, 0, NULL, '', 1),
(26, 'Alumna 26', '', '', '5052302197', NULL, NULL, 0, NULL, '', 1),
(27, 'Alumna 27', '', '', '1843943127', NULL, NULL, 0, NULL, '', 1),
(28, 'Alumna 28', '', '', '4080089819', NULL, NULL, 0, NULL, '', 1),
(29, 'Alumna 29', '', '', '1781141006', NULL, NULL, 0, NULL, '', 1),
(30, 'Alumna 30', '', '', '4329413619', NULL, NULL, 0, NULL, '', 1),
(31, 'Alumna 31', '', '', '1156375669', NULL, NULL, 0, NULL, '', 1),
(32, 'Alumna 32', '', '', '2937932266', NULL, NULL, 0, NULL, '', 1),
(33, 'Alumna 33', '', '', '6782150178', NULL, NULL, 0, NULL, '', 1),
(34, 'Alumna 34', '', '', '4657190973', NULL, NULL, 0, NULL, '', 1),
(35, 'Alumna 35', '', '', '8689724986', NULL, NULL, 0, NULL, '', 1),
(36, 'Alumna 36', '', '', '4050768097', NULL, NULL, 0, NULL, '', 1),
(37, 'Alumna 37', '', '', '4593988043', NULL, NULL, 0, NULL, '', 1),
(38, 'Alumna 38', '', '', '3823885844', NULL, NULL, 0, NULL, '', 1),
(39, 'Alumna 39', '', '', '7679480848', NULL, NULL, 0, NULL, '', 1),
(40, 'Alumna 40', '', '', '7752459515', NULL, NULL, 0, NULL, '', 1),
(41, 'Alumna 41', '', '', '3355609221', NULL, NULL, 0, NULL, '', 1),
(42, 'Alumna 42', '', '', '1707832705', NULL, NULL, 0, NULL, '', 1),
(43, 'Alumna 43', '', '', '6990870962', NULL, NULL, 0, NULL, '', 1),
(44, 'Alumna 44', '', '', '6483679473', NULL, NULL, 0, NULL, '', 1),
(45, 'Alumna 45', '', '', '5883301490', NULL, NULL, 0, NULL, '', 1),
(46, 'Alumna 46', '', '', '9356722133', NULL, NULL, 0, NULL, '', 1),
(47, 'Alumna 47', '', '', NULL, NULL, NULL, 0, NULL, '', 1),
(48, 'Alumna 48', '', '', '8823074726', NULL, NULL, 0, NULL, '', 1),
(49, 'Alumna 49', '', '', '5464185130', NULL, NULL, 0, NULL, '', 1),
(50, 'Alumna 50', '', '', '2722270534', NULL, NULL, 0, NULL, '', 1),
(51, 'Alumna 51', '', '', '3397508568', NULL, NULL, 0, NULL, '', 1),
(52, 'Alumna 52', '', '', '1548279906', NULL, NULL, 0, NULL, '', 1),
(53, 'Alumna 53', '', '', '4711184323', NULL, NULL, 0, NULL, '', 1),
(54, 'Alumna 54', '', '', '2526376253', NULL, NULL, 0, NULL, '', 1),
(55, 'Alumna 55', '', '', '8973146653', NULL, NULL, 0, NULL, '', 1),
(56, 'Alumna 56', '', '', '8551938724', NULL, NULL, 0, NULL, '', 1),
(57, 'Alumna 57', '', '', '4262584590', NULL, NULL, 0, NULL, '', 1),
(58, 'Alumna 58', '', '', '1455141135', NULL, NULL, 0, NULL, '', 1),
(59, 'Alumna 59', '', '', '5704112638', NULL, NULL, 0, NULL, '', 1),
(60, 'Alumna 60', '', '', '8125162039', NULL, NULL, 0, NULL, '', 1),
(61, 'Alumna 61', '', '', NULL, NULL, NULL, 0, NULL, '', 1),
(62, 'Alumna 62', '', '', '1343803650', NULL, NULL, 0, NULL, '', 1),
(63, 'Alumna 63', '', '', '1164838979', NULL, NULL, 0, NULL, '', 1),
(64, 'Alumna 64', '', '', '6880673935', NULL, NULL, 0, NULL, '', 1),
(65, 'Alumna 65', '', '', '2301003509', NULL, NULL, 0, NULL, '', 1),
(66, 'Alumna 66', '', '', '5002956442', NULL, NULL, 0, NULL, '', 1),
(67, 'Alumna 67', '', '', '5014426326', NULL, NULL, 0, NULL, '', 1),
(68, 'Alumna 68', '', '', '5206128246', NULL, NULL, 0, NULL, '', 1),
(69, 'Alumna 69', '', '', '7023459222', NULL, NULL, 0, NULL, '', 1),
(70, 'Alumna 70', '', '', '3431606139', NULL, NULL, 0, NULL, '', 1),
(71, 'Alumna 71', '', '', '2307267659', NULL, NULL, 0, NULL, '', 1),
(72, 'Alumna 72', '', '', '1931740135', NULL, NULL, 0, NULL, '', 1),
(73, 'Alumna 73', '', '', '8191326481', NULL, NULL, 0, NULL, '', 1),
(74, 'Alumna 74', '', '', '4786909739', NULL, NULL, 0, NULL, '', 1),
(75, 'Alumna 75', '', '', NULL, NULL, NULL, 0, NULL, '', 1),
(76, 'Alumna 76', '', '', '2746053248', NULL, NULL, 0, NULL, '', 1),
(77, 'Alumna 77', '', '', NULL, NULL, NULL, 0, NULL, '', 1),
(78, 'Alumna 78', '', '', '6150862334', NULL, NULL, 0, NULL, '', 1),
(79, 'Alumna 79', '', '', NULL, NULL, NULL, 0, NULL, '', 1),
(80, 'Alumna 80', '', '', NULL, NULL, NULL, 0, NULL, '', 1);

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
  `precio_preferencial` tinyint(1) DEFAULT 0,
  `ingreso_total` decimal(12,2) DEFAULT 0.00,
  `gastos_totales` decimal(12,2) DEFAULT 0.00,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `subtotal` decimal(10,2) NOT NULL,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id_egreso` int(11) NOT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `id_agenda_curso` int(11) DEFAULT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(10,0) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `concepto` varchar(100) DEFAULT 'servicio',
  `comprobante` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `total` decimal(10,2) NOT NULL,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestras_asignadas`
--

CREATE TABLE `maestras_asignadas` (
  `id_asignacion` int(11) NOT NULL,
  `id_maestra` int(11) NOT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `id_agenda_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `status` enum('activo','cancelado','reprogramado') DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `id_sucursal` int(11) NOT NULL,
  `nombre_sucursal` varchar(100) NOT NULL,
  `calle` varchar(100) NOT NULL,
  `numero` varchar(100) NOT NULL,
  `colonia` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `codigo_postal` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `fecha_apertura` date DEFAULT NULL,
  `estado_sucursal` enum('Activa','Inactiva') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`id_sucursal`, `nombre_sucursal`, `calle`, `numero`, `colonia`, `ciudad`, `estado`, `codigo_postal`, `telefono`, `correo_electronico`, `fecha_apertura`, `estado_sucursal`) VALUES
(1, 'GUADALAJARA', '', '', '', '', '', '', NULL, NULL, NULL, 'Activa');

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
  `porcentaje_delia` decimal(5,2) DEFAULT NULL,
  `porcentaje_caro` decimal(5,2) DEFAULT NULL,
  `precio_preferencial` decimal(10,2) DEFAULT 0.00,
  `ingreso_total` decimal(12,2) DEFAULT 0.00,
  `gastos_totales` decimal(12,2) DEFAULT 0.00,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`id_taller`, `nombre`, `id_maestra`, `costo_base`, `status`, `porcentaje_delia`, `porcentaje_caro`, `precio_preferencial`, `ingreso_total`, `gastos_totales`, `id_sucursal`) VALUES
(1, 'Peeling Ácidos', NULL, 1000.00, 'Activo', NULL, NULL, 1000.00, 0.00, 0.00, 1),
(2, 'Levantamiento Glúteo Madero Y Peptona', NULL, 1500.00, 'Activo', NULL, NULL, 1500.00, 0.00, 0.00, 1),
(3, 'Enzimas', NULL, 3000.00, 'Activo', NULL, NULL, 3000.00, 0.00, 0.00, 1),
(4, 'Adn De Salmón', NULL, 1200.00, 'Activo', NULL, NULL, 1200.00, 0.00, 0.00, 1),
(5, 'Ampolletería Facial', NULL, 1100.00, 'Activo', NULL, NULL, 1100.00, 0.00, 0.00, 1),
(6, 'Carrusel Corporal', NULL, 1250.00, 'Activo', NULL, NULL, 1250.00, 0.00, 0.00, 1),
(7, 'Autodermapen', NULL, 500.00, 'Activo', NULL, NULL, 500.00, 0.00, 0.00, 1),
(8, 'Carrusel Lifting Facial', NULL, 1250.00, 'Activo', NULL, NULL, 1250.00, 0.00, 0.00, 1),
(9, 'Dermapen Con Exosomas', NULL, 1250.00, 'Activo', NULL, NULL, 1250.00, 0.00, 0.00, 1),
(10, 'Laser Ndyag', NULL, 1250.00, 'Activo', NULL, NULL, 1250.00, 0.00, 0.00, 1),
(11, 'Carrera Especializada Intensiva Septoct', NULL, 19000.00, 'Activo', NULL, NULL, 19000.00, 0.00, 0.00, 1),
(12, 'Dermapen Capilar', NULL, 1250.00, 'Activo', NULL, NULL, 1250.00, 0.00, 0.00, 1),
(13, 'Limpieza Facial Profunda', NULL, 1500.00, 'Activo', NULL, NULL, 1500.00, 0.00, 0.00, 1),
(14, 'Ampolletería Con Dermapen', NULL, 1500.00, 'Activo', NULL, NULL, 1500.00, 0.00, 0.00, 1);

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
  `estado` varchar(30) NOT NULL,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id_agenda`),
  ADD UNIQUE KEY `taller_fecha_lugar` (`id_taller`,`fecha`,`id_sucursal`),
  ADD KEY `id_taller` (`id_taller`),
  ADD KEY `fk_agenda_id_sucursal_1` (`id_sucursal`);

--
-- Indices de la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  ADD PRIMARY KEY (`id_agenda_curso`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `fk_agenda_cursos_id_sucursal_1` (`id_sucursal`);

--
-- Indices de la tabla `alumnas`
--
ALTER TABLE `alumnas`
  ADD PRIMARY KEY (`id_alumna`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `fk_cursos_sucursal_fk` (`id_sucursal`);

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
  ADD KEY `fk_detalle_venta_kit` (`id_kit`),
  ADD KEY `fk_detalle_venta_id_sucursal_1` (`id_sucursal`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id_egreso`),
  ADD KEY `id_agenda` (`id_agenda`),
  ADD KEY `id_agenda_curso` (`id_agenda_curso`),
  ADD KEY `id_sucursal` (`id_sucursal`);

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
  ADD KEY `idx_intermedia_curso` (`id_agenda_curso`),
  ADD KEY `fk_intermedia_a_id_sucursal_1` (`id_sucursal`);

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
-- Indices de la tabla `maestras_asignadas`
--
ALTER TABLE `maestras_asignadas`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `id_maestra` (`id_maestra`),
  ADD KEY `id_agenda` (`id_agenda`),
  ADD KEY `id_agenda_curso` (`id_agenda_curso`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`),
  ADD KEY `fk_modulo_curso` (`id_curso`);

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
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`id_sucursal`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`id_taller`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_maestra` (`id_maestra`),
  ADD KEY `idx_talleres_id_maestra` (`id_maestra`),
  ADD KEY `fk_talleres_sucursal` (`id_sucursal`);

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
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `fk_venta_id_sucursal_1` (`id_sucursal`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id_agenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  MODIFY `id_agenda_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alumnas`
--
ALTER TABLE `alumnas`
  MODIFY `id_alumna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `descuentos`
--
ALTER TABLE `descuentos`
  MODIFY `id_descuento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id_egreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `institutos`
--
ALTER TABLE `institutos`
  MODIFY `id_instituto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `interesadas`
--
ALTER TABLE `interesadas`
  MODIFY `id_interesada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intermedia_a`
--
ALTER TABLE `intermedia_a`
  MODIFY `id_intermedia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_stock`
--
ALTER TABLE `inventario_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kits_productos`
--
ALTER TABLE `kits_productos`
  MODIFY `id_kit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maestras`
--
ALTER TABLE `maestras`
  MODIFY `id_maestra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maestras_asignadas`
--
ALTER TABLE `maestras_asignadas`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id_taller` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`),
  ADD CONSTRAINT `fk_agenda_id_sucursal_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_agenda_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  ADD CONSTRAINT `fk_agenda_cursos__cursos` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_agenda_cursos_id_sucursal_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_agenda_cursos_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_id_sucursal_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cursos_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cursos_sucursal_fk` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `descuentos`
--
ALTER TABLE `descuentos`
  ADD CONSTRAINT `descuentos_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta_id_sucursal_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_kit` FOREIGN KEY (`id_kit`) REFERENCES `kits_productos` (`id_kit`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_venta_venta` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD CONSTRAINT `egresos_ibfk_1` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  ADD CONSTRAINT `egresos_ibfk_2` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`),
  ADD CONSTRAINT `egresos_ibfk_3` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`);

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
  ADD CONSTRAINT `fk_intermediaA_curso` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_intermedia_a_id_sucursal_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_intermedia_a_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE;

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
-- Filtros para la tabla `maestras_asignadas`
--
ALTER TABLE `maestras_asignadas`
  ADD CONSTRAINT `maestras_asignadas_ibfk_1` FOREIGN KEY (`id_maestra`) REFERENCES `maestras` (`id_maestra`),
  ADD CONSTRAINT `maestras_asignadas_ibfk_2` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  ADD CONSTRAINT `maestras_asignadas_ibfk_3` FOREIGN KEY (`id_maestra`) REFERENCES `maestras` (`id_maestra`),
  ADD CONSTRAINT `maestras_asignadas_ibfk_4` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  ADD CONSTRAINT `maestras_asignadas_ibfk_5` FOREIGN KEY (`id_maestra`) REFERENCES `maestras` (`id_maestra`),
  ADD CONSTRAINT `maestras_asignadas_ibfk_6` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  ADD CONSTRAINT `maestras_asignadas_ibfk_7` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`);

--
-- Filtros para la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `fk_modulo_curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_talleres_maestras` FOREIGN KEY (`id_maestra`) REFERENCES `maestras` (`id_maestra`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_talleres_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_id_sucursal_1` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venta_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursales` (`id_sucursal`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venta_taller` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
