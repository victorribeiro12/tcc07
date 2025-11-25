-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para cadastro
CREATE DATABASE IF NOT EXISTS `cadastro` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cadastro`;

-- Copiando estrutura para tabela cadastro.aulas
CREATE TABLE IF NOT EXISTS `aulas` (
  `materia_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `codigo_materia` varchar(100) DEFAULT NULL,
  `notas` decimal(3,1) DEFAULT NULL,
  `carga_horaria` int DEFAULT NULL,
  PRIMARY KEY (`materia_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela cadastro.aulas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cadastro.cadastro_aluno
CREATE TABLE IF NOT EXISTS `cadastro_aluno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `enderco` varchar(150) DEFAULT NULL,
  `data_incio` date DEFAULT NULL,
  `data_termino` datetime DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  `telefone` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela cadastro.cadastro_aluno: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cadastro.cadastro_professor
CREATE TABLE IF NOT EXISTS `cadastro_professor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `enderco` varchar(150) DEFAULT NULL,
  `telefone` decimal(20,6) DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela cadastro.cadastro_professor: ~0 rows (aproximadamente)


-- Copiando estrutura do banco de dados para materiais
CREATE DATABASE IF NOT EXISTS `materiais` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `materiais`;

-- Copiando estrutura para tabela materiais.materias
CREATE TABLE IF NOT EXISTS `materias` (
  `materia_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `codigo_materia` varchar(100) DEFAULT NULL,
  `notas` decimal(3,1) DEFAULT NULL,
  `carga_horaria` int DEFAULT NULL,
  PRIMARY KEY (`materia_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela materiais.materias: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
