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
-- Estrutura da tabela `recurso_sistema`
--

CREATE TABLE IF NOT EXISTS `recurso_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `recurso_sistema`
--

INSERT INTO `recurso_sistema` (`id`, `url`) VALUES
(1, 'Application\\Controller\\IndexController\\index'),
(2, 'Security\\Controller\\IndexController\\index'),
(3, 'Security\\Controller\\PermissaoController\\index'),
(4, 'Security\\Controller\\PermissaoController\\gerenciar'),
(5, 'Security\\Controller\\PermissaoController\\editar'),
(6, 'Security\\Controller\\AutenticacaoController\\alterarDadosAcesso'),
(7, 'Security\\Controller\\GrupoController\\index'),
(8, 'Security\\Controller\\GrupoController\\adicionar'),
(9, 'Security\\Controller\\GrupoController\\editar'),
(10, 'Security\\Controller\\GrupoController\\excluir'),
(11, 'Security\\Controller\\FuncionarioController\\index'),
(12, 'Security\\Controller\\FuncionarioController\\adicionar'),
(13, 'Security\\Controller\\FuncionarioController\\editar'),
(14, 'Security\\Controller\\FuncionarioController\\excluir');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
