-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 21 sep. 2024 à 11:04
-- Version du serveur : 8.3.0
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `test`;

-- --------------------------------------------------------

--
-- Structure de la table `badgeuse_badge`
--

DROP TABLE IF EXISTS `badgeuse_badge`;
CREATE TABLE IF NOT EXISTS `badgeuse_badge` (
  `id_badge` int NOT NULL AUTO_INCREMENT,
  `id_employer` int NOT NULL,
  `badge_date_entree` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `badge_date_sortie` datetime DEFAULT NULL,
  `badge_date_entree_dernier` datetime DEFAULT NULL,
  `badge_date_sortie_dernier` datetime DEFAULT NULL,
  `cron` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_badge`),
  KEY `fk_badge_employer` (`id_employer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `badgeuse_contrat`
--

DROP TABLE IF EXISTS `badgeuse_contrat`;
CREATE TABLE IF NOT EXISTS `badgeuse_contrat` (
  `id_contrat` int NOT NULL AUTO_INCREMENT,
  `id_etablissement` int NOT NULL,
  `id_employer` int NOT NULL,
  `vol_h` int NOT NULL,
  `type_contrat` text NOT NULL,
  PRIMARY KEY (`id_contrat`),
  KEY `fk_contrat_etablissement` (`id_etablissement`),
  KEY `fk_contrat_employer` (`id_employer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `badgeuse_employer`
--

DROP TABLE IF EXISTS `badgeuse_employer`;
CREATE TABLE IF NOT EXISTS `badgeuse_employer` (
  `id_employer` int NOT NULL AUTO_INCREMENT,
  `employer_nom` text NOT NULL,
  `employer_prenom` text NOT NULL,
  `employer_pin` varchar(4) NOT NULL,
  PRIMARY KEY (`id_employer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `badgeuse_etablissements`
--

DROP TABLE IF EXISTS `badgeuse_etablissements`;
CREATE TABLE IF NOT EXISTS `badgeuse_etablissements` (
  `id_etablissement` int NOT NULL AUTO_INCREMENT,
  `etablissement_nom` text NOT NULL,
  `etablissement_code` text NOT NULL,
  PRIMARY KEY (`id_etablissement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `badgeuse_planning`
--

DROP TABLE IF EXISTS `badgeuse_planning`;
CREATE TABLE IF NOT EXISTS `badgeuse_planning` (
  `id_planning` int NOT NULL AUTO_INCREMENT,
  `id_employer` int NOT NULL,
  `planning_entree` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `planning_sortie` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `planning_semaine` int NOT NULL,
  `planning_annee` year NOT NULL,
  `abs` tinyint(1) NOT NULL DEFAULT '0',
  `abs_type` varchar(2) NOT NULL DEFAULT 'NO',
  PRIMARY KEY (`id_planning`),
  KEY `fk_planning_employer` (`id_employer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `badgeuse_profil`
--

DROP TABLE IF EXISTS `badgeuse_profil`;
CREATE TABLE IF NOT EXISTS `badgeuse_profil` (
  `id_profil` int NOT NULL AUTO_INCREMENT,
  `profil_login` text NOT NULL,
  `profil_mdp` text NOT NULL,
  `profil_droit` int NOT NULL,
  PRIMARY KEY (`id_profil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `badgeuse_badge`
--
ALTER TABLE `badgeuse_badge`
  ADD CONSTRAINT `fk_badge_employer` FOREIGN KEY (`id_employer`) REFERENCES `badgeuse_employer` (`id_employer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `badgeuse_contrat`
--
ALTER TABLE `badgeuse_contrat`
  ADD CONSTRAINT `fk_contrat_employer` FOREIGN KEY (`id_employer`) REFERENCES `badgeuse_employer` (`id_employer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contrat_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `badgeuse_etablissements` (`id_etablissement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `badgeuse_planning`
--
ALTER TABLE `badgeuse_planning`
  ADD CONSTRAINT `fk_planning_employer` FOREIGN KEY (`id_employer`) REFERENCES `badgeuse_employer` (`id_employer`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
