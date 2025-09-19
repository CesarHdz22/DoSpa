-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2025 a las 01:34:02
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
-- Base de datos: `instituto_do`
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
  `variacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `variacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnas`
--

CREATE TABLE `alumnas` (
  `id_alumna` int(11) NOT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `descuento_aplicado` tinyint(1) DEFAULT 0,
  `tipo_descuento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `costo_base` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `ingreso_bruto` decimal(10,2) DEFAULT NULL,
  `gastos` decimal(10,2) DEFAULT NULL,
  `precio_preferencial` tinyint(1) DEFAULT 0
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
-- Estructura de tabla para la tabla `egresos_talleres`
--

CREATE TABLE `egresos_talleres` (
  `id_egreso` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `concepto` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `id_taller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_pagos`
--

CREATE TABLE `historial_pagos` (
  `id_pago` int(11) NOT NULL,
  `id_alumna` int(11) DEFAULT NULL,
  `id_agenda` int(11) DEFAULT NULL,
  `monto_pagado` decimal(10,2) DEFAULT NULL,
  `saldo_pendiente` decimal(10,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','depósito','otros') DEFAULT 'efectivo',
  `tipo_servicio` enum('paquete','servicio','kit','producto','otro') DEFAULT 'servicio',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_venta_productos`
--

CREATE TABLE `historial_venta_productos` (
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_kit` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_total` decimal(10,2) DEFAULT NULL,
  `comprador_tipo` varchar(50) DEFAULT NULL,
  `id_comprador` int(11) DEFAULT NULL,
  `id_taller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `nombre_kit` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `es_kit` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_kits`
--

CREATE TABLE `productos_kits` (
  `id_kit` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `id_taller` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `id_maestra` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `costo_base` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `ingreso_bruto` decimal(10,2) DEFAULT NULL,
  `gastos` decimal(10,2) DEFAULT NULL,
  `porcentaje_delia` decimal(5,2) DEFAULT NULL,
  `porcentaje_caro` decimal(5,2) DEFAULT NULL,
  `precio_preferencial` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `id_alumna` (`id_alumna`),
  ADD KEY `id_agenda` (`id_agenda`);

--
-- Indices de la tabla `historial_pagos_cursos`
--
ALTER TABLE `historial_pagos_cursos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_alumna` (`id_alumna`),
  ADD KEY `id_agenda_curso` (`id_agenda_curso`);

--
-- Indices de la tabla `historial_venta_productos`
--
ALTER TABLE `historial_venta_productos`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_taller` (`id_taller`),
  ADD KEY `id_kit` (`id_kit`);

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
  ADD PRIMARY KEY (`id_kit`);

--
-- Indices de la tabla `maestras`
--
ALTER TABLE `maestras`
  ADD PRIMARY KEY (`id_maestra`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id_agenda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `agenda_cursos`
--
ALTER TABLE `agenda_cursos`
  MODIFY `id_agenda_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alumnas`
--
ALTER TABLE `alumnas`
  MODIFY `id_alumna` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `egresos_talleres`
--
ALTER TABLE `egresos_talleres`
  MODIFY `id_egreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_pagos_cursos`
--
ALTER TABLE `historial_pagos_cursos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_venta_productos`
--
ALTER TABLE `historial_venta_productos`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos_talleres`
--
ALTER TABLE `ingresos_talleres`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `institutos`
--
ALTER TABLE `institutos`
  MODIFY `id_instituto` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id_taller` int(11) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `egresos_talleres`
--
ALTER TABLE `egresos_talleres`
  ADD CONSTRAINT `egresos_talleres_ibfk_1` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`);

--
-- Filtros para la tabla `historial_pagos`
--
ALTER TABLE `historial_pagos`
  ADD CONSTRAINT `historial_pagos_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`),
  ADD CONSTRAINT `historial_pagos_ibfk_2` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`);

--
-- Filtros para la tabla `historial_pagos_cursos`
--
ALTER TABLE `historial_pagos_cursos`
  ADD CONSTRAINT `fk_hist_pagos_cursos__agenda` FOREIGN KEY (`id_agenda_curso`) REFERENCES `agenda_cursos` (`id_agenda_curso`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hist_pagos_cursos__alumnas` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_venta_productos`
--
ALTER TABLE `historial_venta_productos`
  ADD CONSTRAINT `fk_hist_venta__kit` FOREIGN KEY (`id_kit`) REFERENCES `kits_productos` (`id_kit`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `historial_venta_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `historial_venta_productos_ibfk_2` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`);

--
-- Filtros para la tabla `ingresos_talleres`
--
ALTER TABLE `ingresos_talleres`
  ADD CONSTRAINT `ingresos_talleres_ibfk_1` FOREIGN KEY (`id_alumna`) REFERENCES `alumnas` (`id_alumna`),
  ADD CONSTRAINT `ingresos_talleres_ibfk_2` FOREIGN KEY (`id_taller`) REFERENCES `talleres` (`id_taller`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
