-- ============================================================
-- uts_matriculas — Ingeniería de Sistemas · UTS
-- ============================================================
DROP DATABASE IF EXISTS `uts_matriculas`;
CREATE DATABASE `uts_matriculas` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `uts_matriculas`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- ──────────────────────────────────────────────
--  ESTUDIANTES
-- ──────────────────────────────────────────────
DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE `estudiantes` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `codigo`       VARCHAR(20)  NOT NULL,
  `nombre`       VARCHAR(100) NOT NULL,
  `programa`     VARCHAR(100) NOT NULL,
  `semestre`     INT NOT NULL DEFAULT 1,
  `promedio`     DECIMAL(3,1) NOT NULL DEFAULT 0.0,
  `creditos_max` INT NOT NULL DEFAULT 20,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `estudiantes` VALUES
  (1,'1005678','Juan Pérez',       'Ingeniería de Sistemas',6,4.2,20,NOW()),
  (2,'1005879','María López',      'Ingeniería de Sistemas',3,3.5,20,NOW()),
  (3,'1005880','Carlos Ramírez',   'Ingeniería de Sistemas',4,4.0,20,NOW()),
  (4,'1005881','Laura Gómez',      'Ingeniería de Sistemas',2,3.8,20,NOW()),
  (5,'1005882','Andrés Torres',    'Ingeniería de Sistemas',1,0.0,20,NOW());

-- ──────────────────────────────────────────────
--  MATERIAS  (6 semestres × 20 créditos)
--  Semestre 1: 20 cr  |  Semestre 2: 20 cr
--  Semestre 3: 20 cr  |  Semestre 4: 20 cr
--  Semestre 5: 20 cr  |  Semestre 6: 20 cr
-- ──────────────────────────────────────────────
DROP TABLE IF EXISTS `materias`;
CREATE TABLE `materias` (
  `id`               INT NOT NULL AUTO_INCREMENT,
  `codigo`           VARCHAR(20)  NOT NULL,
  `nombre`           VARCHAR(100) NOT NULL,
  `creditos`         INT NOT NULL,
  `semestre_plan`    INT NOT NULL,
  `cupos_total`      INT NOT NULL DEFAULT 30,
  `cupos_restantes`  INT NOT NULL DEFAULT 30,
  `docente`          VARCHAR(100) NOT NULL DEFAULT 'Por asignar',
  `salon`            VARCHAR(30)  NOT NULL DEFAULT 'Por asignar',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materias`
  (`id`,`codigo`,`nombre`,`creditos`,`semestre_plan`,`cupos_total`,`cupos_restantes`,`docente`,`salon`) VALUES

-- ── SEMESTRE 1 · 20 créditos ──────────────────
(1, 'IS-101','Matemáticas I',                4,1,35,30,'Dr. Jaime Rodríguez Cruz',   'Aula A-101'),
(2, 'IS-102','Introducción a la Programación',4,1,30,28,'Ing. Luz Karime Castellanos','Lab. B-101'),
(3, 'IS-103','Lógica Matemática',             3,1,32,30,'Dr. Jaime Rodríguez Cruz',   'Aula A-102'),
(4, 'IS-104','Fundamentos de Hardware',       3,1,28,25,'Ing. Pedro Vega Cifuentes',  'Lab. C-101'),
(5, 'IS-105','Comunicación Oral y Escrita',   3,1,40,35,'Dra. Martha Prada Soto',     'Aula A-103'),
(6, 'IS-106','Ética y Ciudadanía',            3,1,40,38,'Dra. Claudia Rangel Vera',   'Aula A-104'),

-- ── SEMESTRE 2 · 20 créditos ──────────────────
(7, 'IS-201','Matemáticas II',                4,2,35,30,'Dr. Jaime Rodríguez Cruz',   'Aula A-201'),
(8, 'IS-202','Programación Orientada a Obj.', 4,2,30,27,'Ing. Luz Karime Castellanos','Lab. B-102'),
(9, 'IS-203','Estructura de Datos',           4,2,28,25,'Ing. Luz Karime Castellanos','Lab. B-103'),
(10,'IS-204','Arquitectura de Computadores',  4,2,28,26,'Ing. Pedro Vega Cifuentes',  'Lab. C-102'),
(11,'IS-205','Inglés Técnico I',              4,2,32,30,'Dra. Diana Patiño Ibáñez',   'Aula A-202'),

-- ── SEMESTRE 3 · 20 créditos ──────────────────
(12,'IS-301','Matemáticas III',               4,3,30,28,'Dr. Jaime Rodríguez Cruz',   'Aula A-301'),
(13,'IS-302','Bases de Datos I',              4,3,30,25,'Ing. Laura Ruiz Castillo',   'Lab. B-201'),
(14,'IS-303','Redes de Computadores I',       4,3,28,25,'Ing. Ricardo Mora Peña',     'Lab. C-201'),
(15,'IS-304','Sistemas Operativos',           4,3,28,24,'Ing. Carlos López Díaz',     'Lab. C-202'),
(16,'IS-305','Inglés Técnico II',             4,3,32,30,'Dra. Diana Patiño Ibáñez',   'Aula A-302'),

-- ── SEMESTRE 4 · 20 créditos ──────────────────
(17,'IS-401','Cálculo Numérico',              3,4,28,25,'Dr. Jaime Rodríguez Cruz',   'Aula A-401'),
(18,'IS-402','Bases de Datos II',             4,4,30,27,'Ing. Laura Ruiz Castillo',   'Lab. B-202'),
(19,'IS-403','Redes de Computadores II',      4,4,28,24,'Ing. Ricardo Mora Peña',     'Lab. C-301'),
(20,'IS-404','Ingeniería de Software I',      4,4,25,22,'Dr. Roberto Sánchez Gil',    'Aula A-402'),
(21,'IS-405','Estadística y Probabilidad',    5,4,30,28,'Dr. Jaime Rodríguez Cruz',   'Aula A-403'),

-- ── SEMESTRE 5 · 20 créditos ──────────────────
(22,'IS-501','Ingeniería de Software II',     4,5,25,20,'Dr. Roberto Sánchez Gil',    'Aula A-501'),
(23,'IS-502','Seguridad Informática',         4,5,25,22,'Ing. Hernando Fonseca Ruiz', 'Lab. C-302'),
(24,'IS-503','Inteligencia Artificial I',     4,5,25,23,'Dra. Sandra Leal Mora',      'Lab. B-301'),
(25,'IS-504','Electiva Profesional I',        4,5,30,28,'Por asignar',                'Aula A-502'),
(26,'IS-505','Gestión de Proyectos TI',       4,5,28,25,'Dra. Beatriz Suárez Cano',   'Aula A-503'),

-- ── SEMESTRE 6 · 20 créditos ──────────────────
(27,'IS-601','Arquitectura Empresarial',      4,6,25,22,'Ing. Luz Karime Castellanos','Aula A-601'),
(28,'IS-602','Inteligencia Artificial II',    4,6,25,20,'Dra. Sandra Leal Mora',      'Lab. B-401'),
(29,'IS-603','Computación en la Nube',        4,6,25,23,'Ing. Pedro Vega Cifuentes',  'Lab. C-401'),
(30,'IS-604','Electiva Profesional II',       4,6,30,28,'Por asignar',                'Aula A-602'),
(31,'IS-605','Proyecto de Grado',             4,6,20,18,'Dra. Luz Karime Castellanos','Sala Proyectos');

-- ── Verificación de créditos por semestre ─────
-- S1: 4+4+3+3+3+3 = 20 ✓
-- S2: 4+4+4+4+4   = 20 ✓
-- S3: 4+4+4+4+4   = 20 ✓
-- S4: 3+4+4+4+5   = 20 ✓
-- S5: 4+4+4+4+4   = 20 ✓
-- S6: 4+4+4+4+4   = 20 ✓

-- ──────────────────────────────────────────────
--  PREREQUISITOS
--  Si el estudiante no aprobó la materia_previa
--  (nota_aprobacion >= 3.0), no puede inscribir
--  la materia_id correspondiente
-- ──────────────────────────────────────────────
DROP TABLE IF EXISTS `prerequisitos`;
CREATE TABLE `prerequisitos` (
  `id`              INT NOT NULL AUTO_INCREMENT,
  `materia_id`      INT NOT NULL COMMENT 'Materia que requiere el prereq',
  `materia_previa_id` INT NOT NULL COMMENT 'Materia que debe estar aprobada',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unico_prereq` (`materia_id`,`materia_previa_id`),
  FOREIGN KEY (`materia_id`)       REFERENCES `materias`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`materia_previa_id`) REFERENCES `materias`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prerequisitos` (`materia_id`,`materia_previa_id`) VALUES
-- Semestre 2 requiere Semestre 1
(7, 1),   -- Matemáticas II        ← Matemáticas I
(8, 2),   -- POO                   ← Intro Programación
(9, 2),   -- Estructura de Datos   ← Intro Programación
(10,4),   -- Arq. Computadores     ← Fundamentos Hardware
(11,5),   -- Inglés Técnico I      ← Com. Oral y Escrita
-- Semestre 3 requiere Semestre 2
(12,7),   -- Matemáticas III       ← Matemáticas II
(13,8),   -- Bases de Datos I      ← POO
(13,9),   -- Bases de Datos I      ← Estructura de Datos
(14,10),  -- Redes I               ← Arq. Computadores
(15,10),  -- Sistemas Operativos   ← Arq. Computadores
(16,11),  -- Inglés Técnico II     ← Inglés Técnico I
-- Semestre 4 requiere Semestre 3
(17,12),  -- Cálculo Numérico      ← Matemáticas III
(18,13),  -- Bases de Datos II     ← Bases de Datos I
(19,14),  -- Redes II              ← Redes I
(20,13),  -- Ing. Software I       ← Bases de Datos I
(21,12),  -- Estadística           ← Matemáticas III
-- Semestre 5 requiere Semestre 4
(22,20),  -- Ing. Software II      ← Ing. Software I
(23,19),  -- Seguridad             ← Redes II
(24,17),  -- IA I                  ← Cálculo Numérico
(25,18),  -- Electiva I            ← Bases de Datos II
(26,20),  -- Gestión Proyectos TI  ← Ing. Software I
-- Semestre 6 requiere Semestre 5
(27,22),  -- Arq. Empresarial      ← Ing. Software II
(28,24),  -- IA II                 ← IA I
(29,23),  -- Nube                  ← Seguridad
(30,25),  -- Electiva II           ← Electiva I
(31,22),  -- Proyecto Grado        ← Ing. Software II
(31,26);  -- Proyecto Grado        ← Gestión Proyectos TI

-- ──────────────────────────────────────────────
--  NOTAS HISTÓRICAS
--  Registra la nota final obtenida por el estudiante
--  en materias ya cursadas (semestres anteriores)
--  Aprobada si nota >= 3.0
-- ──────────────────────────────────────────────
DROP TABLE IF EXISTS `notas`;
CREATE TABLE `notas` (
  `id`            INT NOT NULL AUTO_INCREMENT,
  `estudiante_id` INT NOT NULL,
  `materia_id`    INT NOT NULL,
  `nota`          DECIMAL(3,1) NOT NULL,
  `semestre_cursado` INT NOT NULL,
  `aprobada`      TINYINT(1) GENERATED ALWAYS AS (IF(`nota` >= 3.0, 1, 0)) STORED,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unica_nota` (`estudiante_id`,`materia_id`),
  FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes`(`id`),
  FOREIGN KEY (`materia_id`)    REFERENCES `materias`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Juan Pérez (semestre 6) tiene aprobados semestres 1-5
INSERT INTO `notas` (`estudiante_id`,`materia_id`,`nota`,`semestre_cursado`) VALUES
-- S1
(1,1,4.5,1),(1,2,4.2,1),(1,3,3.8,1),(1,4,4.0,1),(1,5,3.5,1),(1,6,4.8,1),
-- S2
(1,7,4.1,2),(1,8,4.3,2),(1,9,3.9,2),(1,10,4.0,2),(1,11,3.7,2),
-- S3
(1,12,3.8,3),(1,13,4.2,3),(1,14,4.5,3),(1,15,3.6,3),(1,16,4.0,3),
-- S4
(1,17,3.5,4),(1,18,4.1,4),(1,19,4.3,4),(1,20,4.2,4),(1,21,3.9,4),
-- S5
(1,22,4.0,5),(1,23,3.8,5),(1,24,4.1,5),(1,25,3.7,5),(1,26,4.2,5);

-- María López (semestre 3) aprobó S1 y S2
INSERT INTO `notas` (`estudiante_id`,`materia_id`,`nota`,`semestre_cursado`) VALUES
(2,1,3.5,1),(2,2,3.8,1),(2,3,4.0,1),(2,4,3.2,1),(2,5,2.8,1),(2,6,4.1,1),
-- S2 (nota < 3 en POO → no puede inscribir Estructura de Datos ni BD I)
(2,7,3.6,2),(2,8,2.5,2),(2,10,3.4,2),(2,11,3.9,2);

-- Carlos Ramírez (semestre 4) aprobó S1-S3
INSERT INTO `notas` (`estudiante_id`,`materia_id`,`nota`,`semestre_cursado`) VALUES
(3,1,4.0,1),(3,2,4.2,1),(3,3,3.9,1),(3,4,3.7,1),(3,5,4.1,1),(3,6,3.5,1),
(3,7,3.8,2),(3,8,4.0,2),(3,9,3.6,2),(3,10,3.9,2),(3,11,4.2,2),
(3,12,3.7,3),(3,13,4.1,3),(3,14,3.9,3),(3,15,3.5,3),(3,16,4.0,3);

-- Laura Gómez (semestre 2) aprobó S1
INSERT INTO `notas` (`estudiante_id`,`materia_id`,`nota`,`semestre_cursado`) VALUES
(4,1,3.9,1),(4,2,4.1,1),(4,3,3.7,1),(4,4,3.5,1),(4,5,4.0,1),(4,6,4.3,1);

-- ──────────────────────────────────────────────
--  HORARIOS  (un horario real por materia)
-- ──────────────────────────────────────────────
DROP TABLE IF EXISTS `horarios`;
CREATE TABLE `horarios` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `materia_id`  INT NOT NULL,
  `dia`         ENUM('LUN','MAR','MIE','JUE','VIE','SAB') NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_fin`    TIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materia_id` (`materia_id`),
  FOREIGN KEY (`materia_id`) REFERENCES `materias`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `horarios` (`materia_id`,`dia`,`hora_inicio`,`hora_fin`) VALUES
-- S1
(1,'LUN','07:00','09:00'),(1,'MIE','07:00','09:00'),
(2,'MAR','07:00','09:00'),(2,'JUE','07:00','09:00'),
(3,'LUN','09:00','11:00'),(3,'MIE','09:00','11:00'),
(4,'MAR','09:00','11:00'),(4,'JUE','09:00','11:00'),
(5,'VIE','07:00','09:00'),(5,'SAB','07:00','09:00'),
(6,'VIE','09:00','11:00'),
-- S2
(7,'LUN','11:00','13:00'),(7,'MIE','11:00','13:00'),
(8,'MAR','11:00','13:00'),(8,'JUE','11:00','13:00'),
(9,'LUN','13:00','15:00'),(9,'MIE','13:00','15:00'),
(10,'MAR','13:00','15:00'),(10,'JUE','13:00','15:00'),
(11,'VIE','11:00','13:00'),(11,'SAB','11:00','13:00'),
-- S3
(12,'LUN','07:00','09:00'),(12,'JUE','07:00','09:00'),
(13,'MAR','07:00','09:00'),(13,'VIE','07:00','09:00'),
(14,'LUN','09:00','11:00'),(14,'MIE','09:00','11:00'),
(15,'MAR','09:00','11:00'),(15,'JUE','09:00','11:00'),
(16,'VIE','09:00','11:00'),(16,'SAB','09:00','11:00'),
-- S4
(17,'LUN','11:00','13:00'),(17,'JUE','11:00','13:00'),
(18,'MAR','11:00','13:00'),(18,'VIE','11:00','13:00'),
(19,'LUN','13:00','15:00'),(19,'MIE','13:00','15:00'),
(20,'MAR','13:00','15:00'),(20,'JUE','13:00','15:00'),
(21,'MIE','11:00','13:00'),(21,'VIE','13:00','15:00'),
-- S5
(22,'LUN','15:00','17:00'),(22,'MIE','15:00','17:00'),
(23,'MAR','15:00','17:00'),(23,'JUE','15:00','17:00'),
(24,'LUN','17:00','19:00'),(24,'MIE','17:00','19:00'),
(25,'MAR','17:00','19:00'),(25,'JUE','17:00','19:00'),
(26,'VIE','15:00','17:00'),(26,'VIE','17:00','19:00'),
-- S6
(27,'LUN','07:00','09:00'),(27,'MIE','07:00','09:00'),
(28,'MAR','07:00','09:00'),(28,'JUE','07:00','09:00'),
(29,'LUN','09:00','11:00'),(29,'MIE','09:00','11:00'),
(30,'MAR','09:00','11:00'),(30,'JUE','09:00','11:00'),
(31,'VIE','07:00','11:00');

-- ──────────────────────────────────────────────
--  INSCRIPCIONES  (semestre actual de Juan)
-- ──────────────────────────────────────────────
DROP TABLE IF EXISTS `inscripciones`;
CREATE TABLE `inscripciones` (
  `id`                INT NOT NULL AUTO_INCREMENT,
  `estudiante_id`     INT NOT NULL,
  `materia_id`        INT NOT NULL,
  `fecha_inscripcion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `estado`            ENUM('inscrita','cancelada') DEFAULT 'inscrita',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unica_inscripcion` (`estudiante_id`,`materia_id`),
  FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes`(`id`),
  FOREIGN KEY (`materia_id`)    REFERENCES `materias`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Juan Pérez ya inscribió 2 materias del semestre 6
INSERT INTO `inscripciones` (`estudiante_id`,`materia_id`,`estado`) VALUES
(1,27,'inscrita'),
(1,28,'inscrita');

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
