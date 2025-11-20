-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 20/11/2025 às 00:58
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
-- Banco de dados: `db_ifoot`
--
CREATE DATABASE IF NOT EXISTS `db_ifoot` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_ifoot`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `JOGADORES`
--

CREATE TABLE `JOGADORES` (
  `ID_JOG` int(11) NOT NULL,
  `NOME_JOG` varchar(255) NOT NULL,
  `EMAIL_JOG` varchar(50) NOT NULL,
  `CPF_JOG` varchar(11) NOT NULL,
  `CIDADE_JOG` varchar(50) NOT NULL,
  `ENDERECO_JOG` varchar(255) NOT NULL,
  `TEL_JOG` varchar(11) NOT NULL,
  `SENHA_JOG` varchar(8) NOT NULL,
  `ID_UF` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `JOGADOR_PARTIDA`
--

CREATE TABLE `JOGADOR_PARTIDA` (
  `ID_JOG_PTD` int(11) NOT NULL,
  `ID_JOG` int(11) NOT NULL,
  `ID_PTD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `MODALIDADES`
--

CREATE TABLE `MODALIDADES` (
  `ID_MODAL` int(11) NOT NULL,
  `NOME_MODAL` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `MODALIDADES`
--

INSERT INTO `MODALIDADES` (`ID_MODAL`, `NOME_MODAL`) VALUES
(1, 'CAMPO'),
(2, 'SOCIETY'),
(3, 'QUADRA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `PARTIDAS`
--

CREATE TABLE `PARTIDAS` (
  `ID_PTD` int(11) NOT NULL,
  `DATA_PTD` date NOT NULL,
  `HORARIO_INICIO_PTD` time NOT NULL,
  `HORARIO_FIM_PTD` time NOT NULL,
  `ID_QUAD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `PROPRIETARIOS`
--

CREATE TABLE `PROPRIETARIOS` (
  `ID_PROP` int(11) NOT NULL,
  `NOME_PROP` varchar(255) NOT NULL,
  `EMAIL_PROP` varchar(50) NOT NULL,
  `CPF_PROP` varchar(11) NOT NULL,
  `TEL_PROP` varchar(11) NOT NULL,
  `SENHA_PROP` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `QUADRAS`
--

CREATE TABLE `QUADRAS` (
  `ID_QUAD` int(11) NOT NULL,
  `PRECO_HORA_QUAD` decimal(8,2) NOT NULL,
  `ENDERECO_QUAD` varchar(255) NOT NULL,
  `CIDADE_QUAD` varchar(50) NOT NULL,
  `ID_PROP` int(11) NOT NULL,
  `ID_UF` int(11) NOT NULL,
  `ID_MODAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `UF`
--

CREATE TABLE `UF` (
  `ID_UF` int(11) NOT NULL,
  `SIGLA_UF` varchar(2) NOT NULL,
  `NOME_UF` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `UF`
--

INSERT INTO `UF` (`ID_UF`, `SIGLA_UF`, `NOME_UF`) VALUES
(1, 'AC', 'Acre'),
(2, 'AL', 'Alagoas'),
(3, 'AP', 'Amapá'),
(4, 'AM', 'Amazonas'),
(5, 'BA', 'Bahia'),
(6, 'CE', 'Ceará'),
(7, 'DF', 'Distrito Federal'),
(8, 'ES', 'Espírito Santo'),
(9, 'GO', 'Goiás'),
(10, 'MA', 'Maranhão'),
(11, 'MT', 'Mato Grosso'),
(12, 'MS', 'Mato Grosso do Sul'),
(13, 'MG', 'Minas Gerais'),
(14, 'PA', 'Pará'),
(15, 'PB', 'Paraíba'),
(16, 'PR', 'Paraná'),
(17, 'PE', 'Pernambuco'),
(18, 'PI', 'Piauí'),
(19, 'RJ', 'Rio de Janeiro'),
(20, 'RN', 'Rio Grande do Norte'),
(21, 'RS', 'Rio Grande do Sul'),
(22, 'RO', 'Rondônia'),
(23, 'RR', 'Roraima'),
(24, 'SC', 'Santa Catarina'),
(25, 'SP', 'São Paulo'),
(26, 'SE', 'Sergipe'),
(27, 'TO', 'Tocantins');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `JOGADORES`
--
ALTER TABLE `JOGADORES`
  ADD PRIMARY KEY (`ID_JOG`),
  ADD KEY `ID_UF` (`ID_UF`);

--
-- Índices de tabela `JOGADOR_PARTIDA`
--
ALTER TABLE `JOGADOR_PARTIDA`
  ADD PRIMARY KEY (`ID_JOG_PTD`),
  ADD KEY `ID_JOG` (`ID_JOG`),
  ADD KEY `ID_PTD` (`ID_PTD`);

--
-- Índices de tabela `MODALIDADES`
--
ALTER TABLE `MODALIDADES`
  ADD PRIMARY KEY (`ID_MODAL`);

--
-- Índices de tabela `PARTIDAS`
--
ALTER TABLE `PARTIDAS`
  ADD PRIMARY KEY (`ID_PTD`),
  ADD KEY `ID_QUAD` (`ID_QUAD`);

--
-- Índices de tabela `PROPRIETARIOS`
--
ALTER TABLE `PROPRIETARIOS`
  ADD PRIMARY KEY (`ID_PROP`);

--
-- Índices de tabela `QUADRAS`
--
ALTER TABLE `QUADRAS`
  ADD PRIMARY KEY (`ID_QUAD`),
  ADD KEY `ID_PROP` (`ID_PROP`),
  ADD KEY `ID_UF` (`ID_UF`),
  ADD KEY `ID_MODAL` (`ID_MODAL`);

--
-- Índices de tabela `UF`
--
ALTER TABLE `UF`
  ADD PRIMARY KEY (`ID_UF`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `JOGADORES`
--
ALTER TABLE `JOGADORES`
  MODIFY `ID_JOG` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `JOGADOR_PARTIDA`
--
ALTER TABLE `JOGADOR_PARTIDA`
  MODIFY `ID_JOG_PTD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `MODALIDADES`
--
ALTER TABLE `MODALIDADES`
  MODIFY `ID_MODAL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `PARTIDAS`
--
ALTER TABLE `PARTIDAS`
  MODIFY `ID_PTD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `PROPRIETARIOS`
--
ALTER TABLE `PROPRIETARIOS`
  MODIFY `ID_PROP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `QUADRAS`
--
ALTER TABLE `QUADRAS`
  MODIFY `ID_QUAD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `UF`
--
ALTER TABLE `UF`
  MODIFY `ID_UF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `JOGADORES`
--
ALTER TABLE `JOGADORES`
  ADD CONSTRAINT `JOGADORES_ibfk_1` FOREIGN KEY (`ID_UF`) REFERENCES `UF` (`ID_UF`);

--
-- Restrições para tabelas `JOGADOR_PARTIDA`
--
ALTER TABLE `JOGADOR_PARTIDA`
  ADD CONSTRAINT `JOGADOR_PARTIDA_ibfk_1` FOREIGN KEY (`ID_JOG`) REFERENCES `JOGADORES` (`ID_JOG`),
  ADD CONSTRAINT `JOGADOR_PARTIDA_ibfk_2` FOREIGN KEY (`ID_PTD`) REFERENCES `PARTIDAS` (`ID_PTD`);

--
-- Restrições para tabelas `PARTIDAS`
--
ALTER TABLE `PARTIDAS`
  ADD CONSTRAINT `PARTIDAS_ibfk_1` FOREIGN KEY (`ID_QUAD`) REFERENCES `QUADRAS` (`ID_QUAD`);

--
-- Restrições para tabelas `QUADRAS`
--
ALTER TABLE `QUADRAS`
  ADD CONSTRAINT `QUADRAS_ibfk_1` FOREIGN KEY (`ID_PROP`) REFERENCES `PROPRIETARIOS` (`ID_PROP`),
  ADD CONSTRAINT `QUADRAS_ibfk_2` FOREIGN KEY (`ID_UF`) REFERENCES `UF` (`ID_UF`),
  ADD CONSTRAINT `QUADRAS_ibfk_3` FOREIGN KEY (`ID_MODAL`) REFERENCES `MODALIDADES` (`ID_MODAL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
