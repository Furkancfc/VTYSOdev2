-- --------------------------------------------------------
-- Sunucu:                       127.0.0.1
-- Sunucu sürümü:                8.0.30 - MySQL Community Server - GPL
-- Sunucu İşletim Sistemi:       Win64
-- HeidiSQL Sürüm:               12.1.0.6537
-- --------------------------------------------------------
-- tms_db için veritabanı yapısı dökülüyor
CREATE DATABASE IF NOT EXISTS `tms_db`;
USE `tms_db`;

-- tablo yapısı dökülüyor tms_db.project_list
CREATE TABLE IF NOT EXISTS `project_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` int NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11;

-- tms_db.project_list: ~7 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT IGNORE INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`) VALUES
	(10, 'Proje 1', '																		test															', 3, '2024-01-11', '2024-01-18', 6, '8', '2024-01-11 20:04:02');

-- tablo yapısı dökülüyor tms_db.system_settings
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2;

-- tms_db.system_settings: ~0 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT IGNORE INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
	(1, 'OMU Proje Yönetim Sistemi', 'ekocak227@gmail.com', '+90 555 555 55 55', 'Samsun', '');

-- tablo yapısı dökülüyor tms_db.task_list
CREATE TABLE IF NOT EXISTS `task_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `task` varchar(200) NOT NULL,
  `task_member_day` decimal(20,6) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `task_member` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17;

-- tms_db.task_list: ~3 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT IGNORE INTO `task_list` (`id`, `project_id`, `task`, `task_member_day`, `description`, `status`, `date_created`, `task_member`) VALUES
	(16, 10, 'Görev 1', 6.000000, '								test						', 2, '2024-01-11 21:01:23', '8');

-- tablo yapısı dökülüyor tms_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1 = admin, 2 = staff',
  `avatar` varchar(255) NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9;

-- tms_db.users: ~8 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT IGNORE INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
	(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, 'no-image-available.png', '2020-11-26 10:57:04'),
	(6, 'Yigit', 'Ona', 'vyoh123@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 'no-image-available.png', '2024-01-07 14:34:08'),
	(7, 'Enes', 'Kocak', 'ekocak227@gmail.com', 'c3cdbe10cc8ae980d3587012adc5258f', 1, 'no-image-available.png', '2024-01-07 14:37:22'),
	(8, 'Yavuz', 'Malkoc', 'yavuzmalkoc@gmail.com', '202cb962ac59075b964b07152d234b70', 3, 'no-image-available.png', '2024-01-07 16:52:07');

-- tablo yapısı dökülüyor tms_db.user_productivity
CREATE TABLE IF NOT EXISTS `user_productivity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `task_id` int NOT NULL,
  `comment` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7;

-- tms_db.user_productivity: ~4 rows (yaklaşık) tablosu için veriler indiriliyor
INSERT IGNORE INTO `user_productivity` (`id`, `project_id`, `task_id`, `comment`, `subject`, `date`, `start_time`, `end_time`, `user_id`, `time_rendered`, `date_created`) VALUES
	(1, 1, 1, '							&lt;p&gt;Sample Progress&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Test 1&lt;/li&gt;&lt;li&gt;Test 2&lt;/li&gt;&lt;li&gt;Test 3&lt;/li&gt;&lt;/ul&gt;																			', 'Sample Progress', '2020-12-03', '08:00:00', '10:00:00', 1, 2, '2020-12-03 12:13:28'),
	(2, 1, 1, '							Sample Progress						', 'Sample Progress 2', '2020-12-03', '13:00:00', '14:00:00', 1, 1, '2020-12-03 13:48:28'),
	(3, 1, 2, '							Sample						', 'Test', '2020-12-03', '08:00:00', '09:00:00', 5, 1, '2020-12-03 13:57:22'),
	(4, 1, 2, 'asdasdasd', 'Sample Progress', '2020-12-02', '08:00:00', '10:00:00', 2, 2, '2020-12-03 14:36:30');
