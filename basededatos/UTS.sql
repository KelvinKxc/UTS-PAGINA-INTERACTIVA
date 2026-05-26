-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: uts_matriculas
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `programa` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semestre` int NOT NULL DEFAULT '1',
  `promedio` decimal(3,1) NOT NULL DEFAULT '0.0',
  `creditos_max` int NOT NULL DEFAULT '20',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (1,'1005678','Juan Pérez','Ingeniería de Sistemas',6,4.2,20,'2026-05-23 15:18:29'),(2,'1005878','Juan Pérez','Tecnología en Sistemas',4,3.8,20,'2026-05-25 16:38:45'),(3,'1005879','María López','Tecnología en Sistemas',3,3.5,18,'2026-05-25 16:38:45'),(4,'1005880','Carlos Ramírez','Tecnología en Sistemas',5,4.0,22,'2026-05-25 16:38:45'),(5,'1006001','Julián Andrés Díaz Trujillo','Tecnología en Desarrollo de Software',4,3.9,18,'2026-05-26 19:34:56'),(6,'1006002','María Fernanda Gómez Rueda','Tecnología en Gestión Empresarial',3,4.1,18,'2026-05-26 19:34:56'),(7,'1006003','Carlos Eduardo Vargas Pinto','Tecnología en Electrónica Industrial',5,3.6,20,'2026-05-26 19:34:56'),(8,'1006004','Laura Daniela Suárez Morales','Tecnología en Desarrollo de Software',2,4.0,16,'2026-05-26 19:34:56'),(9,'1006005','Andrés Felipe Rojas Quintero','Tecnología en Sistemas de Información',6,3.4,20,'2026-05-26 19:34:56'),(10,'1006006','Valentina Castillo Herrera','Tecnología en Gestión Empresarial',1,4.2,16,'2026-05-26 19:34:56'),(11,'1006007','Diego Alejandro Peña Cáceres','Tecnología en Electrónica Industrial',3,3.8,18,'2026-05-26 19:34:56'),(12,'1006008','Natalia Ríos Sánchez','Tecnología en Sistemas de Información',4,3.9,18,'2026-05-26 19:34:56'),(13,'1006009','Sebastián Molina Ortega','Tecnología en Desarrollo de Software',5,3.6,20,'2026-05-26 19:34:56'),(14,'1006010','Camila Andrea Torres Blanco','Tecnología en Gestión Empresarial',2,4.1,16,'2026-05-26 19:34:56');
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `materia_id` int NOT NULL,
  `dia` enum('LUN','MAR','MIE','JUE','VIE','SAB') COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materia_id` (`materia_id`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
INSERT INTO `horarios` VALUES (1,1,'LUN','06:00:00','08:00:00'),(2,1,'MIE','06:00:00','08:00:00'),(3,2,'MAR','08:00:00','10:00:00'),(4,2,'JUE','08:00:00','10:00:00'),(5,3,'LUN','14:00:00','16:00:00'),(6,3,'MIE','14:00:00','16:00:00'),(7,4,'VIE','16:00:00','18:00:00'),(8,5,'MAR','06:00:00','08:00:00'),(9,5,'JUE','06:00:00','08:00:00'),(10,6,'LUN','08:00:00','10:00:00'),(11,6,'MIE','10:00:00','12:00:00'),(12,7,'LUN','07:00:00','09:00:00'),(13,7,'MIE','07:00:00','09:00:00'),(14,8,'MAR','09:00:00','11:00:00'),(15,8,'JUE','09:00:00','11:00:00'),(16,9,'LUN','11:00:00','13:00:00'),(17,9,'MIE','11:00:00','13:00:00'),(18,10,'MAR','14:00:00','16:00:00'),(19,10,'JUE','14:00:00','16:00:00'),(20,11,'LUN','14:00:00','16:00:00'),(21,11,'MIE','14:00:00','16:00:00'),(22,12,'MAR','16:00:00','18:00:00'),(23,12,'JUE','16:00:00','18:00:00'),(24,13,'VIE','07:00:00','11:00:00'),(25,14,'LUN','16:00:00','18:00:00'),(26,14,'MIE','16:00:00','18:00:00'),(27,15,'MAR','07:00:00','09:00:00'),(28,15,'JUE','07:00:00','09:00:00'),(29,16,'LUN','09:00:00','11:00:00'),(30,16,'MIE','09:00:00','11:00:00'),(31,17,'MAR','11:00:00','13:00:00'),(32,17,'JUE','11:00:00','13:00:00'),(33,18,'VIE','09:00:00','11:00:00'),(34,19,'VIE','11:00:00','13:00:00'),(35,20,'MAR','07:00:00','09:00:00'),(36,20,'VIE','07:00:00','09:00:00'),(37,21,'LUN','07:00:00','09:00:00'),(38,21,'MIE','07:00:00','09:00:00'),(39,22,'MAR','14:00:00','18:00:00'),(40,22,'JUE','14:00:00','18:00:00'),(41,23,'SAB','07:00:00','10:00:00'),(42,24,'LUN','09:00:00','11:00:00'),(43,24,'JUE','09:00:00','11:00:00'),(44,25,'MAR','09:00:00','11:00:00'),(45,25,'VIE','09:00:00','11:00:00'),(46,26,'LUN','11:00:00','13:00:00'),(47,26,'MIE','11:00:00','13:00:00'),(48,27,'MAR','16:00:00','18:00:00'),(49,27,'JUE','16:00:00','18:00:00'),(50,28,'MAR','14:00:00','16:00:00'),(51,28,'JUE','14:00:00','16:00:00'),(52,29,'LUN','14:00:00','16:00:00'),(53,29,'MIE','14:00:00','16:00:00'),(54,29,'VIE','14:00:00','16:00:00'),(55,30,'MAR','16:00:00','18:00:00'),(56,30,'JUE','16:00:00','18:00:00'),(57,31,'VIE','16:00:00','18:00:00'),(58,32,'SAB','07:00:00','09:00:00'),(59,33,'LUN','16:00:00','18:00:00'),(60,33,'MIE','16:00:00','18:00:00'),(61,34,'MAR','16:00:00','18:00:00'),(62,34,'JUE','16:00:00','18:00:00');
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscripciones`
--

DROP TABLE IF EXISTS `inscripciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estudiante_id` int NOT NULL,
  `materia_id` int NOT NULL,
  `fecha_inscripcion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('inscrita','cancelada') COLLATE utf8mb4_unicode_ci DEFAULT 'inscrita',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unica_inscripcion` (`estudiante_id`,`materia_id`),
  KEY `materia_id` (`materia_id`),
  CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripciones`
--

LOCK TABLES `inscripciones` WRITE;
/*!40000 ALTER TABLE `inscripciones` DISABLE KEYS */;
INSERT INTO `inscripciones` VALUES (1,1,3,'2026-05-23 15:18:29','inscrita'),(2,1,4,'2026-05-23 15:18:29','inscrita'),(3,1,6,'2026-05-25 16:50:08','inscrita'),(4,1,1,'2026-05-25 16:50:19','inscrita'),(5,1,5,'2026-05-25 16:50:20','inscrita');
/*!40000 ALTER TABLE `inscripciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creditos` int NOT NULL,
  `semestre_plan` int NOT NULL,
  `cupos_total` int NOT NULL DEFAULT '30',
  `cupos_usados` int NOT NULL DEFAULT '0',
  `docente` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Por asignar',
  `salon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Por asignar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cupos_restantes` int NOT NULL DEFAULT '30',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` VALUES (1,'IS-601','Redes de Computadores',3,6,30,0,'Ing. Ricardo Mora','Sala Sistemas - PM','2026-05-23 15:18:29',30),(2,'IS-602','Sistemas Operativos',3,6,30,30,'Ing. Carlos López','Sala B - 204','2026-05-23 15:18:29',0),(3,'IS-603','Ingeniería de Software II',4,6,25,24,'Dr. Roberto Sánchez','Aula A - 302','2026-05-23 15:18:29',1),(4,'IS-604','Ética Profesional',2,6,20,5,'Dr. María Valdivieso','Virtual - Teams','2026-05-23 15:18:29',15),(5,'IS-605','Algoritmos y Estructuras',4,6,28,28,'Dra. Elena Gómez','Lab. C - 104','2026-05-23 15:18:29',0),(6,'IS-606','Base de Datos II',4,5,30,29,'Ing. Laura Ruiz','Lab. B - 201','2026-05-23 15:18:29',1),(7,'TDS-101','Fundamentos de Programación',3,1,30,5,'Luz Karime Castellanos Joya','Lab. B-101','2026-05-26 19:46:02',25),(8,'TDS-201','Programación Orientada a Objetos',3,2,28,8,'Luz Karime Castellanos Joya','Lab. B-102','2026-05-26 19:46:02',20),(9,'TDS-301','Diseño de Web Services',3,3,30,12,'Luz Karime Castellanos Joya','Lab. B-201','2026-05-26 19:46:02',18),(10,'TDS-302','Desarrollo de Apps Móviles',3,3,25,6,'Jorge Iván Ramírez López','Lab. B-203','2026-05-26 19:46:02',19),(11,'TDS-401','Arquitectura de Software',3,4,30,10,'Luz Karime Castellanos Joya','Aula A-105','2026-05-26 19:46:02',20),(12,'TDS-402','Pruebas y Calidad de Software',3,4,28,4,'Andrés Camilo Peña Silva','Aula B-301','2026-05-26 19:46:02',24),(13,'TDS-501','Proyecto de Grado I',4,5,20,3,'Luz Karime Castellanos Joya','Sala Reuniones','2026-05-26 19:46:02',17),(14,'TDS-502','Inteligencia Artificial Aplicada',3,5,25,7,'Sandra Patricia Leal Mora','Lab. B-204','2026-05-26 19:46:02',18),(15,'TSI-201','Bases de Datos II',3,2,30,10,'Carlos Hernán Morales Gil','Lab. C-101','2026-05-26 19:46:02',20),(16,'TSI-301','Redes y Telecomunicaciones',3,3,28,8,'Pedro Luis Vega Cifuentes','Aula C-203','2026-05-26 19:46:02',20),(17,'TSI-302','Seguridad Informática',3,3,25,11,'Hernando Fonseca Ruiz','Aula C-302','2026-05-26 19:46:02',14),(18,'TSI-401','Infraestructura TI en la Nube',3,4,25,6,'Pedro Luis Vega Cifuentes','Lab. C-401','2026-05-26 19:46:02',19),(19,'TSI-501','Gobierno de TI y COBIT',3,5,20,9,'Marisol Acevedo Quintero','Aula C-501','2026-05-26 19:46:02',11),(20,'TEI-201','Circuitos Eléctricos II',3,2,28,12,'Rafael Gutiérrez Paz','Lab. D-101','2026-05-26 19:46:02',16),(21,'TEI-301','Sistemas Embebidos',3,3,25,16,'Rafael Gutiérrez Paz','Lab. D-201','2026-05-26 19:46:02',9),(22,'TEI-401','Automatización Industrial',4,4,22,15,'Luis Cárdenas Rueda','Lab. D-301','2026-05-26 19:46:02',7),(23,'TEI-501','IoT e Industria 4.0',3,5,20,7,'Luis Cárdenas Rueda','Lab. D-401','2026-05-26 19:46:02',13),(24,'TGE-101','Fundamentos de Administración',3,1,35,10,'Gloria Niño Vargas','Aula E-101','2026-05-26 19:46:02',25),(25,'TGE-201','Contabilidad Financiera',3,2,32,14,'Gloria Niño Vargas','Aula E-201','2026-05-26 19:46:02',18),(26,'TGE-301','Gestión de Proyectos',3,3,30,18,'Beatriz Suárez Cano','Aula E-301','2026-05-26 19:46:02',12),(27,'TGE-401','Mercadeo Digital',3,4,28,8,'Beatriz Suárez Cano','Aula E-401','2026-05-26 19:46:02',20),(28,'GEN-101','Comunicación Oral y Escrita',2,1,40,15,'Martha Lucía Prada Soto','Aula A-101','2026-05-26 19:46:02',25),(29,'GEN-102','Matemáticas Fundamentales',4,1,38,20,'Jaime Rodríguez Cruz','Aula A-201','2026-05-26 19:46:02',18),(30,'GEN-201','Estadística Básica',3,2,35,17,'Jaime Rodríguez Cruz','Aula A-202','2026-05-26 19:46:02',18),(31,'GEN-202','Ética Profesional',2,2,40,12,'Claudia Rangel Vera','Aula A-301','2026-05-26 19:46:02',28),(32,'GEN-301','Constitución y Democracia',2,3,40,5,'Claudia Rangel Vera','Aula A-302','2026-05-26 19:46:02',35),(33,'GEN-401','Inglés Técnico I',3,4,30,15,'Diana Patiño Ibáñez','Aula A-401','2026-05-26 19:46:02',15),(34,'GEN-501','Inglés Técnico II',3,5,28,8,'Diana Patiño Ibáñez','Aula A-402','2026-05-26 19:46:02',20);
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-26 14:46:25
