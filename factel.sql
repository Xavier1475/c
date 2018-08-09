-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2017 a las 23:01:04
-- Versión del servidor: 5.7.14
-- Versión de PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `factel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campoadicional`
--

CREATE TABLE `campoadicional` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `campoAdic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipoIdentificacion` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `identificacion` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` longtext COLLATE utf8_unicode_ci,
  `celular` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correoElectronico` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`, `tipoIdentificacion`, `identificacion`, `direccion`, `celular`, `correoElectronico`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 'Seidor S.A.', '04', '1792573351001', 'Catalina Aldaz N23-534 y Portugal', '023324824', '', '2016-08-16 00:25:35', '2016-08-16 00:51:58', 1, 1, NULL),
(2, 'LINKS S.A', '04', '0992236655001', 'Av Juan Tanca Marengo KM 2', '043726466', '', '2016-09-03 01:19:02', '2016-09-03 01:19:02', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleadicional`
--

CREATE TABLE `detalleadicional` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facturaHasProducto_id` int(11) DEFAULT NULL,
  `notaCreditoHasProducto_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emisor`
--

CREATE TABLE `emisor` (
  `id` int(11) NOT NULL,
  `ruc` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `ambiente` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `tipoEmision` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `razonSocial` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `nombreComercial` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direccionMatriz` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `contribuyenteEspecial` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `obligadoContabilidad` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `dirLogo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `dirFirma` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `dirDocAutorizados` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `passFirma` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `servidorCorreo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `correoRemitente` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passCorreo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `puerto` int(11) NOT NULL,
  `SSLHabilitado` tinyint(1) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `emisor`
--

INSERT INTO `emisor` (`id`, `ruc`, `ambiente`, `tipoEmision`, `razonSocial`, `nombreComercial`, `direccionMatriz`, `contribuyenteEspecial`, `obligadoContabilidad`, `dirLogo`, `dirFirma`, `dirDocAutorizados`, `passFirma`, `servidorCorreo`, `correoRemitente`, `passCorreo`, `puerto`, `SSLHabilitado`, `activo`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, '1755154679001', '1', '1', 'MARTINEZ HIDALGO YOELVYS', 'SOFTPRO', 'PICHINCHA / QUITO / DE LAS TORONJAS E11-116 Y DE LAS HORTENSIAS', NULL, 'NO', 'D:\\\\Directorio\\\\logo.jpg', 'D:\\\\Directorio\\\\yoelvys_martinez_hidalgo.p12', 'D:\\\\Directorio\\\\Autorizados', 'Yo3lvys1987', 'mx1.hostinger.es', 'ventas@facilfact.com', '12valeria', 2525, 0, 1, '2016-08-16 00:13:22', '2016-08-16 01:25:07', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `establecimiento`
--

CREATE TABLE `establecimiento` (
  `id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `establecimiento`
--

INSERT INTO `establecimiento` (`id`, `emisor_id`, `nombre`, `codigo`, `direccion`, `activo`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 1, 'Matriz', '001', 'PICHINCHA / QUITO / DE LAS TORONJAS E11-116 Y DE LAS HORTENSIAS', 1, '2016-08-16 00:13:51', '2016-08-16 00:13:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `claveAcceso` varchar(49) COLLATE utf8_unicode_ci NOT NULL,
  `numeroAutorizacion` varchar(49) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaAutorizacion` datetime DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ambiente` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `tipoEmision` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `secuencial` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmision` date NOT NULL,
  `nombreArchivo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalSinImpuestos` decimal(10,2) NOT NULL,
  `subtotal12` decimal(10,2) NOT NULL,
  `subtotal0` decimal(10,2) NOT NULL,
  `subtotalNoIVA` decimal(10,2) NOT NULL,
  `subtotalExentoIVA` decimal(10,2) NOT NULL,
  `valorICE` decimal(10,2) NOT NULL,
  `valorIRBPNR` decimal(10,2) NOT NULL,
  `iva12` decimal(10,2) NOT NULL,
  `totalDescuento` decimal(10,2) NOT NULL,
  `propina` decimal(10,2) NOT NULL,
  `valorTotal` decimal(10,2) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `ptoEmision_id` int(11) NOT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL,
  `formaPago` varchar(4) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturahasproducto`
--

CREATE TABLE `facturahasproducto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `valorTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuesto`
--

CREATE TABLE `impuesto` (
  `id` int(11) NOT NULL,
  `codigo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `codigoPorcentaje` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `tarifa` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `baseImponible` decimal(10,2) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `facturaHasProducto_id` int(11) DEFAULT NULL,
  `notaCreditoHasProducto_id` int(11) DEFAULT NULL,
  `notaDebito_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestocomprobanteretencion`
--

CREATE TABLE `impuestocomprobanteretencion` (
  `id` int(11) NOT NULL,
  `retencion_id` int(11) NOT NULL,
  `codigo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `codigoRetencion` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `baseImponible` decimal(10,2) NOT NULL,
  `porcentajeRetener` int(11) NOT NULL,
  `valorRetenido` decimal(10,2) NOT NULL,
  `codDocSustento` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numDocSustento` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmisionDocSustento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestoice`
--

CREATE TABLE `impuestoice` (
  `id` int(11) NOT NULL,
  `codigoPorcentaje` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestoirbpnr`
--

CREATE TABLE `impuestoirbpnr` (
  `id` int(11) NOT NULL,
  `codigoPorcentaje` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestoiva`
--

CREATE TABLE `impuestoiva` (
  `id` int(11) NOT NULL,
  `codigoPorcentaje` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tarifa` decimal(10,2) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `impuestoiva`
--

INSERT INTO `impuestoiva` (`id`, `codigoPorcentaje`, `nombre`, `tarifa`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, '3', 'Iva 14%', '14.00', '2016-08-16 00:19:18', '2016-08-16 00:19:18', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id` int(11) NOT NULL,
  `identificador` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `mensaje` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `informacionAdicional` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Factura_id` int(11) DEFAULT NULL,
  `NotaCredito_id` int(11) DEFAULT NULL,
  `NotaDebito_id` int(11) DEFAULT NULL,
  `Retencion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo`
--

CREATE TABLE `motivo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `dotaDebito_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notacredito`
--

CREATE TABLE `notacredito` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `claveAcceso` varchar(49) COLLATE utf8_unicode_ci NOT NULL,
  `numeroAutorizacion` varchar(49) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaAutorizacion` datetime DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ambiente` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `tipoEmision` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `secuencial` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmision` date NOT NULL,
  `tipoDocMod` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmisionDocMod` date NOT NULL,
  `nroDocMod` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `motivo` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `nombreArchivo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalSinImpuestos` decimal(10,2) NOT NULL,
  `subtotal12` decimal(10,2) NOT NULL,
  `subtotal0` decimal(10,2) NOT NULL,
  `subtotalNoIVA` decimal(10,2) NOT NULL,
  `subtotalExentoIVA` decimal(10,2) NOT NULL,
  `valorICE` decimal(10,2) NOT NULL,
  `valorIRBPNR` decimal(10,2) NOT NULL,
  `iva12` decimal(10,2) NOT NULL,
  `totalDescuento` decimal(10,2) NOT NULL,
  `valorTotal` decimal(10,2) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `ptoEmision_id` int(11) NOT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `notacredito`
--

INSERT INTO `notacredito` (`id`, `cliente_id`, `emisor_id`, `establecimiento_id`, `claveAcceso`, `numeroAutorizacion`, `fechaAutorizacion`, `estado`, `ambiente`, `tipoEmision`, `secuencial`, `fechaEmision`, `tipoDocMod`, `fechaEmisionDocMod`, `nroDocMod`, `motivo`, `nombreArchivo`, `totalSinImpuestos`, `subtotal12`, `subtotal0`, `subtotalNoIVA`, `subtotalExentoIVA`, `valorICE`, `valorIRBPNR`, `iva12`, `totalDescuento`, `valorTotal`, `createdAt`, `updatedAt`, `ptoEmision_id`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 1, 1, 1, '1608201604175515467900120010010000000011234567811', '1608201610534017551546790019713530653', '2016-08-16 10:53:40', 'AUTORIZADO', '2', '1', '000000001', '2016-08-16', '01', '2016-08-16', '001-001-000000009', 'Cambio de descripción del servicio prestado', 'NC001-001-000000001', '1451.44', '1451.44', '0.00', '0.00', '0.00', '0.00', '0.00', '203.20', '0.00', '1654.64', '2016-08-16 17:52:48', '2016-08-16 17:53:45', 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notacreditohasproducto`
--

CREATE TABLE `notacreditohasproducto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `valorTotal` decimal(10,2) NOT NULL,
  `notaCredito_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `notacreditohasproducto`
--

INSERT INTO `notacreditohasproducto` (`id`, `producto_id`, `cantidad`, `precioUnitario`, `descuento`, `valorTotal`, `notaCredito_id`) VALUES
(1, 1, '160.50', '8.77', '0.00', '1407.59', 1),
(2, 2, '5.00', '8.77', '0.00', '43.85', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notadebito`
--

CREATE TABLE `notadebito` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `claveAcceso` varchar(49) COLLATE utf8_unicode_ci NOT NULL,
  `numeroAutorizacion` varchar(49) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaAutorizacion` datetime DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ambiente` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `tipoEmision` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `secuencial` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmision` date NOT NULL,
  `tipoDocMod` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmisionDocMod` date NOT NULL,
  `nroDocMod` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nombreArchivo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalSinImpuestos` decimal(10,2) NOT NULL,
  `subtotal12` decimal(10,2) NOT NULL,
  `subtotal0` decimal(10,2) NOT NULL,
  `subtotalNoIVA` decimal(10,2) NOT NULL,
  `subtotalExentoIVA` decimal(10,2) NOT NULL,
  `valorICE` decimal(10,2) NOT NULL,
  `iva12` decimal(10,2) NOT NULL,
  `valorTotal` decimal(10,2) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `ptoEmision_id` int(11) NOT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `impuesto_iva_id` int(11) NOT NULL,
  `impuesto_ice_id` int(11) DEFAULT NULL,
  `impuesto_irbpnr_id` int(11) DEFAULT NULL,
  `codigoPrincipal` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `codigoAuxiliar` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `emisor_id`, `impuesto_iva_id`, `impuesto_ice_id`, `impuesto_irbpnr_id`, `codigoPrincipal`, `codigoAuxiliar`, `nombre`, `precioUnitario`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 1, 1, NULL, NULL, 'DESSAN', NULL, 'Desarrollo San Martin (Horas)', '8.77', '2016-08-16 00:31:33', '2016-08-16 00:37:35', 1, 1, NULL),
(2, 1, 1, NULL, NULL, 'SOPORAVIS', NULL, 'Soporte Avis(Horas)', '8.77', '2016-08-16 00:37:24', '2016-08-16 17:57:26', 1, 1, NULL),
(3, 1, 1, NULL, NULL, 'SDESSAN', NULL, 'Servicios de desarrollo San Martin (Horas)', '8.77', '2016-08-16 17:58:20', '2016-08-16 17:58:20', 1, 1, NULL),
(4, 1, 1, NULL, NULL, 'SSOPORAVIS', NULL, 'Servicios de soporte Avis(Horas)', '8.77', '2016-08-16 17:58:57', '2016-08-16 17:58:57', 1, 1, NULL),
(5, 1, 1, NULL, NULL, 'ACTFPAGO', NULL, 'Actualizacion Forma Pago', '100.00', '2016-09-03 01:18:41', '2016-09-03 01:18:41', 1, 1, NULL),
(6, 1, 1, NULL, NULL, 'SISTFE', NULL, 'Sistema Facturacion Electronica', '3500.00', '2017-01-01 16:05:04', '2017-01-01 16:05:04', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ptoemision`
--

CREATE TABLE `ptoemision` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `secuencialFactura` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `secuencialNotaCredito` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `secuencialNotaDebito` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `secuencialGuiaRemision` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `secuencialRetencion` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ptoemision`
--

INSERT INTO `ptoemision` (`id`, `user_id`, `establecimiento_id`, `nombre`, `codigo`, `secuencialFactura`, `secuencialNotaCredito`, `secuencialNotaDebito`, `secuencialGuiaRemision`, `secuencialRetencion`, `activo`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 1, 1, 'Punto001', '001', '20', '2', '1', '1', '1', 1, '2016-08-16 00:26:45', '2017-01-27 16:01:44', 2, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion`
--

CREATE TABLE `retencion` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `establecimiento_id` int(11) NOT NULL,
  `claveAcceso` varchar(49) COLLATE utf8_unicode_ci NOT NULL,
  `numeroAutorizacion` varchar(49) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaAutorizacion` datetime DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ambiente` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `tipoEmision` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `secuencial` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `fechaEmision` date NOT NULL,
  `periodoFiscal` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `nombreArchivo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `ptoEmision_id` int(11) NOT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `nombre`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 'ROLE_EMISOR', '2016-08-16 00:14:56', '2016-08-16 00:14:56', NULL, NULL, NULL),
(2, 'ROLE_ADMIN', '2016-08-16 00:15:12', '2016-08-16 00:15:12', NULL, NULL, NULL),
(3, 'ROLE_EMISOR_ADMIN', '2016-08-16 00:29:20', '2016-08-16 00:29:20', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  `createdBy_id` int(11) DEFAULT NULL,
  `updatedBy_id` int(11) DEFAULT NULL,
  `deletedBy_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `emisor_id`, `rol_id`, `username`, `password`, `email`, `nombre`, `apellidos`, `salt`, `is_active`, `createdAt`, `updatedAt`, `createdBy_id`, `updatedBy_id`, `deletedBy_id`) VALUES
(1, 1, 3, 'yoelvysmh', 'PpTL+zjcajkQntwCQponP5lwtjVIEojoriB4JjWUUbxbHGRgAsWZ3qyIFtqp43a15FTaR2JwSd1OF58Tm0sgjg==', 'yoelvysmh@gmail.com', 'Yoelvys', 'Martinez Hidalgo', 'ad5511deaed4e375152d9502c67f6d7f', 1, '2016-08-16 00:16:44', '2016-08-16 00:29:41', NULL, NULL, NULL),
(2, 1, 2, 'admin', 'P7wMcjkAWrWSVAdwNEJ5/pE1z6FH8pQ+GM70/JYXpwzOIQ9FfGPm5iWI1n5qkaoP4oRXfNjdBKSghhA4kVjC5A==', 'a@admin.com', 'Administrador', 'General', 'f68e891658e71262a2a64896b0a1cc42', 1, '2016-08-16 00:17:20', '2016-08-16 00:17:20', NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `campoadicional`
--
ALTER TABLE `campoadicional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D7FF66CF3D9AAD1F` (`campoAdic_id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3BA1A2B984291D2B` (`identificacion`),
  ADD KEY `IDX_3BA1A2B93174800F` (`createdBy_id`),
  ADD KEY `IDX_3BA1A2B965FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_3BA1A2B963D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `detalleadicional`
--
ALTER TABLE `detalleadicional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_87797196CE7FF8C7` (`facturaHasProducto_id`),
  ADD KEY `IDX_8779719698D062E6` (`notaCreditoHasProducto_id`);

--
-- Indices de la tabla `emisor`
--
ALTER TABLE `emisor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C30280783174800F` (`createdBy_id`),
  ADD KEY `IDX_C302807865FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_C302807863D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `establecimiento`
--
ALTER TABLE `establecimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1C61EFE06BDF87DF` (`emisor_id`),
  ADD KEY `IDX_1C61EFE03174800F` (`createdBy_id`),
  ADD KEY `IDX_1C61EFE065FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_1C61EFE063D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_36569995DE734E51` (`cliente_id`),
  ADD KEY `IDX_365699956BDF87DF` (`emisor_id`),
  ADD KEY `IDX_3656999571B61351` (`establecimiento_id`),
  ADD KEY `IDX_36569995E01B1B5E` (`ptoEmision_id`),
  ADD KEY `IDX_365699953174800F` (`createdBy_id`),
  ADD KEY `IDX_3656999565FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_3656999563D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `facturahasproducto`
--
ALTER TABLE `facturahasproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1CFCFE017645698E` (`producto_id`),
  ADD KEY `IDX_1CFCFE01F04F795F` (`factura_id`);

--
-- Indices de la tabla `impuesto`
--
ALTER TABLE `impuesto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4F9058F7CE7FF8C7` (`facturaHasProducto_id`),
  ADD KEY `IDX_4F9058F798D062E6` (`notaCreditoHasProducto_id`),
  ADD KEY `IDX_4F9058F7FC96FD8` (`notaDebito_id`);

--
-- Indices de la tabla `impuestocomprobanteretencion`
--
ALTER TABLE `impuestocomprobanteretencion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_86AD65C722EC49A` (`retencion_id`);

--
-- Indices de la tabla `impuestoice`
--
ALTER TABLE `impuestoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6F0EE7863174800F` (`createdBy_id`),
  ADD KEY `IDX_6F0EE78665FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_6F0EE78663D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `impuestoirbpnr`
--
ALTER TABLE `impuestoirbpnr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AB0A0C0B3174800F` (`createdBy_id`),
  ADD KEY `IDX_AB0A0C0B65FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_AB0A0C0B63D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `impuestoiva`
--
ALTER TABLE `impuestoiva`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5FD6C58B3174800F` (`createdBy_id`),
  ADD KEY `IDX_5FD6C58B65FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_5FD6C58B63D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_54DE249DBF127A8F` (`Factura_id`),
  ADD KEY `IDX_54DE249D51CA559D` (`NotaCredito_id`),
  ADD KEY `IDX_54DE249DBCB7242D` (`NotaDebito_id`),
  ADD KEY `IDX_54DE249D6A1F1F62` (`Retencion_id`);

--
-- Indices de la tabla `motivo`
--
ALTER TABLE `motivo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1E553D50359609FE` (`dotaDebito_id`);

--
-- Indices de la tabla `notacredito`
--
ALTER TABLE `notacredito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_799C85D7DE734E51` (`cliente_id`),
  ADD KEY `IDX_799C85D76BDF87DF` (`emisor_id`),
  ADD KEY `IDX_799C85D771B61351` (`establecimiento_id`),
  ADD KEY `IDX_799C85D7E01B1B5E` (`ptoEmision_id`),
  ADD KEY `IDX_799C85D73174800F` (`createdBy_id`),
  ADD KEY `IDX_799C85D765FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_799C85D763D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `notacreditohasproducto`
--
ALTER TABLE `notacreditohasproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_721E66DE7645698E` (`producto_id`),
  ADD KEY `IDX_721E66DE9CAE2D45` (`notaCredito_id`);

--
-- Indices de la tabla `notadebito`
--
ALTER TABLE `notadebito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_60947670DE734E51` (`cliente_id`),
  ADD KEY `IDX_609476706BDF87DF` (`emisor_id`),
  ADD KEY `IDX_6094767071B61351` (`establecimiento_id`),
  ADD KEY `IDX_60947670E01B1B5E` (`ptoEmision_id`),
  ADD KEY `IDX_609476703174800F` (`createdBy_id`),
  ADD KEY `IDX_6094767065FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_6094767063D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5ECD64436BDF87DF` (`emisor_id`),
  ADD KEY `IDX_5ECD6443D0B25EB1` (`impuesto_iva_id`),
  ADD KEY `IDX_5ECD6443F7D0D114` (`impuesto_ice_id`),
  ADD KEY `IDX_5ECD64432780846A` (`impuesto_irbpnr_id`),
  ADD KEY `IDX_5ECD64433174800F` (`createdBy_id`),
  ADD KEY `IDX_5ECD644365FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_5ECD644363D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `ptoemision`
--
ALTER TABLE `ptoemision`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_BE82597CA76ED395` (`user_id`),
  ADD KEY `IDX_BE82597C71B61351` (`establecimiento_id`),
  ADD KEY `IDX_BE82597C3174800F` (`createdBy_id`),
  ADD KEY `IDX_BE82597C65FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_BE82597C63D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `retencion`
--
ALTER TABLE `retencion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6240E309DE734E51` (`cliente_id`),
  ADD KEY `IDX_6240E3096BDF87DF` (`emisor_id`),
  ADD KEY `IDX_6240E30971B61351` (`establecimiento_id`),
  ADD KEY `IDX_6240E309E01B1B5E` (`ptoEmision_id`),
  ADD KEY `IDX_6240E3093174800F` (`createdBy_id`),
  ADD KEY `IDX_6240E30965FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_6240E30963D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F75B25543174800F` (`createdBy_id`),
  ADD KEY `IDX_F75B255465FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_F75B255463D8C20E` (`deletedBy_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2DA179776BDF87DF` (`emisor_id`),
  ADD KEY `IDX_2DA179774BAB96C` (`rol_id`),
  ADD KEY `IDX_2DA179773174800F` (`createdBy_id`),
  ADD KEY `IDX_2DA1797765FF1AEC` (`updatedBy_id`),
  ADD KEY `IDX_2DA1797763D8C20E` (`deletedBy_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `campoadicional`
--
ALTER TABLE `campoadicional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `detalleadicional`
--
ALTER TABLE `detalleadicional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `emisor`
--
ALTER TABLE `emisor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `establecimiento`
--
ALTER TABLE `establecimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `facturahasproducto`
--
ALTER TABLE `facturahasproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `impuesto`
--
ALTER TABLE `impuesto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `impuestocomprobanteretencion`
--
ALTER TABLE `impuestocomprobanteretencion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `impuestoice`
--
ALTER TABLE `impuestoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `impuestoirbpnr`
--
ALTER TABLE `impuestoirbpnr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `impuestoiva`
--
ALTER TABLE `impuestoiva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `motivo`
--
ALTER TABLE `motivo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `notacredito`
--
ALTER TABLE `notacredito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `notacreditohasproducto`
--
ALTER TABLE `notacreditohasproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `notadebito`
--
ALTER TABLE `notadebito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `ptoemision`
--
ALTER TABLE `ptoemision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `retencion`
--
ALTER TABLE `retencion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `FK_3BA1A2B93174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3BA1A2B963D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3BA1A2B965FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `detalleadicional`
--
ALTER TABLE `detalleadicional`
  ADD CONSTRAINT `FK_8779719698D062E6` FOREIGN KEY (`notaCreditoHasProducto_id`) REFERENCES `notacreditohasproducto` (`id`),
  ADD CONSTRAINT `FK_87797196CE7FF8C7` FOREIGN KEY (`facturaHasProducto_id`) REFERENCES `facturahasproducto` (`id`);

--
-- Filtros para la tabla `emisor`
--
ALTER TABLE `emisor`
  ADD CONSTRAINT `FK_C30280783174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C302807863D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C302807865FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `establecimiento`
--
ALTER TABLE `establecimiento`
  ADD CONSTRAINT `FK_1C61EFE03174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1C61EFE063D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1C61EFE065FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_1C61EFE06BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `FK_365699953174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3656999563D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_3656999565FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_365699956BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`),
  ADD CONSTRAINT `FK_3656999571B61351` FOREIGN KEY (`establecimiento_id`) REFERENCES `establecimiento` (`id`),
  ADD CONSTRAINT `FK_36569995DE734E51` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `FK_36569995E01B1B5E` FOREIGN KEY (`ptoEmision_id`) REFERENCES `ptoemision` (`id`);

--
-- Filtros para la tabla `facturahasproducto`
--
ALTER TABLE `facturahasproducto`
  ADD CONSTRAINT `FK_1CFCFE017645698E` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `FK_1CFCFE01F04F795F` FOREIGN KEY (`factura_id`) REFERENCES `factura` (`id`);

--
-- Filtros para la tabla `impuesto`
--
ALTER TABLE `impuesto`
  ADD CONSTRAINT `FK_4F9058F798D062E6` FOREIGN KEY (`notaCreditoHasProducto_id`) REFERENCES `notacreditohasproducto` (`id`),
  ADD CONSTRAINT `FK_4F9058F7CE7FF8C7` FOREIGN KEY (`facturaHasProducto_id`) REFERENCES `facturahasproducto` (`id`),
  ADD CONSTRAINT `FK_4F9058F7FC96FD8` FOREIGN KEY (`notaDebito_id`) REFERENCES `notadebito` (`id`);

--
-- Filtros para la tabla `impuestocomprobanteretencion`
--
ALTER TABLE `impuestocomprobanteretencion`
  ADD CONSTRAINT `FK_86AD65C722EC49A` FOREIGN KEY (`retencion_id`) REFERENCES `retencion` (`id`);

--
-- Filtros para la tabla `impuestoice`
--
ALTER TABLE `impuestoice`
  ADD CONSTRAINT `FK_6F0EE7863174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6F0EE78663D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6F0EE78665FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `impuestoirbpnr`
--
ALTER TABLE `impuestoirbpnr`
  ADD CONSTRAINT `FK_AB0A0C0B3174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AB0A0C0B63D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AB0A0C0B65FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `impuestoiva`
--
ALTER TABLE `impuestoiva`
  ADD CONSTRAINT `FK_5FD6C58B3174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5FD6C58B63D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5FD6C58B65FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `FK_54DE249D51CA559D` FOREIGN KEY (`NotaCredito_id`) REFERENCES `notacredito` (`id`),
  ADD CONSTRAINT `FK_54DE249D6A1F1F62` FOREIGN KEY (`Retencion_id`) REFERENCES `retencion` (`id`),
  ADD CONSTRAINT `FK_54DE249DBCB7242D` FOREIGN KEY (`NotaDebito_id`) REFERENCES `notadebito` (`id`),
  ADD CONSTRAINT `FK_54DE249DBF127A8F` FOREIGN KEY (`Factura_id`) REFERENCES `factura` (`id`);

--
-- Filtros para la tabla `motivo`
--
ALTER TABLE `motivo`
  ADD CONSTRAINT `FK_1E553D50359609FE` FOREIGN KEY (`dotaDebito_id`) REFERENCES `notadebito` (`id`);

--
-- Filtros para la tabla `notacredito`
--
ALTER TABLE `notacredito`
  ADD CONSTRAINT `FK_799C85D73174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_799C85D763D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_799C85D765FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_799C85D76BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`),
  ADD CONSTRAINT `FK_799C85D771B61351` FOREIGN KEY (`establecimiento_id`) REFERENCES `establecimiento` (`id`),
  ADD CONSTRAINT `FK_799C85D7DE734E51` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `FK_799C85D7E01B1B5E` FOREIGN KEY (`ptoEmision_id`) REFERENCES `ptoemision` (`id`);

--
-- Filtros para la tabla `notacreditohasproducto`
--
ALTER TABLE `notacreditohasproducto`
  ADD CONSTRAINT `FK_721E66DE7645698E` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `FK_721E66DE9CAE2D45` FOREIGN KEY (`notaCredito_id`) REFERENCES `notacredito` (`id`);

--
-- Filtros para la tabla `notadebito`
--
ALTER TABLE `notadebito`
  ADD CONSTRAINT `FK_609476703174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6094767063D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6094767065FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_609476706BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`),
  ADD CONSTRAINT `FK_6094767071B61351` FOREIGN KEY (`establecimiento_id`) REFERENCES `establecimiento` (`id`),
  ADD CONSTRAINT `FK_60947670DE734E51` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `FK_60947670E01B1B5E` FOREIGN KEY (`ptoEmision_id`) REFERENCES `ptoemision` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `FK_5ECD64432780846A` FOREIGN KEY (`impuesto_irbpnr_id`) REFERENCES `impuestoirbpnr` (`id`),
  ADD CONSTRAINT `FK_5ECD64433174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5ECD644363D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5ECD644365FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_5ECD64436BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`),
  ADD CONSTRAINT `FK_5ECD6443D0B25EB1` FOREIGN KEY (`impuesto_iva_id`) REFERENCES `impuestoiva` (`id`),
  ADD CONSTRAINT `FK_5ECD6443F7D0D114` FOREIGN KEY (`impuesto_ice_id`) REFERENCES `impuestoice` (`id`);

--
-- Filtros para la tabla `ptoemision`
--
ALTER TABLE `ptoemision`
  ADD CONSTRAINT `FK_BE82597C3174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BE82597C63D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BE82597C65FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BE82597C71B61351` FOREIGN KEY (`establecimiento_id`) REFERENCES `establecimiento` (`id`),
  ADD CONSTRAINT `FK_BE82597CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `retencion`
--
ALTER TABLE `retencion`
  ADD CONSTRAINT `FK_6240E3093174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6240E30963D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6240E30965FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_6240E3096BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`),
  ADD CONSTRAINT `FK_6240E30971B61351` FOREIGN KEY (`establecimiento_id`) REFERENCES `establecimiento` (`id`),
  ADD CONSTRAINT `FK_6240E309DE734E51` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `FK_6240E309E01B1B5E` FOREIGN KEY (`ptoEmision_id`) REFERENCES `ptoemision` (`id`);

--
-- Filtros para la tabla `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `FK_F75B25543174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_F75B255463D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_F75B255465FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_2DA179773174800F` FOREIGN KEY (`createdBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_2DA179774BAB96C` FOREIGN KEY (`rol_id`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `FK_2DA1797763D8C20E` FOREIGN KEY (`deletedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_2DA1797765FF1AEC` FOREIGN KEY (`updatedBy_id`) REFERENCES `user` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_2DA179776BDF87DF` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
