-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS racehub;
USE racehub;

-- Table structure for table `user`
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `age` int(11) NOT NULL DEFAULT 0,
  `gender` varchar(1) NOT NULL DEFAULT 'M',
  `image` text NOT NULL DEFAULT 'default.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cycling`
CREATE TABLE `cycling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `distance_km` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `coordinates` varchar(255) DEFAULT NULL,
  `unevenness` int(11) NOT NULL,
  `entry_fee` int(11) DEFAULT NULL,
  `available_slots` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cycling_participant`
CREATE TABLE `cycling_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `cycling_id` int(11) DEFAULT NULL,
  `time` bigint(20) NOT NULL DEFAULT '0',
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `IDX_6FD84039A76ED395` (`user_id`),
  KEY `IDX_6FD84039A1206764` (`cycling_id`),
  CONSTRAINT `FK_6FD84039A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_6FD84039A1206764` FOREIGN KEY (`cycling_id`) REFERENCES `cycling` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `running`
CREATE TABLE `running` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `distance_km` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `coordinates` varchar(255) DEFAULT NULL,
  `entry_fee` int(11) DEFAULT NULL,
  `available_slots` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `running_participant`
CREATE TABLE `running_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `running_id` int(11) NOT NULL,
  `time` bigint(20) NOT NULL DEFAULT '0',
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `IDX_229F0410A76ED395` (`user_id`),
  KEY `IDX_229F041083E27A5E` (`running_id`),
  CONSTRAINT `FK_229F0410A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_229F041083E27A5E` FOREIGN KEY (`running_id`) REFERENCES `running` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `trail_running`
CREATE TABLE `trail_running` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `distance_km` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `coordinates` varchar(255) DEFAULT NULL,
  `unevenness` int(11) NOT NULL,
  `entry_fee` int(11) DEFAULT NULL,
  `available_slots` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `trail_running_participant`
CREATE TABLE `trail_running_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `trail_running_id` int(11) DEFAULT NULL,
  `time` bigint(20) NOT NULL DEFAULT '0',
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_TRP_USER_ID` (`user_id`),
  KEY `IDX_TRP_TRAIL_RUNNING_ID` (`trail_running_id`),
  CONSTRAINT `FK_TRP_USER_ID` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_TRP_TRAIL_RUNNING_ID` FOREIGN KEY (`trail_running_id`) REFERENCES `trail_running` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `doctrine_migration_versions`
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table structure for table `messenger_messages`
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample admin user
INSERT INTO `user` (`email`, `roles`, `password`, `name`, `banned`, `age`, `gender`, `image`) 
VALUES ('admin@example.com', '["ROLE_ADMIN"]', '$2y$13$A8MQM2ZNOi99EW.ML7srhOJsCaybSbexAj/0yXrJs4gQ/2BqMMW2K', 'Admin User', 0, 30, 'M', 'default.png');
-- Password is 'password'

-- Insert sample regular user
INSERT INTO `user` (`email`, `roles`, `password`, `name`, `banned`, `age`, `gender`, `image`) 
VALUES ('user@example.com', '["ROLE_USER"]', '$2y$13$A8MQM2ZNOi99EW.ML7srhOJsCaybSbexAj/0yXrJs4gQ/2BqMMW2K', 'Regular User', 0, 25, 'F', 'default.png');
-- Password is 'password'