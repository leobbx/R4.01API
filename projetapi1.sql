-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 29 mars 2023 à 15:43
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetapi`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `date_pub` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `text` text NOT NULL,
  `Id_Utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `date_pub`, `text`, `Id_Utilisateur`) VALUES
(4, '2023-03-29 13:25:06', 'Huit Aurillacois participeront ce week-end aux 24 heures en rollers du Mans. Issus de la section roller adultes de Session Libre d’Aurillac, Luc Geoffroy, Guillaume Longue-Épée, Jérôme Focret, Aurélien Fournier, Magali Laffaire, Xavier Furet et Nicolas Seguy se préparent depuis plusieurs mois pour cette aventure. Si les 24 heures rollers n’ont pas la notoriété de la célèbre course automobile, c’est l’une des compétitions les plus importantes pour un grand nombre de patineurs français et européens. Même s’ils sont tous sportifs, nos huit Aurillacois vont devoir puiser dans leurs réserves pour tenter d’atteindre les 100 tours de piste, soit 420 kilomètres pour une moyenne de 17,5 km/h, que leur capitaine Luc Geoffroy a fixés comme objectif. Le départ sera donné ce samedi 28 juin à 16 heures – verdict… 24 heures plus tard.', 1),
(5, '2023-03-29 13:31:22', 'Le 1er décembre dernier, les Restaurants du Cœur ont entamé leur 24ème campagne d’aide aux personnes les plus démunies. Afin de venir en aide à cette association et à ses milliers de bénévoles, Danone et Carrefour ont décidé de se mobiliser et de s’engager durablement via un programme en 3 volets : Aide à la collecte alimentaire des Restos du Cœur des 6 et 7 mars dans les magasins du Carrefour, Carrefour market et Champion= implication massive des salariés (sur la base du volontariat) de Danone et Carrefour pour renforcer l’action des bénévoles des Restos.Opération « promo-partage » dans tous les magasins Carrefour ,Carrefour market et Champion : pour 5 produits Danone achetés (en promotion sur prospectus, du 25 février au 8 mars 2009 chez Champion et du 17 au 25 mars 2009 chez Carrefour) = 1 repas offert aux Restos du Cœur. Grâce à cette opération, Danone et Carrefour ont l’objectif ambitieux d’offrir 1 million de repas aux Restos.', 1),
(7, '2023-03-29 13:33:42', 'Le département du Cantal est connu pour ses fromages alors quoi de mieux de l’acheter directement chez le producteur !!?? C’est le cas de la « Ferme fromagerie » La Grange de la Haute-Vallée qui propose la vente direct de fromages fermiers d’Auvergne ! C’est au cœur du Cantal, dans le parc des Volcans d’Auvergne entre Murat et Saint- Flour, que la ferme la Grange de la Haute-Vallée vous ouvre ses portes. L’élevage se compose de Montbeliardes sélectionnées selon des critères génétiques rigoureux.La fromagerie est artisanale et respectueuse des méthodes anciennes pour capter les saveurs du terroir, mais les équipements sont modernes pour que l’ensemble réponde à toutes les exigences particulières des A.O.C !', 1),
(8, '2023-03-29 13:35:19', 'Comme les amis savent le faire avec une attention particulièrement délicate, je me suis vu offrir par une amie des contenants originaux et extrêmement design ! J’ai tout de suite adoré la matière mate, la composition, la forme et la couleur ! Elle m’explique donc que ces objets sont biodégradables à plutôt court terme (puisque le fabricant précise que le produit se détruit au bout de 2-3 ans de mise à la déchetterie !), et ce pourquoi ? La solution réside dans la composition du produit puisque les produits sont en réalité composés de… fibres de bambou ! J’avoue avoir été complètement bluffée pensant sincèrement qu’il s’agissait d’une nouvelle gamme de matière plastique bien chimico-chimique ! Je me suis donc rendue sur le site web du fabricant de produits BIOBU, à savoir EKOBO. Je découvre donc sur ce site modern au design épuré toute une gamme de « vaisselle » se voulant alternative car purement DD (Développement durable) et dans le contexte de la prochaine COP 21 ça le fait !', 2),
(9, '2023-03-29 13:36:12', 'Début de mois oblige, voici quelques expressions et mots clefs qui ont permis à certains d’arriver sur mon blog ! Pour ce mois d’octobre, c’est encore mon billet sur le film « Bienvenue chez les ch’tites coquines » qui a eu le plus de succès. Suivi du billet sur « Les plans de montage de LEGO » (on est des grands enfants) et en 3ème position c’est le « GPS Géonaute Keymaze 300« . Toujours le même trio !! A noter tout de même que l’article sur la « TNT HD gratuit » se place à la 5ème position, sachant que le billet a été mis en ligne le 28 octobre. En 3 jours il a explosé le nombre de consultations et du coup a fait presque doubler mes statistiques !! Il semble que tout le monde veuille savoir si sa ville est couverte par la TNT HD.', 2),
(10, '2023-03-29 13:37:07', 'Aprés un mois de teasing et de marketing viral, le buzz a fait son travail et tout le monde parle du « 36 d’Aurillac« … Et bien aprés un mois d’attente, voici le communiqué de presse officiel de l’événement : « Les 22 et 23 février prochain, 36 heures seront consacrées à des solos et petites pièces dansées à Aurillac. Pour cette première édition, 15 chorégraphes professionnels d’Auvergne viendront présenter une partie de leur travail, échanger avec le public, la presse, les programmateurs et les institutions sur la création chorégraphique en région.Cet événement est organisé par le Théâtre d’Aurillac et Vendetta Mathea La Manufacture, Centre de Formation Professionnelle Danse habilité par le Ministère de la Culture et de la Communication et Centre Artistique Mouvement Image. « Nous souhaitons avec Jean-Paul Peuch,contribuer au développement des richesses créatrices de notre territoire, favoriser l’émergence de nouveaux talents », Vendetta Mathea.', 2),
(11, '2023-03-29 13:39:36', 'Si vous voulez changer de téléphone mobile ou de forfait, ATTENTION ! Si vous voulez profiter des fêtes de Noël pour changer… un conseil attendez ! La raison : Free Mobile Un 4ème opérateur arrive sur le marché. En plus de SFR, Orange ou Bouygues, vous pourrez bientôt choisir Free Mobile ! Les dernières rumeurs parlent d’un lancement autour du 17 décembre pour pouvoir être présent sous le sapin (Ouè, pas certain du tout). Dans tout les cas ne vous ré-engagez pas avant de voir ce que ce nouvel opérateur va nous proposer… surtout si vous êtes abonnés ADSL via Free. Xavier Niel, le Monsieur Free/Iliad, promet de casser les prix et bien plus encore… Donc, wait and see… Vous n’êtes pas à 2 mois près ! Dans le cas où vous voudriez changer pour Free Mobile et donc changer d’opérateur, il y a quelque précautions à prendre. 1. Connaitre ça date d’engagement et son code RIO (pour conserver votre numéro). 2. Calculer les frais de résiliation, si il y en a ! 3. Débloquer son téléphone (désimlockage).', 4),
(12, '2023-03-29 13:40:45', 'Pendant trois jours, les 6, 7 et 8 juillet, Aurillac devient le rendez-vous incontournable de tous les gourmands ! » On croyait les fêtes de terroir désuètes et folkloriques ; Les Européennes du Goût prouvent que la gastronomie et les produits de nos régions peuvent être célébrés d’une manière originale, conviviale et moderne.Cuisiner, c’est toujours être soucieux de la tradition et gourmand d’innovations. Pour 2007, les Européennes du Goût font la part belle à internet. En 2005 et 2006, 25 000 gastronomes sont venus se régaler dans le Cantal ; la recette de ce succès ? Un festival à la fois gastronomique et culturel, qui met tous les sens en éveil. Au programme : Marché gourmand, cours de cuisine, ateliers du goût, concours, fanfares culinaires, échanges de savoir-faire, dégustations… Un véritable lieu d’échanges entre le public et les producteurs, mais aussi de démonstrations avec de grands chefs, des artisans, des blogueurs culinaires…« ', 4),
(13, '2023-03-29 13:41:27', 'C’est ce week-end que débute le 16è Festival de Musique Classique du Cantal, Voyage d’Hiver 2012 ! Au programme cette année un gros week-end « piano » avec 4 concerts les 16,17 et 18 décembre ! On enchaine le 11 février avec OPUS 62, sextuor du Pas-de-Calais. Puis en mars, trois concerts : Un duo violoncelle (Francis Salque) – accordéo (Vincent Peirani) le 3 mars. Un trio flamenco Vicente Pradal, le 10 et du piano-jazz le 11 avec Baptiste Trotignon. Toujours en mars, le 4, rendez vous à St Flour pour découvrir les quatuors de G. Onslow, interprétés par Concordia.', 4);

-- --------------------------------------------------------

--
-- Structure de la table `like_dislike`
--

CREATE TABLE `like_dislike` (
  `Id_Article` int(11) NOT NULL,
  `Id_Utilisateur` int(11) NOT NULL,
  `statute` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `like_dislike`
--

INSERT INTO `like_dislike` (`Id_Article`, `Id_Utilisateur`, `statute`) VALUES
(4, 2, '0'),
(4, 4, '1');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `login`, `role`, `password`) VALUES
(1, 'jean', 'publisher', 'e70802a6a4fd53d5ee08adaf678cf26dfd906b68'),
(2, 'marie', 'publisher', '085446cd5ab627ec1c467307525ee49ccaf3ee5f'),
(3, 'leo', 'moderator', '1f0a51c36efaa0f44e4899c26d2028681997c8ea'),
(4, 'max', 'publisher', '55cbe7fd00627a28668d1d7c9899bdb602dad69d'),
(5, 'fatime', 'moderator', '2e37b6d1bfdaf8eeea7e026df76ab089df0f523d');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `Id_Utilisateur` (`Id_Utilisateur`);

--
-- Index pour la table `like_dislike`
--
ALTER TABLE `like_dislike`
  ADD PRIMARY KEY (`Id_Article`,`Id_Utilisateur`),
  ADD KEY `Id_Utilisateur` (`Id_Utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`Id_Utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `like_dislike`
--
ALTER TABLE `like_dislike`
  ADD CONSTRAINT `like_dislike_ibfk_1` FOREIGN KEY (`Id_Article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `like_dislike_ibfk_2` FOREIGN KEY (`Id_Utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
