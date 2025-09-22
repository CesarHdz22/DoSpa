-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2025 a las 04:50:06
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
  `cant_alum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id_agenda`, `id_taller`, `fecha`, `hora_inicio`, `hora_fin`, `ubicacion`, `variacion`, `cant_alum`) VALUES
(1, 1, '2025-09-10', '10:00:00', '13:00:00', 'Aula 101', 'Matutino', 1),
(2, 2, '2025-09-15', '15:00:00', '18:00:00', 'Sala Principal', 'Vespertino', 1),
(3, 3, '2025-09-20', '09:00:00', '12:00:00', 'Salón Creativo', 'Fin de semana', 3),
(4, 3, '2025-12-02', '15:00:00', '18:00:00', 'Victoria', '', 3);

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
  `cant_alum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `agenda_cursos`
--

INSERT INTO `agenda_cursos` (`id_agenda_curso`, `id_curso`, `fecha`, `hora_inicio`, `hora_fin`, `ubicacion`, `variacion`, `cant_alum`) VALUES
(1, 1, '2025-09-25', '09:00:00', '12:00:00', 'Aula 201', 'Matutino', 1),
(2, 2, '2025-09-30', '16:00:00', '19:00:00', 'Laboratorio A', 'Vespertino', 1),
(3, 3, '2025-10-05', '10:00:00', '13:00:00', 'Sala B', 'Fin de semana', 2);

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
  `tipo_descuento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnas`
--

INSERT INTO `alumnas` (`id_alumna`, `nombre`, `apat`, `amat`, `telefono`, `correo`, `direccion`, `descuento_aplicado`, `tipo_descuento`) VALUES
(1, 'Laura', 'Sánchez', 'Perez', '555-123-4567', 'laura@example.com', 'Calle 1, Colonia Centro', 1, '10%'),
(2, 'Mariana', 'Díaz', 'Martinez', '555-987-6543', 'mariana@example.com', 'Calle 2, Colonia Norte', 0, NULL),
(3, 'Fernanda', 'Gómez', 'Farias', '555-321-7890', 'fernanda@example.com', 'Calle 3, Colonia Sur', 1, 'Estudiante');

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
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apat`, `amat`, `correo`, `telefono`, `direccion`) VALUES
(1, 'Juan', 'Perez', 'Hernandez', 'juan.perez@gmail.com', '5512345678', 'Av. Reforma #123, CDMX'),
(2, 'María', 'Lopez', 'Marquez', 'maria.lopez@yahoo.com', '3322233344', 'Calle Hidalgo #45, Guadalajara'),
(3, 'Carlos', 'Ramirez', 'Valdez', 'carlos.ramirez@hotmail.com', '8111122233', 'Col. Centro, Monterrey'),
(4, 'Ana Maria', 'Torres', 'Rubio', 'ana.torres@outlook.com', '2223344556', 'Blvd. Díaz Ordaz #890, Puebla');

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
(78, 23, 1, NULL, 1, 500.00, 500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos_talleres`
--

CREATE TABLE `egresos_talleres` (
  `id_egreso` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `concepto` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `id_taller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `egresos_talleres`
--

INSERT INTO `egresos_talleres` (`id_egreso`, `fecha`, `concepto`, `monto`, `id_taller`) VALUES
(1, '2025-09-05', 'Materiales de repostería', 500.00, 1),
(2, '2025-09-12', 'Maquillajes y brochas', 800.00, 2),
(3, '2025-09-18', 'Papel y pinturas', 300.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pagos`
--

CREATE TABLE `historial_pagos` (
  `id_pago` int(11) NOT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT NULL,
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','depósito','otros') DEFAULT 'efectivo',
  `tipo_servicio` enum('paquete','servicio','kit','producto','otro') DEFAULT 'servicio',
  `comprobante` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_pagos`
--

INSERT INTO `historial_pagos` (`id_pago`, `idVenta`, `id_agenda`, `monto_pagado`, `saldo_pendiente`, `fecha_pago`, `metodo_pago`, `tipo_servicio`, `comprobante`) VALUES
(4, 1, NULL, 500.00, 1100.00, '2025-09-17 05:20:26', 'transferencia', 'producto', ''),
(5, 1, NULL, 300.00, 800.00, '2025-09-18 05:20:26', 'transferencia', 'producto', ''),
(6, 2, NULL, 300.00, 500.00, '2025-09-10 05:25:32', 'transferencia', 'producto', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pagos_cursos`
--

CREATE TABLE `historial_pagos_cursos` (
  `id_pago` int(11) NOT NULL,
  `id_alumna` int(11) DEFAULT NULL,
  `id_agenda_curso` int(11) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','depósito','otros') DEFAULT 'efectivo',
  `tipo_servicio` enum('paquete','servicio','kit','producto','otro') DEFAULT 'servicio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_pagos_cursos`
--

INSERT INTO `historial_pagos_cursos` (`id_pago`, `id_alumna`, `id_agenda_curso`, `monto_pagado`, `saldo_pendiente`, `fecha_pago`, `metodo_pago`, `tipo_servicio`) VALUES
(1, 1, 1, 1500.00, 1000.00, '2025-09-20', 'efectivo', 'servicio'),
(2, 2, 2, 3000.00, 0.00, '2025-09-29', 'tarjeta', 'paquete'),
(3, 3, 3, 1000.00, 1000.00, '2025-10-01', 'transferencia', 'kit'),
(4, 2, 3, 0.00, 0.00, '2025-09-21', 'otros', 'servicio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_talleres`
--

CREATE TABLE `ingresos_talleres` (
  `id_ingreso` int(11) NOT NULL,
  `id_alumna` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tipo_ingreso` varchar(100) DEFAULT NULL,
  `metodo_pago` varchar(100) DEFAULT NULL,
  `id_taller` int(11) DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos_talleres`
--

INSERT INTO `ingresos_talleres` (`id_ingreso`, `id_alumna`, `fecha`, `tipo_ingreso`, `metodo_pago`, `id_taller`, `costo`) VALUES
(1, 1, '2025-09-06', 'Inscripción', 'efectivo', 1, 1500.00),
(2, 2, '2025-09-13', 'Inscripción', 'tarjeta', 2, 2000.00),
(3, 3, '2025-09-19', 'Inscripción', 'transferencia', 3, 1000.00),
(4, 1, '2025-09-21', 'inscripcion', 'otros', 3, 0.00),
(5, 3, '2025-09-21', 'inscripcion', 'otros', 3, 0.00),
(6, 1, '2025-09-21', 'inscripcion', 'otros', 3, 0.00),
(7, 1, '2025-09-21', 'inscripcion', 'otros', 2, 0.00);

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
-- Estructura de tabla para la tabla `intermedia_a`
--

CREATE TABLE `intermedia_a` (
  `id_intermedia` int(11) NOT NULL,
  `id_alumna` int(11) NOT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `id_agenda_curso` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intermedia_a`
--

INSERT INTO `intermedia_a` (`id_intermedia`, `id_alumna`, `id_agenda`, `id_agenda_curso`, `fecha`) VALUES
(1, 1, 3, NULL, '2025-09-22 00:44:57'),
(2, 2, NULL, 3, '2025-09-22 00:46:21'),
(3, 2, 3, NULL, '2025-09-22 00:57:23'),
(4, 2, 2, NULL, '2025-09-22 01:01:57'),
(5, 3, 1, NULL, '2025-09-22 01:02:01'),
(6, 3, 3, NULL, '2025-09-22 01:04:15'),
(7, 1, 4, NULL, '2025-09-22 01:07:07'),
(8, 3, NULL, 1, '2025-09-22 01:08:26'),
(9, 3, NULL, 3, '2025-09-22 01:08:30'),
(10, 2, NULL, 2, '2025-09-22 01:08:33'),
(11, 2, 4, NULL, '2025-09-22 01:58:19'),
(12, 3, 4, NULL, '2025-09-22 02:17:52');

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
      SET cant_alum = cnt
      WHERE id_agenda = NEW.id_agenda;
  -- Si se insertó con id_agenda_curso (curso)
  ELSEIF NEW.id_agenda_curso IS NOT NULL AND NEW.id_agenda_curso <> 0 THEN
    SELECT COUNT(*) INTO cnt FROM intermedia_a WHERE id_agenda_curso = NEW.id_agenda_curso;
    UPDATE agenda_cursos
      SET cant_alum = cnt
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
(2, 'Kit Maquillaje', 1500.00, 0, 0),
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
  `porcentaje_ganancia` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `maestras`
--

INSERT INTO `maestras` (`id_maestra`, `nombre`, `base`, `acuerdo`, `gastos`, `porcentaje_ganancia`) VALUES
(1, 'María López', 'Base A', '50/50', 1200.50, 40.00),
(2, 'Ana Martínez', 'Base B', '60/40', 800.00, 35.00),
(3, 'Claudia Torres', 'Base C', '70/30', 950.75, 45.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `Stock` int(11) NOT NULL,
  `Activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `precio_unitario`, `Stock`, `Activo`) VALUES
(1, 'Libro de repostería', 500.00, 3, 1),
(2, 'Set de brochas', 800.00, 10, 1),
(3, 'Caja de pinturas', 300.00, 99, 1);

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
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_kits`
--

INSERT INTO `productos_kits` (`id_kit`, `id_producto`) VALUES
(1, 1),
(2, 2),
(3, 3);

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
(3, 'Taller de Manualidades', 3, 1000.00, 'pendiente', 0.00, 0.00, 30.00, 70.00, 0);

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
  `Pass` varchar(15) NOT NULL,
  `Cargo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id_Usuario`, `Nombre`, `Apat`, `Amat`, `Correo`, `User`, `Pass`, `Cargo`) VALUES
(1, 'Cesar Alejandro', 'Hernandez', 'Medina', 'cesarflow4k@gmail.com', 'Magik', '1234', 'Admin'),
(2, 'salvador', 'de la garza', 'cvazos', 'salba3@gmail.com', 'salba', '1232', 'admin');

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
(23, '2025-09-22 02:02:00', 'alumna', 2, NULL, NULL, 500.00, 'Pendiente');

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
-- Indices de la tabla `egresos_talleres`
--
ALTER TABLE `egresos_talleres`
  ADD PRIMARY KEY (`id_egreso`),
  ADD KEY `id_taller` (`id_taller`);

--
-- Indices de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_agenda` (`id_agenda`),
  ADD KEY `idVenta` (`idVenta`);

--
-- Indices de la tabla `historial_pagos_cursos`
--
ALTER TABLE `historial_pagos_cursos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_alumna` (`id_alumna`),
  ADD KEY `id_agenda_curso` (`id_agenda_curso`);

--
-- Indices de la tabla `ingresos_talleres`
--
ALTER TABLE `ingresos_talleres`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `id_alumna` (`id_alumna`),
  ADD KEY `id_taller` (`id_taller`);

--
-- Indices de la tabla `institutos`
--
ALTER TABLE `institutos`
  ADD PRIMARY KEY (`id_instituto`);

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
  MODIFY `id_alumna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `idDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `egresos_talleres`
--
ALTER TABLE `egresos_talleres`
  MODIFY `id_egreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `historial_pagos_cursos`
--
ALTER TABLE `historial_pagos_cursos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ingresos_talleres`
--
ALTER TABLE `ingresos_talleres`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `institutos`
--
ALTER TABLE `institutos`
  MODIFY `id_instituto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `intermedia_a`
--
ALTER TABLE `intermedia_a`
  MODIFY `id_intermedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id_maestra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `Id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
-- Filtros para la tabla `egresos_talleres`
--
ALTER TABLE `egresos_talleres`
  ADD CONSTRAINT `egresos_talleres_ibfk_1` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`);

--
-- Filtros para la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD CONSTRAINT `historial_pagos_ibfk_2` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  ADD CONSTRAINT `historial_pagos_ibfk_3` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`);

--
-- Filtros para la tabla `historial_pagos_cursos`
--
ALTER TABLE `historial_pagos_cursos`
  ADD CONSTRAINT `fk_hist_pagos_cursos__agenda` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hist_pagos_cursos__alumnas` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingresos_talleres`
--
ALTER TABLE `ingresos_talleres`
  ADD CONSTRAINT `ingresos_talleres_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`),
  ADD CONSTRAINT `ingresos_talleres_ibfk_2` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`);

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
