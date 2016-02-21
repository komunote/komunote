-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Sam 10 Novembre 2012 à 14:06
-- Version du serveur: 5.1.50
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `komu7dh_komuadmin`
--
CREATE DATABASE `komu7dh_komuadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `komu7dh_komuadmin`;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id auto incrémenté',
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `pseudo` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'mode de passe encrypté hmac sha256',
  `question` tinyint(3) unsigned NOT NULL COMMENT 'question secrète',
  `answer` char(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'réponse à la question secrète',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date inscription',
  `dateConnection` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date connexion',
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100003 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `email`, `pseudo`, `password`, `question`, `answer`, `date`, `dateConnection`, `active`) VALUES
(1, 'backfire_david@hotmail.fr', 'komunote', 'dbf7559486ccb1e2b7f8a8107a4edba61babfea1911363a2ce343c2b151f5726', 0, 'armani', '2012-01-02 23:00:00', '0000-00-00 00:00:00', 1),
(100002, 'david_chabrier@hotmail.com', 'komunoteseller', 'dbf7559486ccb1e2b7f8a8107a4edba61babfea1911363a2ce343c2b151f5726', 0, 'armani', '2012-01-07 23:00:00', '0000-00-00 00:00:00', 1);
--
-- Base de données: `komu7dh_komukeyword`
--
CREATE DATABASE `komu7dh_komukeyword` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `komu7dh_komukeyword`;

-- --------------------------------------------------------

--
-- Structure de la table `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
  `keyword` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'mot clé',
  `length` int(10) unsigned NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date dernière modification',
  `hit` int(11) NOT NULL COMMENT 'nombre de fois que le mot clé a été recherché ou ajouté',
  PRIMARY KEY (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `keyword`
--

INSERT INTO `keyword` (`keyword`, `length`, `weight`, `last_modified`, `hit`) VALUES
('330i', 0, 0, '2011-12-06 17:30:30', 1),
('335i', 0, 0, '2011-12-06 16:39:44', 1),
('bmw', 0, 0, '2011-12-06 16:39:43', 1),
('devil', 0, 0, '2011-06-01 22:25:10', 13),
('disgaea', 0, 0, '2011-06-02 00:09:04', 8),
('jeux', 0, 0, '2011-06-01 22:25:10', 19),
('nouveau', 0, 0, '2011-06-01 22:25:10', 19),
('ps3', 0, 0, '2011-06-02 00:09:04', 30),
('test', 0, 0, '2011-06-01 23:43:35', 1),
('ps2', 0, 0, '2012-10-31 11:48:49', 1),
('shadow', 0, 0, '2012-10-31 11:48:49', 1),
('hearts', 0, 0, '2012-10-31 11:48:49', 1),
('from', 0, 0, '2012-10-31 11:48:49', 1),
('the', 0, 0, '2012-10-31 11:48:49', 1),
('new', 0, 0, '2012-10-31 11:48:49', 1),
('world', 0, 0, '2012-10-31 11:48:49', 1),
('version', 0, 0, '2012-10-31 11:48:49', 1);
--
-- Base de données: `komu7dh_komurate`
--
CREATE DATABASE `komu7dh_komurate` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `komu7dh_komurate`;

-- --------------------------------------------------------

--
-- Structure de la table `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `id_order` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL COMMENT 'celui qui donne un commentaire',
  `id_seller` int(10) unsigned NOT NULL COMMENT 'celui qui recoit le commentaire',
  `comment` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'commentaire de l''acheteur',
  `answer` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'réponse du vendeur',
  `score` tinyint(4) NOT NULL DEFAULT '100' COMMENT 'note de l''acheteur',
  `date_comment` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date du commentaire acheteur',
  `date_answer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date de réponse vendeur',
  PRIMARY KEY (`id_order`,`id_user`,`id_seller`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `rating`
--

INSERT INTO `rating` (`id_order`, `id_user`, `id_seller`, `comment`, `answer`, `score`, `date_comment`, `date_answer`) VALUES
(1, 1, 100002, 'trÃ¨s bon vendeur', 'merci bon acheteur', 89, '2012-01-12 11:09:30', '2012-01-12 11:14:28'),
(7, 1, 100002, 'super vendeur', 'super acheteur', 80, '2012-10-30 10:20:49', '2012-10-30 10:20:49');
--
-- Base de données: `komu7dh_komusales`
--
CREATE DATABASE `komu7dh_komusales` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `komu7dh_komusales`;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id auto incrémenté',
  `id_seller` int(10) unsigned NOT NULL COMMENT 'id seller',
  `id_user` int(10) unsigned NOT NULL COMMENT 'id user',
  `id_item` int(10) unsigned NOT NULL COMMENT 'id item sha256',
  `quantity` tinyint(3) unsigned NOT NULL,
  `unit_price` decimal(10,2) unsigned NOT NULL COMMENT 'prix unitaire',
  `unit_shipping_price` decimal(10,2) unsigned NOT NULL COMMENT 'prix unitaire frais de port',
  `shipping_type` tinyint(3) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date de validation ou d''annulation',
  `is_validated` tinyint(1) NOT NULL DEFAULT '0',
  `is_cancelled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `order`
--

INSERT INTO `order` (`id`, `id_seller`, `id_user`, `id_item`, `quantity`, `unit_price`, `unit_shipping_price`, `shipping_type`, `date_created`, `date_updated`, `is_validated`, `is_cancelled`) VALUES
(5, 1, 100002, 100007, 1, '25000.00', '0.00', 0, '2012-10-30 10:18:29', '2012-10-30 10:18:29', 0, 1),
(4, 100002, 1, 2, 1, '65.00', '46.00', 0, '2012-10-30 10:16:18', '2012-10-30 10:16:18', 0, 1),
(6, 100002, 1, 1, 1, '837.00', '30.00', 0, '2012-10-30 10:18:09', '2012-10-30 10:18:09', 0, 1),
(7, 100002, 1, 1, 1, '837.00', '30.00', 0, '2012-10-30 10:19:43', '2012-10-30 10:19:43', 1, 0),
(8, 1, 100002, 100008, 1, '69.99', '3.99', 0, '2012-11-08 09:16:42', '0000-00-00 00:00:00', 0, 0);
--
-- Base de données: `komu7dh_komushop`
--
CREATE DATABASE `komu7dh_komushop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `komu7dh_komushop`;

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id auto incrémenté',
  `id_user` int(10) unsigned NOT NULL COMMENT 'id user',
  `keywords` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` decimal(10,2) unsigned NOT NULL COMMENT 'prix TTC',
  `unit_shipping_price` decimal(10,2) unsigned NOT NULL COMMENT 'Frais de port',
  `shipping_type` tinyint(10) unsigned DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'état de l''objet : neuf, occasion, mauvais état',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date_dernière modification',
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `keywords` (`keywords`),
  KEY `state` (`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100009 ;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`id`, `id_user`, `keywords`, `description`, `unit_price`, `unit_shipping_price`, `shipping_type`, `state`, `last_modified`, `date_created`) VALUES
(1, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '837.00', '30.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(2, 100002, 'PSP Final Fantasy Advent Children', 'un grand jeu pour la plateforme de Sony', '65.00', '46.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(3, 100002, 'PSP Final Fantasy Advent Children', 'Un jeu d''aventure Ã  vous couper le souffle', '524.00', '65.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(4, 100002, 'xbox 360 Gears of war', 'un grand jeu pour la plateforme de Sony', '345.00', '59.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(5, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '569.00', '78.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(6, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '26.00', '88.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(7, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '73.00', '8.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(8, 100002, 'ps3 uncharted 3', 'Meilleur jeux FPS de l''annÃ©e', '594.00', '38.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(9, 100002, 'xbox 360 Gears of war', 'un grand jeu pour la plateforme de Sony', '290.00', '18.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(10, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '750.00', '95.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(11, 100002, 'wii Mario Kart', 'un grand jeu pour la plateforme de Sony', '586.00', '14.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(12, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '794.00', '36.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(13, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '777.00', '55.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(14, 100002, 'xbox 360 Gears of war', 'un grand jeu pour la plateforme de Sony', '249.00', '26.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(15, 100002, 'PSP Final Fantasy Advent Children', 'un grand jeu pour la plateforme de Sony', '923.00', '44.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(16, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '822.00', '8.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(17, 100002, 'PSP Final Fantasy Advent Children', 'TrÃ¨s bon jeu de voiture', '594.00', '44.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(18, 100002, 'ps3 uncharted 3', 'Meilleur jeux FPS de l''annÃ©e', '723.00', '42.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(19, 100002, 'xbox 360 Gears of war', 'Un jeu d''aventure Ã  vous couper le souffle', '526.00', '12.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(20, 100002, 'wii Mario Kart', 'TrÃ¨s bon jeu de voiture', '931.00', '39.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(21, 100002, 'wii Mario Kart', 'Meilleur jeux FPS de l''annÃ©e', '759.00', '14.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(22, 100002, 'wii Mario Kart', 'TrÃ¨s bon jeu de voiture', '216.00', '64.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(23, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '518.00', '92.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(24, 100002, 'wii Mario Kart', 'Meilleur jeux FPS de l''annÃ©e', '105.00', '6.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(25, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '311.00', '30.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(26, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '759.00', '47.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(27, 100002, 'PSP Final Fantasy Advent Children', 'Un jeu d''aventure Ã  vous couper le souffle', '347.00', '61.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(28, 100002, 'wii Mario Kart', 'un grand jeu pour la plateforme de Sony', '209.00', '99.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(29, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '223.00', '17.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(30, 100002, 'wii Mario Kart', 'Meilleur jeux FPS de l''annÃ©e', '13.00', '68.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(31, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '237.00', '8.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(32, 100002, 'xbox 360 Gears of war', 'Un jeu d''aventure Ã  vous couper le souffle', '359.00', '96.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(33, 100002, 'xbox 360 Gears of war', 'Un jeu d''aventure Ã  vous couper le souffle', '284.00', '87.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(34, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '599.00', '67.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(35, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '659.00', '36.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(36, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '715.00', '90.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(37, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '462.00', '72.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(38, 100002, 'xbox 360 Gears of war', 'Un jeu d''aventure Ã  vous couper le souffle', '713.00', '30.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(39, 100002, 'wii Mario Kart', 'Meilleur jeux FPS de l''annÃ©e', '639.00', '30.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(40, 100002, 'wii Mario Kart', 'TrÃ¨s bon jeu de voiture', '406.00', '1.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(41, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '69.00', '79.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(42, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '849.00', '97.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(43, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '638.00', '70.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(44, 100002, 'ps3 uncharted 3', 'Meilleur jeux FPS de l''annÃ©e', '353.00', '83.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(45, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '762.00', '71.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(46, 100002, 'wii Mario Kart', 'Un jeu d''aventure Ã  vous couper le souffle', '775.00', '27.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(47, 100002, 'wii Mario Kart', 'TrÃ¨s bon jeu de voiture', '768.00', '44.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(48, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '631.00', '42.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(49, 100002, 'wii Mario Kart', 'un grand jeu pour la plateforme de Sony', '870.00', '61.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(50, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '456.00', '46.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(51, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '616.00', '92.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(54, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '789.00', '72.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(55, 100002, 'xbox 360 Gears of war', 'Un jeu d''aventure Ã  vous couper le souffle', '621.00', '82.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(56, 100002, 'wii Mario Kart', 'Un jeu d''aventure Ã  vous couper le souffle', '278.00', '91.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(57, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '367.00', '80.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(58, 100002, 'PSP Final Fantasy Advent Children', 'TrÃ¨s bon jeu de voiture', '835.00', '29.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(59, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '343.00', '60.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(60, 100002, 'PSP Final Fantasy Advent Children', 'un grand jeu pour la plateforme de Sony', '491.00', '48.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(61, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '521.00', '22.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(62, 100002, 'wii Mario Kart', 'Un jeu d''aventure Ã  vous couper le souffle', '610.00', '99.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(63, 100002, 'PSP Final Fantasy Advent Children', 'TrÃ¨s bon jeu de voiture', '624.00', '86.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(64, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '448.00', '19.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(65, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '917.00', '13.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(67, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '148.00', '94.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(68, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '671.00', '71.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(69, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '457.00', '19.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(70, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '854.00', '12.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(71, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '488.00', '80.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(72, 100002, 'ps3 uncharted 3', 'Meilleur jeux FPS de l''annÃ©e', '909.00', '63.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(73, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '683.00', '92.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(74, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '769.00', '2.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(75, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '354.00', '17.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(76, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '903.00', '29.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(77, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '390.00', '97.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(78, 100002, 'wii Mario Kart', 'Meilleur jeux FPS de l''annÃ©e', '860.00', '80.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(79, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '993.00', '7.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(80, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '451.00', '53.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(81, 100002, 'PSP Final Fantasy Advent Children', 'Meilleur jeux FPS de l''annÃ©e', '269.00', '68.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(82, 100002, 'wii Mario Kart', 'un grand jeu pour la plateforme de Sony', '787.00', '77.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(83, 100002, 'PSP Final Fantasy Advent Children', 'Un jeu d''aventure Ã  vous couper le souffle', '345.00', '36.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(84, 100002, 'xbox 360 Gears of war', 'Meilleur jeux FPS de l''annÃ©e', '132.00', '7.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(85, 100002, 'PSP Final Fantasy Advent Children', 'TrÃ¨s bon jeu de voiture', '960.00', '78.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(86, 100002, 'PSP Final Fantasy Advent Children', 'Un jeu d''aventure Ã  vous couper le souffle', '649.00', '43.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(87, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '781.00', '62.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(88, 100002, 'xbox 360 Gears of war', 'un grand jeu pour la plateforme de Sony', '280.00', '60.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(89, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '244.00', '26.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(90, 100002, 'wii Mario Kart', 'TrÃ¨s bon jeu de voiture', '112.00', '70.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(91, 100002, 'wii Mario Kart', 'Un jeu d''aventure Ã  vous couper le souffle', '518.00', '37.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(92, 100002, 'wii Mario Kart', 'TrÃ¨s bon jeu de voiture', '889.00', '41.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(93, 100002, 'ps3 uncharted 3', 'un grand jeu pour la plateforme de Sony', '208.00', '64.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(94, 100002, 'ps3 uncharted 3', 'TrÃ¨s bon jeu de voiture', '288.00', '26.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(95, 100002, 'ps3 uncharted 3', 'Un jeu d''aventure Ã  vous couper le souffle', '392.00', '24.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(96, 100002, 'xbox 360 Gears of war', 'TrÃ¨s bon jeu de voiture', '147.00', '92.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(97, 100002, 'xbox 360 Gears of war', 'Un jeu d''aventure Ã  vous couper le souffle', '21.00', '59.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(98, 100002, 'ps3 uncharted 3', 'Meilleur jeux FPS de l''annÃ©e', '708.00', '59.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(99, 100002, 'wii Mario Kart', 'un grand jeu pour la plateforme de Sony', '481.00', '26.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(100, 100002, 'PSP Final Fantasy Advent Children', 'TrÃ¨s bon jeu de voiture', '637.00', '38.00', 0, 0, '2012-10-30 09:21:05', '2012-01-03 16:31:22'),
(100007, 1, 'bmw 335i', 'bmw 306 cv', '25000.00', '0.00', 0, 1, '2012-10-30 09:24:54', '2012-10-30 09:24:54'),
(100008, 1, 'ps2 shadow hearts from the new world version us', 'jeu neuf version us', '69.99', '3.99', 3, 0, '2012-10-31 11:48:49', '2012-10-31 11:48:49');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
