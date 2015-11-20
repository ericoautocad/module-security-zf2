-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 20/11/2015 às 10h01min
-- Versão do Servidor: 5.5.44
-- Versão do PHP: 5.5.28-1+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `seubancodedados`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissao_acl`
--

CREATE TABLE IF NOT EXISTS `permissao_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permissao` varchar(45) DEFAULT NULL,
  `recurso_sistema_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_permissao_acl_recurso_sistema_idx` (`recurso_sistema_id`),
  KEY `fk_permissao_acl_grupo1_idx` (`grupo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `permissao_acl`
--

INSERT INTO `permissao_acl` (`id`, `permissao`, `recurso_sistema_id`, `grupo_id`) VALUES

(1, 'allow', 1, 1),
(2, 'allow', 2, 1),
(3, 'allow', 3, 1),
(4, 'allow', 4, 1),
(5, 'allow', 5, 1),
(6, 'allow', 6, 1),
(7, 'allow', 7, 1),
(8, 'allow', 8, 1),
(9, 'allow', 9, 1),
(10, 'allow', 10, 1),
(11, 'allow', 11, 1),
(12, 'allow', 12, 1),
(13, 'allow', 13, 1),
(14, 'allow', 14, 1);

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `permissao_acl`
--
ALTER TABLE `permissao_acl`
  ADD CONSTRAINT `fk_permissao_acl_grupo1` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_permissao_acl_recurso_sistema` FOREIGN KEY (`recurso_sistema_id`) REFERENCES `recurso_sistema` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
