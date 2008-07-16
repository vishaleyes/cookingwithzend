-- MySQL dump 10.10
--
-- Host: localhost    Database: recipe_development
-- ------------------------------------------------------
-- Server version	5.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `comment` text,
  `recipe_id` int(11) default NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `ingredient_measurements`
--
-- Removed for the time being - CL 16/07/08
--DROP TABLE IF EXISTS `ingredient_measurements`;
--CREATE TABLE `ingredient_measurements` (
--  `id` int(11) NOT NULL auto_increment,
--  `ingredient_id` int(11) default NULL,
--  `measurement_id` int(11) default NULL,
--  `position` int(11) default NULL,
--  PRIMARY KEY  (`id`)
--) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`) VALUES
(1, 'Eggs'),(2, 'Self Raising Flour'),(3, 'Plain Flour'),(4, 'Caster Sugar'),(5, 'Icing Sugar'),(6, 'Tinned Plum Tomatoes');

--
-- Table structure for table `measurements`
--

DROP TABLE IF EXISTS `measurements`;
CREATE TABLE `measurements` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `abbreviation` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `abbreviation` (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `measurements`
--

INSERT INTO `measurements` (`id`, `name`, `abbreviation`) VALUES
(1, 'Tablespoon', 'tbsp'),(2, 'Teaspoon', 'tsp'),(3, 'Grams', 'g'),(4, 'Kilograms', 'kg'),(5, 'Litre', 'l'),(6, 'Millilitre', 'ml'),(7, 'Centimetre', 'cm'),(8, 'Quarter', '&frac14;'),(9, 'Half', '&frac12;');

--
-- Table structure for table `method_items`
--

DROP TABLE IF EXISTS `method_items`;
CREATE TABLE `method_items` (
  `id` int(11) NOT NULL auto_increment,
  `description` text NOT NULL,
  `position` int(11) default NULL,
  `recipe_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `recipe_id` int(11) default NULL,
  `value` int(11) default NULL,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `recipe_id` (`recipe_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `recipe_ingredients`
--

DROP TABLE IF EXISTS `recipe_ingredients`;
CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `measurement_id` int(11) default NULL,
  `quantity` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY  (`recipe_id`,`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
CREATE TABLE `recipes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `cooking_time` int(11) default NULL,
  `preparation_time` int(11) default NULL,
  `serves` int(11) default NULL,
  `difficulty` int(2) default '1',
  `freezable` int(1) default '0',
  `created` datetime default NULL,
  `updated` timestamp NULL default NULL,
  `ingredients_count` int(11) default '0',
  `creator_id` int(11) NOT NULL,
  `comments_count` int(11) default '0',
  `ratings_count` int(11) default '0',
  `view_count` int(9) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `user_id` int(11) default NULL,
  `expire` int(11) NOT NULL default '0',
  `updated` int(11) NOT NULL default '0',
  `data` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `foreign_username` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contains web connection user sessions';

--
-- Table structure for table `taggings`
--

DROP TABLE IF EXISTS `taggings`;
CREATE TABLE `taggings` (
  `id` int(11) NOT NULL auto_increment,
  `tag_id` int(11) NOT NULL,
  `taggable_id` int(11) NOT NULL,
  `taggable_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `index_taggings_on_tag_id_and_taggable_id_and_taggable_type` (`tag_id`,`taggable_id`,`taggable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `password` varchar(64) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` varchar(60) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `last_login` datetime default NULL,
  `recipes_count` int(11) default '0',
  `comments_count` int(11) default '0',
  `ratings_count` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

