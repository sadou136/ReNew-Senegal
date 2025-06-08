-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 31 mai 2025 à 04:12
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `phone`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `message`, `sent_at`) VALUES
(3, 'yama', 'yama457@gmail.com', 'je taime!', '2025-05-30 00:32:04'),
(4, 'Tidiane', 'tidiane457@gmail.com', 'mbahalou saloum!', '2025-05-30 00:59:15');

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email`, `subscribed_at`) VALUES
(4, 'yama457@gmail.com', '2025-05-30 23:43:59'),
(5, 'sadou12@gmail.com', '2025-05-30 23:59:19');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`) VALUES
(4, 5, '356000.00', '2025-05-29 20:50:23'),
(5, 5, '150000.00', '2025-05-29 20:55:38'),
(6, 6, '110000.00', '2025-05-30 01:00:03'),
(7, 6, '344000.00', '2025-05-30 21:00:44'),
(8, 6, '628000.00', '2025-05-30 21:23:41'),
(9, 6, '95000.00', '2025-05-30 21:25:40'),
(10, 6, '100000.00', '2025-05-30 21:45:23'),
(11, 6, '280000.00', '2025-05-30 21:47:31'),
(12, 6, '115000.00', '2025-05-30 21:48:51'),
(13, 9, '408000.00', '2025-05-31 00:22:48'),
(14, 9, '190000.00', '2025-05-31 00:47:53');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(5, 4, 18, 4, '89000.00'),
(6, 5, 1, 1, '150000.00'),
(7, 6, 8, 1, '110000.00'),
(8, 7, 4, 1, '95000.00'),
(9, 7, 6, 1, '210000.00'),
(10, 7, 24, 1, '39000.00'),
(11, 8, 1, 3, '150000.00'),
(12, 8, 18, 2, '89000.00'),
(13, 9, 11, 1, '95000.00'),
(14, 10, 5, 1, '100000.00'),
(15, 11, 9, 2, '140000.00'),
(16, 12, 15, 1, '115000.00'),
(17, 13, 14, 2, '99000.00'),
(18, 13, 6, 1, '210000.00'),
(19, 14, 4, 2, '95000.00');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `marque` varchar(100) NOT NULL,
  `stockage` varchar(50) NOT NULL,
  `etat` varchar(100) NOT NULL,
  `garantie` varchar(100) NOT NULL,
  `vedette` tinyint(1) NOT NULL DEFAULT 0,
  `categories` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `image`, `marque`, `stockage`, `etat`, `garantie`, `vedette`, `categories`, `stock`, `date_ajout`) VALUES
(1, 'iPhone 11 - 128 Go', 'iPhone 11 reconditionné, débloqué, excellent état.', '150000.00', 'https://img.freepik.com/vecteurs-libre/appareil-telephone-intelligent-realiste_52683-29765.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Apple', '128 Go', 'Comme neuf', '6 mois', 0, 'apple', 10, '2025-05-29 20:46:01'),
(2, 'iPhone XR - 64 Go', 'iPhone XR reconditionné, grade A.', '120000.00', 'https://img.freepik.com/vecteurs-libre/smartphone-fond-ecran-degrade_23-2147846500.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Apple', '64 Go', 'Très bon état', '3 mois', 0, 'apple', 8, '2025-05-29 20:46:01'),
(3, 'iPhone 12 - 256 Go', 'Reconditionné avec batterie 90%, débloqué.', '180000.00', 'https://img.freepik.com/photos-premium/smartphone-mobile-telephone-portable-vert-menthe-couleur-avant-arriere-vue-blanc-isoler-maquette-vide-vide-techn_178037-2116.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Apple', '256 Go', 'Bon état', '6 mois', 0, 'apple', 5, '2025-05-29 20:46:01'),
(4, 'iPhone 8 - 64 Go', 'Compact, puissant, idéal pour tous.', '95000.00', 'https://img.freepik.com/vecteurs-premium/couleur-noire-du-smartphone-ecran-vide-face-arriere-fond-blanc_175654-707.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Apple', '64 Go', 'Comme neuf', '3 mois', 0, 'apple', 12, '2025-05-29 20:46:01'),
(5, 'iPhone SE 2020 - 128 Go', 'Rapide et léger, parfait pour le quotidien.', '100000.00', 'https://img.freepik.com/vecteurs-libre/conception-realiste-smartphone-blanc-trois-cameras_23-2148374059.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Apple', '128 Go', 'Très bon état', '6 mois', 0, 'apple', 9, '2025-05-29 20:46:01'),
(6, 'iPhone 13 - 128 Go', 'Dernier modèle reconditionné.', '210000.00', 'https://img.freepik.com/photos-premium/ecran-isole-telephone-intelligent-violet-fond-blanc_752648-31.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Apple', '128 Go', 'Comme neuf', '6 mois', 0, 'apple', 7, '2025-05-29 20:46:01'),
(7, 'Samsung Galaxy S20 - 128 Go', 'Écran AMOLED, performant.', '135000.00', 'https://img.freepik.com/vecteurs-premium/illustration-vectorielle-realiste-smartphone-avant-arriere_174496-1028.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Samsung', '128 Go', 'Bon état', '6 mois', 0, 'samsung', 10, '2025-05-29 20:46:01'),
(8, 'Samsung Galaxy A52 - 128 Go', 'Milieu de gamme très équilibré.', '110000.00', 'https://img.freepik.com/vecteurs-premium/isole-maquette-smartphone-conception-3d-realiste-vue-avant-arriere-illustration-vectorielle_541075-578.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Samsung', '128 Go', 'Comme neuf', '3 mois', 0, 'samsung', 6, '2025-05-29 20:46:01'),
(9, 'Samsung Galaxy Note 10 - 256 Go', 'Grand écran, stylet S-Pen inclus.', '140000.00', 'https://img.freepik.com/vecteurs-libre/style-realiste-pour-nouveau-modele-smartphone_23-2148380821.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Samsung', '256 Go', 'Très bon état', '6 mois', 0, 'samsung', 4, '2025-05-29 20:46:01'),
(10, 'Samsung Galaxy S9 - 64 Go', 'Compact et puissant.', '85000.00', 'https://img.freepik.com/vecteurs-libre/illustration-telephones-portables_1319-183.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Samsung', '64 Go', 'Bon état', '3 mois', 0, 'samsung', 8, '2025-05-29 20:46:01'),
(11, 'Samsung Galaxy S10e - 128 Go', 'Petit format, très performant.', '95000.00', 'https://img.freepik.com/vecteurs-premium/maquettes-smartphone-realistes_66219-1766.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Samsung', '128 Go', 'Très bon état', '6 mois', 0, 'samsung', 7, '2025-05-29 20:46:01'),
(12, 'Samsung Galaxy A12 - 64 Go', 'Bonne autonomie, excellent rapport qualité/prix.', '75000.00', 'https://img.freepik.com/psd-premium/maquette-samsung-galaxy-z-flip-3-flottante_1332-47392.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Samsung', '64 Go', 'Bon état', '3 mois', 0, 'samsung', 10, '2025-05-29 20:46:01'),
(13, 'Xiaomi Redmi Note 10 - 128 Go', 'Bon rapport qualité/prix, écran AMOLED.', '95000.00', 'https://img.freepik.com/vecteurs-premium/smartphone-violet-mi-maquette-modele-isole-illustration-vectorielle_541075-219.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Xiaomi', '128 Go', 'Comme neuf', '6 mois', 0, 'xiaomi', 12, '2025-05-29 20:46:01'),
(14, 'Xiaomi Poco X3 - 128 Go', 'Puissant et endurant.', '99000.00', 'https://img.freepik.com/psd-gratuit/appareil-mobile-realiste-isole_23-2150427382.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Xiaomi', '128 Go', 'Bon état', '3 mois', 0, 'xiaomi', 6, '2025-05-29 20:46:01'),
(15, 'Xiaomi Mi 11 Lite - 128 Go', 'Léger, performant, photo.', '115000.00', 'https://img.freepik.com/photos-gratuite/vitrine-produits-cas-telephone-portable_53876-96836.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Xiaomi', '128 Go', 'Très bon état', '6 mois', 0, 'xiaomi', 5, '2025-05-29 20:46:01'),
(16, 'Xiaomi Redmi 9A - 32 Go', 'Entrée de gamme simple et efficace.', '60000.00', 'https://img.freepik.com/vecteurs-premium/illustration-vectorielle-realiste-smartphone-avant-arriere_174496-1028.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Xiaomi', '32 Go', 'Bon état', '3 mois', 0, 'xiaomi', 10, '2025-05-29 20:46:01'),
(17, 'Xiaomi Mi A3 - 64 Go', 'Android One, rapide et fluide.', '85000.00', 'https://img.freepik.com/psd-premium/maquette-smartphone_333302-934.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Xiaomi', '64 Go', 'Très bon état', '6 mois', 0, 'xiaomi', 8, '2025-05-29 20:46:01'),
(18, 'Xiaomi Redmi Note 8 - 64 Go', 'Polyvalent, excellent pour le prix.', '89000.00', 'https://img.freepik.com/vecteurs-premium/vue-avant-arriere-du-smartphone-realiste-noir-moderne_175392-3.jpg?uid=R105410626&ga=GA1.1.564805598.1748532078&semt=ais_hybrid&w=740', 'Xiaomi', '64 Go', 'Comme neuf', '3 mois', 0, 'xiaomi', 9, '2025-05-29 20:46:01'),
(19, 'Itel P37 - 64 Go', 'Batterie longue durée, écran large.', '45000.00', 'https://promo.sn/wp-content/uploads/2021/11/P37-Pro-800x800-1.jpg', 'Itel', '64 Go', 'Bon état', '3 mois', 0, 'itel', 15, '2025-05-29 20:46:01'),
(20, 'Itel S16 Pro - 64 Go', 'Compact et élégant.', '50000.00', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCbx0vxhdxkHiDVw3LV5MWuyLei1DRm0QRJw&s', 'Itel', '64 Go', 'Très bon état', '3 mois', 0, 'itel', 10, '2025-05-29 20:46:01'),
(21, 'Itel Vision 1 Pro - 32 Go', 'Bon choix économique.', '40000.00', 'https://media.veli.store/media/product/Itel_Vision_1_Pro_L6502_2_GB_32_GB_Dazzle_Black_34fg5755.png', 'Itel', '32 Go', 'Comme neuf', '3 mois', 0, 'itel', 10, '2025-05-29 20:46:01'),
(22, 'Itel A58 - 64 Go', 'Smartphone d’entrée de gamme.', '42000.00', 'https://www.devicespecifications.com/images/model/2b355b10/640/gallery_0.jpg', 'Itel', '64 Go', 'Bon état', '3 mois', 0, 'itel', 12, '2025-05-29 20:46:01'),
(23, 'Itel S17 - 64 Go', 'Photo améliorée, design moderne.', '48000.00', 'https://sn.jumia.is/unsafe/fit-in/500x500/filters:fill(white)/product/66/638001/2.jpg?2227', 'Itel', '64 Go', 'Très bon état', '6 mois', 0, 'itel', 11, '2025-05-29 20:46:01'),
(24, 'Itel A49 - 32 Go', 'Convivial et économique.', '39000.00', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSbooaTMnDybwL-Zk_hoTRPeUBj8sgywC1KDQ&s', 'Itel', '32 Go', 'Bon état', '3 mois', 0, 'itel', 13, '2025-05-29 20:46:01');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(5, 'yama', '$2y$10$tgEtBb.UXG.x0BG6cdy.1OdkLd/lk4Ml.ZZyQi4gaezZKLTxFdV0W', 'yama457@gmail.com', '2025-05-29 20:48:58'),
(6, 'Tidiane', '$2y$10$O0DM2EOfbbaDRbMCLV5cLurnHEcApxCusYQKgdJIt4P8iEUDxzYli', 'tidiane457@gmail.com', '2025-05-30 00:48:26'),
(7, 'samba', '$2y$10$7Z/Zx7ppcgGz7D5aLEw48.59GsDLBF5zce0qO4OyGhddsXmbP.CNa', 'samba15@gmail.com', '2025-05-30 22:29:55'),
(8, 'demba', '$2y$10$iUHgjNiTnNiM6BFhFm8/Fu.zxtNE6is98S6QDs9EVEcHJcG56mkvu', 'demba145@gmail.com', '2025-05-30 23:16:10'),
(9, 'Sadou', '$2y$10$it/HQYWl9VJGiOZMCNXqaumd6Mv.22FK/Xd8li2ZNMcoIbMy1Uwgq', 'sadou12@gmail.com', '2025-05-30 23:54:42');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
