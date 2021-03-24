-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-02-2021 a las 17:59:08
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
-- Estructura de tabla para la tabla `copia_respaldos`
--

CREATE TABLE `copia_respaldos` (
  `id` int(11) NOT NULL,
  `copia_respaldo` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` tinyint(3) DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `copia_respaldos`
--

INSERT INTO `copia_respaldos` (`id`, `copia_respaldo`, `fecha`, `estado`) VALUES
(1, 'archivo_out_07-10-2020_04-48-20.sql', '2020-10-07 16:48:21', 2),
(2, 'archivo_out_07-10-2020_04-53-14.sql', '2020-10-07 16:53:15', 2),
(3, 'archivo_out_15-10-2020_09-08-30.sql', '2020-10-15 21:08:31', 2),
(4, 'archivo_out_24-02-2021_06-29-56.sql', '2021-02-24 18:29:57', 2),
(5, 'archivo_out_24-02-2021_06-34-03.sql', '2021-02-24 18:34:03', 2),
(6, 'archivo_out_24-02-2021_06-50-54.sql', '2021-02-24 18:50:55', 2),
(7, NULL, '2021-02-24 19:11:25', -1),
(8, 'data.txt', '2021-02-24 19:12:22', -1),
(9, 'archivo_out_24-02-2021_07-13-44.sql', '2021-02-24 19:13:45', -1),
(10, 'archivo_out_24-02-2021_07-15-43.sql', '2021-02-24 19:15:44', 2),
(11, 'mundosoft5_0.sql', '2021-02-24 19:17:40', -1),
(12, '111.txt', '2021-02-24 19:18:51', -1),
(13, 'comprobantes.php', '2021-02-24 19:20:14', -1),
(14, 'mundosoft5_0.sql', '2021-02-24 19:20:32', -1),
(15, 'mundosoft5_0.sql', '2021-02-24 19:27:28', -1),
(16, 'mundosoft5_0.sql', '2021-02-24 19:32:29', -1),
(17, 'mundosoft5_0.sql', '2021-02-24 19:34:00', -1),
(18, 'mundosoft5_0.sql', '2021-02-24 19:36:13', 2),
(19, 'archivo_out_24-02-2021_07-36-49.sql', '2021-02-24 19:36:50', 2),
(20, 'archivo_out_25-02-2021_10-52-59.sql', '2021-02-25 10:53:00', 2),
(21, 'mundosoft5_0_1.sql', '2021-02-25 10:55:06', -1),
(22, 'mundosoft5_0_1.sql', '2021-02-25 11:01:10', 2),
(23, 'archivo_out_25-02-2021_11-36-52.sql', '2021-02-25 11:39:24', 2),
(24, 'archivo_out_25-02-2021_11-51-12.sql', '2021-02-25 11:51:12', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `copia_respaldos`
--
ALTER TABLE `copia_respaldos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `copia_respaldos`
--
ALTER TABLE `copia_respaldos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
