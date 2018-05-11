-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3312
-- Généré le :  ven. 11 mai 2018 à 16:26
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

--
-- Déchargement des données de la table `implique`
--

INSERT INTO `implique` (`IdImpl`, `IdProjet`, `Nom`, `Prenom`, `Role`) VALUES
(1, 1, 'Bialo', 'Ian', 0),
(2, 1, 'Teyssandier', 'Clément', 0),
(3, 1, 'Holzhammer', 'David', 0),
(4, 1, 'Ober', 'Ober', 0),
(5, 1, 'Szpynda', 'Gab', 1);

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `IdProjet` int(11) NOT NULL,
  `IdStruct` int(11) NOT NULL,
  `IdRes` int(11) NOT NULL,
  `IdRep` int(11) NOT NULL,
  `IdSuivi` int(11) NOT NULL,
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

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`IdProjet`, `IdStruct`, `IdRes`, `IdRep`, `IdSuivi`, `DateDep`, `Expose`, `DateDeb`, `Duree`, `Lieu`, `Aide`, `Budget`, `Fin`, `InteretGeneral`, `Domaine`, `Mecenat`, `Fiscal`, `Valorisation`, `Document`) VALUES
(1, 1, 1, 1, 1, '2018-04-25', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur quis volutpat nulla. Pellentesque tincidunt leo venenatis felis sollicitudin metus.', '2018-04-09', 2, 'Chez Zguegz', 1300, 1300, 'Pour les befors', 1, 'Before', 1, 1, 'C\''est bien', 0),
(6, 6, 6, 6, 6, '2018-04-24', 'Ceci est un test', '2018-07-15', 12, 'Chez moi', 12, 12, 'Oui les fins oui', 1, 'Oui le domaine oui', 1, 1, NULL, 2);

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

--
-- Déchargement des données de la table `representant`
--

INSERT INTO `representant` (`IdRep`, `Nom`, `Prenom`, `Qualite`) VALUES
(1, 'Rem', 'Zguegz', 'Goyot'),
(6, 'RepJohn', 'RepJohnny', 'http://localhost:8012/DBProject/');

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

--
-- Déchargement des données de la table `responsable`
--

INSERT INTO `responsable` (`IdRes`, `Nom`, `Prenom`, `Position`, `Adresse`, `CodePostal`, `Ville`, `Tel`, `Courriel`) VALUES
(1, 'Berhu', 'Baptiste', 'Goyot', '2 Ter Boulevard Charlemagne', '54000', 'Nancy', '0606060606', 'ian.bialo9@etu.univ-lorraine.fr'),
(6, 'ResJohn', 'ResJohnny', 'oui la position oui', '5, rue des johns', '55555', 'JohnCity', '0606060606', 'ian.bialo@demathieu-bard.fr');

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
  `Site` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `structure`
--

INSERT INTO `structure` (`IdStruct`, `Nom`, `Adresse`, `CodePostal`, `Ville`, `Raison`, `Type`, `Site`) VALUES
(1, 'Amphux', '2 Ter Boulevard Charlemagne', '54000', 'Nancy', 'Ceci est une raison', 'Une association', 'http://localhost:8012/s3a_s02_bialo_fraschini_holzhammer_tey'),
(6, 'Unixs', '5, rue des johns', '55555', 'JohnCity', 'Oui la raison oui', 'Une institution', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `suivi`
--

CREATE TABLE `suivi` (
  `IdSuivi` int(11) NOT NULL,
  `Chrono` int(11) DEFAULT NULL,
  `DateRep` date NOT NULL,
  `Montant` int(11) NOT NULL,
  `DateEnvoiConv` date NOT NULL,
  `DateRecepConv` date NOT NULL,
  `DateRecepRecu` date NOT NULL,
  `DateEnvoiCheque` date NOT NULL,
  `Observations` text,
  `Document` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `suivi`
--

INSERT INTO `suivi` (`IdSuivi`, `Chrono`, `DateRep`, `Montant`, `DateEnvoiConv`, `DateRecepConv`, `DateRecepRecu`, `DateEnvoiCheque`, `Observations`, `Document`) VALUES
(1, NULL, '2018-05-11', 0, '2018-05-11', '2018-05-11', '2018-05-11', '2018-05-11', NULL, 0),
(6, NULL, '2018-05-11', 0, '2018-05-11', '2018-05-11', '2018-05-11', '2018-05-11', NULL, 0);

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
  ADD KEY `IdRep` (`IdRep`),
  ADD KEY `IdSuivi` (`IdSuivi`);

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
-- Index pour la table `suivi`
--
ALTER TABLE `suivi`
  ADD PRIMARY KEY (`IdSuivi`);

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
  MODIFY `IdImpl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `IdProjet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `representant`
--
ALTER TABLE `representant`
  MODIFY `IdRep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `responsable`
--
ALTER TABLE `responsable`
  MODIFY `IdRes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `structure`
--
ALTER TABLE `structure`
  MODIFY `IdStruct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `suivi`
--
ALTER TABLE `suivi`
  MODIFY `IdSuivi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `projet_ibfk_3` FOREIGN KEY (`IdRep`) REFERENCES `representant` (`IdRep`),
  ADD CONSTRAINT `projet_ibfk_4` FOREIGN KEY (`IdSuivi`) REFERENCES `suivi` (`IdSuivi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
