-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS racehub;
USE racehub;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `cycling_participant`
CREATE TABLE `cycling_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `cycling_id` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6FD84039A76ED395` (`user_id`),
  KEY `IDX_6FD84039A1206764` (`cycling_id`)
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

-- Table structure for table `running`
CREATE TABLE `running` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `running_participant`
CREATE TABLE `running_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `running_id` int(11) NOT NULL,
  `time` datetime DEFAULT NULL,
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_229F0410A76ED395` (`user_id`),
  KEY `IDX_229F041083E27A5E` (`running_id`)
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `trail_running_participant`
CREATE TABLE `trail_running_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `trail_running_id` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4ACEDEF3A76ED395` (`user_id`),
  KEY `IDX_4ACEDEF377F47B5C` (`trail_running_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `user`
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraints
ALTER TABLE `cycling_participant`
  ADD CONSTRAINT `FK_6FD84039A1206764` FOREIGN KEY (`cycling_id`) REFERENCES `cycling` (`id`),
  ADD CONSTRAINT `FK_6FD84039A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `running_participant`
  ADD CONSTRAINT `FK_229F041083E27A5E` FOREIGN KEY (`running_id`) REFERENCES `running` (`id`),
  ADD CONSTRAINT `FK_229F0410A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `trail_running_participant`
  ADD CONSTRAINT `FK_4ACEDEF377F47B5C` FOREIGN KEY (`trail_running_id`) REFERENCES `trail_running` (`id`),
  ADD CONSTRAINT `FK_4ACEDEF3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

-- Insert sample data
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250304130343', '2025-03-05 17:49:31', 76),
('DoctrineMigrations\\Version20250304174534', '2025-03-05 17:49:32', 83),
('DoctrineMigrations\\Version20250304180043', '2025-03-05 17:49:32', 10),
('DoctrineMigrations\\Version20250304181338', '2025-03-05 17:49:32', 138);

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `banned`) VALUES
(1, 'john.doe@example.com', '[\"ROLE_USER\"]', '$2y$13$hP1NxHxq5N9ZuR5zAY6YruRYxwAjwwX4RvzVWJvwj9JYfnzaWAn6.', 'John Doe', 0),
(2, 'jane.smith@example.com', '[\"ROLE_USER\"]', '$2y$13$hP1NxHxq5N9ZuR5zAY6YruRYxwAjwwX4RvzVWJvwj9JYfnzaWAn6.', 'Jane Smith', 0),
(3, 'admin@example.com', '[\"ROLE_ADMIN\"]', '$2y$13$hP1NxHxq5N9ZuR5zAY6YruRYxwAjwwX4RvzVWJvwj9JYfnzaWAn6.', 'Admin User', 0);

INSERT INTO `trail_running` (`id`, `name`, `description`, `date`, `distance_km`, `location`, `coordinates`, `unevenness`, `entry_fee`, `available_slots`, `status`, `category`, `image`) VALUES
(1, 'Mountain Challenge 2024', 'Exciting mountain trail running event', '2024-06-15 08:00:00', 21, 'Sierra Nevada', '37.054402,-3.887383', 1200, 30, 100, 'open', 'intermediate', 'mountain-challenge.jpg'),
(2, 'Forest Trail Run', 'Beautiful forest trail running experience', '2024-07-20 09:00:00', 15, 'Black Forest', '37.891416,-4.779483', 800, 25, 50, 'open', 'beginner', 'forest-trail.jpg'),
(3, 'Ultra Trail Marathon', 'Challenging ultra trail marathon', '2024-08-10 06:00:00', 50, 'Pyrenees', '42.642508,1.012573', 2500, 50, 75, 'open', 'advanced', 'ultra-trail.jpg');

INSERT INTO `trail_running_participant` (`id`, `user_id`, `trail_running_id`, `time`, `dorsal`, `banned`) VALUES
(1, 1, 1, '2024-06-15 11:30:00', 101, 0),
(2, 2, 1, '2024-06-15 11:45:00', 102, 0),
(3, 1, 2, '2024-07-20 11:15:00', 201, 0),
(4, 2, 3, '2024-08-10 16:30:00', 301, 0),
(5, 3, 2, '2024-07-20 10:45:00', 202, 0);