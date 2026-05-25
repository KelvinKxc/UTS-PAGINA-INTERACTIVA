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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (1,'1005678','Juan Pérez','Ingeniería de Sistemas',6,4.2,20,'2026-05-23 15:18:29'),(2,'1005878','Juan Pérez','Tecnología en Sistemas',4,3.8,20,'2026-05-25 16:38:45'),(3,'1005879','María López','Tecnología en Sistemas',3,3.5,18,'2026-05-25 16:38:45'),(4,'1005880','Carlos Ramírez','Tecnología en Sistemas',5,4.0,22,'2026-05-25 16:38:45');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
INSERT INTO `horarios` VALUES (1,1,'LUN','06:00:00','08:00:00'),(2,1,'MIE','06:00:00','08:00:00'),(3,2,'MAR','08:00:00','10:00:00'),(4,2,'JUE','08:00:00','10:00:00'),(5,3,'LUN','14:00:00','16:00:00'),(6,3,'MIE','14:00:00','16:00:00'),(7,4,'VIE','16:00:00','18:00:00'),(8,5,'MAR','06:00:00','08:00:00'),(9,5,'JUE','06:00:00','08:00:00'),(10,6,'LUN','08:00:00','10:00:00'),(11,6,'MIE','10:00:00','12:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripciones`
--

LOCK TABLES `inscripciones` WRITE;
/*!40000 ALTER TABLE `inscripciones` DISABLE KEYS */;
INSERT INTO `inscripciones` VALUES (1,1,3,'2026-05-23 15:18:29','inscrita'),(2,1,4,'2026-05-23 15:18:29','inscrita');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` VALUES (1,'IS-601','Redes de Computadores',3,6,30,0,'Ing. Ricardo Mora','Sala Sistemas - PM','2026-05-23 15:18:29',30),(2,'IS-602','Sistemas Operativos',3,6,30,30,'Ing. Carlos López','Sala B - 204','2026-05-23 15:18:29',30),(3,'IS-603','Ingeniería de Software II',4,6,25,24,'Dr. Roberto Sánchez','Aula A - 302','2026-05-23 15:18:29',30),(4,'IS-604','Ética Profesional',2,6,20,5,'Dr. María Valdivieso','Virtual - Teams','2026-05-23 15:18:29',30),(5,'IS-605','Algoritmos y Estructuras',4,6,28,28,'Dra. Elena Gómez','Lab. C - 104','2026-05-23 15:18:29',30),(6,'IS-606','Base de Datos II',4,5,30,29,'Ing. Laura Ruiz','Lab. B - 201','2026-05-23 15:18:29',30);
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

-- Dump completed on 2026-05-25 11:44:16
