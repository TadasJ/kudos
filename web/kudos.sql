SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `user_role`;
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `value` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `user_role` (`id`, `title`, `value`) VALUES
(1, 'Administrator', 'admin'),
(2, 'Project Manager', 'manager'),
(3, 'Employee', 'employee');

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `username` varchar(128) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(64) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `total_points` int(11) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `create_at` timestamp NOT NULL DEFAULT 0,
  `update_at` timestamp NOT NULL DEFAULT 0,
  `login_at` timestamp NOT NULL DEFAULT 0
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `role_id`, `username`, `password_hash`, `first_name`, `last_name`, `total_points`, `is_active`, `create_at`, `update_at`, `login_at`) VALUES
(1, 1, 'Admin', '$2y$13$mySup3r4w3someS3cr3tMerAoG6NkgDEzwIs2UokQ3GxTH.JSiMIe', NULL, NULL, NULL, 1, '2015-05-12 21:12:49', '2015-05-12 21:12:49', '2015-05-13 10:50:21'),
(2, 3, 'tadas.juscius@gmail.com', '$2y$13$mySup3r4w3someS3cr3tMeZX7aJFkeh0Dw9e0/Bw2nteFbaCqJ616', 'Tadas', 'Juščius', NULL, 1, '2015-05-13 15:04:27', '2015-05-13 20:15:03', '2015-05-13 14:51:11'),
(3, 2, 'inga@datadog.lt', '$2y$13$mySup3r4w3someS3cr3tMe3NxurerUY.7Qv7LdqPsQ19JMSAJjYL2', 'Inga', 'Bogarevič', NULL, 1, '2015-05-13 20:47:18', '2015-05-13 20:47:18', '2015-05-13 20:47:18');

DROP TABLE IF EXISTS `achievement`;
CREATE TABLE IF NOT EXISTS `achievement` (
  `id` int(10) unsigned NOT NULL,
  `goal_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  `title` varchar(512) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `goal`;
CREATE TABLE IF NOT EXISTS `goal` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(512) NOT NULL,
  `value` varchar(128) DEFAULT NULL,
  `points_reward` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 0,
  `create_at` timestamp NOT NULL DEFAULT 0,
  `update_at` timestamp NOT NULL DEFAULT 0
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `goal` (`id`, `title`, `value`, `points_reward`, `is_active`, `create_at`, `update_at`) VALUES
(1, 'Find a bug in another developer''s code.', NULL, 5, 1, '2015-05-13 22:07:33', '2015-05-13 22:07:33'),
(2, 'Get merge request approved with no fixes.', NULL, 2, 1, '2015-05-13 22:12:10', '2015-05-13 21:12:34');

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(10) unsigned NOT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT 0,
  `update_at` timestamp NOT NULL DEFAULT 0
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `team` (`id`, `manager_id`, `name`, `create_at`, `update_at`) VALUES
(1, 3, 'Room 2', '2015-05-13 20:48:58', '2015-05-13 20:15:23');

DROP TABLE IF EXISTS `team_to_user`;
CREATE TABLE IF NOT EXISTS `team_to_user` (
  `id` int(10) unsigned NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `team_to_user` (`id`, `team_id`, `user_id`) VALUES
(4, 1, 2);

ALTER TABLE `achievement`
  ADD PRIMARY KEY (`id`), ADD KEY `goal_id` (`goal_id`), ADD KEY `user_id` (`user_id`), ADD KEY `manager_id` (`manager_id`);

ALTER TABLE `goal`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `team`
  ADD PRIMARY KEY (`id`), ADD KEY `manager_id` (`manager_id`);

ALTER TABLE `team_to_user`
  ADD PRIMARY KEY (`id`), ADD KEY `team_id` (`team_id`), ADD KEY `user_id` (`user_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`), ADD KEY `role_id` (`role_id`);

ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `achievement`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `goal`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
ALTER TABLE `team`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
ALTER TABLE `team_to_user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `user_role`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

ALTER TABLE `achievement`
ADD CONSTRAINT `achievement_to_goal` FOREIGN KEY (`goal_id`) REFERENCES `goal` (`id`),
ADD CONSTRAINT `achievement_to_manager` FOREIGN KEY (`manager_id`) REFERENCES `user` (`id`),
ADD CONSTRAINT `achievement_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `team`
ADD CONSTRAINT `team_to_manager` FOREIGN KEY (`manager_id`) REFERENCES `user` (`id`);

ALTER TABLE `team_to_user`
ADD CONSTRAINT `team_to_user_to_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
ADD CONSTRAINT `team_to_user_to_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `user`
ADD CONSTRAINT `user_to_user_role` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
