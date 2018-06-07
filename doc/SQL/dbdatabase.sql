-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3312
-- Généré le :  jeu. 07 juin 2018 à 10:04
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
(12, 16, 'Conom1', 'Coprenom1', 0),
(13, 16, 'Conom2', 'Coprenom2', 0),
(14, 16, 'Conom3', 'Coprenom3', 0);

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
  `Document` int(1) NOT NULL,
  `Nouv` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`IdProjet`, `IdStruct`, `IdRes`, `IdRep`, `IdSuivi`, `DateDep`, `Expose`, `DateDeb`, `Duree`, `Lieu`, `Aide`, `Budget`, `Fin`, `InteretGeneral`, `Domaine`, `Mecenat`, `Fiscal`, `Valorisation`, `Document`, `Nouv`) VALUES
(1, 1, 1, 1, 1, '2018-04-25', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur quis volutpat nulla. Pellentesque tincidunt leo venenatis felis sollicitudin metus.', '2018-05-16', 12, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 1300, 1300, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 1, 1, NULL, 0, 0),
(6, 6, 6, 6, 6, '2018-04-24', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur quis volutpat nulla. Pellentesque tincidunt leo venenatis felis sollicitudin metus.', '2018-07-15', 12, 'Chez moi', 12, 12, 'Oui les fins oui', 1, 'Oui le domaine oui', 1, 1, NULL, 2, 0),
(16, 18, 18, 18, 16, '2018-05-15', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur quis volutpat nulla. Pellentesque tincidunt leo venenatis felis sollicitudin metus.', '2018-10-11', 5, 'quelque part', 5, 5, 'pour plein de choses', 1, 'Ceci est le domaine', 1, 1, 'c&#39;est cool', 2, 0);

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
(1, 'Rem', 'Zguegz', 'Gogo'),
(6, 'Ober', 'Ober', 'http://localhost:8012/DBProject/'),
(18, 'Ian', 'Bialo', 'oui la qualité');

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
(1, 'Berhu', 'Baptiste', 'Gogo', '2 Ter Boulevard Charlemagne', '54000', 'Nancy', '0606060606', 'ian.bialo9@etu.univ-lorraine.fr'),
(6, 'Gan', 'Dayv', 'oui la position oui', '5, rue des johns', '55555', 'JohnCity', '0606060606', 'ian.bialo@demathieu-bard.fr'),
(18, 'Teyss', 'Clem', 'la position ouai', '5, rue des johns', '55555', 'JohnCity', '0606060606', 'ian.bialo@demathieu-bard.fr');

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
(1, 'Amphux', '2 Ter Boulevard Charlemagne', '54000', 'Nancy', 'Ceci est une raison', 'Une association', 'http://iut-charlemagne.univ-lorraine.fr/'),
(6, 'Unix', '5, rue des johns', '55555', 'JohnCity', 'Oui la raison oui', 'Une institution', NULL),
(18, 'Je suis une structure', '5, rue des johns', '55555', 'JohnCity', 'je suis la raison', 'Une institution', 'http://localhost:8012/DBProject/DBProject/');

-- --------------------------------------------------------

--
-- Structure de la table `suivi`
--

CREATE TABLE `suivi` (
  `IdSuivi` int(11) NOT NULL,
  `Chrono` varchar(11) NOT NULL DEFAULT '0',
  `Montant` int(11) NOT NULL,
  `DateRep` date DEFAULT NULL,
  `DateEnvoiConv` date DEFAULT NULL,
  `DateRecepConv` date DEFAULT NULL,
  `DateRecepRecu` date DEFAULT NULL,
  `DateEnvoiCheque` date DEFAULT NULL,
  `Observations` text,
  `Document` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `suivi`
--

INSERT INTO `suivi` (`IdSuivi`, `Chrono`, `Montant`, `DateRep`, `DateEnvoiConv`, `DateRecepConv`, `DateRecepRecu`, `DateEnvoiCheque`, `Observations`, `Document`) VALUES
(1, '0', 0, NULL, '2018-06-20', '2018-06-10', '2018-07-12', '2018-09-13', NULL, 1),
(6, '2018_1', 0, '2018-05-19', NULL, NULL, NULL, NULL, NULL, 3),
(16, '0', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `droit` int(1) NOT NULL,
  `dateCreation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`login`, `mdp`, `droit`, `dateCreation`) VALUES
('default@admin.fr', '$2y$12$BpTza7YIfXvjr1IV.GEm2eSoW8KffhVnSl9uZqRA2c4V0CpPf.2aS', 2, '2018-05-31');

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `implique`
--
ALTER TABLE `implique`
  MODIFY `IdImpl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `IdProjet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `representant`
--
ALTER TABLE `representant`
  MODIFY `IdRep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `responsable`
--
ALTER TABLE `responsable`
  MODIFY `IdRes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `structure`
--
ALTER TABLE `structure`
  MODIFY `IdStruct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `suivi`
--
ALTER TABLE `suivi`
  MODIFY `IdSuivi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
