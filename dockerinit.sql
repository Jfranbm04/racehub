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
  `gender` varchar(1) NOT NULL,
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
  `time` bigint(20) DEFAULT NULL,
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_229F0410A76ED395` (`user_id`),
  KEY `IDX_229F041083E27A5E` (`running_id`)
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

-- Table structure for table `cycling_participant`
CREATE TABLE `cycling_participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `cycling_id` int(11) DEFAULT NULL,
  `time` bigint(20) DEFAULT NULL,
  `dorsal` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6FD84039A76ED395` (`user_id`),
  KEY `IDX_6FD84039A1206764` (`cycling_id`)
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
  `time` bigint(20) DEFAULT NULL,
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
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `age` int(11) NOT NULL DEFAULT 0,
  `gender` varchar(1) NOT NULL DEFAULT 'M',
  `image` text NOT NULL DEFAULT 'default.png',
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

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `banned`, `age`, `gender`, `image`) VALUES
(1, 'john.doe@example.com', '[\"ROLE_USER\"]', '$2y$13$hP1NxHxq5N9ZuR5zAY6YruRYxwAjwwX4RvzVWJvwj9JYfnzaWAn6.', 'John Doe', 0, 30, 'M', 'default.png'),
(2, 'jane.smith@example.com', '[\"ROLE_USER\"]', '$2y$13$hP1NxHxq5N9ZuR5zAY6YruRYxwAjwwX4RvzVWJvwj9JYfnzaWAn6.', 'Jane Smith', 0, 28, 'F', 'default.png'),
(3, 'admin@example.com', '[\"ROLE_ADMIN\"]', '$2y$13$hP1NxHxq5N9ZuR5zAY6YruRYxwAjwwX4RvzVWJvwj9JYfnzaWAn6.', 'Admin User', 0, 35, 'M', 'default.png');

INSERT INTO `trail_running` (`id`, `name`, `description`, `date`, `distance_km`, `location`, `coordinates`, `unevenness`, `entry_fee`, `available_slots`, `status`, `category`, `image`, `gender`) VALUES
(1, 'Mountain Challenge 2024', 'Exciting mountain trail running event', '2024-06-15 08:00:00', 21, 'Sierra Nevada', '37.054402,-3.887383', 1200, 30, 100, 'open', 'intermediate', 'mountain-challenge.jpg', 'M'),
(2, 'Forest Trail Run', 'Beautiful forest trail running experience', '2024-07-20 09:00:00', 15, 'Black Forest', '37.891416,-4.779483', 800, 25, 50, 'open', 'beginner', 'forest-trail.jpg', 'F'),
(3, 'Ultra Trail Marathon', 'Challenging ultra trail marathon', '2024-08-10 06:00:00', 50, 'Pyrenees', '42.642508,1.012573', 2500, 50, 75, 'open', 'advanced', 'ultra-trail.jpg', 'M');

INSERT INTO `trail_running_participant` (`id`, `user_id`, `trail_running_id`, `time`, `dorsal`, `banned`) VALUES
(1, 1, 1, 1718450400000, 101, 0),
(2, 2, 1, 1718451100000, 102, 0),
(3, 1, 2, 1721894100000, 201, 0),
(4, 2, 3, 1723392600000, 301, 0),
(5, 3, 2, 1721892300000, 202, 0);

-- Insert sample cycling events
INSERT INTO `cycling` (`id`, `name`, `description`, `date`, `distance_km`, `location`, `coordinates`, `unevenness`, `entry_fee`, `available_slots`, `status`, `category`, `image`, `gender`) VALUES
(1, 'Spring City Tour 2024', 'Urban cycling event through city landmarks', '2024-04-20 09:00:00', 30, 'Madrid City', '40.416775,-3.703790', 500, 25, 150, 'open', 'beginner', 'city-tour.jpg', 'M'),
(2, 'Mountain Bike Challenge', 'Challenging mountain bike race', '2024-05-15 08:00:00', 45, 'Sierra Mountains', '40.589721,-4.148821', 1500, 40, 100, 'open', 'advanced', 'mountain-bike.jpg', 'M'),
(3, 'Coastal Route Race', 'Scenic coastal cycling competition', '2024-06-01 07:30:00', 60, 'Costa del Sol', '36.721261,-4.421265', 800, 35, 200, 'open', 'intermediate', 'coastal-route.jpg', 'F');

-- Insert sample cycling participants
INSERT INTO `cycling_participant` (`id`, `user_id`, `cycling_id`, `time`, `dorsal`, `banned`) VALUES
(1, 1, 1, 1713694200000, 101, 0),
(2, 2, 1, 1713695100000, 102, 0),
(3, 1, 2, 1715766000000, 201, 0),
(4, 3, 3, 1717236600000, 301, 0);

-- Insert sample running events
INSERT INTO `running` (`id`, `name`, `description`, `date`, `distance_km`, `location`, `coordinates`, `entry_fee`, `available_slots`, `status`, `category`, `image`, `gender`) VALUES
(1, 'City Marathon 2024', 'Annual city marathon event', '2024-05-01 07:00:00', 42, 'Barcelona', '41.385063,2.173404', 45, 500, 'open', 'advanced', 'city-marathon.jpg', 'M'),
(2, 'Summer Night Run', 'Evening running event through parks', '2024-07-15 20:00:00', 10, 'Valencia Parks', '39.474332,-0.376291', 20, 300, 'open', 'beginner', 'night-run.jpg', 'F'),
(3, 'Cross Country Challenge', 'Cross country running competition', '2024-09-10 08:30:00', 15, 'Natural Park', '37.891416,-4.779483', 30, 200, 'open', 'intermediate', 'cross-country.jpg', 'M');

-- Insert sample running participants
INSERT INTO `running_participant` (`id`, `user_id`, `running_id`, `time`, `dorsal`, `banned`) VALUES
(1, 1, 1, 1714554000000, 401, 0),
(2, 2, 1, 1714555800000, 402, 0),
(3, 3, 2, 1721592900000, 501, 0),
(4, 1, 3, 1725939600000, 601, 0);