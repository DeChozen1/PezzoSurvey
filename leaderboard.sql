-- Adminer 4.8.1 MySQL 10.8.3-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `leaderboard`;
CREATE TABLE `leaderboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `slices` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `leaderboard` (`id`, `fullName`, `email`, `slices`, `image`, `created_at`) VALUES
(1,	'Akash',	'akash@gmail.com',	23,	'./uploads/40ed21be87949043879a492cbc6ffb95.png',	'2023-07-11 22:40:01'),
(2,	'Hello',	'hello@gmail.com',	25,	'./uploads/2d05ef5b066a6be2bfe7b200f0db9558.png',	'2023-07-12 04:47:56')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `fullName` = VALUES(`fullName`), `email` = VALUES(`email`), `slices` = VALUES(`slices`), `image` = VALUES(`image`), `created_at` = VALUES(`created_at`);

-- 2023-07-12 04:53:44
