-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2019 at 07:25 PM
-- Server version: 5.7.25-0ubuntu0.16.04.2
-- PHP Version: 7.1.26-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bark`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL,
  `name` text,
  `url` text,
  `icon` text,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `name`, `url`, `icon`, `order`) VALUES
(4, 'Пользователи/Администраторы', '/admins', 'users', 1),
(22, 'Настройка/Параметры', '/config', 'cogs', 19),
(23, 'Настройка/Меню CMS', '/adminmenu', 'bars', 20);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `key` text NOT NULL,
  `value` text NOT NULL,
  `description` text NOT NULL,
  `value_type` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `key`, `value`, `description`) VALUES
(1, 'adminEmail', 'info@sisols.com', 'Email администратора'),
(2, 'adminName', 'Bark.ru', 'Имя администратора'),
(7, 'siteURL', 'http://bark', 'URL-адрес сайта');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_city` int(11) DEFAULT '0',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `phone` text COLLATE utf8_unicode_ci,
  `fio` text COLLATE utf8_unicode_ci,
  `email` text COLLATE utf8_unicode_ci,
  `datebirth` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `gender` tinyint(4) DEFAULT '0',
  `address` text COLLATE utf8_unicode_ci,
  `id_pharmacy` int(11) DEFAULT '0',
  `company` text COLLATE utf8_unicode_ci,
  `inn` text COLLATE utf8_unicode_ci,
  `ogrn` text COLLATE utf8_unicode_ci,
  `address_legal` text COLLATE utf8_unicode_ci,
  `address_post` text COLLATE utf8_unicode_ci,
  `bankaccount` text COLLATE utf8_unicode_ci,
  `bank` text COLLATE utf8_unicode_ci,
  `bik` text COLLATE utf8_unicode_ci,
  `coraccount` text COLLATE utf8_unicode_ci,
  `contact_person` text COLLATE utf8_unicode_ci,
  `contract` text COLLATE utf8_unicode_ci,
  `passport` text COLLATE utf8_unicode_ci,
  `address_propiska` text COLLATE utf8_unicode_ci,
  `address_real` text COLLATE utf8_unicode_ci,
  `partner_type` tinyint(4) NOT NULL DEFAULT '0',
  `rights` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favourites` text COLLATE utf8_unicode_ci,
  `linked` int(11) DEFAULT '0',
  `id_partner` int(11) DEFAULT '0',
  `apikey` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `id_city`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `status`, `created_at`, `updated_at`, `phone`, `fio`, `email`, `datebirth`, `type`, `gender`, `address`, `id_pharmacy`, `company`, `inn`, `ogrn`, `address_legal`, `address_post`, `bankaccount`, `bank`, `bik`, `coraccount`, `contact_person`, `contract`, `passport`, `address_propiska`, `address_real`, `partner_type`, `rights`, `favourites`, `linked`, `id_partner`, `apikey`) VALUES
(2, 0, 'admin', 'wGTbvqI_7ciEAhergJ2BCFjCH9wUpCsd', '$2y$13$kL2SE1IPpOLNNsJ4j72IAeRhMEwyJjvQfxfFUWkcTOiYudy62EV2.', NULL, 10, 1530265004, 1530265004, '', '', NULL, 0, 0, 0, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_menu`
--
ALTER TABLE `admin_menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `id_2` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_menu`
--
ALTER TABLE `admin_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
