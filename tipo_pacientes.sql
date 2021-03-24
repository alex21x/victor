-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-02-2021 a las 00:24:05
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
-- Estructura de tabla para la tabla `tipo_pacientes`
--

CREATE TABLE `tipo_pacientes` (
  `tipo_pac_id` int(11) NOT NULL,
  `tipo_pac_descrip` varchar(255) DEFAULT NULL,
  `tipo_pac_estado` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_pacientes`
--

INSERT INTO `tipo_pacientes` (`tipo_pac_id`, `tipo_pac_descrip`, `tipo_pac_estado`) VALUES
(1, 'CLINICA', 2),
(2, 'UCI', 2),
(3, 'COMA', 2),
(4, '12', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tipo_pacientes`
--
ALTER TABLE `tipo_pacientes`
  ADD PRIMARY KEY (`tipo_pac_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tipo_pacientes`
--
ALTER TABLE `tipo_pacientes`
  MODIFY `tipo_pac_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
