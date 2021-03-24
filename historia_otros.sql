-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-02-2021 a las 21:16:05
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mundosoft5.0`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia_otros`
--

CREATE TABLE `historia_otros` (
  `hio_id` int(11) NOT NULL,
  `hio_his_id` int(11) DEFAULT NULL,
  `hio_producto_id` int(11) DEFAULT NULL,
  `hio_descripcion` varchar(300) DEFAULT NULL,
  `hio_cantidad` decimal(10,2) DEFAULT NULL,
  `hio_observacion` varchar(100) DEFAULT NULL,
  `hio_estado` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `historia_otros`
--

INSERT INTO `historia_otros` (`hio_id`, `hio_his_id`, `hio_producto_id`, `hio_descripcion`, `hio_cantidad`, `hio_observacion`, `hio_estado`) VALUES
(1, 30, 15189, 'ACI BASIC SUSP X220ML - UNI', '1.00', '', 2),
(2, 30, 15217, 'AERO-SIM COMPUESTO GOTAS - UNI', '1.00', '', 2),
(3, 31, 15222, 'AGUA 7 ESPIRIRUS FCOx30ML - UNI', '1.00', '', 2),
(4, 32, 15180, 'ACEITE DE ALMENDRAS FCOx30ML - UNI', '1.00', '', 2),
(11, 33, 15219, 'AEROX PLUS GOTAS - UNI', '1.00', 'observacion10', 2),
(10, 33, 15180, 'ACEITE DE ALMENDRAS FCOx30ML - UNI', '1.00', 'observacion12', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `historia_otros`
--
ALTER TABLE `historia_otros`
  ADD PRIMARY KEY (`hio_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `historia_otros`
--
ALTER TABLE `historia_otros`
  MODIFY `hio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
