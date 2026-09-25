-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 02 sep. 2026 à 20:42
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quizbd`
--

-- --------------------------------------------------------

--
-- Structure de la table `culture`
--

CREATE TABLE `culture` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `reponse` text DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `culture`
--

INSERT INTO `culture` (`id`, `question`, `reponse`, `level`, `type`) VALUES
(1, 'Qui a écrit les célèbres fables ?', 'Jean de La Fontaine', 1, 'texte'),
(2, 'Quelle est la capitale de la Suède ?', 'Stockholm', 1, 'texte'),
(3, 'Quel est le nom de cette célèbre rue ?', 'Abbey Road', 1, 'texte'),
(4, 'Quel acteur a joué dans ces films ?', 'Robert De Niro', 1, 'qcm'),
(5, 'Comment appelle-t-on ce sapin de Briançon ?', 'mélèze', 2, 'qcm'),
(6, 'Qui a créé le journal Libération ?', 'Jean-Paul Sartre', 2, 'qcm'),
(7, 'Quelle est la capitale de l\'Iran ?', 'Téhéran', 2, 'texte'),
(8, 'Qui est à l\'origine de La Comédie humaine ?', 'Balzac', 2, 'qcm'),
(9, 'Qui a écrit ce célèbre livre ?', 'George Orwell', 2, 'texte'),
(10, 'Quel concert a été organisé afin de venir en aide à l\'Afrique ?', 'Live Aid', 3, 'qcm'),
(11, 'Quel artiste voit son morceau devenir le premier clip noir à passer sur MTV ?', 'Michael Jackson', 3, 'qcm'),
(12, 'Qui fut la femme de Serge Gainsbourg ?', 'Jane Birkin', 3, 'qcm'),
(13, 'Quelle ville a vu ces deux artistes enregistrer des albums mythiques ?', 'Berlin', 3, 'qcm'),
(14, 'Qui est cet artiste ?', 'Eddie Mitchell', 3, 'qcm');

-- --------------------------------------------------------

--
-- Structure de la table `gamecard`
--

CREATE TABLE `gamecard` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gamecard`
--

INSERT INTO `gamecard` (`id`, `name`, `username`, `email`, `pwd`, `role`, `profile_picture`, `created_at`) VALUES
(1, 'Admin', 'Lytecott', 'admin@lytecott.com', '0fcee95cc7b4f2067da8ba1e330de18e', 'admin', NULL, '2026-09-02 10:18:13'),
(2, 'abakar', 'adoum', 'a@yahoo.fr', '$2y$10$HNokkfzGYNWoCk4ubpioROuedpGxSTz4PxfCzyaUfajIDfYvc/IyC', 'admin', NULL, '2026-09-02 10:29:08');

-- --------------------------------------------------------

--
-- Structure de la table `histoire`
--

CREATE TABLE `histoire` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `reponse` text DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `histoire`
--

INSERT INTO `histoire` (`id`, `question`, `reponse`, `level`, `type`) VALUES
(1, 'Durant quelle guerre le napalm était-il jeté sur la population locale ?', 'guerre du Vietnam', 1, 'qcm'),
(2, 'De quel logo s\'agit-il ?', 'ONU', 1, 'texte'),
(3, 'Quel scandale correspond à ces deux images ?', 'scandale du Watergate', 1, 'texte'),
(4, 'Quel événement est représenté par cette image ?', 'assassinat de JFK', 1, 'qcm'),
(5, 'Qui est ce célèbre roi français ?', 'Louis XIV', 1, 'qcm'),
(6, 'Quel était le surnom du roi que vous venez de voir ?', 'Roi Soleil', 2, 'texte'),
(7, 'Comment appelait-on cette femme politique ?', 'la Dame de Fer', 2, 'qcm'),
(8, 'Que s\'est-il passé en 1905 en France ?', 'séparation de l\'Église et de l\'État', 2, 'qcm'),
(9, 'Quel pays est symbolisé par ces figures politiques ?', 'Cuba', 2, 'texte'),
(10, 'En quelle année l\'Algérie est-elle devenue indépendante ?', '1962', 3, 'texte'),
(11, 'En quelle année les premières écritures sont-elles apparues en Mésopotamie ?', '-3500', 3, 'qcm'),
(12, 'En quelle année s\'est effondré l\'Empire romain ?', '476', 3, 'qcm'),
(13, 'Comment s\'appelle cette révolution russe de 1917 ?', 'révolution d\'Octobre', 3, 'texte'),
(14, 'Quel est le nom de ce président américain ?', 'Jimmy Carter', 3, 'texte');

-- --------------------------------------------------------

--
-- Structure de la table `jeuxactive`
--

CREATE TABLE `jeuxactive` (
  `id` int(11) NOT NULL,
  `nom_jeu` varchar(100) NOT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `theme` varchar(50) NOT NULL,
  `ordreActuel` text DEFAULT NULL,
  `ordreSuivant` text DEFAULT NULL,
  `nbrErreur` int(11) DEFAULT 0,
  `currentScore` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `jeuxactive`
--

INSERT INTO `jeuxactive` (`id`, `nom_jeu`, `statut`, `theme`, `ordreActuel`, `ordreSuivant`, `nbrErreur`, `currentScore`) VALUES
(2, '', 'actif', 'histoire', '13/9/8/3/6/1/11/14/12/7/10/2/4/5', '', 0, 0),
(2, '', 'actif', 'sciences', '3/2/6/8/10/9/13/1/5/14/12/4/11/7', '', 0, 0),
(2, '', 'actif', 'sport', '13/8/1/3/6/9/5/11/10/4/2/12/14/7', '', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `propositions`
--

CREATE TABLE `propositions` (
  `questionId` int(11) NOT NULL,
  `proposition1` text DEFAULT NULL,
  `proposition2` text DEFAULT NULL,
  `proposition3` text DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `propositions`
--

INSERT INTO `propositions` (`questionId`, `proposition1`, `proposition2`, `proposition3`, `theme`) VALUES
(1, 'un direct du bras avant', 'coup bas', 'saut sur l\'arbitre', 'sport'),
(2, 'lancer franc', 'faute', 'arbitre', 'sport'),
(4, 'frisbee', 'golf', 'bowling', 'sport'),
(5, 'métal', 'acier', 'bois', 'sport'),
(7, '7', '3', '14', 'sport'),
(8, '20', '3', '10', 'sport'),
(10, '10', '23', '7,32 m', 'sport'),
(11, 'Le Havre AC', 'Nantes', 'PSG', 'sport'),
(12, '30 km/h', '80 km/h', '60 km/h', 'sport'),
(13, 'Björn Borg', 'Ivan Lendl', 'Djokovic', 'sport'),
(14, 'voile', 'golf', 'polo', 'sport'),
(1, '5', '4', '6', 'sciences'),
(2, 'espace', 'relativité générale', 'algèbre', 'sciences'),
(3, '1/6', '2/3', '10', 'sciences'),
(4, 'Marie Curie', 'Simone de Beauvoir', 'J.K. Rowling', 'sciences'),
(5, 'paillasson', 'tableau', 'paillasse', 'sciences'),
(6, '1000°C', '100°C', '18°C', 'sciences'),
(7, 'hématologie', 'cardiologie', 'immunologie', 'sciences'),
(9, 'artère carotide', 'globules blancs', 'cellules immunitaires', 'sciences'),
(13, 'génie des chiffres', 'dieu allemand', 'prince des mathématiciens', 'sciences'),
(14, 'alcool', 'énantiomère', 'acide hydroxyle', 'sciences'),
(1, 'guerre de Corée', 'guerre du Vietnam', 'guerre du Golfe', 'histoire'),
(4, 'investiture de JFK', 'arrivée du président Bush', 'assassinat de JFK', 'histoire'),
(5, 'Louis XVI', 'Henri V', 'Louis XIV', 'histoire'),
(7, 'Thatcher', 'la Dame de Fer', 'la guerrière', 'histoire'),
(8, 'Séparation du clergé', 'Séparation de l\'Église et de l\'État', 'bataille de Nevers', 'histoire'),
(11, '-230', '-600', '-3500', 'histoire'),
(12, '543', '1200', '476', 'histoire'),
(1, 'La Fontaine', 'Molière', 'Racine', 'culture'),
(4, 'Owen Wilson', 'Robert De Niro', 'Johnny Hallyday', 'culture'),
(5, 'mélèze', 'cèdre', 'pommier', 'culture'),
(6, 'Giscard d\'Estaing', 'Jean-Paul Sartre', 'Henry Miller', 'culture'),
(8, 'Voltaire', 'Racine', 'Balzac', 'culture'),
(10, 'Live Aid', 'Les Enfoirés', 'Sunday Service', 'culture'),
(11, 'Prince', 'Michael Jackson', 'Ali', 'culture'),
(12, 'Madonna', 'Jane Birkin', 'Vanessa Paradis', 'culture'),
(13, 'Stockholm', 'Paris', 'Berlin', 'culture'),
(14, 'Johnny Hallyday', 'Eddie Mitchell', 'Charles Aznavour', 'culture'),
(2, 'Stockholm', 'Oslo', 'Helsinki', 'culture'),
(3, 'Abbey Road', 'Baker Street', 'Rodeo Drive', 'culture'),
(7, 'Balzac', 'Hugo', 'Zola', 'culture'),
(9, 'George Orwell', 'Aldous Huxley', 'Ray Bradbury', 'culture'),
(15, 'Réponse 1', 'Réponse 2', 'Réponse 3', 'culture');

-- --------------------------------------------------------

--
-- Structure de la table `sciences`
--

CREATE TABLE `sciences` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `reponse` text DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sciences`
--

INSERT INTO `sciences` (`id`, `question`, `reponse`, `level`, `type`) VALUES
(1, 'ABC est un triangle rectangle en A, AB=4, AC=3, combien mesure l\'hypoténuse BC ?', '5', 1, 'texte'),
(2, 'Dans quel domaine s\'est illustré cet homme ?', 'relativité générale', 1, 'qcm'),
(3, 'Quelle est la probabilité d\'obtenir un 6 avec un dé non truqué ?', '1/6', 1, 'qcm'),
(4, 'Quelle femme a découvert la radioactivité ?', 'Marie Curie', 1, 'qcm'),
(5, 'Sur quel support travaille le chimiste ?', 'paillasse', 2, 'qcm'),
(6, 'À quelle température l\'eau se transforme-t-elle en gaz ?', '100°C', 2, 'qcm'),
(7, 'Quelles cellules sont responsables de l\'immunité ?', 'globules blancs', 2, 'texte'),
(8, 'Comment s\'appelle la médecine du sang ?', 'hématologie', 2, 'texte'),
(9, 'Quel vaisseau est relié au cerveau ?', 'artère carotide', 3, 'qcm'),
(10, 'Quelle est la primitive de 1/x ?', 'ln|x| + C', 3, 'texte'),
(11, 'Qui est ce célèbre scientifique ?', 'Stephen Hawking', 3, 'texte'),
(12, 'Comment est mort ce personnage tristement célèbre ?', 'En mangeant une pomme empoisonnée', 3, 'texte'),
(13, 'Quel est le surnom de ce célèbre mathématicien ?', 'le prince des mathématiciens', 3, 'qcm'),
(14, 'Quelle est la nature de cette molécule ?', 'énantiomère', 3, 'qcm');

-- --------------------------------------------------------

--
-- Structure de la table `sport`
--

CREATE TABLE `sport` (
  `id` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `reponse` text DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sport`
--

INSERT INTO `sport` (`id`, `question`, `reponse`, `level`, `type`) VALUES
(1, 'Qu\'est-ce qu\'un jab en boxe ?', 'un direct du bras avant', 1, 'qcm'),
(2, 'Quelle est cette pénalité ?', 'lancer franc', 1, 'qcm'),
(3, 'Combien de joueurs forment une équipe de basket ?', '5', 1, 'texte'),
(4, 'Quel sport correspond à ces termes ?', 'bowling', 1, 'qcm'),
(5, 'Comment étaient faites les queues de billard ?', 'bois', 2, 'qcm'),
(6, 'Dans quel sport retrouve-t-on ces termes ?', 'football américain', 2, 'texte'),
(7, 'Combien de périodes y a-t-il dans un match de hockey ?', '3', 2, 'qcm'),
(8, 'Combien de haies un athlète doit-il éviter lors d\'un 400 m haies ?', '10', 2, 'qcm'),
(9, 'Quelle équipe possède le plus grand stade ?', 'Barcelone', 2, 'texte'),
(10, 'Quelle est la taille d\'une cage de football ?', '7,32 m', 2, 'qcm'),
(11, 'Quel est le premier club français créé en France ?', 'Le Havre AC', 3, 'qcm'),
(12, 'Quelle est la vitesse moyenne d\'un lévrier ?', '60 km/h', 3, 'qcm'),
(13, 'Lequel de ces tennismen a remporté le plus de fois Roland-Garros ?', 'Björn Borg', 3, 'qcm'),
(14, 'Dans quel sport utilise-t-on un sand-wedge ?', 'golf', 3, 'qcm');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `culture`
--
ALTER TABLE `culture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gamecard`
--
ALTER TABLE `gamecard`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `jeuxactive`
--
ALTER TABLE `jeuxactive`
  ADD PRIMARY KEY (`id`,`theme`);

--
-- Index pour la table `sport`
--
ALTER TABLE `sport`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `culture`
--
ALTER TABLE `culture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `gamecard`
--
ALTER TABLE `gamecard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `sport`
--
ALTER TABLE `sport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
