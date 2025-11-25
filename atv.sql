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


-- Copiando estrutura do banco de dados para plataforma_mecanica
CREATE DATABASE IF NOT EXISTS `plataforma_mecanica` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `plataforma_mecanica`;

-- Copiando estrutura para tabela plataforma_mecanica.alternativas
CREATE TABLE IF NOT EXISTS `alternativas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `questao_id` int NOT NULL,
  `texto_alternativa` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correta` tinyint(1) DEFAULT '0',
  `ordem` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_questao` (`questao_id`),
  CONSTRAINT `alternativas_ibfk_1` FOREIGN KEY (`questao_id`) REFERENCES `questoes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.alternativas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso_id` int NOT NULL,
  `autor_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fixado` tinyint(1) DEFAULT '0',
  `data_publicacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `autor_id` (`autor_id`),
  KEY `idx_curso` (`curso_id`),
  KEY `idx_fixado` (`fixado`),
  KEY `idx_data_publicacao` (`data_publicacao`),
  CONSTRAINT `anuncios_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anuncios_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.anuncios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.aulas
CREATE TABLE IF NOT EXISTS `aulas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `modulo_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `tipo_conteudo` enum('video','texto','pdf','quiz','pratica') COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci,
  `url_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracao_minutos` int DEFAULT NULL,
  `ordem` int NOT NULL,
  `obrigatoria` tinyint(1) DEFAULT '1',
  `liberada` tinyint(1) DEFAULT '1',
  `material_apoio` text COLLATE utf8mb4_unicode_ci,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_modulo` (`modulo_id`),
  CONSTRAINT `aulas_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.aulas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.avaliacoes_cursos
CREATE TABLE IF NOT EXISTS `avaliacoes_cursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso_id` int NOT NULL,
  `aluno_id` int NOT NULL,
  `nota` int DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `data_avaliacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_avaliacao` (`curso_id`,`aluno_id`),
  KEY `aluno_id` (`aluno_id`),
  KEY `idx_curso` (`curso_id`),
  KEY `idx_nota` (`nota`),
  CONSTRAINT `avaliacoes_cursos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacoes_cursos_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `avaliacoes_cursos_chk_1` CHECK (((`nota` >= 1) and (`nota` <= 5)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.avaliacoes_cursos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `icone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `ordem` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.categorias: ~8 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nome`, `descricao`, `icone`, `ativo`, `ordem`) VALUES
	(1, 'Mecânica Automotiva Básica', 'Fundamentos de mecânica para iniciantes', NULL, 1, 1),
	(2, 'Motor e Sistema de Combustão', 'Curso sobre motores e sistemas de injeção', NULL, 1, 2),
	(3, 'Suspensão e Direção', 'Sistemas de suspensão e direção veicular', NULL, 1, 3),
	(4, 'Sistema Elétrico', 'Elétrica automotiva e eletrônica embarcada', NULL, 1, 4),
	(5, 'Transmissão', 'Câmbio, embreagem e sistemas de transmissão', NULL, 1, 5),
	(6, 'Freios', 'Sistema de freios ABS e convencional', NULL, 1, 6),
	(7, 'Ar Condicionado', 'Climatização automotiva', NULL, 1, 7),
	(8, 'Diagnóstico', 'Scanner e diagnóstico de falhas', NULL, 1, 8);

-- Copiando estrutura para tabela plataforma_mecanica.certificados
CREATE TABLE IF NOT EXISTS `certificados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricula_id` int NOT NULL,
  `codigo_verificacao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_certificado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_emissao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `carga_horaria` int DEFAULT NULL,
  `nota_final` decimal(5,2) DEFAULT NULL,
  `validade` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_verificacao` (`codigo_verificacao`),
  UNIQUE KEY `unique_certificado` (`matricula_id`),
  KEY `idx_codigo` (`codigo_verificacao`),
  CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.certificados: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.comentarios_aulas
CREATE TABLE IF NOT EXISTS `comentarios_aulas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aula_id` int NOT NULL,
  `autor_id` int NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp_video` int DEFAULT NULL,
  `respondendo_id` int DEFAULT NULL,
  `curtidas` int DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `respondendo_id` (`respondendo_id`),
  KEY `idx_aula` (`aula_id`),
  KEY `idx_autor` (`autor_id`),
  CONSTRAINT `comentarios_aulas_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_aulas_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_aulas_ibfk_3` FOREIGN KEY (`respondendo_id`) REFERENCES `comentarios_aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.comentarios_aulas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `instrutor_id` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `capa_curso` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nivel` enum('iniciante','intermediario','avancado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `duracao_horas` int DEFAULT NULL,
  `carga_horaria` int DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT '0.00',
  `gratuito` tinyint(1) DEFAULT '0',
  `codigo_acesso` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_alunos` int DEFAULT NULL,
  `requisitos` text COLLATE utf8mb4_unicode_ci,
  `objetivos` text COLLATE utf8mb4_unicode_ci,
  `status` enum('rascunho','publicado','arquivado') COLLATE utf8mb4_unicode_ci DEFAULT 'rascunho',
  `destaque` tinyint(1) DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_publicacao` timestamp NULL DEFAULT NULL,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_acesso` (`codigo_acesso`),
  KEY `idx_status` (`status`),
  KEY `idx_instrutor` (`instrutor_id`),
  KEY `idx_categoria` (`categoria_id`),
  KEY `idx_data_criacao` (`data_criacao`),
  KEY `idx_status_curso` (`status`,`data_publicacao`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`instrutor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.cursos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.exercicios_praticos
CREATE TABLE IF NOT EXISTS `exercicios_praticos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aula_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `objetivos` text COLLATE utf8mb4_unicode_ci,
  `materiais_necessarios` text COLLATE utf8mb4_unicode_ci,
  `tempo_estimado` int DEFAULT NULL,
  `nivel_dificuldade` enum('facil','medio','dificil') COLLATE utf8mb4_unicode_ci DEFAULT 'medio',
  `video_demonstracao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `criterios_avaliacao` text COLLATE utf8mb4_unicode_ci,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_aula` (`aula_id`),
  CONSTRAINT `exercicios_praticos_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.exercicios_praticos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.instrutores
CREATE TABLE IF NOT EXISTS `instrutores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `especialidade` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anos_experiencia` int DEFAULT NULL,
  `certificacoes` text COLLATE utf8mb4_unicode_ci,
  `curriculum` text COLLATE utf8mb4_unicode_ci,
  `avaliacao_media` decimal(3,2) DEFAULT '0.00',
  `total_avaliacoes` int DEFAULT '0',
  `data_aprovacao` timestamp NULL DEFAULT NULL,
  `aprovado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `instrutores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.instrutores: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.logs_acesso
CREATE TABLE IF NOT EXISTS `logs_acesso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `tipo_acao` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `data_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_data` (`data_hora`),
  CONSTRAINT `logs_acesso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.logs_acesso: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.materiais
CREATE TABLE IF NOT EXISTS `materiais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aula_id` int NOT NULL,
  `nome_arquivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_arquivo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tamanho_kb` int DEFAULT NULL,
  `url_arquivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_aula` (`aula_id`),
  CONSTRAINT `materiais_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.materiais: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.matriculas
CREATE TABLE IF NOT EXISTS `matriculas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aluno_id` int NOT NULL,
  `curso_id` int NOT NULL,
  `data_matricula` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_conclusao` timestamp NULL DEFAULT NULL,
  `progresso_percentual` decimal(5,2) DEFAULT '0.00',
  `status` enum('ativo','concluido','cancelado','trancado') COLLATE utf8mb4_unicode_ci DEFAULT 'ativo',
  `nota_final` decimal(5,2) DEFAULT NULL,
  `certificado_emitido` tinyint(1) DEFAULT '0',
  `data_certificado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_matricula` (`aluno_id`,`curso_id`),
  KEY `idx_aluno` (`aluno_id`),
  KEY `idx_curso` (`curso_id`),
  KEY `idx_status` (`status`),
  KEY `idx_data_matricula` (`data_matricula`),
  CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.matriculas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.modulos
CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `ordem` int NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_curso` (`curso_id`),
  CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.modulos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.notificacoes
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `tipo` enum('matricula','aula_nova','anuncio','avaliacao','mensagem','certificado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lida` tinyint(1) DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_lida` (`lida`),
  CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.notificacoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.pagamentos
CREATE TABLE IF NOT EXISTS `pagamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricula_id` int NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `metodo_pagamento` enum('cartao_credito','cartao_debito','boleto','pix') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pendente','aprovado','recusado','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente',
  `codigo_transacao` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_pagamento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aprovacao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_matricula` (`matricula_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `pagamentos_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.pagamentos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.progresso_aulas
CREATE TABLE IF NOT EXISTS `progresso_aulas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricula_id` int NOT NULL,
  `aula_id` int NOT NULL,
  `concluida` tinyint(1) DEFAULT '0',
  `tempo_assistido` int DEFAULT '0',
  `data_inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_conclusao` timestamp NULL DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_progresso` (`matricula_id`,`aula_id`),
  KEY `idx_matricula` (`matricula_id`),
  KEY `idx_aula` (`aula_id`),
  CONSTRAINT `progresso_aulas_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progresso_aulas_ibfk_2` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.progresso_aulas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.questoes
CREATE TABLE IF NOT EXISTS `questoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `pergunta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('multipla_escolha','verdadeiro_falso','dissertativa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pontos` decimal(5,2) DEFAULT '1.00',
  `ordem` int DEFAULT NULL,
  `imagem_questao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `explicacao` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_quiz` (`quiz_id`),
  CONSTRAINT `questoes_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.questoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.quizzes
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aula_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `tempo_limite` int DEFAULT NULL,
  `nota_minima` decimal(5,2) DEFAULT '7.00',
  `tentativas_permitidas` int DEFAULT '3',
  `ordem_aleatoria` tinyint(1) DEFAULT '0',
  `mostrar_resultado` tinyint(1) DEFAULT '1',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_aula` (`aula_id`),
  CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.quizzes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.respostas_alunos
CREATE TABLE IF NOT EXISTS `respostas_alunos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tentativa_id` int NOT NULL,
  `questao_id` int NOT NULL,
  `alternativa_id` int DEFAULT NULL,
  `resposta_texto` text COLLATE utf8mb4_unicode_ci,
  `correta` tinyint(1) DEFAULT NULL,
  `pontos_obtidos` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questao_id` (`questao_id`),
  KEY `alternativa_id` (`alternativa_id`),
  KEY `idx_tentativa` (`tentativa_id`),
  CONSTRAINT `respostas_alunos_ibfk_1` FOREIGN KEY (`tentativa_id`) REFERENCES `tentativas_quiz` (`id`) ON DELETE CASCADE,
  CONSTRAINT `respostas_alunos_ibfk_2` FOREIGN KEY (`questao_id`) REFERENCES `questoes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `respostas_alunos_ibfk_3` FOREIGN KEY (`alternativa_id`) REFERENCES `alternativas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.respostas_alunos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.respostas_forum
CREATE TABLE IF NOT EXISTS `respostas_forum` (
  `id` int NOT NULL AUTO_INCREMENT,
  `topico_id` int NOT NULL,
  `autor_id` int NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resposta_aceita` tinyint(1) DEFAULT '0',
  `curtidas` int DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_edicao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_topico` (`topico_id`),
  KEY `idx_autor` (`autor_id`),
  CONSTRAINT `respostas_forum_ibfk_1` FOREIGN KEY (`topico_id`) REFERENCES `topicos_discussao` (`id`) ON DELETE CASCADE,
  CONSTRAINT `respostas_forum_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.respostas_forum: ~0 rows (aproximadamente)

-- Copiando estrutura para procedure plataforma_mecanica.sp_atualizar_progresso
DELIMITER //
CREATE PROCEDURE `sp_atualizar_progresso`(
    IN p_matricula_id INT
)
BEGIN
    DECLARE v_total_aulas INT;
    DECLARE v_aulas_concluidas INT;
    DECLARE v_progresso DECIMAL(5,2);
    
    -- Conta total de aulas do curso
    SELECT COUNT(DISTINCT a.id) INTO v_total_aulas
    FROM matriculas m
    JOIN cursos c ON m.curso_id = c.id
    JOIN modulos mo ON c.id = mo.curso_id
    JOIN aulas a ON mo.id = a.modulo_id
    WHERE m.id = p_matricula_id;
    
    -- Conta aulas concluídas
    SELECT COUNT(*) INTO v_aulas_concluidas
    FROM progresso_aulas
    WHERE matricula_id = p_matricula_id AND concluida = TRUE;
    
    -- Calcula progresso
    IF v_total_aulas > 0 THEN
        SET v_progresso = (v_aulas_concluidas / v_total_aulas) * 100;
        
        UPDATE matriculas
        SET progresso_percentual = v_progresso
        WHERE id = p_matricula_id;
        
        -- Se progresso = 100%, marca como concluído
        IF v_progresso >= 100 THEN
            UPDATE matriculas
            SET status = 'concluido',
                data_conclusao = NOW()
            WHERE id = p_matricula_id;
        END IF;
    END IF;
END//
DELIMITER ;

-- Copiando estrutura para procedure plataforma_mecanica.sp_matricular_aluno
DELIMITER //
CREATE PROCEDURE `sp_matricular_aluno`(
    IN p_aluno_id INT,
    IN p_curso_id INT
)
BEGIN
    DECLARE v_existe INT;
    
    -- Verifica se já existe matrícula
    SELECT COUNT(*) INTO v_existe
    FROM matriculas
    WHERE aluno_id = p_aluno_id AND curso_id = p_curso_id;
    
    IF v_existe = 0 THEN
        INSERT INTO matriculas (aluno_id, curso_id, status)
        VALUES (p_aluno_id, p_curso_id, 'ativo');
        
        -- Cria notificação
        INSERT INTO notificacoes (usuario_id, tipo, titulo, mensagem)
        SELECT p_aluno_id, 'matricula', 'Matrícula Confirmada', 
               CONCAT('Você foi matriculado no curso: ', titulo)
        FROM cursos WHERE id = p_curso_id;
    END IF;
END//
DELIMITER ;

-- Copiando estrutura para tabela plataforma_mecanica.submissoes_exercicios
CREATE TABLE IF NOT EXISTS `submissoes_exercicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exercicio_id` int NOT NULL,
  `matricula_id` int NOT NULL,
  `descricao_trabalho` text COLLATE utf8mb4_unicode_ci,
  `arquivos_enviados` text COLLATE utf8mb4_unicode_ci,
  `data_submissao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_avaliacao` timestamp NULL DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pendente','avaliado','reenviar') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `idx_exercicio` (`exercicio_id`),
  KEY `idx_matricula` (`matricula_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `submissoes_exercicios_ibfk_1` FOREIGN KEY (`exercicio_id`) REFERENCES `exercicios_praticos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `submissoes_exercicios_ibfk_2` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.submissoes_exercicios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.tentativas_quiz
CREATE TABLE IF NOT EXISTS `tentativas_quiz` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matricula_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `data_inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_conclusao` timestamp NULL DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `aprovado` tinyint(1) DEFAULT '0',
  `numero_tentativa` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_matricula` (`matricula_id`),
  KEY `idx_quiz` (`quiz_id`),
  CONSTRAINT `tentativas_quiz_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tentativas_quiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.tentativas_quiz: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.topicos_discussao
CREATE TABLE IF NOT EXISTS `topicos_discussao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `curso_id` int NOT NULL,
  `autor_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fixado` tinyint(1) DEFAULT '0',
  `fechado` tinyint(1) DEFAULT '0',
  `visualizacoes` int DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_curso` (`curso_id`),
  KEY `idx_autor` (`autor_id`),
  CONSTRAINT `topicos_discussao_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `topicos_discussao_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.topicos_discussao: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela plataforma_mecanica.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_usuario` enum('aluno','instrutor','administrador') COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `cpf` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` text COLLATE utf8mb4_unicode_ci,
  `cidade` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `ativo` tinyint(1) DEFAULT '1',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_acesso` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `idx_email` (`email`),
  KEY `idx_tipo` (`tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela plataforma_mecanica.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nome_completo`, `email`, `senha_hash`, `tipo_usuario`, `foto_perfil`, `telefone`, `data_nascimento`, `cpf`, `endereco`, `cidade`, `estado`, `cep`, `bio`, `ativo`, `data_cadastro`, `ultimo_acesso`) VALUES
	(1, 'Administrador Sistema', 'admin@mecanica.com', '$2y$10$exemplo_hash_senha', 'administrador', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-10-14 13:29:22', NULL);

-- Copiando estrutura para view plataforma_mecanica.vw_cursos_completos
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_cursos_completos` (
	`id` INT(10) NOT NULL,
	`titulo` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`descricao` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`nivel` ENUM('iniciante','intermediario','avancado') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`duracao_horas` INT(10) NULL,
	`preco` DECIMAL(10,2) NULL,
	`status` ENUM('rascunho','publicado','arquivado') NULL COLLATE 'utf8mb4_unicode_ci',
	`instrutor_nome` VARCHAR(150) NULL COLLATE 'utf8mb4_unicode_ci',
	`categoria_nome` VARCHAR(100) NULL COLLATE 'utf8mb4_unicode_ci',
	`total_modulos` BIGINT(19) NOT NULL,
	`total_aulas` BIGINT(19) NOT NULL,
	`total_matriculas` BIGINT(19) NOT NULL,
	`avaliacao_media` DECIMAL(14,4) NULL,
	`total_avaliacoes` BIGINT(19) NOT NULL
) ENGINE=MyISAM;

-- Copiando estrutura para view plataforma_mecanica.vw_desempenho_cursos
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_desempenho_cursos` (
	`curso_id` INT(10) NOT NULL,
	`titulo` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`total_alunos` BIGINT(19) NOT NULL,
	`alunos_concluidos` BIGINT(19) NOT NULL,
	`progresso_medio` DECIMAL(9,6) NULL,
	`nota_media` DECIMAL(9,6) NULL,
	`total_avaliacoes` BIGINT(19) NOT NULL,
	`avaliacao_media` DECIMAL(14,4) NULL
) ENGINE=MyISAM;

-- Copiando estrutura para view plataforma_mecanica.vw_progresso_alunos
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `vw_progresso_alunos` (
	`matricula_id` INT(10) NOT NULL,
	`aluno_nome` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`curso_titulo` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`progresso_percentual` DECIMAL(5,2) NULL,
	`status` ENUM('ativo','concluido','cancelado','trancado') NULL COLLATE 'utf8mb4_unicode_ci',
	`aulas_concluidas` BIGINT(19) NOT NULL,
	`total_aulas` BIGINT(19) NOT NULL,
	`data_matricula` TIMESTAMP NULL,
	`data_conclusao` TIMESTAMP NULL
) ENGINE=MyISAM;

-- Copiando estrutura para trigger plataforma_mecanica.trg_atualizar_avaliacao_curso
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_atualizar_avaliacao_curso` AFTER INSERT ON `avaliacoes_cursos` FOR EACH ROW BEGIN
    UPDATE cursos c
    SET c.data_atualizacao = NOW()
    WHERE c.id = NEW.curso_id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Copiando estrutura para trigger plataforma_mecanica.trg_log_ultimo_acesso
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_log_ultimo_acesso` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
    IF NEW.ultimo_acesso != OLD.ultimo_acesso THEN
        INSERT INTO logs_acesso (usuario_id, tipo_acao, descricao)
        VALUES (NEW.id, 'login', 'Acesso ao sistema');
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Copiando estrutura para view plataforma_mecanica.vw_cursos_completos
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_cursos_completos`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_cursos_completos` AS select `c`.`id` AS `id`,`c`.`titulo` AS `titulo`,`c`.`descricao` AS `descricao`,`c`.`nivel` AS `nivel`,`c`.`duracao_horas` AS `duracao_horas`,`c`.`preco` AS `preco`,`c`.`status` AS `status`,`u`.`nome_completo` AS `instrutor_nome`,`cat`.`nome` AS `categoria_nome`,count(distinct `m`.`id`) AS `total_modulos`,count(distinct `a`.`id`) AS `total_aulas`,count(distinct `mat`.`id`) AS `total_matriculas`,avg(`av`.`nota`) AS `avaliacao_media`,count(`av`.`id`) AS `total_avaliacoes` from ((((((`cursos` `c` left join `usuarios` `u` on((`c`.`instrutor_id` = `u`.`id`))) left join `categorias` `cat` on((`c`.`categoria_id` = `cat`.`id`))) left join `modulos` `m` on((`c`.`id` = `m`.`curso_id`))) left join `aulas` `a` on((`m`.`id` = `a`.`modulo_id`))) left join `matriculas` `mat` on((`c`.`id` = `mat`.`curso_id`))) left join `avaliacoes_cursos` `av` on((`c`.`id` = `av`.`curso_id`))) group by `c`.`id`;

-- Copiando estrutura para view plataforma_mecanica.vw_desempenho_cursos
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_desempenho_cursos`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_desempenho_cursos` AS select `c`.`id` AS `curso_id`,`c`.`titulo` AS `titulo`,count(distinct `m`.`id`) AS `total_alunos`,count(distinct (case when (`m`.`status` = 'concluido') then `m`.`id` end)) AS `alunos_concluidos`,avg(`m`.`progresso_percentual`) AS `progresso_medio`,avg(`m`.`nota_final`) AS `nota_media`,count(distinct `av`.`id`) AS `total_avaliacoes`,avg(`av`.`nota`) AS `avaliacao_media` from ((`cursos` `c` left join `matriculas` `m` on((`c`.`id` = `m`.`curso_id`))) left join `avaliacoes_cursos` `av` on((`c`.`id` = `av`.`curso_id`))) group by `c`.`id`;

-- Copiando estrutura para view plataforma_mecanica.vw_progresso_alunos
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `vw_progresso_alunos`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_progresso_alunos` AS select `m`.`id` AS `matricula_id`,`u`.`nome_completo` AS `aluno_nome`,`c`.`titulo` AS `curso_titulo`,`m`.`progresso_percentual` AS `progresso_percentual`,`m`.`status` AS `status`,count(`pa`.`id`) AS `aulas_concluidas`,count(`a`.`id`) AS `total_aulas`,`m`.`data_matricula` AS `data_matricula`,`m`.`data_conclusao` AS `data_conclusao` from (((((`matriculas` `m` join `usuarios` `u` on((`m`.`aluno_id` = `u`.`id`))) join `cursos` `c` on((`m`.`curso_id` = `c`.`id`))) left join `modulos` `mo` on((`c`.`id` = `mo`.`curso_id`))) left join `aulas` `a` on((`mo`.`id` = `a`.`modulo_id`))) left join `progresso_aulas` `pa` on(((`m`.`id` = `pa`.`matricula_id`) and (`a`.`id` = `pa`.`aula_id`) and (`pa`.`concluida` = true)))) group by `m`.`id`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
