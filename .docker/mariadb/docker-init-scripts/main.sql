CREATE TABLE IF NOT EXISTS `contactus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(600) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
