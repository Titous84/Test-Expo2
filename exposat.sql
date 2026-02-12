-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 23 mai 2025 à 03:37
-- Version du serveur : 8.0.41
-- Version de PHP : 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `exposat`
--

-- --------------------------------------------------------

--
-- Structure de la table `acquaintance_conflict`
--

CREATE TABLE `acquaintance_conflict` (
  `id` int NOT NULL,
  `judge_id` int DEFAULT NULL,
  `users_id` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `max_members` int NOT NULL,
  `survey_id` int DEFAULT NULL,
  `acronym` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `activated`, `max_members`, `survey_id`, `acronym`) VALUES
(1, 'Sciences de la vie', 1, 6, 1, 'SN-S'),
(2, 'Sciences physiques', 1, 6, 1, 'SB-P'),
(3, 'SH - Intervention Sociale', 1, 6, 1, 'SH-IS'),
(4, 'SH - Gestion durable des affaires', 1, 6, 1, 'SH-GD'),
(5, 'SH - Relations et développement international', 1, 6, 1, 'SH-RDI'),
(6, 'Tech Informatique', 1, 6, 1, 'Info'),
(7, 'Tech Soins Infirmiers', 1, 6, 1, 'Soins');

-- --------------------------------------------------------

--
-- Structure de la table `categories_judge`
--

CREATE TABLE `categories_judge` (
  `id` int NOT NULL,
  `categories_id` int NOT NULL,
  `judge_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `code_verification`
--

CREATE TABLE `code_verification` (
  `id` int NOT NULL,
  `codeVerification` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempsAjout` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `component_type`
--

CREATE TABLE `component_type` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_person`
--

CREATE TABLE `contact_person` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `contact_person`
--

INSERT INTO `contact_person` (`id`, `name`, `email`) VALUES
(33, 'Enseignant', 'enseignant@cegepvicto.ca');

-- --------------------------------------------------------

--
-- Structure de la table `contest`
--

CREATE TABLE `contest` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `criteria`
--

CREATE TABLE `criteria` (
  `id` int NOT NULL,
  `rating_section_id` int NOT NULL,
  `criteria` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `position` int NOT NULL,
  `max_value` int NOT NULL DEFAULT '1',
  `incremental_value` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `criteria`
--

INSERT INTO `criteria` (`id`, `rating_section_id`, `criteria`, `position`, `max_value`, `incremental_value`) VALUES
(85, 26, 'Esthétisme général du stand.', 1, 1, 1),
(86, 26, 'Utilisation de l\'espace de présentation.', 2, 1, 1),
(87, 27, 'Dynamisme des animateurs.', 1, 1, 1),
(88, 27, 'Compréhensibilité des animateurs.', 2, 1, 1),
(89, 27, 'Interaction du juge favorisée.', 3, 1, 1),
(90, 28, 'Utilisation de la bonne terminologie.', 1, 1, 1),
(91, 28, 'Utilisation des ressources en totalité.', 2, 1, 1),
(92, 28, 'Connaissance du sujet.', 3, 1, 1),
(93, 28, 'Qualité des sources d\'information.', 4, 1, 1),
(94, 28, 'Sujet bien cerné.', 5, 1, 1),
(95, 28, 'Niveau de difficulté du sujet.', 6, 1, 1),
(96, 28, 'Capacité de répondre aux questions.', 7, 1, 1),
(97, 28, 'Capacité d’improviser.', 8, 1, 1),
(98, 28, 'Regard critique sur la démarche.', 9, 1, 1),
(99, 29, 'Approche non conventionnelle.', 1, 1, 1),
(100, 29, 'Exposants artisans du stand.', 2, 1, 1),
(101, 30, 'Qualité de la langue écrite (Stand, documentation)...', 1, 1, 1),
(102, 30, 'Qualité de la langue parlée (Animation).', 2, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `criteria_evaluation`
--

CREATE TABLE `criteria_evaluation` (
  `score` float NOT NULL,
  `evaluation_id` int NOT NULL,
  `criteria_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int NOT NULL,
  `judge_id` int NOT NULL,
  `teams_id` int NOT NULL,
  `comments` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `survey_id` int NOT NULL,
  `heure` int NOT NULL,
  `est_actif` tinyint DEFAULT NULL,
  `rating_section_id` int DEFAULT NULL,
  `global_score_removed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `info_events`
--

CREATE TABLE `info_events` (
  `id` int NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `beginning` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ending` datetime NOT NULL,
  `event_processed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `judge`
--

CREATE TABLE `judge` (
  `id` int NOT NULL,
  `uuid` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `categories_id` int NOT NULL,
  `users_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `judge`
--

INSERT INTO `judge` (`id`, `uuid`, `categories_id`, `users_id`) VALUES
(34, '94097b1b-05b7-4c13-879d-69ad597846e9', 6, 782);

-- --------------------------------------------------------

--
-- Structure de la table `rating_section`
--

CREATE TABLE `rating_section` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `position` int NOT NULL,
  `survey_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rating_section`
--

INSERT INTO `rating_section` (`id`, `name`, `position`, `survey_id`) VALUES
(26, 'PRÉSENTATION VISUELLE', 1, 1),
(27, 'ANIMATION', 2, 1),
(28, 'INTÉGRATION DES CONNAISSANCES', 3, 1),
(29, 'ORIGINALITÉ DU PROJET', 4, 1),
(30, 'LANGUE', 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `results`
--

CREATE TABLE `results` (
  `id` int NOT NULL,
  `teams_id` int DEFAULT NULL,
  `note` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(0, 'Admin', ''),
(1, 'Juge', ''),
(3, 'Participants', '');

-- --------------------------------------------------------

--
-- Structure de la table `site_component`
--

CREATE TABLE `site_component` (
  `id` int NOT NULL,
  `type_id` int NOT NULL,
  `picture` varchar(512) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `title` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `order` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `survey`
--

CREATE TABLE `survey` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `survey`
--

INSERT INTO `survey` (`id`, `name`) VALUES
(1, 'GrillePourTous');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE `teams` (
  `id` int NOT NULL,
  `team_number` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `project_picture` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `years` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `categories_id` int DEFAULT NULL,
  `equipments_needed` enum('test') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `survey_id` int DEFAULT NULL,
  `judge_assignation` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `team_number`, `name`, `description`, `project_picture`, `years`, `categories_id`, `equipments_needed`, `activated`, `creation_date`, `survey_id`, `judge_assignation`) VALUES
(28, 'Info1', 'Projet test', 'Description test', NULL, '2e année et +', 6, 'test', 1, '2025-05-23 03:21:06', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `teams_contact_person`
--

CREATE TABLE `teams_contact_person` (
  `id` int NOT NULL,
  `teams_id` int NOT NULL,
  `contact_person_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `teams_contact_person`
--

INSERT INTO `teams_contact_person` (`id`, `teams_id`, `contact_person_id`) VALUES
(411, 28, 33);

-- --------------------------------------------------------

--
-- Structure de la table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `time_slots`
--

INSERT INTO `time_slots` (`id`, `time`) VALUES
(1, '10:00:00'),
(2, '10:30:00'),
(57, '11:00:00'),
(58, '11:30:00'),
(60, '12:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pwd` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numero_da` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `picture_consent` tinyint(1) NOT NULL DEFAULT '0',
  `reset_token` int DEFAULT NULL,
  `activation_token` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `pwd`, `email`, `numero_da`, `picture`, `picture_consent`, `reset_token`, `activation_token`, `activated`, `blacklisted`, `role_id`) VALUES
(1, 'le testeur', 'professionnel', 'test', '$2y$10$wNOJM7zdwWO2KoDZ3jSCKOXbTJnVUnHKlgrJ8sRdwAky/3FzwOiRW', 'test@letesteur.test', NULL, '', 0, NULL, NULL, 1, 0, 0),
(782, 'Juge', 'Test', NULL, NULL, 'jugetest@courriel.com', NULL, NULL, 0, NULL, NULL, 1, 0, 1),
(783, 'Membre', 'Test1', NULL, NULL, NULL, '5830586', NULL, 0, NULL, 'e1f8e1de-4463-4999-aefc-34e4c51fd2d9', 0, 0, 3),
(784, 'Membre', 'Test2', NULL, NULL, NULL, '3460599', NULL, 0, NULL, 'c8bab8ab-5bac-4858-af8a-702e78710c58', 0, 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `users_teams`
--

CREATE TABLE `users_teams` (
  `id` int NOT NULL,
  `teams_id` int NOT NULL,
  `users_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `users_teams`
--

INSERT INTO `users_teams` (`id`, `teams_id`, `users_id`) VALUES
(814, 28, 783),
(815, 28, 784);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `acquaintance_conflict`
--
ALTER TABLE `acquaintance_conflict`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_conflict_id_idx` (`users_id`),
  ADD KEY `judge_conflict_id_idx` (`judge_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_survey` (`survey_id`);

--
-- Index pour la table `categories_judge`
--
ALTER TABLE `categories_judge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_index` (`categories_id`),
  ADD KEY `judges_index` (`judge_id`);

--
-- Index pour la table `code_verification`
--
ALTER TABLE `code_verification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `component_type`
--
ALTER TABLE `component_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contact_person`
--
ALTER TABLE `contact_person`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_evaluation_id` (`rating_section_id`);

--
-- Index pour la table `criteria_evaluation`
--
ALTER TABLE `criteria_evaluation`
  ADD PRIMARY KEY (`evaluation_id`,`criteria_id`),
  ADD KEY `evaluation_criteria` (`evaluation_id`) USING BTREE,
  ADD KEY `criteria_evaluation` (`criteria_id`) USING BTREE;

--
-- Index pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questionnaire_juge_id` (`judge_id`),
  ADD KEY `questionnaire_equipe_id` (`teams_id`),
  ADD KEY `survey_index` (`survey_id`),
  ADD KEY `heure_index` (`heure`),
  ADD KEY `evaluation_ibfk_1` (`rating_section_id`);

--
-- Index pour la table `info_events`
--
ALTER TABLE `info_events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `judge`
--
ALTER TABLE `judge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compte_juge_id` (`users_id`),
  ADD KEY `categorie_juge_id` (`categories_id`);

--
-- Index pour la table `rating_section`
--
ALTER TABLE `rating_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_survey` (`survey_id`);

--
-- Index pour la table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `site_component`
--
ALTER TABLE `site_component`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_component` (`type_id`);

--
-- Index pour la table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categories_id`),
  ADD KEY `teams_survey` (`survey_id`);

--
-- Index pour la table `teams_contact_person`
--
ALTER TABLE `teams_contact_person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_person_index` (`contact_person_id`),
  ADD KEY `teams_index` (`teams_id`);

--
-- Index pour la table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Index pour la table `users_teams`
--
ALTER TABLE `users_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_users_teams` (`users_id`,`teams_id`),
  ADD KEY `compte_id` (`users_id`),
  ADD KEY `equipe_id` (`teams_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `categories_judge`
--
ALTER TABLE `categories_judge`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `code_verification`
--
ALTER TABLE `code_verification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `component_type`
--
ALTER TABLE `component_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `contact_person`
--
ALTER TABLE `contact_person`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `contest`
--
ALTER TABLE `contest`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT pour la table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `info_events`
--
ALTER TABLE `info_events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `judge`
--
ALTER TABLE `judge`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `rating_section`
--
ALTER TABLE `rating_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `results`
--
ALTER TABLE `results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `site_component`
--
ALTER TABLE `site_component`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `teams_contact_person`
--
ALTER TABLE `teams_contact_person`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=412;

--
-- AUTO_INCREMENT pour la table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=785;

--
-- AUTO_INCREMENT pour la table `users_teams`
--
ALTER TABLE `users_teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=816;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_survey` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `categories_judge`
--
ALTER TABLE `categories_judge`
  ADD CONSTRAINT `categories_index` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `judges_index` FOREIGN KEY (`judge_id`) REFERENCES `judge` (`id`);

--
-- Contraintes pour la table `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `section_evaluation_id` FOREIGN KEY (`rating_section_id`) REFERENCES `rating_section` (`id`);

--
-- Contraintes pour la table `criteria_evaluation`
--
ALTER TABLE `criteria_evaluation`
  ADD CONSTRAINT `criteria_evaluation_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`),
  ADD CONSTRAINT `criteria_evaluation_ibfk_2` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`id`);

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `evaluation_ibfk_1` FOREIGN KEY (`rating_section_id`) REFERENCES `rating_section` (`id`),
  ADD CONSTRAINT `heure_index` FOREIGN KEY (`heure`) REFERENCES `time_slots` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `judge_index` FOREIGN KEY (`judge_id`) REFERENCES `judge` (`id`),
  ADD CONSTRAINT `survey_index` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`),
  ADD CONSTRAINT `survey_teams_index` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`);

--
-- Contraintes pour la table `judge`
--
ALTER TABLE `judge`
  ADD CONSTRAINT `categorie_juge_id` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `compte_juge_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `site_component`
--
ALTER TABLE `site_component`
  ADD CONSTRAINT `type_component` FOREIGN KEY (`type_id`) REFERENCES `component_type` (`id`);

--
-- Contraintes pour la table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `categorie_id` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `teams_survey` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `teams_contact_person`
--
ALTER TABLE `teams_contact_person`
  ADD CONSTRAINT `contact_person_index` FOREIGN KEY (`contact_person_id`) REFERENCES `contact_person` (`id`),
  ADD CONSTRAINT `teams_index` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Contraintes pour la table `users_teams`
--
ALTER TABLE `users_teams`
  ADD CONSTRAINT `compte_id` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `equipe_id` FOREIGN KEY (`teams_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
