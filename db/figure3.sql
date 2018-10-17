-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 05 oct. 2018 à 01:19
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
-- Base de données :  `figure3`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `title`, `description`) VALUES
(1, 'Les flips', NULL),
(2, 'Les graps', NULL),
(3, 'Les rotations', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `figure_id`, `author_id`, `content`, `created_at`) VALUES
(1, 6, 1, 'test1', '2018-10-01 00:14:45'),
(2, 5, 1, 'test1', '2018-10-04 15:34:12'),
(3, 5, 1, 'figure original', '2018-10-04 15:34:37'),
(4, 5, 1, 'test2', '2018-10-04 15:35:05'),
(5, 5, 1, 'pas mal la figure', '2018-10-04 15:35:15'),
(6, 5, 1, 'figure risquée', '2018-10-04 15:35:43'),
(7, 5, 1, 'figure test', '2018-10-04 15:35:53'),
(8, 5, 1, 'c\'est dur à reproduire', '2018-10-04 15:36:21'),
(9, 5, 1, 'revoir la figure', '2018-10-04 15:39:27'),
(10, 5, 1, 'test3', '2018-10-04 15:39:36'),
(11, 5, 1, 'test4', '2018-10-04 15:39:44'),
(12, 5, 1, 'test5', '2018-10-04 15:40:11');

-- --------------------------------------------------------

--
-- Structure de la table `figure`
--

CREATE TABLE `figure` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `video_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `figure`
--

INSERT INTO `figure` (`id`, `category_id`, `video_id`, `user_id`, `title`, `slug`, `content`, `create_at`) VALUES
(5, 1, 5, 1, 'Mute', 'mute', 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant', '2018-09-30 22:02:25'),
(6, 1, 6, 1, 'tail grab', 'tail-grab', 'saisie de la partie arrière de la planche, avec la main arrière', '2018-09-30 23:30:49'),
(7, 1, 7, 1, 'Indy', 'indy', 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière', '2018-09-30 23:53:26'),
(8, 1, 8, 1, 'front flips', 'front-flips', 'Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.', '2018-10-01 00:05:22'),
(9, 2, 9, 1, 'Seat belt', 'seat-belt', 'saisie du carre frontside à l\'arrière avec la main avant', '2018-10-04 00:37:33'),
(12, 3, 12, 1, '900', '900', 'pour deux tours et demi', '2018-10-04 14:28:18'),
(13, 3, 13, 1, '180', '180', 'désigne un demi-tour, soit 180 degrés d\'angle', '2018-10-04 14:35:23'),
(14, 1, 14, 1, 'Mac Twist', 'mac-twist', 'se confondent souvent avec certaines rotations horizontales désaxées', '2018-10-04 14:37:19'),
(15, 1, 15, 1, 'Hakon Flip', 'hakon-flip', 'se confondent souvent avec certaines rotations horizontales désaxées', '2018-10-04 15:05:10'),
(16, 2, 16, 1, 'truck driver', 'truck-driver', 'saisie du carre avant et carre arrière avec chaque main', '2018-10-04 15:07:59');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) DEFAULT NULL,
  `path` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `figure_id`, `path`) VALUES
(1, NULL, 'f157e78853d636d20fd00b761cd34ed4666d00d2.jpeg'),
(6, 5, 'bc0c32ebf3a1975546ac6b3ce49b2b687057f6e2.jpeg'),
(7, 6, '628e8dd4019e901fc053b6dc4d90d8fcdd6036d8.jpeg'),
(8, 6, '0f74346f7c57747a75a91b2a51bdc982a8e427de.jpeg'),
(9, 6, 'bbf5e900cb1fd17fedbb4a5b2d8341a0bb6e011f.jpeg'),
(10, 7, '28e9b0b18c216212c974bd975334dfb2a66dcd82.jpeg'),
(11, 8, '7d038557847baec98c710a29aab7d5fd57887c3e.jpeg'),
(12, 8, 'cc8dc569bdcf6df14dc5e1f469ee3fa9d34d2b08.jpeg'),
(13, 5, 'c922a352b038e5e9f82186251c06e0b2b01ca183.jpeg'),
(14, 9, '4b44c00b73f997ca01bc50ca2fbfda5d523fd903.jpeg'),
(16, 12, 'dc657ff28a95cbdd586d8f6e6761fc551c26211e.jpeg'),
(17, 13, '2ecf17e33039390d80ac8e91c7926630ff7d21ac.jpeg'),
(18, 14, '0f384c22874bdde615d7b3c3e0fe4c88d81ce7cd.jpeg'),
(19, 15, '0c1a7eaf3f9dc13b0865f324a8bf3ecde31ac348.jpeg'),
(20, 16, 'f14da5dae303f3f81241ec02148ba38a48dc8b1a.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `image_id`, `email`, `firstname`, `username`, `password`, `lastname`, `change_password`) VALUES
(1, 6, 'muhammed-inan@outlook.com', 'muhammed', 'fener', '$2y$13$CZK8RVZJ9XTaBjMU6MY3FO5knShYnBPVU3xd3F58xag5F9XvMN2vC', 'inan', 'b975af39d600ca9b09a6cd9c8a9ac8fe322eb624f85bd7aa361001d5b1e7c3a9');

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `type`, `identif`, `date`) VALUES
(5, 'youtube', 'cPeCbmHIGEE', '2018-09-30 22:02:25'),
(6, 'youtube', 'ewAPUJMprgI', '2018-09-30 23:30:49'),
(7, 'youtube', 'KoHzXi7Usl8', '2018-09-30 23:53:26'),
(8, 'youtube', '8CtWgw9xYRE', '2018-10-01 00:05:22'),
(9, 'youtube', 'tbD14cpo1qk', '2018-10-04 00:37:33'),
(12, 'youtube', 'y4Yp3-llWDs', '2018-10-04 14:28:18'),
(13, 'youtube', 'obHWnBYl3Eo', '2018-10-04 14:35:23'),
(14, 'youtube', 'myZKTpqbAyg', '2018-10-04 14:37:19'),
(15, 'youtube', 'crDzvmi91XQ', '2018-10-04 15:05:10'),
(16, 'youtube', '8CtWgw9xYRE', '2018-10-04 15:07:59');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C5C011B5` (`figure_id`),
  ADD KEY `IDX_9474526CF675F31B` (`author_id`);

--
-- Index pour la table `figure`
--
ALTER TABLE `figure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_2F57B37A29C1004E` (`video_id`),
  ADD KEY `IDX_2F57B37A12469DE2` (`category_id`),
  ADD KEY `IDX_2F57B37AA76ED395` (`user_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045F5C011B5` (`figure_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D6493DA5256D` (`image_id`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `figure`
--
ALTER TABLE `figure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`),
  ADD CONSTRAINT `FK_9474526CF675F31B` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `figure`
--
ALTER TABLE `figure`
  ADD CONSTRAINT `FK_2F57B37A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_2F57B37A29C1004E` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `FK_2F57B37AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F5C011B5` FOREIGN KEY (`figure_id`) REFERENCES `figure` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6493DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
