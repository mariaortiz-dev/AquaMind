-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/11/2025 às 17:58
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `aquamind`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `imagem` varchar(255) DEFAULT NULL,
  `data_adicionado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`id`, `id_produto`, `nome`, `preco`, `quantidade`, `imagem`, `data_adicionado`) VALUES
(18, 5, 'Bomba Compacta', 650.00, 1, 'imagem_produtos/bomba.png', '2025-11-26 16:45:03'),
(20, 7, 'Kit Limpeza', 300.00, 1, 'imagem_produtos/kitlimpeza.webp', '2025-11-26 16:45:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `last_activity` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `session_id`, `ip`, `user_agent`, `login_time`, `last_activity`, `logout_time`, `is_active`) VALUES
(1, 1, 't6mq4d19hr99l32s9bav442qlb', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-12 22:31:56', '2025-11-12 22:32:12', '2025-11-12 22:32:12', 0),
(3, 1, 'm5c3d79qo8sea12cau0s6l293h', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-12 22:32:48', '2025-11-12 22:33:31', '2025-11-12 22:33:31', 0),
(4, 1, 'iutspigc6fe4mghvc0d9meumnd', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-12 22:33:39', '2025-11-12 22:52:11', '2025-11-12 22:52:11', 0),
(7, 1, 'rqk3am1neu0a12ufl7ud9c522b', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-12 22:52:35', '2025-11-12 23:06:07', '2025-11-12 23:06:07', 0),
(9, 1, 'u3kf8tp29lg8bdbrj5mfe6cvsh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-12 23:23:58', '2025-11-12 23:31:02', '2025-11-12 23:31:02', 0),
(11, 1, '7ied8b2aq2ffpll5l0g8imvb40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-12 23:44:07', '2025-11-12 23:44:24', '2025-11-12 23:44:24', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `estoque`, `categoria`, `imagem`, `data_cadastro`) VALUES
(1, 'Cloro para Piscina', 'Produto de alta qualidade para tratamento de água.', 150.00, 5, 'Tratamento de Água', 'imagem_produtos/cloro.webp', '2025-11-13 13:58:09'),
(2, 'Algicida', 'Evita o aparecimento de algas e mantém a piscina cristalina.', 69.99, 0, 'Tratamento de Água', 'imagem_produtos/algicida.webp', '2025-11-13 13:58:09'),
(3, 'Redutor de pH (pH-)', 'Diminui o pH alto da piscina, evitando irritações na pele e olhos.', 30.00, 30, 'Tratamento de Água', 'imagem_produtos/pH-menos.webp', '2025-11-13 13:58:09'),
(4, 'Clarificante', 'Deixa a água mais cristalina e transparente após o tratamento.', 25.00, 6, 'Tratamento de Água', 'imagem_produtos/clarificante.webp', '2025-11-13 13:58:09'),
(5, 'Bomba Compacta', 'Bomba compacta e econômica.', 650.00, 100, 'Equipamentos de Piscina', 'imagem_produtos/bomba.png', '2025-11-19 15:30:57'),
(6, 'Mangueira Espiral Flutuante 10m', 'Mangueira espiral flutuante de alta durabilidade (10m).', 80.00, 100, 'Equipamentos de Piscina', 'imagem_produtos/mangueira.jpg', '2025-11-19 15:30:57'),
(7, 'Kit Limpeza', 'Kit completo para manutenção e limpeza de piscinas.', 300.00, 100, 'Equipamentos de Piscina', 'imagem_produtos/kitlimpeza.webp', '2025-11-19 15:30:57'),
(8, 'Robô Aspirador Automático para Piscinas', 'Robô inteligente, equipado com sistema de sucção de alta performance, escovas inferiores e tecnologia.', 3000.00, 100, 'Equipamentos de Piscina', 'imagem_produtos/robo.jpg', '2025-11-19 15:30:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` enum('masculino','feminino','outros') NOT NULL,
  `nome_materno` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('master','cliente') DEFAULT 'cliente',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_usuario` enum('master','comum') NOT NULL DEFAULT 'comum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `data_nascimento`, `genero`, `nome_materno`, `email`, `telefone`, `cpf`, `cep`, `rua`, `bairro`, `cidade`, `uf`, `numero`, `login`, `senha`, `tipo`, `data_cadastro`, `tipo_usuario`) VALUES
(1, 'Administrador Master', '1990-01-01', 'outros', 'Sistema AquaMind', 'admin@aquamind.com', '(00) 00000-0000', '000.000.000-00', '00000-000', 'Rua Principal', 'Centro', 'AquaCity', 'AC', '100', 'master', '$2y$10$9qhxXPQgYZ03uScujWranOmKqYM9Q6E.Ut/5VZToAEeoo7cyy39ri', 'master', '2025-11-08 17:37:06', 'master'),
(2, 'João da Silva', '1995-06-15', 'masculino', 'Maria da Silva', 'joao.silva@example.com', '(11) 98765-4321', '123.456.789-00', '12345-678', 'Av. das Águas Claras', 'Jardim Azul', 'HydroCity', 'SP', '250', 'joaos', '$2y$10$XqjIwSh4BxI/dsO4wUveUOamZx8h2yZQ1oe7X9FTnUO2bnmXZSnMK', 'cliente', '2025-11-08 17:37:13', 'comum'),
(11, 'Anderson Magalhaes Falcao', '1979-12-30', 'masculino', 'Vilma T Magalhaes', 'amfaaa@gmail.com', '(21) 96721-1323', '010.440.117-69', '21820-160', 'Rua Minerva', 'Bangu', 'Rio de Janeiro', 'RJ', '', 'aaaaaa', '$2y$10$/d0IV8QNf53YnUZxaeJuD..unohjzI7JFPDkonvgCwOr2x2HhI3hi', 'cliente', '2025-11-23 18:10:19', 'comum'),
(13, 'Anderson M Falcao', '1979-12-30', 'masculino', 'Anderson M Falcao', 'bbbbbb@gmail.com', '(21) 97721-1323', '082.985.457-61', '00000-000', '', '', '', '', '', 'bbbbbb', '$2y$10$HnZOFHtT/FNF6E9G/x2j0OpPIph8sCdxIzdIdaxguUmq.MYwNCaoO', 'cliente', '2025-11-25 16:50:59', 'comum');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `login_time` (`login_time`),
  ADD KEY `is_active` (`is_active`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
