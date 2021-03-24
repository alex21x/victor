/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.1.34-MariaDB : Database - facturacion_electronica
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`facturacion_electronica` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `facturacion_electronica`;

/*Table structure for table `accesos` */

DROP TABLE IF EXISTS `accesos`;

CREATE TABLE `accesos` (
  `id` int(10) unsigned NOT NULL,
  `acceso` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `accesos` */

insert  into `accesos`(`id`,`acceso`) values (0,'sin acceso'),(1,'con acceso');

/*Table structure for table `activos` */

DROP TABLE IF EXISTS `activos`;

CREATE TABLE `activos` (
  `id` int(10) unsigned NOT NULL,
  `activo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `activos` */

insert  into `activos`(`id`,`activo`) values (0,'inactivo'),(1,'activo');

/*Table structure for table `anios` */

DROP TABLE IF EXISTS `anios`;

CREATE TABLE `anios` (
  `id` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL,
  `activo` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `anios` */

insert  into `anios`(`id`,`anio`,`activo`) values (1,2014,'0'),(2,2015,'0'),(3,2016,'0'),(4,2017,'1');

/*Table structure for table `areas` */

DROP TABLE IF EXISTS `areas`;

CREATE TABLE `areas` (
  `id` int(11) unsigned NOT NULL,
  `area` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `areas` */

insert  into `areas`(`id`,`area`) values (1,'wedcard1'),(2,'wedcard2'),(3,'wedcard3'),(4,'Unidad Tributaria'),(5,'Laboral'),(6,'Procesal Penal Civil');

/*Table structure for table `bancos` */

DROP TABLE IF EXISTS `bancos`;

CREATE TABLE `bancos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banco` varchar(200) DEFAULT NULL,
  `abreviado` varchar(20) DEFAULT NULL,
  `descripcion1` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `bancos` */

insert  into `bancos`(`id`,`banco`,`abreviado`,`descripcion1`) values (1,'crédito','BCP','BANCO CREDITO'),(2,'continental','BBVA','BANCO CONTINENTAL'),(3,'nación',NULL,'BANCO DE LA NACION');

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('82babb8feee06f7ec285769edc228b2b','190.81.111.215','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',1417185576,'a:9:{s:9:\"user_data\";s:0:\"\";s:11:\"empleado_id\";s:1:\"2\";s:7:\"usuario\";s:12:\"Héctor ivan\";s:3:\"dni\";s:6:\"112233\";s:16:\"apellido_paterno\";s:10:\"De La Cruz\";s:16:\"apellido_materno\";s:10:\"Del Carpio\";s:16:\"tipo_empleado_id\";s:1:\"1\";s:13:\"tipo_empleado\";s:13:\"administrador\";s:20:\"categoria_abogado_id\";s:1:\"2\";}'),('c96217a12d7f219c52921a8fb68bfab0','190.81.111.215','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',1417191437,'a:10:{s:9:\"user_data\";s:0:\"\";s:11:\"empleado_id\";s:3:\"124\";s:7:\"usuario\";s:5:\"ERIKA\";s:3:\"dni\";s:8:\"46131422\";s:16:\"apellido_paterno\";s:4:\"ABAD\";s:16:\"apellido_materno\";s:6:\"REALPE\";s:16:\"tipo_empleado_id\";s:1:\"4\";s:13:\"tipo_empleado\";s:7:\"abogado\";s:20:\"categoria_abogado_id\";s:1:\"2\";s:17:\"flash:old:mensaje\";s:33:\"Actividad: Ingresada exitosamente\";}'),('97a650b319fedf8c882c431b12ca8656','190.81.111.215','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0',1417185360,'a:10:{s:9:\"user_data\";s:0:\"\";s:11:\"empleado_id\";s:1:\"2\";s:7:\"usuario\";s:12:\"Héctor ivan\";s:3:\"dni\";s:6:\"112233\";s:16:\"apellido_paterno\";s:10:\"De La Cruz\";s:16:\"apellido_materno\";s:10:\"Del Carpio\";s:16:\"tipo_empleado_id\";s:1:\"1\";s:13:\"tipo_empleado\";s:13:\"administrador\";s:20:\"categoria_abogado_id\";s:1:\"2\";s:17:\"flash:old:mensaje\";s:15:\"Datos Correctos\";}');

/*Table structure for table `clientes` */

DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruc` varchar(20) DEFAULT NULL,
  `razon_social` varchar(220) DEFAULT NULL,
  `razon_social_sunat` varchar(220) DEFAULT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `domicilio1` varchar(250) DEFAULT NULL,
  `domicilio2` varchar(250) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `email2` varchar(150) DEFAULT NULL,
  `email3` varchar(150) DEFAULT NULL,
  `pagina_web` varchar(180) DEFAULT NULL,
  `telefono_fijo_1` varchar(70) DEFAULT NULL,
  `telefono_fijo_2` varchar(70) DEFAULT NULL,
  `telefono_movil_1` varchar(70) DEFAULT NULL,
  `telefono_movil_2` varchar(70) DEFAULT NULL,
  `empleado_id_responsable` int(11) DEFAULT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `activo` varchar(20) DEFAULT NULL,
  `fecha_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `empleado_id_insert` int(11) NOT NULL,
  `fecha_update` timestamp NULL DEFAULT NULL,
  `empleado_id_update` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `tipo_cliente_id` int(11) DEFAULT NULL,
  `tipo_cliente` varchar(40) DEFAULT NULL,
  `eliminado_cliente` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `clientes` */

insert  into `clientes`(`id`,`ruc`,`razon_social`,`razon_social_sunat`,`nombres`,`domicilio1`,`domicilio2`,`email`,`email2`,`email3`,`pagina_web`,`telefono_fijo_1`,`telefono_fijo_2`,`telefono_movil_1`,`telefono_movil_2`,`empleado_id_responsable`,`empresa_id`,`activo`,`fecha_insert`,`empleado_id_insert`,`fecha_update`,`empleado_id_update`,`grupo_id`,`tipo_cliente_id`,`tipo_cliente`,`eliminado_cliente`) values (1,'40708627','De La Cruz',NULL,'Hector','','','','','','','','','','',NULL,1,'activo','2018-09-07 13:29:29',2,NULL,NULL,NULL,1,'Persona Natural',0),(2,'20602535933','Sistemas Integrales TI','Sistemas Integrales TI',NULL,'Av. Brasil 123','','','','','','','','','',NULL,1,'inactivo','2018-09-07 13:29:32',2,NULL,NULL,NULL,2,'Persona Jurídica',0);

/*Table structure for table `comprobante_anulados` */

DROP TABLE IF EXISTS `comprobante_anulados`;

CREATE TABLE `comprobante_anulados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `comprobante_id` int(11) NOT NULL,
  `empleado_insert` int(11) NOT NULL,
  `fecha_insert` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `comprobante_anulados` */

/*Table structure for table `comprobantes` */

DROP TABLE IF EXISTS `comprobantes`;

CREATE TABLE `comprobantes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `tipo_documento_id` int(4) DEFAULT NULL,
  `serie` char(4) DEFAULT NULL,
  `numero` char(8) DEFAULT NULL,
  `fecha_de_emision` date DEFAULT NULL,
  `fecha_de_baja` date DEFAULT NULL,
  `moneda_id` int(3) DEFAULT NULL,
  `tipo_de_cambio` decimal(10,3) DEFAULT NULL,
  `fecha_de_vencimiento` date DEFAULT NULL,
  `operacion_gratuita` tinyint(1) DEFAULT NULL,
  `operacion_cancelada` tinyint(1) DEFAULT NULL,
  `detraccion` tinyint(1) DEFAULT NULL,
  `elemento_adicional_id` int(4) DEFAULT NULL,
  `porcentaje_de_detraccion` float(10,2) DEFAULT NULL,
  `total_detraccion` float(10,3) DEFAULT NULL,
  `descuento_global` decimal(10,2) DEFAULT NULL,
  `total_exonerada` decimal(10,2) DEFAULT NULL,
  `total_inafecta` decimal(10,2) DEFAULT NULL,
  `total_gravada` decimal(10,2) DEFAULT NULL,
  `total_igv` decimal(10,2) DEFAULT NULL,
  `total_gratuita` decimal(10,2) DEFAULT NULL,
  `total_otros_cargos` decimal(10,2) DEFAULT NULL,
  `total_descuentos` tinyint(1) DEFAULT NULL,
  `total_a_pagar` decimal(10,2) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `empresa_id` int(4) DEFAULT NULL,
  `tipo_pago_id` int(4) DEFAULT NULL,
  `tipo_nota_id` tinyint(4) DEFAULT NULL,
  `tipo_nota_codigo` varchar(10) DEFAULT NULL,
  `com_adjunto_id` int(11) DEFAULT NULL,
  `ace_sunat` tinyint(4) DEFAULT '0',
  `cod_sunat` int(11) DEFAULT NULL,
  `des_sunat` varchar(50) DEFAULT NULL,
  `enviado_sunat` tinyint(4) DEFAULT '0',
  `estado_sunat` tinyint(4) DEFAULT '1' COMMENT '1 no validado 0 validado por sunat',
  `enviado_cliente` tinyint(4) DEFAULT '0',
  `enviado_equipo` tinyint(4) DEFAULT '0',
  `anulado` tinyint(4) DEFAULT '0',
  `eliminado` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0 normal, 1 eliminar_enviado, 2 eliminar_confirmado',
  `empleado_insert` int(11) NOT NULL,
  `fecha_insert` datetime NOT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comprobante_serie_numero` (`serie`,`numero`,`tipo_documento_id`,`empresa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `comprobantes` */

insert  into `comprobantes`(`id`,`cliente_id`,`tipo_documento_id`,`serie`,`numero`,`fecha_de_emision`,`fecha_de_baja`,`moneda_id`,`tipo_de_cambio`,`fecha_de_vencimiento`,`operacion_gratuita`,`operacion_cancelada`,`detraccion`,`elemento_adicional_id`,`porcentaje_de_detraccion`,`total_detraccion`,`descuento_global`,`total_exonerada`,`total_inafecta`,`total_gravada`,`total_igv`,`total_gratuita`,`total_otros_cargos`,`total_descuentos`,`total_a_pagar`,`observaciones`,`empresa_id`,`tipo_pago_id`,`tipo_nota_id`,`tipo_nota_codigo`,`com_adjunto_id`,`ace_sunat`,`cod_sunat`,`des_sunat`,`enviado_sunat`,`estado_sunat`,`enviado_cliente`,`enviado_equipo`,`anulado`,`eliminado`,`empleado_insert`,`fecha_insert`,`fecha_delete`) values (1,1,1,'F001','1','2018-05-04',NULL,1,0.000,'2018-05-04',0,0,1,11,12.00,336.000,0.00,0.00,0.00,2372.88,427.12,0.00,0.00,0,2800.00,'',1,1,NULL,NULL,NULL,0,NULL,NULL,1,1,0,0,0,0,2,'2018-05-04 18:47:32',NULL),(2,1,1,'F001','2','2018-07-06',NULL,1,NULL,'2018-07-06',0,0,1,11,12.00,129.600,0.00,0.00,0.00,915.25,164.74,0.00,0.00,0,1080.00,'',1,1,NULL,NULL,NULL,0,NULL,NULL,1,1,0,0,0,0,2,'2018-07-06 19:03:42',NULL),(3,1,1,'F001','3','2018-07-06',NULL,1,NULL,'2018-07-06',0,0,0,NULL,NULL,NULL,0.00,0.00,0.00,169.49,30.51,0.00,0.00,0,200.00,'',1,1,NULL,NULL,NULL,0,NULL,NULL,0,1,0,0,0,0,2,'2018-07-06 20:10:53',NULL),(4,1,1,'F001','4','2018-08-24',NULL,1,NULL,'2018-08-24',0,0,0,NULL,NULL,NULL,NULL,0.00,0.00,84.75,15.25,NULL,NULL,NULL,100.00,'',1,1,NULL,NULL,NULL,0,NULL,NULL,1,1,0,0,0,0,2,'2018-08-24 21:14:48',NULL),(5,1,1,'F001','5','2018-08-31',NULL,1,NULL,'2018-08-31',0,0,0,NULL,NULL,NULL,NULL,0.00,0.00,8.47,1.53,NULL,NULL,NULL,10.00,'',1,1,NULL,NULL,NULL,0,NULL,NULL,1,1,0,0,0,0,2,'2018-08-31 14:38:40',NULL);

/*Table structure for table `comprobantes_facturas` */

DROP TABLE IF EXISTS `comprobantes_facturas`;

CREATE TABLE `comprobantes_facturas` (
  `comprobante_id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `emp_insert` int(11) DEFAULT NULL,
  `date_insert` datetime DEFAULT NULL,
  `fecha_eliminado` datetime DEFAULT NULL,
  PRIMARY KEY (`comprobante_id`,`factura_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `comprobantes_facturas` */

/*Table structure for table `contacto_contratos` */

DROP TABLE IF EXISTS `contacto_contratos`;

CREATE TABLE `contacto_contratos` (
  `contacto_id` int(11) unsigned NOT NULL,
  `contrato_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`contacto_id`,`contrato_id`),
  KEY `FK_contratos` (`contrato_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `contacto_contratos` */

/*Table structure for table `cuentas` */

DROP TABLE IF EXISTS `cuentas`;

CREATE TABLE `cuentas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) DEFAULT NULL,
  `banco_id` int(11) DEFAULT NULL,
  `moneda_id` int(11) DEFAULT NULL,
  `cuenta` varchar(50) DEFAULT NULL,
  `interbancario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `cuentas` */

insert  into `cuentas`(`id`,`empresa_id`,`banco_id`,`moneda_id`,`cuenta`,`interbancario`) values (1,1,1,1,'194-1983239-0-15','002-194-001983239015-97'),(2,1,1,2,'194-1967757-1-41','002-194-001967757141-96'),(3,1,2,1,'0011-0160-0200315236','011-160-000200315236-96'),(4,1,2,2,'0011-0160-0200315244','011-160-000200315244-90'),(5,1,2,3,'0011-0160-0200316496','011-160-000200316496-95'),(6,1,3,1,'00-000354724',''),(7,2,1,1,'194-0052423024','002-19400-0052423-0-2490'),(8,2,2,1,'0011-0160-01-00025648','011-160-000100025648-95'),(9,2,2,2,'0011-0160-01-00000629','011-160-000100000629-94'),(10,2,3,1,'000354732',NULL);

/*Table structure for table `elemento_adicionales` */

DROP TABLE IF EXISTS `elemento_adicionales`;

CREATE TABLE `elemento_adicionales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `descripcion` varchar(60) DEFAULT NULL,
  `activo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

/*Data for the table `elemento_adicionales` */

insert  into `elemento_adicionales`(`id`,`codigo`,`descripcion`,`activo`) values (1,'1000','Monto en Letras','inactivo'),(2,'1002','Leyenda \"TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRES','inactivo'),(3,'2000','Leyenda \"COMPROBANTE DE PERCEPCION\"','inactivo'),(4,'2001','Leyenda \"BIENES TRANSFERIDOS EN LA AMAZONÃA REGIÃ“N SELVA P','inactivo'),(5,'2002','Leyenda \"SERVICIOS PRESTADOS EN LA AMAZONÃA REGIÃ“N SELVA P','inactivo'),(6,'2003','Leyenda \"CONTRATOS DE CONSTRUCCION EJECUTADOS EN LA AMAZONÃ','inactivo'),(7,'2004','Leyenda \"Agencia de Viaje - Paquete turÃ­stico\"','inactivo'),(8,'2005','Leyenda \"Venta realizada por emiso itinerante\"','inactivo'),(9,'2006','Leyenda \"Operacion sujeta a detraccÃ­on\"','inactivo'),(10,'3000','banco de naciÃ³n nro de cuenta','activo'),(11,'3001','NUMERO DE CTA EN EL BN','activo'),(12,'3002','Recursos HidrobiolÃ³gicos-Nombre y matrÃ­cula de la embarcac','inactivo'),(13,'3003','Recursos HidrobiolÃ³gicos-Tipo y cantidad de es','inactivo'),(14,'3004','Recursos HidrobiolÃ³gicos-Lugar de descarga','inactivo'),(15,'3005','Recursos HidrobiolÃ³gicos-Fecha de descarga','inactivo'),(16,'3006','Transporte Bienes vÃ­a terrestre-Numero Registro MTC','inactivo'),(17,'3007','Transporte Bienes vÃ­a terrestre-configuracÃ­on vehicular','inactivo'),(18,'3008','Transporte Bienes vÃ­a terrestre-punto de origen','inactivo'),(19,'3009','Transporte Bienes vÃ­a terrestre-punto de destino','inactivo'),(20,'3010','Transporte Bienes vÃ­a terrestre-valor referencial prelimina','inactivo'),(21,'4000','Beneficio hospedajes:CÃ³digo PaÃ­s de emÃ­sion del pasaporte','inactivo'),(22,'4001','Beneficio hospedajes:CÃ³digo PaÃ­s de residencia del sujeto ','inactivo'),(23,'4002','Beneficio Hospedajes:Fecha de ingreso al paÃ­s','inactivo'),(24,'4003','Beneficio Hospedajes:Fecha de ingreso al establecimiento','inactivo'),(25,'4004','Beneficio Hospedajes:Fecha de salida al establecimiento','inactivo'),(26,'4005','Beneficio Hospedajes:NÃºmero de dÃ­as de permanencia','inactivo'),(27,'4006','Beneficio Hospedajes:Fecha de consumo','inactivo'),(28,'4007','Beneficio Hospedajes:Paquete turÃ­stico - Nombre y Apellidos','inactivo'),(29,'4008','Beneficio Hospedajes:Tipo documento de identidad del hÃºespe','inactivo'),(30,'4009','Beneficio Hospedajes:Numero de documento de identidad de huÃ','inactivo'),(31,'5000','Proveedores Estado: Numero de Expediente','inactivo'),(32,'5001','Proveedores Estado: CÃ³digo de unidad ejecutora','inactivo'),(33,'5002','Proveedores Estado: NÂ° de proceso de selecciÃ³n','inactivo'),(34,'5003','Proveedores Estado: NÂ° de contrato','inactivo'),(35,'6000','Comercializacion de Oro:CÃ³digo Ãšnico ConcesiÃ³n Minera','inactivo'),(36,'6001','Comercializacion de Oro:NÂ° declaraciÃ³n compromiso','inactivo'),(37,'6002','Comercializacion de Oro:NÂ° Reg. Especial .Comerci. Oro','inactivo'),(38,'6003','Comercializacion de Oro:NÂ° ResoluciÃ³n que autoriza Planta ','inactivo'),(39,'6004','Comercializacion de Oro:Ley Mineral(%concent. oro)','inactivo');

/*Table structure for table `empleados` */

DROP TABLE IF EXISTS `empleados`;

CREATE TABLE `empleados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuarios` varchar(50) NOT NULL,
  `contrasena` varchar(50) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellido_paterno` varchar(80) DEFAULT NULL,
  `apellido_materno` varchar(80) DEFAULT NULL,
  `dni` varchar(30) DEFAULT NULL,
  `domicilio` text,
  `telefono_fijo` varchar(60) DEFAULT NULL,
  `telefono_celular_1` varchar(60) DEFAULT NULL,
  `telefono_celular_2` varchar(60) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `tipo_empleado_id` int(11) unsigned NOT NULL DEFAULT '7',
  `tipo_cargo_id` int(11) DEFAULT NULL,
  `secretaria_recepcion_documentos` tinyint(4) NOT NULL,
  `categoria_abogado_id` int(11) unsigned NOT NULL,
  `tipo_horario_id` int(11) DEFAULT NULL,
  `empresa_id` int(11) NOT NULL DEFAULT '1',
  `fecha_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `empleado_id_insert` int(11) DEFAULT NULL,
  `fecha_update` timestamp NULL DEFAULT NULL,
  `empleado_id_update` int(11) DEFAULT NULL,
  `empleado_fac` tinyint(3) NOT NULL DEFAULT '0',
  `activo` varchar(10) DEFAULT NULL,
  `acceso` varchar(10) DEFAULT NULL,
  `foto` varchar(120) DEFAULT NULL,
  `curriculum` varchar(120) DEFAULT NULL,
  `cookie` int(11) DEFAULT NULL,
  `nueva_contrasena` tinyint(4) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `FK_tipo_empleados` (`tipo_empleado_id`),
  KEY `FK_categoria_abogados` (`categoria_abogado_id`)
) ENGINE=MyISAM AUTO_INCREMENT=414 DEFAULT CHARSET=utf8;

/*Data for the table `empleados` */

insert  into `empleados`(`id`,`usuarios`,`contrasena`,`nombre`,`apellido_paterno`,`apellido_materno`,`dni`,`domicilio`,`telefono_fijo`,`telefono_celular_1`,`telefono_celular_2`,`email`,`fecha_nacimiento`,`tipo_empleado_id`,`tipo_cargo_id`,`secretaria_recepcion_documentos`,`categoria_abogado_id`,`tipo_horario_id`,`empresa_id`,`fecha_insert`,`empleado_id_insert`,`fecha_update`,`empleado_id_update`,`empleado_fac`,`activo`,`acceso`,`foto`,`curriculum`,`cookie`,`nueva_contrasena`) values (2,'','12','Alejandro','De La Cruz','Del Carpio','112244','Callao','44','55','55','hdelacruz@tytl.com.pe','2017-06-13',1,9,0,2,16,1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1,1,'activo','con acceso','2.jpg','2.pdf',1690861,1);

/*Table structure for table `empresas` */

DROP TABLE IF EXISTS `empresas`;

CREATE TABLE `empresas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empresa` varchar(180) DEFAULT NULL,
  `nombre_comercial` varchar(250) DEFAULT NULL,
  `descripcion1` varchar(200) NOT NULL,
  `ruc` varchar(20) NOT NULL,
  `domicilio_fiscal` varchar(200) DEFAULT NULL,
  `telefono_fijo` varchar(30) DEFAULT NULL,
  `telefono_fijo2` varchar(30) DEFAULT NULL,
  `telefono_movil` varchar(30) DEFAULT NULL,
  `telefono_movil2` varchar(30) DEFAULT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `activo` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `empresas` */

insert  into `empresas`(`id`,`empresa`,`nombre_comercial`,`descripcion1`,`ruc`,`domicilio_fiscal`,`telefono_fijo`,`telefono_fijo2`,`telefono_movil`,`telefono_movil2`,`foto`,`correo`,`activo`) values (1,'Coca Cola','Coca Cola','Coca Cola','10407086','Lima 247','','','949 058 569','','pelota-semilla-jd-thuds.jpg','hector.sistema21@gmail.com','activo');

/*Table structure for table `igv` */

DROP TABLE IF EXISTS `igv`;

CREATE TABLE `igv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `valor` decimal(10,3) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `activo` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `igv` */

insert  into `igv`(`id`,`valor`,`fecha`,`activo`) values (1,0.180,'2015-12-03','activo'),(2,0.190,'2015-11-05','inactivo');

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comprobante_id` int(11) DEFAULT NULL,
  `tipo_item_id` int(11) DEFAULT NULL,
  `descripcion` text,
  `cantidad` int(10) DEFAULT NULL,
  `tipo_igv_id` int(10) DEFAULT NULL,
  `importe` float(10,2) DEFAULT NULL,
  `subtotal` float(10,2) DEFAULT NULL,
  `igv` float(10,2) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `eliminado` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `items` */

insert  into `items`(`id`,`comprobante_id`,`tipo_item_id`,`descripcion`,`cantidad`,`tipo_igv_id`,`importe`,`subtotal`,`igv`,`total`,`eliminado`) values (1,1,2,'caramelos abc',2,1,500.00,1000.00,152.54,1000.00,0),(2,1,2,'tortas mnp',3,1,600.00,1800.00,274.58,1800.00,0),(3,2,2,'zapatos',1,1,80.00,80.00,12.20,80.00,0),(4,2,2,'medias',5,1,200.00,1000.00,152.54,1000.00,0),(5,3,2,'aa',1,1,200.00,200.00,30.51,200.00,0),(6,4,2,'zapato',2,1,50.00,100.00,15.25,100.00,0),(7,5,2,'cccc',1,1,10.00,10.00,1.53,10.00,0);

/*Table structure for table `monedas` */

DROP TABLE IF EXISTS `monedas`;

CREATE TABLE `monedas` (
  `id` int(11) NOT NULL,
  `moneda` varchar(50) NOT NULL,
  `abreviado` varchar(10) NOT NULL,
  `abrstandar` varchar(10) NOT NULL,
  `simbolo` varchar(2) NOT NULL,
  `activo` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `monedas` */

insert  into `monedas`(`id`,`moneda`,`abreviado`,`abrstandar`,`simbolo`,`activo`) values (1,'soles','sol','PEN','S/','1'),(2,'dólares','dol','USD','$','1'),(3,'euros','eur','EUR','E','1');

/*Table structure for table `ser_nums` */

DROP TABLE IF EXISTS `ser_nums`;

CREATE TABLE `ser_nums` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `tipo_documento_id` int(10) NOT NULL,
  `viene_de` int(11) NOT NULL COMMENT 'solo si tipo_documento_id = 7 (notas de credito) si dicha nota de credito biene de factura entonces la SERIE comenzara con F, si viene de boleta.. comenzara con B',
  `serie` char(4) DEFAULT NULL,
  `eliminado` tinyint(3) NOT NULL DEFAULT '0',
  `fecha_insert` datetime NOT NULL,
  `fecha_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`empresa_id`,`tipo_documento_id`,`viene_de`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `ser_nums` */

insert  into `ser_nums`(`id`,`empresa_id`,`tipo_documento_id`,`viene_de`,`serie`,`eliminado`,`fecha_insert`,`fecha_update`) values (18,2,8,0,'B001',0,'0000-00-00 00:00:00','2017-06-02 20:32:05'),(17,2,7,1,'F001',0,'0000-00-00 00:00:00','2017-12-18 17:15:50'),(16,2,3,0,'B001',0,'0000-00-00 00:00:00','2017-06-02 20:32:05'),(15,2,1,0,'F001',0,'0000-00-00 00:00:00','2017-06-08 11:30:35'),(19,1,1,0,'F001',0,'0000-00-00 00:00:00','2017-11-03 13:40:41'),(20,1,3,0,'B001',0,'0000-00-00 00:00:00','2017-11-27 12:48:14'),(21,1,7,1,'F001',0,'0000-00-00 00:00:00','2017-12-18 16:39:02'),(22,1,8,0,'F001',0,'0000-00-00 00:00:00','2018-04-25 19:32:52'),(23,1,7,3,'F001',0,'0000-00-00 00:00:00','2018-04-25 19:32:52'),(24,2,7,3,'B001',0,'0000-00-00 00:00:00','2017-12-18 17:16:21');

/*Table structure for table `tdoc_identidad` */

DROP TABLE IF EXISTS `tdoc_identidad`;

CREATE TABLE `tdoc_identidad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tdoc_identidad` varchar(60) DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tdoc_identidad` */

insert  into `tdoc_identidad`(`id`,`codigo`,`tdoc_identidad`,`eliminado`) values (1,'0','DOC.TRIB.NO.NOM.SIN.RUC',0),(2,'1','DOC.NACIONAL DE IDENTIDAD',0),(3,'4','CARNET DE EXTRANJERIA',0),(4,'6','REG. UNICO DE CONTRIBUYENTES',0),(5,'7','PASAPORTE',0),(6,'A','CED.DEPLOMATICA DE IDENTIDAD',0);

/*Table structure for table `tipo_cambio` */

DROP TABLE IF EXISTS `tipo_cambio`;

CREATE TABLE `tipo_cambio` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `moneda_id` int(10) NOT NULL,
  `tipo_cambio` decimal(10,3) DEFAULT NULL,
  `fecha` date NOT NULL,
  `activo` varchar(20) NOT NULL DEFAULT 'inactivo',
  `eliminado` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=369 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_cambio` */

insert  into `tipo_cambio`(`id`,`moneda_id`,`tipo_cambio`,`fecha`,`activo`,`eliminado`) values (1,2,3.150,'2017-04-10','inactivo',1),(2,2,3.623,'2017-04-17','activo',1),(3,2,3.260,'2017-04-18','inactivo',1),(4,2,234.012,'2017-04-17','inactivo',1),(5,2,3.620,'2017-04-17','inactivo',1),(6,3,3.260,'2017-04-05','inactivo',1),(7,3,2.500,'2017-04-18','inactivo',1),(8,3,3.560,'2017-04-18','inactivo',1),(9,2,3.150,'2017-04-18','inactivo',1),(10,3,3.230,'2017-04-18','inactivo',1),(11,2,3.240,'2017-04-19','inactivo',1),(12,2,4.010,'2017-04-28','inactivo',1),(13,2,3.900,'2017-05-10','inactivo',1),(14,2,3.272,'2017-06-01','inactivo',0),(15,2,3.271,'2017-06-02','inactivo',0),(16,2,3.276,'2017-06-21','inactivo',0),(17,2,3.273,'2017-06-03','inactivo',0),(18,2,3.273,'2017-06-04','inactivo',1),(19,2,3.273,'2017-06-04','inactivo',0),(20,2,3.273,'2017-06-05','inactivo',0),(21,2,3.272,'2017-06-06','inactivo',0),(22,2,3.269,'2017-06-07','inactivo',0),(23,2,3.268,'2017-06-08','inactivo',0),(24,2,3.272,'2017-06-09','inactivo',0),(25,2,3.270,'2017-06-10','inactivo',0),(26,2,3.270,'2017-06-11','inactivo',0),(27,2,3.270,'2017-06-12','inactivo',1),(28,2,3.270,'2017-06-12','inactivo',0),(29,2,3.277,'2017-06-13','inactivo',0),(30,2,3.278,'2017-06-14','inactivo',0),(31,2,3.278,'2017-06-16','inactivo',0),(32,2,3.271,'2017-06-15','inactivo',0),(33,2,3.276,'2017-06-17','inactivo',0),(34,2,3.276,'2017-06-18','inactivo',0),(35,2,3.276,'2017-06-19','inactivo',0),(36,2,3.270,'2017-06-20','inactivo',0),(37,2,3.271,'2017-06-22','inactivo',0),(38,2,3.269,'2017-06-23','inactivo',0),(39,2,3.260,'2017-06-24','inactivo',0),(40,2,3.260,'2017-06-25','inactivo',0),(41,2,3.260,'2017-06-26','inactivo',0),(42,2,3.255,'2017-06-27','inactivo',0),(43,2,3.255,'2017-07-03','inactivo',0),(44,2,3.253,'2017-07-04','inactivo',0),(45,2,3.258,'2017-07-05','inactivo',0),(46,2,3.264,'2017-07-06','inactivo',0),(47,2,3.257,'2017-07-07','inactivo',0),(48,2,3.251,'2017-07-10','inactivo',0),(49,2,3.256,'2017-07-11','inactivo',0),(50,2,3.255,'2017-07-12','inactivo',0),(51,2,3.252,'2017-07-13','inactivo',0),(52,2,3.252,'2017-07-14','inactivo',0),(53,2,3.252,'2017-07-15','inactivo',0),(54,2,3.252,'2017-07-16','inactivo',0),(55,2,3.246,'2017-07-17','inactivo',0),(56,2,3.251,'2017-07-18','inactivo',0),(57,2,3.246,'2017-07-19','inactivo',0),(58,2,3.243,'2017-07-20','inactivo',0),(59,2,3.241,'2017-07-21','inactivo',0),(60,2,3.241,'2017-07-22','inactivo',0),(61,2,3.241,'2017-07-23','inactivo',0),(62,2,3.247,'2017-07-24','inactivo',0),(63,2,3.247,'2017-07-25','inactivo',0),(64,2,3.248,'2017-07-26','inactivo',0),(65,2,3.248,'2017-07-27','inactivo',0),(66,2,3.248,'2017-07-28','inactivo',0),(67,2,3.248,'2017-07-29','inactivo',0),(68,2,3.248,'2017-07-30','inactivo',0),(69,2,3.250,'2017-07-31','inactivo',0),(70,2,3.242,'2017-08-01','inactivo',0),(71,2,3.240,'2017-08-02','inactivo',0),(72,2,3.242,'2017-08-03','inactivo',0),(73,2,3.240,'2017-08-04','inactivo',0),(74,2,3.240,'2017-08-05','inactivo',0),(75,2,3.240,'2017-08-06','inactivo',0),(76,2,3.242,'2017-08-07','inactivo',0),(77,2,3.244,'2017-08-08','inactivo',0),(78,2,3.244,'2017-08-09','inactivo',0),(79,2,3.249,'2017-08-10','inactivo',0),(80,2,3.249,'2017-08-11','inactivo',0),(81,2,3.249,'2017-08-12','inactivo',0),(82,2,3.249,'2017-08-13','inactivo',0),(83,2,3.251,'2017-08-14','inactivo',0),(84,2,3.246,'2017-08-15','inactivo',0),(85,2,3.247,'2017-08-16','inactivo',0),(86,2,3.245,'2017-08-17','inactivo',0),(87,2,3.243,'2017-08-18','inactivo',0),(88,2,3.243,'2017-08-19','inactivo',0),(89,2,3.243,'2017-08-20','inactivo',0),(90,2,3.243,'2017-08-21','inactivo',0),(91,2,3.240,'2017-08-22','inactivo',0),(92,2,3.240,'2017-08-23','inactivo',0),(93,2,3.239,'2017-08-24','inactivo',0),(94,2,3.238,'2017-08-25','inactivo',0),(95,2,3.238,'2017-08-26','inactivo',0),(96,2,3.238,'2017-08-27','inactivo',0),(97,2,3.238,'2017-08-28','inactivo',0),(98,2,3.238,'2017-08-29','inactivo',0),(99,2,3.238,'2017-08-30','inactivo',0),(100,2,3.242,'2017-08-31','inactivo',0),(101,2,3.242,'2017-09-01','inactivo',0),(102,2,3.242,'2017-09-02','inactivo',0),(103,2,3.242,'2017-09-03','inactivo',0),(104,2,3.240,'2017-09-04','inactivo',0),(105,2,3.241,'2017-09-05','inactivo',0),(106,2,3.238,'2017-09-06','inactivo',0),(107,2,3.238,'2017-09-07','inactivo',0),(108,2,3.236,'2017-09-08','inactivo',0),(109,2,3.236,'2017-09-09','inactivo',0),(110,2,3.236,'2017-09-10','inactivo',0),(111,2,3.235,'2017-09-11','inactivo',0),(112,2,3.232,'2017-09-12','inactivo',0),(113,2,3.235,'2017-09-13','inactivo',0),(114,2,3.240,'2017-09-14','inactivo',0),(115,2,3.240,'2017-09-15','inactivo',0),(116,2,3.240,'2017-09-16','inactivo',0),(117,2,3.240,'2017-09-17','inactivo',0),(118,2,3.248,'2017-09-18','inactivo',0),(119,2,3.249,'2017-09-19','inactivo',0),(120,2,3.245,'2017-09-20','inactivo',0),(121,2,3.245,'2017-09-21','inactivo',0),(122,2,3.250,'2017-09-22','inactivo',0),(123,2,3.250,'2017-09-23','inactivo',0),(124,2,3.250,'2017-09-24','inactivo',0),(125,2,3.248,'2017-09-25','inactivo',0),(126,2,3.255,'2017-09-26','inactivo',0),(127,2,3.271,'2017-09-27','inactivo',0),(128,2,3.276,'2017-09-28','inactivo',0),(129,2,3.270,'2017-09-29','inactivo',0),(130,2,3.270,'2017-09-30','inactivo',0),(131,2,3.270,'2017-10-01','inactivo',0),(132,2,3.270,'2017-10-02','inactivo',0),(133,2,3.271,'2017-10-03','inactivo',0),(134,2,3.268,'2017-10-04','inactivo',0),(135,2,3.260,'2017-10-05','inactivo',0),(136,2,3.256,'2017-10-06','inactivo',0),(137,2,3.256,'2017-10-07','inactivo',0),(138,2,3.256,'2017-10-08','inactivo',0),(139,2,3.268,'2017-10-09','inactivo',0),(140,2,3.273,'2017-10-10','inactivo',0),(141,2,3.267,'2017-10-11','inactivo',0),(142,2,3.262,'2017-10-12','inactivo',0),(143,2,3.256,'2017-10-13','inactivo',0),(144,2,3.256,'2017-10-14','inactivo',0),(145,2,3.256,'2017-10-15','inactivo',0),(146,2,3.251,'2017-10-16','inactivo',0),(147,2,3.247,'2017-10-17','inactivo',0),(148,2,3.246,'2017-10-18','inactivo',0),(149,2,3.244,'2017-10-19','inactivo',0),(150,2,3.370,'2017-10-20','inactivo',0),(151,2,3.370,'2017-10-21','inactivo',0),(152,2,3.370,'2017-10-22','inactivo',0),(153,2,3.240,'2017-10-23','inactivo',0),(154,2,3.241,'2017-10-24','inactivo',0),(155,2,3.244,'2017-10-25','inactivo',0),(156,2,3.235,'2017-10-26','inactivo',0),(157,2,3.239,'2017-10-27','inactivo',0),(158,2,3.239,'2017-10-28','inactivo',0),(159,2,3.239,'2017-10-29','inactivo',0),(160,2,3.248,'2017-10-30','inactivo',0),(161,2,3.253,'2017-10-31','inactivo',0),(162,2,3.253,'2017-11-01','inactivo',0),(163,2,3.250,'2017-11-02','inactivo',0),(164,2,3.244,'2017-11-03','inactivo',0),(165,2,3.244,'2017-11-04','inactivo',0),(166,2,3.244,'2017-11-05','inactivo',0),(167,2,3.246,'2017-11-06','inactivo',0),(168,2,3.242,'2017-11-07','inactivo',0),(169,2,3.243,'2017-11-08','inactivo',0),(170,2,3.244,'2017-11-09','inactivo',0),(171,2,3.246,'2017-11-11','inactivo',0),(172,2,3.246,'2017-11-12','inactivo',0),(173,2,3.243,'2017-11-13','inactivo',0),(174,2,3.244,'2017-11-14','inactivo',0),(175,2,3.243,'2017-11-15','inactivo',0),(176,2,3.250,'2017-11-16','inactivo',0),(177,2,3.252,'2017-11-17','inactivo',0),(178,2,3.252,'2017-11-18','inactivo',0),(179,2,3.252,'2017-11-19','inactivo',0),(180,2,3.246,'2017-11-20','inactivo',0),(181,2,3.244,'2017-11-21','inactivo',0),(182,2,3.236,'2017-11-22','inactivo',0),(183,2,3.241,'2017-11-23','inactivo',0),(184,2,3.241,'2017-11-24','inactivo',0),(185,2,3.241,'2017-11-25','inactivo',0),(186,2,3.241,'2017-11-26','inactivo',0),(187,2,3.240,'2017-11-27','inactivo',0),(188,2,3.238,'2017-11-28','inactivo',0),(189,2,3.237,'2017-11-29','inactivo',0),(190,2,3.234,'2017-11-30','inactivo',0),(191,3,3.820,'2017-11-30','inactivo',1),(192,3,3.832,'2017-12-01','inactivo',0),(193,2,3.235,'2017-12-01','inactivo',0),(194,2,3.234,'2017-12-04','inactivo',0),(195,2,3.237,'2017-12-05','inactivo',0),(196,2,3.236,'2017-12-06','inactivo',0),(197,2,3.238,'2017-12-11','inactivo',0),(198,2,3.238,'2017-12-12','inactivo',0),(199,2,3.236,'2017-12-13','inactivo',0),(200,2,3.233,'2017-12-14','inactivo',0),(201,2,3.244,'2017-12-15','inactivo',0),(202,2,3.262,'2017-12-18','inactivo',0),(203,2,3.292,'2017-12-19','inactivo',0),(204,2,3.269,'2017-12-20','inactivo',0),(205,2,3.276,'2017-12-21','inactivo',0),(206,2,3.268,'2017-12-22','inactivo',0),(207,2,3.241,'2017-12-26','inactivo',0),(208,2,3.243,'2017-12-27','inactivo',0),(209,2,3.244,'2017-12-28','inactivo',0),(210,2,3.244,'2017-12-29','inactivo',0),(211,2,3.244,'2018-01-02','inactivo',0),(212,3,3.945,'2018-01-02','inactivo',0),(213,2,3.245,'2018-01-03','inactivo',0),(214,3,3.955,'2018-01-03','inactivo',0),(215,2,3.230,'2018-01-04','inactivo',0),(216,2,3.208,'2018-01-05','inactivo',0),(217,3,3.900,'2018-01-04','inactivo',0),(218,2,3.215,'2018-01-08','inactivo',0),(219,3,3.917,'2018-01-05','inactivo',0),(220,2,3.219,'2018-01-09','inactivo',0),(221,3,4.017,'2018-01-08','inactivo',0),(222,2,3.220,'2018-01-10','inactivo',0),(223,2,3.220,'2018-01-11','inactivo',0),(224,2,3.218,'2018-01-12','inactivo',0),(225,2,3.217,'2018-01-15','inactivo',0),(226,2,3.216,'2018-01-16','inactivo',0),(227,3,3.940,'2018-01-09','inactivo',0),(228,3,3.882,'2018-01-10','inactivo',0),(229,3,3.941,'2018-01-11','inactivo',0),(230,3,3.959,'2018-01-12','inactivo',0),(231,3,4.031,'2018-01-15','inactivo',0),(232,2,3.217,'2018-01-17','inactivo',0),(233,3,3.953,'2018-01-16','inactivo',0),(234,2,3.212,'2018-01-18','inactivo',0),(235,2,3.213,'2018-01-19','inactivo',0),(236,2,4.005,'2018-01-18','inactivo',1),(237,3,4.011,'2018-01-17','inactivo',0),(238,2,3.217,'2018-01-22','inactivo',0),(239,3,4.013,'2018-01-19','inactivo',0),(240,2,3.216,'2018-01-23','inactivo',0),(241,3,4.075,'2018-01-22','inactivo',0),(242,2,3.219,'2018-01-24','inactivo',0),(243,3,4.081,'2018-01-23','inactivo',0),(244,2,3.214,'2018-01-25','inactivo',0),(245,3,4.069,'2018-01-24','inactivo',0),(246,2,3.210,'2018-01-26','inactivo',0),(247,3,4.041,'2018-01-25','inactivo',0),(248,2,3.215,'2018-01-29','inactivo',0),(249,3,4.101,'2018-01-26','inactivo',0),(250,2,3.219,'2018-01-30','inactivo',0),(251,3,4.127,'2018-01-29','inactivo',0),(252,2,3.217,'2018-01-31','inactivo',0),(253,3,4.063,'2018-01-30','inactivo',0),(254,2,3.217,'2018-02-01','inactivo',0),(255,3,4.088,'2018-01-31','inactivo',0),(256,2,3.213,'2018-02-02','inactivo',0),(257,3,4.136,'2018-02-01','inactivo',0),(258,2,3.218,'2018-02-05','inactivo',0),(259,3,4.073,'2018-02-02','inactivo',0),(260,2,3.225,'2018-02-06','inactivo',0),(261,2,3.251,'2018-02-07','inactivo',0),(262,3,4.090,'2018-02-05','inactivo',0),(263,3,4.090,'2018-02-06','inactivo',0),(264,2,3.247,'2018-02-08','inactivo',0),(265,0,4.086,'2018-02-07','inactivo',0),(266,2,3.254,'2018-02-09','inactivo',0),(267,3,4.064,'2018-02-08','inactivo',0),(268,2,3.270,'2018-02-12','inactivo',0),(269,3,4.145,'2018-02-09','inactivo',0),(270,2,3.265,'2018-02-13','inactivo',0),(271,3,4.122,'2018-02-12','inactivo',0),(272,2,3.270,'2018-02-14','inactivo',0),(273,3,4.134,'2018-02-13','inactivo',0),(274,2,3.270,'2018-02-15','inactivo',0),(275,3,4.139,'2018-02-14','inactivo',0),(276,3,4.121,'2018-02-15','inactivo',0),(277,2,3.250,'2018-02-16','inactivo',1),(278,2,3.250,'2018-02-16','inactivo',0),(279,2,3.243,'2018-02-19','inactivo',0),(280,2,3.250,'2018-02-20','inactivo',0),(281,3,4.086,'2018-02-16','inactivo',0),(282,3,4.136,'2018-02-19','inactivo',0),(283,2,3.253,'2018-02-21','inactivo',0),(284,3,4.112,'2018-02-20','inactivo',0),(285,2,3.249,'2018-02-22','inactivo',0),(286,3,4.080,'2018-02-21','inactivo',0),(287,0,3.251,'2018-02-23','inactivo',0),(288,3,4.122,'2018-02-22','inactivo',0),(289,2,3.251,'2018-02-26','inactivo',0),(290,3,4.078,'2018-02-23','inactivo',0),(291,2,3.250,'2018-02-27','inactivo',0),(292,3,4.148,'2018-02-26','inactivo',0),(293,2,3.250,'2018-03-28','inactivo',1),(294,3,4.085,'2018-03-27','inactivo',1),(295,3,4.083,'2018-02-28','inactivo',0),(296,2,3.261,'2018-03-01','inactivo',0),(297,2,3.265,'2018-03-02','inactivo',0),(298,3,4.063,'2018-03-01','inactivo',0),(299,2,3.259,'2018-03-05','inactivo',0),(300,3,4.056,'2018-03-02','inactivo',0),(301,2,3.250,'2018-03-06','inactivo',0),(302,3,4.135,'2018-03-05','inactivo',0),(303,2,3.252,'2018-03-07','inactivo',0),(304,3,4.177,'2018-03-06','inactivo',0),(305,2,3.257,'2018-03-08','inactivo',0),(306,3,4.131,'2018-03-07','inactivo',0),(307,2,3.256,'2018-03-09','inactivo',0),(308,3,4.124,'2018-03-08','inactivo',0),(309,2,3.260,'2018-03-12','inactivo',0),(310,3,4.135,'2018-03-09','inactivo',0),(311,2,3.259,'2018-03-13','inactivo',0),(312,3,4.110,'2018-03-12','inactivo',0),(313,2,3.260,'2018-03-14','inactivo',0),(314,3,4.167,'2018-03-13','inactivo',0),(315,2,3.261,'2018-03-15','inactivo',0),(316,3,4.135,'2018-03-14','inactivo',0),(317,2,3.262,'2018-03-16','inactivo',0),(318,3,4.092,'2018-03-15','inactivo',0),(319,2,3.268,'2018-03-19','inactivo',0),(320,3,4.128,'2018-03-16','inactivo',0),(321,2,3.273,'2018-03-20','inactivo',0),(322,3,4.135,'2018-03-19','inactivo',0),(323,2,3.269,'2018-03-21','inactivo',0),(324,3,4.135,'2018-03-20','inactivo',1),(325,2,3.262,'2018-03-22','inactivo',1),(326,2,3.263,'2018-03-22','inactivo',0),(327,3,4.179,'2018-03-20','inactivo',0),(328,3,4.112,'2018-03-21','inactivo',0),(329,2,3.243,'2018-03-23','inactivo',0),(330,3,4.080,'2018-03-22','inactivo',0),(331,2,3.237,'2018-03-26','inactivo',0),(332,3,4.019,'2018-03-23','inactivo',0),(333,2,3.219,'2018-03-27','inactivo',0),(334,3,4.086,'2018-03-26','inactivo',0),(335,2,3.223,'2018-03-28','inactivo',0),(336,3,4.078,'2018-03-27','inactivo',0),(337,2,3.229,'2018-03-29','inactivo',0),(338,3,4.062,'2018-03-28','inactivo',0),(339,2,3.229,'2018-04-03','inactivo',0),(340,3,4.127,'2018-03-29','inactivo',1),(341,3,4.063,'2018-03-30','inactivo',1),(342,3,4.088,'2018-03-31','inactivo',1),(343,2,3.226,'2018-04-04','inactivo',0),(344,3,4.102,'2018-04-02','inactivo',0),(345,2,3.229,'2018-04-02','inactivo',0),(346,3,4.130,'2018-04-03','inactivo',0),(347,2,3.228,'2018-04-05','inactivo',0),(348,3,4.072,'2018-04-04','inactivo',0),(349,2,3.228,'2018-04-06','inactivo',0),(350,3,4.097,'2018-04-05','inactivo',0),(351,2,3.234,'2018-04-09','inactivo',0),(352,2,3.239,'2018-04-10','inactivo',0),(353,3,4.091,'2018-04-06','inactivo',0),(354,3,4.073,'2018-04-09','inactivo',0),(355,2,3.239,'2018-04-11','inactivo',0),(356,3,4.124,'2018-04-10','inactivo',0),(357,2,3.239,'2018-04-12','inactivo',0),(358,3,4.110,'2018-04-11','inactivo',0),(359,2,3.239,'2018-04-16','inactivo',0),(360,3,4.046,'2018-04-12','inactivo',0),(361,2,3.224,'2018-04-17','inactivo',0),(362,3,4.100,'2018-04-16','inactivo',0),(363,2,3.221,'2018-04-18','inactivo',0),(364,3,4.103,'2018-04-17','inactivo',0),(365,2,3.217,'2018-04-19','inactivo',0),(366,3,4.084,'2018-04-18','inactivo',0),(367,2,3.221,'2018-04-20','inactivo',0),(368,3,4.034,'2018-04-19','inactivo',0);

/*Table structure for table `tipo_clientes` */

DROP TABLE IF EXISTS `tipo_clientes`;

CREATE TABLE `tipo_clientes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_cliente` varchar(40) DEFAULT NULL,
  `codigo` varchar(10) NOT NULL,
  `abr_standar` varchar(30) NOT NULL,
  `activo` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_clientes` */

insert  into `tipo_clientes`(`id`,`tipo_cliente`,`codigo`,`abr_standar`,`activo`) values (1,'Persona Natural','1','DOC.NACIONAL DE IDEN','activo'),(2,'Persona Jurídica','6','REG. UNICO DE CONTRI','activo'),(3,'Empresas Del Extranjero','0','DOC.TRIB.NO.DOM.SIN.','activo'),(4,'Carnet de Extranjeria','4','CARNET DE EXTRANJERIA','activo'),(5,'Pasaporte','7','PASAPORTE','activo');

/*Table structure for table `tipo_contratos` */

DROP TABLE IF EXISTS `tipo_contratos`;

CREATE TABLE `tipo_contratos` (
  `id` int(11) unsigned NOT NULL,
  `tipo_contrato` varchar(80) DEFAULT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `tipo_contratos` */

insert  into `tipo_contratos`(`id`,`tipo_contrato`,`orden`) values (1,'permanente',1),(2,'caso puntual',3),(3,'por horas',2);

/*Table structure for table `tipo_documentos` */

DROP TABLE IF EXISTS `tipo_documentos`;

CREATE TABLE `tipo_documentos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(4) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `abr` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_documentos` */

insert  into `tipo_documentos`(`id`,`codigo`,`tipo_documento`,`abr`) values (1,'01','Factura','F'),(3,'03','Boleta de Venta','B'),(7,'07','Nota de Credito','NC'),(8,'08','Nota de Debito','ND');

/*Table structure for table `tipo_empleados` */

DROP TABLE IF EXISTS `tipo_empleados`;

CREATE TABLE `tipo_empleados` (
  `id` int(11) unsigned NOT NULL,
  `tipo_empleado` varchar(200) DEFAULT NULL,
  `fla_abogado` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `tipo_empleados` */

insert  into `tipo_empleados`(`id`,`tipo_empleado`,`fla_abogado`) values (1,'administrador',0),(2,'secretaria',0),(3,'socio',1),(4,'abogado',1),(5,'practicante',1),(6,'otros Abogados',1),(7,'TYTL-Asesor_Andina',0),(8,'contabilidad',0),(9,'Recursos Humanos',0);

/*Table structure for table `tipo_horarios` */

DROP TABLE IF EXISTS `tipo_horarios`;

CREATE TABLE `tipo_horarios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) DEFAULT NULL,
  `descripcion` text,
  `ingreso` time NOT NULL DEFAULT '00:00:00',
  `salida` time NOT NULL DEFAULT '00:00:00',
  `ingresoSabado` time DEFAULT NULL,
  `salidaSabado` time DEFAULT NULL,
  `refrigerio` time NOT NULL DEFAULT '00:00:00',
  `diasLaborable` tinyint(3) NOT NULL DEFAULT '1',
  `notificacion` tinyint(3) NOT NULL DEFAULT '0',
  `eliminado` tinyint(4) NOT NULL DEFAULT '0',
  `fecha_insert` datetime NOT NULL,
  `empleado_id_insert` int(11) NOT NULL,
  `fecha_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `empleado_id_update` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tipo_horarios` */

/*Table structure for table `tipo_igv` */

DROP TABLE IF EXISTS `tipo_igv`;

CREATE TABLE `tipo_igv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo_igv` varchar(60) DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_igv` */

insert  into `tipo_igv`(`id`,`codigo`,`tipo_igv`,`eliminado`) values (1,'10','Gravado - Operación Onerosa',0),(2,'11','Gravado - Retiro por premio',0),(3,'12','Gravado - Retiro por donación',0),(4,'13','Gravado - Retiro',0),(5,'14','Gravado - Retiro por publicidad',0),(6,'15','Gravado - Bonificaciones',0),(7,'16','Gravado - Retiro por entrega a trabajadores',0),(8,'20','Exonerado - Operación Onerosa',0),(9,'30','Inafecto - Operación Onerosa',0),(10,'31','Inafecto - Retiro por Bonificación',0),(11,'32','Inafecto - Retiro',0),(12,'33','Inafecto - Retiro por Muestras Médicas',0),(13,'34','Inafecto - Retiro por Convenio Colectivo',0),(14,'35','Inafecto - Retiro por premio',0),(15,'36','Inafecto - Retiro por publicidad',0),(16,'40','Exportación',0);

/*Table structure for table `tipo_items` */

DROP TABLE IF EXISTS `tipo_items`;

CREATE TABLE `tipo_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_item` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_items` */

insert  into `tipo_items`(`id`,`tipo_item`) values (2,'PRODUCTO');

/*Table structure for table `tipo_ncreditos` */

DROP TABLE IF EXISTS `tipo_ncreditos`;

CREATE TABLE `tipo_ncreditos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo_ncredito` varchar(60) DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_ncreditos` */

insert  into `tipo_ncreditos`(`id`,`codigo`,`tipo_ncredito`,`eliminado`) values (1,'01','Anulación de la operacion',0),(2,'02','Anulación por error en el RUC',0),(3,'03','Corrección por error en la descripcion',0),(4,'04','Descuento Global',0),(5,'05','Descuento por ítem',0),(6,'06','Devolución total',0),(7,'07','Devolución por ítem',0),(8,'08','Bonificación',0),(9,'09','Disminición en el valor',0),(10,'10','Otros conceptos',0);

/*Table structure for table `tipo_ndebitos` */

DROP TABLE IF EXISTS `tipo_ndebitos`;

CREATE TABLE `tipo_ndebitos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo_ndebito` varchar(60) DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_ndebitos` */

insert  into `tipo_ndebitos`(`id`,`codigo`,`tipo_ndebito`,`eliminado`) values (1,'01','Interes por mora',0),(2,'02','Aumento en el valor',0),(3,'03','Penalidades / Otros conceptos',0);

/*Table structure for table `tipo_pagos` */

DROP TABLE IF EXISTS `tipo_pagos`;

CREATE TABLE `tipo_pagos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_pago` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_pagos` */

insert  into `tipo_pagos`(`id`,`tipo_pago`) values (1,'Efectivo'),(2,'Tarjeta'),(3,'Cheque'),(4,'Letra de Cambio'),(5,'Deposito en cuenta'),(6,'Otros');

/* Procedure structure for procedure `get_numero` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_numero` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`grupotytl`@`localhost` PROCEDURE `get_numero`(IN _empresa_id INT, IN _tipo_documento_id INT, IN _serie VARCHAR(4))
BEGIN
   SELECT MAX(CAST(numero AS UNSIGNED)) numero FROM comprobantes WHERE empresa_id = _empresa_id AND tipo_documento_id = _tipo_documento_id AND serie = _serie;
END */$$
DELIMITER ;

/* Procedure structure for procedure `get_serie` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_serie` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`grupotytl`@`localhost` PROCEDURE `get_serie`(IN _empresa_id INT, IN _tipo_documento_id INT)
BEGIN
   SELECT SERIE FROM `ser_nums` WHERE empresa_id = _empresa_id AND `tipo_documento_id` = _tipo_documento_id;
END */$$
DELIMITER ;

/* Procedure structure for procedure `get_tipo_cambio` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_tipo_cambio` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`grupotytl`@`localhost` PROCEDURE `get_tipo_cambio`(IN _moneda_id INT, IN _fecha DATE)
BEGIN   
   SELECT tipo_cambio FROM tipo_cambio WHERE moneda_id = _moneda_id AND fecha <= _fecha ORDER BY fecha DESC LIMIT 1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `insert_items` */

/*!50003 DROP PROCEDURE IF EXISTS  `insert_items` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`grupotytl`@`localhost` PROCEDURE `insert_items`(IN _comprobante_id INT, IN _tipo_item_id INT, IN _descripcion VARCHAR(500), IN _cantidad INT, IN _tipo_igv_id INT, IN _importe DECIMAL(10,2), IN _subtotal DECIMAL(10,2), IN _igv DECIMAL(10,2), IN _total DECIMAL(10,2), IN _eliminado INT)
BEGIN
   INSERT  INTO items( comprobante_id,   tipo_item_id,  descripcion,  cantidad,  tipo_igv_id,  importe,  subtotal,  igv,  total,  eliminado) VALUES 
                     (_comprobante_id,  _tipo_item_id, _descripcion, _cantidad, _tipo_igv_id, _importe, _subtotal, _igv, _total, _eliminado);
END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_last_id` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_last_id` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`grupotytl`@`localhost` PROCEDURE `sp_last_id`()
BEGIN
   SELECT LAST_INSERT_ID() last_id;
END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_comprobantes_insert` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_comprobantes_insert` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`grupotytl`@`localhost` PROCEDURE `sp_comprobantes_insert`(
	IN cliente_id INT(5), IN tipo_documento_id INT(2), IN serie VARCHAR(4), IN numero VARCHAR(6), IN fecha_de_emision DATE,
	IN fecha_de_baja DATE, IN moneda_id INT(2), IN tipo_de_cambio DECIMAL(10,3), IN fecha_de_vencimiento DATE, IN operacion_gratuita INT,
	IN operacion_cancelada INT, IN detraccion INT(2), IN elemento_adicional_id INT(4), IN porcentaje_de_detraccion DECIMAL(10,2), IN total_detraccion DECIMAL(10,3),
	IN descuento_global DECIMAL(10,2), IN total_exonerada DECIMAL(10,2), IN total_inafecta DECIMAL(10,2), IN total_gravada DECIMAL(10,2), IN total_igv DECIMAL(10,2),
	IN total_gratuita DECIMAL(10,2), IN total_otros_cargos DECIMAL(10,2), IN total_descuentos INT(2), IN total_a_pagar DECIMAL(10,2), IN observaciones VARCHAR(100),
	IN empresa_id INT(4), IN tipo_pago_id INT(4), IN tipo_nota_id INT(4), IN tipo_nota_codigo VARCHAR(10), IN com_adjunto_id INT(11),
	IN ace_sunat INT(4), IN cod_sunat INT(4), IN des_sunat VARCHAR(50), IN enviado_sunat INT(10), IN estado_sunat INT(11),
	IN enviado_cliente INT(4), IN enviado_equipo INT(4), IN anulado INT(4), IN eliminado INT(10), IN empleado_insert INT(11),
	IN fecha_insert DATETIME
)
BEGIN
  INSERT INTO `comprobantes` (
    `cliente_id`,
    `tipo_documento_id`,
    `serie`,
    `numero`,
    `fecha_de_emision`,
    `fecha_de_baja`,
    `moneda_id`,
    `tipo_de_cambio`,    
    `fecha_de_vencimiento`,
    `operacion_gratuita`,
    `operacion_cancelada`,
    `detraccion`,
    `elemento_adicional_id`,
    `porcentaje_de_detraccion`,
    `total_detraccion`,        
    `descuento_global`,
    `total_exonerada`,
    `total_inafecta`,
    `total_gravada`,
    `total_igv`,    
    `total_gratuita`,
    `total_otros_cargos`,
    `total_descuentos`,
    `total_a_pagar`,
    `observaciones`,    
    `empresa_id`,
    `tipo_pago_id`,
    `tipo_nota_id`,
    `tipo_nota_codigo`,
    `com_adjunto_id`,    
    `ace_sunat`,
    `cod_sunat`,
    `des_sunat`,
    `enviado_sunat`,
    `estado_sunat`,        
    `enviado_cliente`,
    `enviado_equipo`,
    `anulado`,
    `eliminado`,
    `empleado_insert`,    
    `fecha_insert`
  ) 
  VALUES
    (
      cliente_id,
      tipo_documento_id,
      serie,
      numero,
      fecha_de_emision,
      fecha_de_baja,
      moneda_id,
      tipo_de_cambio,     
      fecha_de_vencimiento,
      operacion_gratuita,
      operacion_cancelada,
      detraccion,
      elemento_adicional_id,
      porcentaje_de_detraccion,
      total_detraccion,      
      descuento_global,
      total_exonerada,
      total_inafecta,
      total_gravada,
      total_igv,            
      total_gratuita,
      total_otros_cargos,
      total_descuentos,
      total_a_pagar,
      observaciones,      
      empresa_id,
      tipo_pago_id,
      tipo_nota_id,
      tipo_nota_codigo,
      com_adjunto_id,      
      ace_sunat,
      cod_sunat,
      des_sunat,
      enviado_sunat,
      estado_sunat,      
      enviado_cliente,
      enviado_equipo,
      anulado,
      eliminado,
      empleado_insert,
      fecha_insert
    ) ;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
