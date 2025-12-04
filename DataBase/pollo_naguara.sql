-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2025 a las 03:15:52
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
-- Base de datos: `pollo_naguara`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `color_primario` varchar(7) NOT NULL DEFAULT '#FF6B00',
  `color_secundario` varchar(7) NOT NULL DEFAULT '#FF8C42',
  `titulo_landing` varchar(100) DEFAULT 'Pollo NaGuara',
  `descripcion_landing` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email_contacto` varchar(100) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT 'assets/logo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `color_primario`, `color_secundario`, `titulo_landing`, `descripcion_landing`, `telefono`, `email_contacto`, `logo_url`) VALUES
(1, '#FF6B00', '#FF8C42', 'Pollo Na\'Guara', 'La mejor calidad en pollos y aliños.', '0414-1234567', 'contacto@pollonaguara.com', 'assets/logo.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 20, 12.50),
(2, 1, 2, 10, 8.75),
(3, 1, 3, 18, 6.25),
(4, 2, 2, 5, 8.75),
(5, 2, 4, 4, 3.25),
(6, 2, 5, 11, 2.50),
(7, 3, 1, 15, 12.50),
(8, 3, 6, 10, 10.75),
(9, 3, 4, 8, 3.25),
(10, 4, 4, 7, 3.25),
(11, 5, 7, 8, 25.00),
(12, 6, 2, 1, 8.75),
(13, 7, 2, 12, 8.75),
(14, 8, 2, 32, 8.75);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 10,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `categoria`, `stock_actual`, `stock_minimo`, `precio`, `activo`) VALUES
(1, 'POLLO PRUEBA SQL', 'Pollos Enteros', 100, 20, 50756756.00, 0),
(2, 'Milanesa de Pollo', 'Milanesas', 0, 15, 8.75, 1),
(3, 'Alas de Pollo', 'Pollos Enteros', 32, 10, 6.25, 0),
(4, 'Aliño Vegetal Natural', 'Aliños', 9, 10, 3.25, 1),
(5, 'Especias Mixtas', 'Especias', 5, 8, 2.50, 1),
(6, 'Pechuga de Pollo', 'Pollos Enteros', 38, 12, 10.75, 1),
(7, 'Combo Parrillero', 'Otros', 2, 5, 25.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `estado`) VALUES
(4, 'Ana Martínez', 'ana@pollonaguara.com', '', 'Administrador', 1),
(5, 'Pepito El Grande', 'prueba@123.com', '$2y$10$0WM/hUQAIyGnF/QPhhmM2eBQJgty/rfVJ8lJjP15N.VdqKNhHW.Qm', 'Administrador', 1),
(6, '12345', '1234@gmail.com', '', 'Empleado', 1),
(7, 'Enrique Perez', '444@gmail.com', '$2y$10$nhasq2p3s.slJLewhtLa0O/C1aLEs/rmZN8xoslig89/SiR6Q7xEe', 'Administrador', 1),
(8, '1234', '12345@gmail.com', '$2y$10$e6MtVPrHUQeMXi/bbJuu/u5SbTp1YNboIdbgCANdRpKnzBUlhaBW2', 'Empleado', 1),
(9, 'Pastor Perez', 'pastor@gmail.com', '$2y$10$CuHhg13gvU/e6UYl08nULONm7cfavzTJGXXQb.NKXb7azubyjRORi', 'Empleado', 1),
(10, 'Fabian Da Cal', 'dacal@gmail.com', '$2y$10$W.MSN/jU4FHu6JLkKzhXle3l/yS9DM2lMuucRuQC6VmEfe8eBDcZq', 'Empleado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `codigo_venta` varchar(20) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `cliente` varchar(100) NOT NULL,
  `vendedor` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `codigo_venta`, `fecha`, `cliente`, `vendedor`, `total`, `estado`) VALUES
(1, '#VN-00125', '2024-01-15 14:30:00', 'Restaurante La Casona', 'María González', 450.00, 'Completada'),
(2, '#VN-00124', '2024-01-15 13:15:00', 'Cliente Final', 'Carlos López', 85.50, 'Completada'),
(3, '#VN-00123', '2024-01-15 11:45:00', 'Cafetería El Buen Sabor', 'Ana Martínez', 320.75, 'Cancelada'),
(4, '#VN-1763758215', '2025-11-21 16:50:15', '123123', 'Pepito El Grande', 22.75, 'Completada'),
(5, '#VN-1763758246', '2025-11-21 16:50:46', '12313', 'Pepito El Grande', 200.00, 'Completada'),
(6, '#VN-1763759072', '2025-11-21 17:04:32', 'asdasd', 'Pepito El Grande', 8.75, 'Completada'),
(7, '#VN-1764764564', '2025-12-03 08:22:44', 'wweqweqwe', 'Enrique Perez', 105.00, 'Completada'),
(8, '#VN-1764764603', '2025-12-03 08:23:23', 'Jenny', 'Enrique Perez', 280.00, 'Completada');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
