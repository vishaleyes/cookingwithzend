-- MySQL dump 10.10
--
-- Host: 10.0.3.62    Database: recipe_development
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
-- Dumping data for table `recipe_ingredients`
--


/*!40000 ALTER TABLE `recipe_ingredients` DISABLE KEYS */;
LOCK TABLES `recipe_ingredients` WRITE;
INSERT INTO `recipe_ingredients` VALUES (1,1,NULL,2,0),(1,2,3,0,400),(1,6,6,2,400),(2,4,3,0,400),(2,5,3,0,400);
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipe_ingredients` ENABLE KEYS */;

--
-- Dumping data for table `users`
--


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'5b8abbfc38cda495','Catharsis','cookery@catharsis.co.uk','2008-07-17 09:52:36','2008-07-17 09:52:36','2008-07-17 09:52:36',0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Dumping data for table `taggings`
--


/*!40000 ALTER TABLE `taggings` DISABLE KEYS */;
LOCK TABLES `taggings` WRITE;
INSERT INTO `taggings` VALUES (1,1,1,'Recipe'),(3,1,2,'Recipe'),(2,2,1,'Recipe'),(4,3,2,'Recipe');
UNLOCK TABLES;
/*!40000 ALTER TABLE `taggings` ENABLE KEYS */;

--
-- Dumping data for table `method_items`
--


/*!40000 ALTER TABLE `method_items` DISABLE KEYS */;
LOCK TABLES `method_items` WRITE;
INSERT INTO `method_items` VALUES (1,'Chuck it all in a bowl',NULL,1),(2,'Mix for 1 hour',NULL,1),(3,'Cook for 1 hour',NULL,1),(4,'Eat everything in one go',NULL,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `method_items` ENABLE KEYS */;

--
-- Dumping data for table `recipes`
--


/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
LOCK TABLES `recipes` WRITE;
INSERT INTO `recipes` VALUES (1,'Test 1',1,1,1,1,0,'2008-07-17 09:55:20','2008-07-17 08:56:16',3,1,0,0,0),(2,'Test 2',1,1,1,1,0,'2008-07-17 10:05:24','2008-07-17 09:07:02',2,1,0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;

--
-- Dumping data for table `tags`
--


/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
LOCK TABLES `tags` WRITE;
INSERT INTO `tags` VALUES (3,'bar'),(2,'foo'),(1,'test');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

