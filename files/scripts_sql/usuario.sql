-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 20/11/2015 às 10h00min
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
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(60) DEFAULT NULL,
  `senha` varchar(250) DEFAULT NULL,
  `salt` varchar(250) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `grupo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_grupo1_idx` (`grupo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `login`, `senha`, `salt`, `ativo`, `grupo_id`) VALUES
(1, 'erico.leandro', '$2y$10$LmlkY2NtLm10ZTJyLnJvYuogpGpZFZTm22B1RGKI906d1II14fx4i', '.idccm.mte2r.roc@oadgicgcouocr2ug@ocaaaigomc.oioae.ocaaioero@cac', 1, 1);

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_grupo1` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
