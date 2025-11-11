-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11-Nov-2025 às 01:57
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

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
-- Estrutura da tabela `equipes`
--

CREATE TABLE `equipes` (
  `ID_EQP` int(11) NOT NULL,
  `NOME_EQP` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `equipes_partidas`
--

CREATE TABLE `equipes_partidas` (
  `ID_EQP_PTD` int(11) NOT NULL,
  `ID_EQP` int(11) NOT NULL,
  `ID_PTD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogadores`
--

CREATE TABLE `jogadores` (
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
-- Estrutura da tabela `jogadores_equipes`
--

CREATE TABLE `jogadores_equipes` (
  `ID_JOG_EQP` int(11) NOT NULL,
  `ID_JOG` int(11) NOT NULL,
  `ID_EQP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modalidades`
--

CREATE TABLE `modalidades` (
  `ID_MODAL` int(11) NOT NULL,
  `NOME_MODAL` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `partidas`
--

CREATE TABLE `partidas` (
  `ID_PTD` int(11) NOT NULL,
  `DATA_PTD` date NOT NULL,
  `HORARIO_INICIO_PTD` time NOT NULL,
  `HORARIO_FIM_PTD` time NOT NULL,
  `ID_QUAD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `proprietarios`
--

CREATE TABLE `proprietarios` (
  `ID_PROP` int(11) NOT NULL,
  `NOME_PROP` varchar(255) NOT NULL,
  `EMAIL_PROP` varchar(50) NOT NULL,
  `CPF_PROP` varchar(11) NOT NULL,
  `TEL_PROP` varchar(11) NOT NULL,
  `SENHA_PROP` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `quadras`
--

CREATE TABLE `quadras` (
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
-- Estrutura da tabela `uf`
--

CREATE TABLE `uf` (
  `ID_UF` int(11) NOT NULL,
  `SIGLA_UF` varchar(2) NOT NULL,
  `NOME_UF` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `uf`
--

INSERT INTO `uf` (`ID_UF`, `SIGLA_UF`, `NOME_UF`) VALUES
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
-- Índices para tabela `equipes`
--
ALTER TABLE `equipes`
  ADD PRIMARY KEY (`ID_EQP`);

--
-- Índices para tabela `equipes_partidas`
--
ALTER TABLE `equipes_partidas`
  ADD PRIMARY KEY (`ID_EQP_PTD`),
  ADD KEY `ID_EQP` (`ID_EQP`),
  ADD KEY `ID_PTD` (`ID_PTD`);

--
-- Índices para tabela `jogadores`
--
ALTER TABLE `jogadores`
  ADD PRIMARY KEY (`ID_JOG`),
  ADD KEY `ID_UF` (`ID_UF`);

--
-- Índices para tabela `jogadores_equipes`
--
ALTER TABLE `jogadores_equipes`
  ADD PRIMARY KEY (`ID_JOG_EQP`),
  ADD KEY `ID_JOG` (`ID_JOG`),
  ADD KEY `ID_EQP` (`ID_EQP`);

--
-- Índices para tabela `modalidades`
--
ALTER TABLE `modalidades`
  ADD PRIMARY KEY (`ID_MODAL`);

--
-- Índices para tabela `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`ID_PTD`),
  ADD KEY `ID_QUAD` (`ID_QUAD`);

--
-- Índices para tabela `proprietarios`
--
ALTER TABLE `proprietarios`
  ADD PRIMARY KEY (`ID_PROP`);

--
-- Índices para tabela `quadras`
--
ALTER TABLE `quadras`
  ADD PRIMARY KEY (`ID_QUAD`),
  ADD KEY `ID_PROP` (`ID_PROP`),
  ADD KEY `ID_UF` (`ID_UF`),
  ADD KEY `ID_MODAL` (`ID_MODAL`);

--
-- Índices para tabela `uf`
--
ALTER TABLE `uf`
  ADD PRIMARY KEY (`ID_UF`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `equipes`
--
ALTER TABLE `equipes`
  MODIFY `ID_EQP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `equipes_partidas`
--
ALTER TABLE `equipes_partidas`
  MODIFY `ID_EQP_PTD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jogadores`
--
ALTER TABLE `jogadores`
  MODIFY `ID_JOG` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jogadores_equipes`
--
ALTER TABLE `jogadores_equipes`
  MODIFY `ID_JOG_EQP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `modalidades`
--
ALTER TABLE `modalidades`
  MODIFY `ID_MODAL` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `partidas`
--
ALTER TABLE `partidas`
  MODIFY `ID_PTD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `proprietarios`
--
ALTER TABLE `proprietarios`
  MODIFY `ID_PROP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quadras`
--
ALTER TABLE `quadras`
  MODIFY `ID_QUAD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `uf`
--
ALTER TABLE `uf`
  MODIFY `ID_UF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `equipes_partidas`
--
ALTER TABLE `equipes_partidas`
  ADD CONSTRAINT `equipes_partidas_ibfk_1` FOREIGN KEY (`ID_EQP`) REFERENCES `equipes` (`ID_EQP`),
  ADD CONSTRAINT `equipes_partidas_ibfk_2` FOREIGN KEY (`ID_PTD`) REFERENCES `partidas` (`ID_PTD`);

--
-- Limitadores para a tabela `jogadores`
--
ALTER TABLE `jogadores`
  ADD CONSTRAINT `jogadores_ibfk_1` FOREIGN KEY (`ID_UF`) REFERENCES `uf` (`ID_UF`);

--
-- Limitadores para a tabela `jogadores_equipes`
--
ALTER TABLE `jogadores_equipes`
  ADD CONSTRAINT `jogadores_equipes_ibfk_1` FOREIGN KEY (`ID_JOG`) REFERENCES `jogadores` (`ID_JOG`),
  ADD CONSTRAINT `jogadores_equipes_ibfk_2` FOREIGN KEY (`ID_EQP`) REFERENCES `equipes` (`ID_EQP`);

--
-- Limitadores para a tabela `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`ID_QUAD`) REFERENCES `quadras` (`ID_QUAD`);

--
-- Limitadores para a tabela `quadras`
--
ALTER TABLE `quadras`
  ADD CONSTRAINT `quadras_ibfk_1` FOREIGN KEY (`ID_PROP`) REFERENCES `proprietarios` (`ID_PROP`),
  ADD CONSTRAINT `quadras_ibfk_2` FOREIGN KEY (`ID_UF`) REFERENCES `uf` (`ID_UF`),
  ADD CONSTRAINT `quadras_ibfk_3` FOREIGN KEY (`ID_MODAL`) REFERENCES `modalidades` (`ID_MODAL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
