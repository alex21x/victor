-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-03-2021 a las 01:01:27
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
-- Base de datos: `pos_msp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobantes`
--

CREATE TABLE `comprobantes` (
  `com_id` int(11) NOT NULL,
  `com_fecha_emision` datetime DEFAULT NULL,
  `com_serie` varchar(30) DEFAULT NULL,
  `com_numero` varchar(30) DEFAULT NULL,
  `com_tipo_documento_id` int(11) NOT NULL,
  `com_cliente_id` int(11) NOT NULL,
  `com_total_grabada` decimal(10,2) DEFAULT NULL,
  `com_total_exonerada` decimal(10,2) DEFAULT NULL,
  `com_total_inafecta` decimal(10,2) DEFAULT NULL,
  `com_total_pagar` decimal(10,2) DEFAULT NULL,
  `com_estado_sunat` tinyint(3) DEFAULT NULL,
  `com_anulado` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante_detalles`
--

CREATE TABLE `comprobante_detalles` (
  `cde_id` int(11) NOT NULL,
  `cde_comprobante` varchar(50) DEFAULT NULL,
  `cde_cantidad` decimal(10,2) DEFAULT NULL,
  `cde_subtotal` decimal(10,2) DEFAULT NULL,
  `cde_igv` decimal(10,2) DEFAULT NULL,
  `cde_tipo_igv_id` int(11) DEFAULT NULL,
  `cde_total` decimal(10,2) DEFAULT NULL,
  `cde_estado` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comprobantes`
--
ALTER TABLE `comprobantes`
  ADD PRIMARY KEY (`com_id`);

--
-- Indices de la tabla `comprobante_detalles`
--
ALTER TABLE `comprobante_detalles`
  ADD PRIMARY KEY (`cde_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comprobantes`
--
ALTER TABLE `comprobantes`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comprobante_detalles`
--
ALTER TABLE `comprobante_detalles`
  MODIFY `cde_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
