-- MySQL dump 10.10
--
-- Host: 10.0.3.62    Database: recipes_production
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
  `user_id` int(11) unsigned NOT NULL,
  `comment` text collate utf8_unicode_ci,
  `recipe_id` int(11) unsigned NOT NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `recipe_id` (`recipe_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comments`
--


/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
LOCK TABLES `comments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL auto_increment,
  `name` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ingredients`
--


/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
LOCK TABLES `ingredients` WRITE;
INSERT INTO `ingredients` VALUES (1,'Eggs'),(2,'Self Raising Flour'),(3,'Plain Flour'),(4,'Caster Sugar'),(5,'Icing Sugar'),(6,'Tinned Plum Tomatoes'),(7,'Butter'),(8,'Baking Soda'),(9,'Vanilla pod'),(10,'Cocoa Powder'),(11,'Milk'),(12,'Cooking Chocolate'),(13,'Parsnips'),(14,'Garlic Cloves'),(15,'Root Ginger'),(16,'Olive Oil'),(17,'Vegetable Stock'),(18,'Canned Coconut Milk '),(19,'Thai Red Curry Paste '),(20,'Fresh Corriander'),(21,'Spring Onions'),(22,'Arborio Rice'),(23,'Skinless Salmon Fillet'),(24,'Lemon'),(25,'Fresh Tarragon'),(26,'Soft Brown Sugar'),(27,'Vanilla Extract'),(28,'Snickers Bars'),(29,'Onion'),(30,'Hot Curry Paste'),(31,'Red Lentils'),(32,'Cumin Seeds'),(33,'Sultanas'),(34,'Lime'),(35,'Fat Free Yoghurt'),(36,'Fresh Mint'),(37,'Chapattis');
UNLOCK TABLES;
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;

--
-- Table structure for table `measurements`
--

DROP TABLE IF EXISTS `measurements`;
CREATE TABLE `measurements` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `abbreviation` (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `measurements`
--


/*!40000 ALTER TABLE `measurements` DISABLE KEYS */;
LOCK TABLES `measurements` WRITE;
INSERT INTO `measurements` VALUES (1,'Tablespoon','tbsp'),(2,'Teaspoon','tsp'),(3,'Grams','g'),(4,'Kilograms','kg'),(5,'Litre','l'),(6,'Millilitre','ml'),(7,'Centimetre','cm'),(8,'Quarter','&frac14;'),(9,'Half','&frac12;');
UNLOCK TABLES;
/*!40000 ALTER TABLE `measurements` ENABLE KEYS */;

--
-- Table structure for table `method_items`
--

DROP TABLE IF EXISTS `method_items`;
CREATE TABLE `method_items` (
  `id` int(11) NOT NULL auto_increment,
  `description` text collate utf8_unicode_ci NOT NULL,
  `position` int(11) default NULL,
  `recipe_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `recipe_id` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `method_items`
--


/*!40000 ALTER TABLE `method_items` DISABLE KEYS */;
LOCK TABLES `method_items` WRITE;
INSERT INTO `method_items` VALUES (1,'Add the vanilla to the sugar, as much as you desire, then beat the eggs and sugar together until it turns white and fluffy ',NULL,1),(2,'Melt the butter and leave it to cool ',NULL,1),(3,'Melt the Chocolate and leave it to cool, this is best done by placing a glass bowl with the chocolate in over a pan of simmering water',NULL,1),(4,'Mix all the dry ingredients, the cocoa, flour and baking soda then gradually fold the mixture into the beaten sugar and eggs ',NULL,1),(5,'Pour in the milk, mix. Pour in the melted chocolate, mix. Pour in the butter in small doses and mix until you cant see any butter floating on the top',NULL,1),(6,'Butter a rectangular cake tin, then lightly dust with flour (plain will be fine)',NULL,1),(7,'Pour in the mixture, just about half fill the height of the tin, lightly dust the top with a covering of your choice, I use finely chopped almonds and the rest of the dark chocolate sprinkles. ',NULL,1),(8,'Place in a pre-heated oven at 175°C/350°F/Gas Mark 3. Cook for 15-20 minutes depending on how gooey you like your brownies, less time = more gooey',NULL,1),(9,'Remove from oven and cut into squares in the tin, then leave to cool before you remove them.',NULL,1),(10,'Ideally serve warm with vanilla ice cream or cream or both if your feeling really wicked ',NULL,1),(11,'Pre-heat the oven to 200&deg;C/400&deg;F/Gas 6. Place the parsnips, garlic and ginger in a large, deep roasting tin, drizzle over the oil, season with salt and pepper and roast for 20 minutes until golden.',NULL,2),(12,'Pour in half the stock, the coconut milk and curry paste and return to the oven for a further 20 mins until the vegetables are tender.',NULL,2),(13,'Transfer to a liquidiser and blend until creamy and smooth. Add the remaining hot stock and the coriander and check the seasoning. Ladle into warm bowls and serve. ',NULL,2),(14,'Cube the salmon, slice the spring onions.\r\n',NULL,3),(15,'Melt the butter in a pan over a medium heat. Add the spring onions and cook for 3 minutes until they are soft.\r\n',NULL,3),(16,'Pour in the rice and stir well to coat the rice grains in the butter.\r\n',NULL,3),(17,'Pour in the hot vegetable stock, a ladleful at a time, stir continuously and allow the stock to be absorbed into the rice before adding more.\r\n',NULL,3),(18,'Cook the rice for 12 mins (approx), the rice should still be slightly nutty. Then toss in the salmon cubes and cook until the salmon turn opaque and starts to flake.',NULL,3),(19,'Stir in the lemon zest and tarragon, then season with black pepper. Watch if you add salt as Vegetable stock and salmon can be salty. Serve immediately',NULL,3),(20,'Line a 12 hole muffin tin with muffin cases.',NULL,4),(21,'Heat the oven to 200C/180C fan/gas 6. In a bowl, combine the dry ingredients, the sugar, flour, baking soda.\r\n',NULL,4),(22,'Mix the wet ingrediants in a jug then pour into the dry and stir until just combined. ',NULL,4),(23,'Don\'t over mix or muffins will be tough.\r\n',NULL,4),(24,'Chop up 2 and a half of the snickers bars and fold that in.',NULL,4),(25,'Spoon the mix into the cases, chop the rest of the Snickers bar and sprinkle in top.',NULL,4),(26,'Bake for 15-18 minutes until golden and firm. Cool on a rack and see how long you can avoid eating them.',NULL,4),(27,'Finely chop the onion, garlic and ginger. Add to a pan with the curry paste and the cumin seeds. Cook gently for 3 minutes.\r\n',NULL,5),(28,'Add the lentils and the stock to the pan, bring to the boil and simmer gently for 20 minutes.',NULL,5),(29,'Add the sultanas and cook for a further 10 minutes.',NULL,5),(30,'Add the lime and salt and pepper to taste.',NULL,5),(31,'Ladle into bowls and top each serving with a large spoonful of yoghurt and a sprinkling of mint.',NULL,5),(32,'Serve with warm chapatties',NULL,5);
UNLOCK TABLES;
/*!40000 ALTER TABLE `method_items` ENABLE KEYS */;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `recipe_id` int(11) unsigned NOT NULL,
  `value` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`recipe_id`,`user_id`),
  KEY `recipe_id` (`recipe_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ratings`
--


/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
LOCK TABLES `ratings` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;

--
-- Table structure for table `recipe_ingredients`
--

DROP TABLE IF EXISTS `recipe_ingredients`;
CREATE TABLE `recipe_ingredients` (
  `recipe_id` int(11) unsigned NOT NULL,
  `ingredient_id` int(11) unsigned NOT NULL,
  `measurement_id` int(11) unsigned default NULL,
  `quantity` int(11) unsigned default NULL,
  `amount` float unsigned default NULL,
  PRIMARY KEY  (`recipe_id`,`ingredient_id`),
  CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `recipe_ingredients`
--


/*!40000 ALTER TABLE `recipe_ingredients` DISABLE KEYS */;
LOCK TABLES `recipe_ingredients` WRITE;
INSERT INTO `recipe_ingredients` VALUES (1,1,NULL,4,NULL),(1,2,3,NULL,220),(1,4,3,NULL,400),(1,7,3,NULL,125),(1,8,NULL,1,NULL),(1,9,9,NULL,NULL),(1,10,1,6,NULL),(1,11,6,NULL,100),(1,12,3,NULL,150),(2,13,3,NULL,750),(2,14,NULL,6,NULL),(2,15,7,NULL,4),(2,16,1,1,NULL),(2,17,5,NULL,1),(2,18,6,1,400),(2,19,1,1,NULL),(2,20,1,1,NULL),(3,7,3,NULL,25),(3,17,5,NULL,1),(3,21,NULL,8,NULL),(3,22,3,NULL,350),(3,23,NULL,4,NULL),(3,24,NULL,1,NULL),(3,25,NULL,NULL,NULL),(4,1,NULL,2,NULL),(4,2,3,NULL,250),(4,7,3,NULL,85),(4,8,2,1,NULL),(4,11,6,NULL,250),(4,26,3,NULL,140),(4,27,2,1,NULL),(4,28,NULL,3,NULL),(5,14,NULL,2,NULL),(5,15,7,4,NULL),(5,17,5,NULL,1.5),(5,29,NULL,1,NULL),(5,30,1,1,NULL),(5,31,3,NULL,200),(5,32,2,1,NULL),(5,33,3,NULL,50),(5,34,NULL,1,NULL),(5,35,3,NULL,150),(5,36,1,2,NULL),(5,37,NULL,4,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipe_ingredients` ENABLE KEYS */;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
CREATE TABLE `recipes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `cooking_time` int(11) unsigned default NULL,
  `preparation_time` int(11) unsigned default NULL,
  `serves` int(11) default NULL,
  `difficulty` int(2) unsigned default '1',
  `freezable` int(1) default '0',
  `created` datetime default NULL,
  `updated` datetime default NULL,
  `ingredients_count` int(11) default '0',
  `creator_id` int(11) NOT NULL,
  `comments_count` int(11) default '0',
  `ratings_count` int(11) default '0',
  `view_count` int(9) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `recipes`
--


/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
LOCK TABLES `recipes` WRITE;
INSERT INTO `recipes` VALUES (1,'Chocolate Brownies',20,40,15,4,0,'2008-07-25 11:35:38','2008-07-25 12:22:22',9,1,0,0,0),(2,'Curried Parsnip Soup',45,15,6,1,1,'2008-07-25 15:20:52','2008-07-25 15:29:12',8,1,0,0,0),(3,'Salmon and Tarragon Risotto',30,10,4,3,0,'2008-09-04 15:52:01','2008-09-04 16:00:40',7,1,0,0,0),(4,'Snicker Muffins',18,15,12,2,0,'2008-09-04 16:04:47','2008-09-04 16:08:41',8,1,0,0,0),(5,'Curried Lentil &amp; Lime Soup',40,10,5,1,1,'2008-09-04 16:13:11','2008-09-04 16:24:31',12,1,0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;

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
-- Dumping data for table `sessions`
--


/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
LOCK TABLES `sessions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

--
-- Table structure for table `taggings`
--

DROP TABLE IF EXISTS `taggings`;
CREATE TABLE `taggings` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `tag_id` int(11) NOT NULL,
  `taggable_id` int(11) NOT NULL,
  `taggable_type` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `index_taggings_on_tag_id_and_taggable_id_and_taggable_type` (`tag_id`,`taggable_id`,`taggable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `taggings`
--


/*!40000 ALTER TABLE `taggings` DISABLE KEYS */;
LOCK TABLES `taggings` WRITE;
INSERT INTO `taggings` VALUES (1,1,1,'Recipe'),(2,2,1,'Recipe'),(3,3,1,'Recipe'),(4,4,1,'Recipe'),(7,5,2,'Recipe'),(15,5,5,'Recipe'),(6,6,2,'Recipe'),(16,6,5,'Recipe'),(5,7,2,'Recipe'),(14,7,5,'Recipe'),(8,8,3,'Recipe'),(9,9,3,'Recipe'),(10,10,4,'Recipe');
UNLOCK TABLES;
/*!40000 ALTER TABLE `taggings` ENABLE KEYS */;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags`
--


/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
LOCK TABLES `tags` WRITE;
INSERT INTO `tags` VALUES (10,'cakes'),(1,'chocolate'),(8,'fish'),(9,'italian'),(7,'lunch'),(2,'organic'),(3,'pudding'),(5,'soup'),(4,'sweet'),(6,'vegetarian');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `password` varchar(64) collate utf8_unicode_ci NOT NULL,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `openid` varchar(255) collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `last_login` datetime default NULL,
  `status` enum('pending','banned','admin','active','suspended') collate utf8_unicode_ci default 'pending',
  `recipes_count` int(11) default '0',
  `comments_count` int(11) default '0',
  `ratings_count` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'5b8abbfc38cda495','Catharsis','cookery@catharsis.co.uk','http://www.flickr.com/photos/catharsisjelly/','2008-07-17 09:52:36','2008-07-17 09:52:36','2008-09-04 15:44:01','active',3,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

