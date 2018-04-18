-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3312
-- Généré le :  mer. 18 avr. 2018 à 16:27
-- Version du serveur :  10.1.31-MariaDB
-- Version de PHP :  7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbdatabase`
--

-- --------------------------------------------------------

--
-- Structure de la table `implique`
--

CREATE TABLE `implique` (
  `IdImpl` int(11) NOT NULL,
  `IdProjet` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Role` int(1) NOT NULL COMMENT '0: co-financeur - 1: parrain'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `IdProjet` int(11) NOT NULL,
  `IdStruct` int(11) NOT NULL,
  `IdRes` int(11) NOT NULL,
  `IdRep` int(11) NOT NULL,
  `DateDep` date NOT NULL,
  `Expose` text NOT NULL,
  `DateDeb` date NOT NULL,
  `Duree` int(11) NOT NULL,
  `Lieu` varchar(255) NOT NULL,
  `Aide` int(11) NOT NULL,
  `Budget` int(11) NOT NULL,
  `Fin` text NOT NULL,
  `InteretGeneral` int(1) NOT NULL,
  `Domaine` text NOT NULL,
  `Mecenat` int(1) NOT NULL,
  `Fiscal` int(1) NOT NULL,
  `Valorisation` varchar(255) DEFAULT NULL,
  `Document` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `representant`
--

CREATE TABLE `representant` (
  `IdRep` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Qualite` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `responsable`
--

CREATE TABLE `responsable` (
  `IdRes` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Position` varchar(255) NOT NULL,
  `Adresse` varchar(255) NOT NULL,
  `CodePostal` varchar(255) NOT NULL,
  `Ville` varchar(255) NOT NULL,
  `Tel` varchar(255) NOT NULL,
  `Courriel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `structure`
--

CREATE TABLE `structure` (
  `IdStruct` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Adresse` varchar(255) NOT NULL,
  `CodePostal` varchar(255) NOT NULL,
  `Ville` varchar(255) NOT NULL,
  `Raison` text NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Site` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `organisme` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adr` varchar(255) NOT NULL,
  `tel` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_temp`
--

CREATE TABLE `user_temp` (
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `organisme` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adr` varchar(255) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `implique`
--
ALTER TABLE `implique`
  ADD PRIMARY KEY (`IdImpl`),
  ADD KEY `IdProjet` (`IdProjet`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`IdProjet`),
  ADD KEY `IdStruct` (`IdStruct`),
  ADD KEY `IdRes` (`IdRes`),
  ADD KEY `IdRep` (`IdRep`);

--
-- Index pour la table `representant`
--
ALTER TABLE `representant`
  ADD PRIMARY KEY (`IdRep`);

--
-- Index pour la table `responsable`
--
ALTER TABLE `responsable`
  ADD PRIMARY KEY (`IdRes`);

--
-- Index pour la table `structure`
--
ALTER TABLE `structure`
  ADD PRIMARY KEY (`IdStruct`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`login`);

--
-- Index pour la table `user_temp`
--
ALTER TABLE `user_temp`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `implique`
--
ALTER TABLE `implique`
  MODIFY `IdImpl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `IdProjet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `representant`
--
ALTER TABLE `representant`
  MODIFY `IdRep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `responsable`
--
ALTER TABLE `responsable`
  MODIFY `IdRes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `structure`
--
ALTER TABLE `structure`
  MODIFY `IdStruct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `implique`
--
ALTER TABLE `implique`
  ADD CONSTRAINT `implique_ibfk_1` FOREIGN KEY (`IdProjet`) REFERENCES `projet` (`IdProjet`);

--
-- Contraintes pour la table `projet`
--
ALTER TABLE `projet`
  ADD CONSTRAINT `projet_ibfk_1` FOREIGN KEY (`IdStruct`) REFERENCES `structure` (`IdStruct`),
  ADD CONSTRAINT `projet_ibfk_2` FOREIGN KEY (`IdRes`) REFERENCES `responsable` (`IdRes`),
  ADD CONSTRAINT `projet_ibfk_3` FOREIGN KEY (`IdRep`) REFERENCES `representant` (`IdRep`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
