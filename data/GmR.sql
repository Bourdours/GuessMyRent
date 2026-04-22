-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 17 avr. 2026 à 11:57
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `GmR`
--

-- --------------------------------------------------------

--
-- Structure de la table `API_ESTATE`
--

CREATE TABLE `API_ESTATE` (
  `id_api` int(11) NOT NULL,
  `api_external_id` int(11) NOT NULL,
  `id_estate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ESTATE`
--

CREATE TABLE `ESTATE` (
  `id_estate` int(11) NOT NULL,
  `rent` int(11) NOT NULL,
  `is_charges_included` tinyint(1) DEFAULT NULL,
  `adress` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` int(10) NOT NULL,
  `gps_y` decimal(15,8) DEFAULT NULL,
  `gps_x` decimal(15,8) DEFAULT NULL,
  `square_meters` decimal(15,2) NOT NULL,
  `room` int(11) DEFAULT NULL,
  `chamber` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `image1` varchar(50) NOT NULL,
  `image2` varchar(50) DEFAULT NULL,
  `image3` varchar(50) DEFAULT NULL,
  `image4` varchar(50) DEFAULT NULL,
  `id_status` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ESTATE_MODIFICATION`
--

CREATE TABLE `ESTATE_MODIFICATION` (
  `id_modification` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `modification` varchar(250) DEFAULT NULL,
  `id_estate` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `GAME`
--

CREATE TABLE `GAME` (
  `id_game` int(11) NOT NULL,
  `game_result` decimal(15,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `guess` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_estate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `MESSAGE`
--

CREATE TABLE `MESSAGE` (
  `id_message` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `objet` varchar(50) DEFAULT NULL,
  `content` varchar(500) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `STATUS`
--

CREATE TABLE `STATUS` (
  `id_status` int(11) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `STATUS`
--

INSERT INTO `STATUS` (`id_status`, `label`) VALUES
(1, 'déposé'),
(2, 'jouable'),
(3, 'archivé'),
(4, 'correction'),
(5, 'actualisation');

-- --------------------------------------------------------

--
-- Structure de la table `TYPE`
--

CREATE TABLE `TYPE` (
  `id_type` int(11) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `TYPE`
--

INSERT INTO `TYPE` (`id_type`, `label`) VALUES
(1, 'appartement'),
(2, 'maison'),
(3, 'collocation'),
(4, 'château'),
(5, 'péniche'),
(6, 'loft'),
(7, 'ferme'),
(8, 'studio');

-- --------------------------------------------------------

--
-- Structure de la table `USER`
--

CREATE TABLE `USER` (
  `id_user` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(112) NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `avatar` varchar(50) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Index pour la table `API_ESTATE`
--
ALTER TABLE `API_ESTATE`
  ADD PRIMARY KEY (`id_api`),
  ADD UNIQUE KEY `api_external_id` (`api_external_id`),
  ADD UNIQUE KEY `id_estate` (`id_estate`);

--
-- Index pour la table `ESTATE`
--
ALTER TABLE `ESTATE`
  ADD PRIMARY KEY (`id_estate`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `fk_estate_user` (`id_user`);

--
-- Index pour la table `ESTATE_MODIFICATION`
--
ALTER TABLE `ESTATE_MODIFICATION`
  ADD PRIMARY KEY (`id_modification`),
  ADD KEY `id_estate` (`id_estate`),
  ADD KEY `fk_modif_user` (`id_user`);

--
-- Index pour la table `GAME`
--
ALTER TABLE `GAME`
  ADD PRIMARY KEY (`id_game`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `GAME_ibfk_2` (`id_estate`);

--
-- Index pour la table `MESSAGE`
--
ALTER TABLE `MESSAGE`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `fk_message_user` (`id_user`);

--
-- Index pour la table `STATUS`
--
ALTER TABLE `STATUS`
  ADD PRIMARY KEY (`id_status`);

--
-- Index pour la table `TYPE`
--
ALTER TABLE `TYPE`
  ADD PRIMARY KEY (`id_type`);

--
-- Index pour la table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `API_ESTATE`
--
ALTER TABLE `API_ESTATE`
  MODIFY `id_api` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `ESTATE`
--
ALTER TABLE `ESTATE`
  MODIFY `id_estate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `ESTATE_MODIFICATION`
--
ALTER TABLE `ESTATE_MODIFICATION`
  MODIFY `id_modification` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `GAME`
--
ALTER TABLE `GAME`
  MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT pour la table `MESSAGE`
--
ALTER TABLE `MESSAGE`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `STATUS`
--
ALTER TABLE `STATUS`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `TYPE`
--
ALTER TABLE `TYPE`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `USER`
--
ALTER TABLE `USER`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `API_ESTATE`
--
ALTER TABLE `API_ESTATE`
  ADD CONSTRAINT `API_ESTATE_ibfk_1` FOREIGN KEY (`id_estate`) REFERENCES `ESTATE` (`id_estate`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ESTATE`
--
ALTER TABLE `ESTATE`
  ADD CONSTRAINT `ESTATE_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `STATUS` (`id_status`),
  ADD CONSTRAINT `ESTATE_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `TYPE` (`id_type`),
  ADD CONSTRAINT `ESTATE_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`),
  ADD CONSTRAINT `fk_estate_user` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`) ON DELETE SET NULL;

--
-- Contraintes pour la table `ESTATE_MODIFICATION`
--
ALTER TABLE `ESTATE_MODIFICATION`
  ADD CONSTRAINT `ESTATE_MODIFICATION_ibfk_1` FOREIGN KEY (`id_estate`) REFERENCES `ESTATE` (`id_estate`),
  ADD CONSTRAINT `ESTATE_MODIFICATION_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`),
  ADD CONSTRAINT `fk_modif_user` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `GAME`
--
ALTER TABLE `GAME`
  ADD CONSTRAINT `GAME_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`),
  ADD CONSTRAINT `GAME_ibfk_2` FOREIGN KEY (`id_estate`) REFERENCES `ESTATE` (`id_estate`) ON DELETE SET NULL;

--
-- Contraintes pour la table `MESSAGE`
--
ALTER TABLE `MESSAGE`
  ADD CONSTRAINT `MESSAGE_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`),
  ADD CONSTRAINT `fk_message_user` FOREIGN KEY (`id_user`) REFERENCES `USER` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
