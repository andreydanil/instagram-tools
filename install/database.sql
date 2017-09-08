-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 19 Octobre 2016 à 04:22
-- Version du serveur :  10.1.16-MariaDB
-- Version de PHP :  5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `tbl_instagram_account`
--

DROP TABLE IF EXISTS `tbl_instagram_account`;
CREATE TABLE `tbl_instagram_account` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_instagram_follow`
--

DROP TABLE IF EXISTS `tbl_instagram_follow`;
CREATE TABLE `tbl_instagram_follow` (
  `id` int(11) NOT NULL,
  `pk` varchar(128) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `account_name` varchar(128) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_instagram_save`
--

DROP TABLE IF EXISTS `tbl_instagram_save`;
CREATE TABLE `tbl_instagram_save` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `description` text,
  `image` text,
  `uid` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_schedule`
--

DROP TABLE IF EXISTS `tbl_schedule`;
CREATE TABLE `tbl_schedule` (
  `id` int(11) NOT NULL,
  `schedule_type` varchar(128) DEFAULT NULL,
  `media_id` varchar(255) DEFAULT NULL,
  `code` varchar(128) DEFAULT NULL,
  `social` varchar(128) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `fid` varchar(125) DEFAULT NULL,
  `account` varchar(128) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `access_token` text,
  `cid` varchar(128) DEFAULT NULL,
  `message` text,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `url` text,
  `image` text,
  `caption` varchar(30) DEFAULT NULL,
  `time_post` datetime DEFAULT NULL,
  `delete` int(1) DEFAULT '0',
  `deplay` int(11) DEFAULT '5',
  `maximum` int(11) DEFAULT '1',
  `repeat_post` int(1) DEFAULT '0',
  `repeat_time` int(11) DEFAULT NULL,
  `repeat_end` date DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `message_error` text,
  `uid` int(11) DEFAULT '0',
  `status` int(1) DEFAULT '1',
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_settings`
--

DROP TABLE IF EXISTS `tbl_settings`;
CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL,
  `register` int(1) DEFAULT '1',
  `maximum_account` int(11) DEFAULT '1',
  `upload_max_size` int(11) DEFAULT '1',
  `auto_active_user` int(1) DEFAULT '1',
  `default_language` varchar(64) DEFAULT 'en',
  `default_timezone` varchar(128) DEFAULT 'Asia/Ho_Chi_Minh',
  `default_deplay` int(4) DEFAULT '180',
  `minimum_deplay` int(1) DEFAULT '180',
  `purchase_code` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `facebook_secret` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `google_secret` varchar(255) DEFAULT NULL,
  `twitter_id` varchar(255) DEFAULT NULL,
  `twitter_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `title`, `description`, `keywords`, `logo`, `theme`, `register`, `maximum_account`, `upload_max_size`, `auto_active_user`, `default_language`, `default_timezone`, `default_deplay`, `minimum_deplay`, `purchase_code`, `facebook_id`, `facebook_secret`, `google_id`, `google_secret`, `twitter_id`, `twitter_secret`) VALUES
(1, 'Instagram Tools For Marketing', 'Instagram Tools For Marketing', 'Instagram Tools For Marketing', 'assets/img/logo.png', NULL, 1, 1, 30, 0, 'en', 'Asia/Ho_Chi_Minh', 180, 180, 'ITEM-PURCHASE-CODE', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `admin` int(1) DEFAULT '0',
  `pid` varchar(128) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `timezone` varchar(255) NOT NULL,
  `maximum_account` int(11) DEFAULT '1',
  `status` int(1) DEFAULT '1',
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `admin`, `pid`, `type`, `fullname`, `email`, `password`, `timezone`, `maximum_account`, `status`, `changed`, `created`) VALUES
(1, 1, NULL, 'direct', 'admin_fullname', 'admin_email', 'admin_password', '', 100, 1, '0000-00-00 00:00:00', '2016-09-27 00:00:00');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `tbl_instagram_account`
--
ALTER TABLE `tbl_instagram_account`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_instagram_follow`
--
ALTER TABLE `tbl_instagram_follow`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_instagram_save`
--
ALTER TABLE `tbl_instagram_save`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_schedule`
--
ALTER TABLE `tbl_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `tbl_instagram_account`
--
ALTER TABLE `tbl_instagram_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tbl_instagram_follow`
--
ALTER TABLE `tbl_instagram_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tbl_instagram_save`
--
ALTER TABLE `tbl_instagram_save`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tbl_schedule`
--
ALTER TABLE `tbl_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
