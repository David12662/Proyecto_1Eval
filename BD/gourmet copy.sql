-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2024 a las 01:25:31
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
-- Base de datos: `gourmet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `dniCliente` varchar(100) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`dniCliente`, `idProducto`, `cantidad`) VALUES
('1231231', 2, 8),
('1231231', 5, 10),
('anon_673f35ac142d67.37271426', 1, 10),
('anon_673f35ac142d67.37271426', 2, 29),
('anon_673f961c54a673.55262507', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `dniCliente` varchar(9) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `email` varchar(75) NOT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `administrador` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`dniCliente`, `nombre`, `direccion`, `email`, `pwd`, `administrador`) VALUES
('1', 'Admin', 'Avda Correos 132', 'admin@midominio.es', 'Admin\r\n', 'true'),
('10', 'Victor', 'Avda Correos 132', 'victor@midominio.es', 'Victor', 'false'),
('1231231', 'david', 'sadsad', 'davidevis12@gmail.com', '$2y$10$vNzAvRygQTLBzk.1GyD1vO.RCu6VKdnssF8/ese3fPopBEAfhGUCa', NULL),
('15', 'Laura', 'C/ Admin', 'admin@gmail.com', 'Laura', 'false'),
('44444444', 'Marta', 'C/ Valeras 4', 'marta@midominio.es', 'Marta', 'false'),
('7777777', 'Miguel', 'C/Santoña15', 'manuel@midominio.es', 'Miguel', 'false');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineaspedidos`
--

CREATE TABLE `lineaspedidos` (
  `idPedido` int(4) NOT NULL,
  `nlinea` int(2) NOT NULL,
  `idProducto` int(6) DEFAULT NULL,
  `cantidad` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lineaspedidos`
--

INSERT INTO `lineaspedidos` (`idPedido`, `nlinea`, `idProducto`, `cantidad`) VALUES
(1, 1, 2, 5),
(1, 2, 1, 3),
(1, 3, 3, 3),
(1, 4, 4, 3),
(2, 1, 5, 10),
(2, 2, 7, 10),
(5, 1, 5, 3),
(5, 2, 5, 3),
(5, 3, 2, 4),
(5, 4, 9, 4),
(6, 1, 1, 3),
(6, 2, 7, 2),
(6, 3, 9, 2),
(6, 4, 6, 200),
(10, 1, 6, 2),
(10, 2, 1, 2),
(10, 3, 9, 4),
(10, 4, 4, 10),
(11, 1, 11, 200),
(12, 1, 2, 3),
(12, 2, 9, 2),
(12, 3, 5, 10),
(12, 4, 4, 1),
(13, 1, 8, 3),
(13, 2, 9, 3),
(13, 3, 1, 200),
(13, 4, 3, 4),
(13, 5, 4, 10),
(14, 1, 1, 1),
(14, 2, 7, 1),
(14, 3, 8, 4),
(16, 1, 2, 8),
(16, 2, 5, 10),
(17, 1, 2, 8),
(17, 2, 5, 10),
(18, 1, 2, 8),
(18, 2, 5, 10),
(19, 1, 2, 8),
(19, 2, 5, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(4) NOT NULL,
  `fecha` date NOT NULL,
  `dirEntrega` varchar(50) NOT NULL,
  `nTarjeta` varchar(50) DEFAULT NULL,
  `fechaCaducidad` date DEFAULT NULL,
  `matriculaRepartidor` varchar(8) DEFAULT NULL,
  `dniCliente` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `fecha`, `dirEntrega`, `nTarjeta`, `fechaCaducidad`, `matriculaRepartidor`, `dniCliente`) VALUES
(1, '1111-01-01', 'C/ Valeras, 22', '111111', '2020-02-02', 'pbf-1144', '10'),
(2, '2021-11-16', 'C/ Princesa, 15', '333333', '2020-02-02', 'bbc-2589', '10'),
(5, '2020-11-09', '', NULL, NULL, NULL, '10'),
(6, '1010-11-16', '', NULL, NULL, NULL, '10'),
(10, '2020-11-17', '', NULL, NULL, NULL, '15'),
(11, '2020-11-17', '', NULL, NULL, NULL, '32'),
(12, '2020-11-18', '', NULL, NULL, NULL, '15'),
(13, '2020-11-19', '', NULL, NULL, NULL, '10'),
(14, '2020-11-23', '', NULL, NULL, NULL, '10'),
(15, '2020-11-23', '', NULL, NULL, NULL, '10'),
(16, '2024-11-22', 'sadsad', '', '0000-00-00', '', '1231231'),
(17, '2024-11-22', 'sadsad', '', '0000-00-00', '', '1231231'),
(18, '2024-11-22', 'sadsad', '', '0000-00-00', '', '1231231'),
(19, '2024-11-22', 'sadsad', '', '0000-00-00', '', '1231231');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(6) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `origen` varchar(50) DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `categoria` enum('frio','congelado','seco') DEFAULT NULL,
  `precio` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `nombre`, `origen`, `foto`, `categoria`, `precio`) VALUES
(1, 'Macarrones', 'Italia', 'macarrones.jpg', 'seco', 1),
(2, 'Tallarines', 'Italia', 'tallarines.jpg', 'seco', 1),
(3, 'Atun', 'España', 'atun.jpg', 'seco', 1),
(4, 'Sardinillas', 'España', 'sardinas.jpg', 'seco', 1),
(5, 'Mejillones', 'España', 'mejillones.jpg', 'seco', 1),
(6, 'Fideos', 'Italia', 'fideos.jpg', 'seco', 1),
(7, 'Galletas Cuadradas', 'Francia', 'galletas.jpg', 'seco', 1),
(8, 'Barquillos', 'España', 'barquillos.jpg', 'seco', 1),
(9, 'Leche entera', 'España', 'leche.jpg', 'frio', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`dniCliente`,`idProducto`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dniCliente`);

--
-- Indices de la tabla `lineaspedidos`
--
ALTER TABLE `lineaspedidos`
  ADD PRIMARY KEY (`idPedido`,`nlinea`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
