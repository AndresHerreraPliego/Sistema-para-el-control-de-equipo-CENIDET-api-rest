-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.33 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para cenidet
CREATE DATABASE IF NOT EXISTS `cenidet` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `cenidet`;

-- Volcando estructura para tabla cenidet.actividad
CREATE TABLE IF NOT EXISTS `actividad` (
  `id_personal` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje` varchar(200) DEFAULT NULL,
  `inicia` date DEFAULT NULL,
  `expira` date DEFAULT NULL,
  `destinatario` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.departamento
CREATE TABLE IF NOT EXISTS `departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.entrada
CREATE TABLE IF NOT EXISTS `entrada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_salida` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equipo` (`id_equipo`),
  KEY `fk_salida` (`id_salida`),
  CONSTRAINT `equipo_fk` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id`),
  CONSTRAINT `salida_fk` FOREIGN KEY (`id_salida`) REFERENCES `salida` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.equipo
CREATE TABLE IF NOT EXISTS `equipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `no_serie` varchar(200) DEFAULT NULL,
  `valor` int(50) DEFAULT NULL,
  `folio` int(50) DEFAULT NULL,
  `modelo` varchar(200) NOT NULL,
  `no_inventario` varchar(200) DEFAULT NULL,
  `no_sep` int(50) DEFAULT NULL,
  `etiquetas` json DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `doc_soporte` varchar(500) DEFAULT NULL,
  `id_personal` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equipo_tipo` (`id_tipo`),
  KEY `equipo_personal` (`id_personal`),
  CONSTRAINT `equipo_ibfk_2` FOREIGN KEY (`id_tipo`) REFERENCES `tipo` (`id`),
  CONSTRAINT `equipo_personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.personal
CREATE TABLE IF NOT EXISTS `personal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido_paterno` varchar(50) DEFAULT NULL,
  `apellido_materno` varchar(50) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `matricula` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `contraseña` varchar(59) DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3671 DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.personal_departamento
CREATE TABLE IF NOT EXISTS `personal_departamento` (
  `id_personal` int(11) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  KEY `fk_departamento_perscenidetonal` (`id_departamento`),
  KEY `departamento_personal` (`id_personal`),
  CONSTRAINT `departamento_personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`),
  CONSTRAINT `personal_departamento_ibfk_2` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.salida
CREATE TABLE IF NOT EXISTS `salida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `id_personal` int(11) DEFAULT NULL,
  `estatus` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_salida_departamento` (`id_departamento`),
  KEY `salida_personal` (`id_personal`),
  CONSTRAINT `salida_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id`),
  CONSTRAINT `salida_personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1016 DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.salida_equipo
CREATE TABLE IF NOT EXISTS `salida_equipo` (
  `id_salida` int(11) DEFAULT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  KEY `FK_equipo` (`id_equipo`),
  KEY `FK_salida_equipo_salida` (`id_salida`),
  CONSTRAINT `FK_equipo` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id`),
  CONSTRAINT `FK_salida_equipo_salida` FOREIGN KEY (`id_salida`) REFERENCES `salida` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.salida_prestamo
CREATE TABLE IF NOT EXISTS `salida_prestamo` (
  `id_salida` int(11) DEFAULT NULL,
  `nombre_persona` varchar(100) NOT NULL,
  `rol` varchar(50) NOT NULL,
  KEY `FK_salida` (`id_salida`),
  CONSTRAINT `FK_salida` FOREIGN KEY (`id_salida`) REFERENCES `salida` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla cenidet.tipo
CREATE TABLE IF NOT EXISTS `tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para disparador cenidet.actividad_cuenta
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `actividad_cuenta` AFTER INSERT ON `personal` FOR EACH ROW INSERT INTO actividad VALUES(NEW.id, 'Creacion de cuenta', CONCAT('Se ha creado tu cuenta ', NEW.nombre),
CURRENT_DATE(),CURRENT_TIME())//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.actividad_entrada
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER actividad_entrada BEFORE INSERT ON entrada FOR EACH ROW 
INSERT INTO actividad VALUES((SELECT  salida.id_personal FROM salida WHERE salida.id = NEW.id_salida),
'Devolviste equipo',CONCAT('Haz devuelto el equipo ',(SELECT nombre FROM equipo WHERE id = NEW.id_equipo)),
CURRENT_DATE(),CURRENT_TIME())//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.actividad_prestamo
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER actividad_prestamo BEFORE INSERT ON salida_prestamo FOR EACH ROW 
INSERT INTO actividad VALUES((SELECT  salida.id_personal FROM salida WHERE salida.id = NEW.id_salida),
'Prestaste equipo',CONCAT('Se ha generado un nuevo codigo para prestar equipo a ',NEW.nombre_persona),
CURRENT_DATE(),CURRENT_TIME())//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.actividad_recolectado
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER actividad_recolectado BEFORE INSERT ON salida_equipo FOR EACH ROW 
INSERT INTO actividad VALUES((SELECT  salida.id_personal FROM salida WHERE salida.id = NEW.id_salida),
'Recoleccion de equipo',
CONCAT('Se uso el codigo ',NEW.id_salida,' para recolectar el equipo ',(SELECT nombre FROM equipo WHERE id = NEW.id_equipo)),
CURRENT_DATE(),CURRENT_TIME())//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.actividad_salida
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER actividad_salida BEFORE INSERT ON salida FOR EACH ROW 
INSERT INTO actividad VALUES(NEW.id_personal,'Solicitaste equipo','Se ha generado un nuevo codigo para solicitar salida de equipo',CURRENT_DATE(),CURRENT_TIME())//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.entrada_equipo
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER entrada_equipo AFTER INSERT ON entrada
FOR EACH ROW 
DELETE FROM salida_equipo WHERE 
salida_equipo.id_salida = NEW.id_salida AND salida_equipo.id_equipo = NEW.id_equipo//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.equipo_devuelto
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER equipo_devuelto AFTER INSERT ON entrada
FOR EACH ROW 
UPDATE salida SET estatus = 'devuelto' WHERE salida.id = NEW.id_salida//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador cenidet.equipo_recolectado
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `equipo_recolectado` AFTER INSERT ON `salida_equipo` FOR EACH ROW UPDATE salida SET estatus = 'recolectado' WHERE salida.id = NEW.id_salida//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
