-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Dim 17 Avril 2022 à 13:05
-- Version du serveur :  5.7.29
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `in20b1012`
--
CREATE DATABASE IF NOT EXISTS `in20b1012` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `in20b1012`;

-- --------------------------------------------------------

--
-- Structure de la table `Caracteriser`
--

CREATE TABLE `Caracteriser` (
  `did` int(11) NOT NULL,
  `tid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Caracteriser`
--

INSERT INTO `Caracteriser` (`did`, `tid`) VALUES
(1, 2),
(2, 3),
(2, 9),
(3, 6),
(4, 3),
(4, 4),
(5, 5),
(5, 10),
(6, 8),
(7, 3),
(8, 11),
(9, 7),
(10, 11),
(10, 13),
(11, 11),
(11, 13),
(12, 12),
(13, 13),
(14, 13),
(15, 14),
(16, 14),
(16, 15),
(17, 12),
(18, 13),
(18, 11),
(19, 13),
(19, 11);

-- --------------------------------------------------------

--
-- Structure de la table `Depense`
--

CREATE TABLE `Depense` (
  `did` int(11) NOT NULL,
  `dateHeure` int(11) NOT NULL,
  `montant` decimal(10,0) NOT NULL,
  `libelle` varchar(500) NOT NULL,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Depense`
--

INSERT INTO `Depense` (`did`, `dateHeure`, `montant`, `libelle`, `gid`, `uid`) VALUES
(1, 1649423210, '50', 'restaurant Italien', 1, 1),
(2, 1649422212, '25', 'croissant à la boulangerie', 1, 2),
(3, 1649422212, '20', 'glace', 1, 3),
(4, 1649250732, '35', 'croissant + pain aux chocolats', 1, 5),
(5, 1649337132, '300', 'visite d\'un musée d\'art', 1, 4),
(6, 1649423532, '45', 'pause crepee ', 1, 1),
(7, 1649139132, '42', 'baguette et croissant', 1, 3),
(8, 1649225532, '96', 'diner dans un resto ', 1, 2),
(9, 1649405532, '10', 'souvenir ', 1, 5),
(10, 1649423172, '100', 'nouveau costume pour moi (freddy)', 2, 6),
(11, 1649257572, '200', 'nouvelle cameras', 2, 6),
(12, 1649427792, '50', 'réparation du générateur', 2, 6),
(13, 1649514192, '30', 'nouvelle corde pour ma guitare', 2, 7),
(14, 1649514192, '200', 'nouvelle exosquelette', 2, 7),
(15, 1649168592, '150', '50 boites de pate à pizza', 2, 8),
(16, 1649229792, '65', 'kits à pizza', 2, 8),
(17, 1649352192, '200', 'réparation du four', 2, 8),
(18, 1649434992, '100', 'nouveaux rideau', 2, 9),
(19, 1649330592, '50', 'nouveau crochet', 2, 9);

-- --------------------------------------------------------

--
-- Structure de la table `Facture`
--

CREATE TABLE `Facture` (
  `fid` int(11) NOT NULL,
  `scan` varchar(50) NOT NULL DEFAULT 'fichier non trouver',
  `did` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Facture`
--

INSERT INTO `Facture` (`fid`, `scan`, `did`) VALUES
(1, 'fichier non trouver', 1),
(2, 'fichier non trouver', 2),
(3, 'fichier non trouver', 3),
(4, 'fichier non trouver', 4),
(5, 'fichier non trouver', 5),
(6, 'fichier non trouver', 6),
(7, 'fichier non trouver', 7),
(8, 'fichier non trouver', 8),
(9, 'fichier non trouver', 9),
(10, 'fichier non trouver', 10),
(11, 'fichier non trouver', 11),
(12, 'fichier non trouver', 12),
(13, 'fichier non trouver', 13),
(14, 'fichier non trouver', 14),
(15, 'fichier non trouver', 15),
(16, 'fichier non trouver', 16),
(17, 'fichier non trouver', 17),
(18, 'fichier non trouver', 18),
(19, 'fichier non trouver', 19);

-- --------------------------------------------------------

--
-- Structure de la table `Groupe`
--

CREATE TABLE `Groupe` (
  `gid` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `devise` varchar(1) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Groupe`
--

INSERT INTO `Groupe` (`gid`, `nom`, `devise`, `uid`) VALUES
(1, 'voyage entre amis', '€', 1),
(2, 'pizzeria', '$', 6);

-- --------------------------------------------------------

--
-- Structure de la table `Participer`
--

CREATE TABLE `Participer` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `estConfirme` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Participer`
--

INSERT INTO `Participer` (`uid`, `gid`, `estConfirme`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 2, 1),
(7, 2, 1),
(8, 2, 1),
(9, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Tag`
--

CREATE TABLE `Tag` (
  `tid` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `gid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Tag`
--

INSERT INTO `Tag` (`tid`, `tag`, `gid`) VALUES
(1, 'italien', 1),
(2, 'resto', 1),
(3, 'croissant', 1),
(4, 'pain', 1),
(5, 'musée', 1),
(6, 'glace', 1),
(7, 'souvenir', 1),
(8, 'pause', 1),
(9, 'boulangerie', 1),
(10, 'visite', 1),
(11, 'nouveau', 2),
(12, 'réparation', 2),
(13, 'nouvelle', 2),
(14, 'pizza', 2),
(15, 'kits', 2);

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `uid` int(11) NOT NULL,
  `courriel` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `motPasse` varchar(50) NOT NULL,
  `estActif` decimal(1,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`uid`, `courriel`, `nom`, `prenom`, `motPasse`, `estActif`) VALUES
(1, 'samuel@gmail.com', 'Jacquemin', 'Samuel', 'Sam12345', '0'),
(2, 'audric@gmail.com', 'Clemeur', 'Audric', 'Ac123456', '0'),
(3, 'antoine@gmail.com', 'Magermans', 'Antoine', 'Ant12345', '0'),
(4, 'tim@gmail.com', 'Pankerts', 'Tim', 'Tim12345', '0'),
(5, 'brian@gmail.com', 'Hogan', 'brian', 'bri23456', '0'),
(6, 'freddy@gmail.com', 'Fazbear', 'Freddy', 'Fred1234', '0'),
(7, 'bonnie@gmail.com', 'Bonbon', 'Bonnie', 'Bon12345', '0'),
(8, 'chica@gmail.com', 'The Chicken', 'Chica', 'Pizza345', '0'),
(9, 'foxy@gmail.com', 'The Pirate', 'Foxy', 'Foxy2345', '0');

-- --------------------------------------------------------

--
-- Structure de la table `Versement`
--

CREATE TABLE `Versement` (
  `uid` int(11) NOT NULL,
  `uid_1` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `dateHeure` int(11) NOT NULL,
  `montant` double NOT NULL,
  `estConfirmer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Versement`
--

INSERT INTO `Versement` (`uid`, `uid_1`, `gid`, `dateHeure`, `montant`, `estConfirmer`) VALUES
(9, 6, 2, 1649589538, 7.5, 0),
(7, 6, 2, 1649589637, 56.25, 0),
(2, 4, 1, 1649589656, 3.6, 0),
(5, 4, 1, 1649589669, 79.6, 0),
(9, 8, 2, 1649589786, 128.75, 0),
(1, 4, 1, 1649590113, 29.6, 0),
(3, 4, 1, 1649940703, 62.6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tblMembersNewsletter`
--

CREATE TABLE `tblMembersNewsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Depense`
--
ALTER TABLE `Depense`
  ADD PRIMARY KEY (`did`);

--
-- Index pour la table `Facture`
--
ALTER TABLE `Facture`
  ADD PRIMARY KEY (`fid`);

--
-- Index pour la table `Groupe`
--
ALTER TABLE `Groupe`
  ADD PRIMARY KEY (`gid`);

--
-- Index pour la table `Tag`
--
ALTER TABLE `Tag`
  ADD PRIMARY KEY (`tid`);

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`uid`);

--
-- Index pour la table `Versement`
--
ALTER TABLE `Versement`
  ADD PRIMARY KEY (`dateHeure`);

--
-- Index pour la table `tblMembersNewsletter`
--
ALTER TABLE `tblMembersNewsletter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Depense`
--
ALTER TABLE `Depense`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT pour la table `Facture`
--
ALTER TABLE `Facture`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `Groupe`
--
ALTER TABLE `Groupe`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `Tag`
--
ALTER TABLE `Tag`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `tblMembersNewsletter`
--
ALTER TABLE `tblMembersNewsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
