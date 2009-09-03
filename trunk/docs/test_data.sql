-- MySQL dump 10.11
--
-- Host: localhost    Database: recipes
-- ------------------------------------------------------
-- Server version	5.0.75-0ubuntu10.2

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
-- Dumping data for table `acl_resources`
--

LOCK TABLES `acl_resources` WRITE;
/*!40000 ALTER TABLE `acl_resources` DISABLE KEYS */;
INSERT INTO `acl_resources` VALUES (1,'recipe',1),(2,'recipe:index',1),(3,'recipe:popular',1),(4,'recipe:user',1),(5,'recipe:view',1),(6,'error:error',1),(7,'user:new',1),(8,'user:view',1),(9,'ajax:get-ingredients',2),(10,'ajax:get-measurements',2),(11,'comment:new',2),(12,'recipe:new',2),(13,'ingredient:new',2),(14,'ingredient:edit',3),(15,'recipe:delete',3),(16,'recipe:edit',3),(17,'admin',4),(18,'admin:index',4),(19,'admin:resources',4),(20,'admin:add-resource',4);
/*!40000 ALTER TABLE `acl_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `acl_roles`
--

LOCK TABLES `acl_roles` WRITE;
/*!40000 ALTER TABLE `acl_roles` DISABLE KEYS */;
INSERT INTO `acl_roles` VALUES (1,'guest',NULL),(2,'member',1),(3,'owner',2),(4,'admin',3);
/*!40000 ALTER TABLE `acl_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,'Comment by me',5,'2009-08-26 13:50:11'),(2,1,'Comment by me',5,'2009-08-26 13:50:43'),(3,1,'Comment by me',5,'2009-08-26 13:50:57'),(4,1,'Foo you',5,'2009-08-26 16:18:05'),(5,1,'Wibble',5,'2009-08-26 16:33:12');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'Eggs'),(2,'Self Raising Flour'),(3,'Plain Flour'),(4,'Caster Sugar'),(5,'Icing Sugar'),(6,'Tinned Plum Tomatoes'),(7,'Butter'),(8,'Baking Soda'),(9,'Vanilla pod'),(10,'Cocoa Powder'),(11,'Milk'),(12,'Cooking Chocolate'),(13,'Parsnips'),(14,'Garlic Cloves'),(15,'Root Ginger'),(16,'Olive Oil'),(17,'Vegetable Stock'),(18,'Canned Coconut Milk '),(19,'Thai Red Curry Paste '),(20,'Fresh Corriander'),(21,'Spring Onions'),(22,'Arborio Rice'),(23,'Skinless Salmon Fillet'),(24,'Lemon'),(25,'Fresh Tarragon'),(26,'Soft Brown Sugar'),(27,'Vanilla Extract'),(28,'Snickers Bars'),(29,'Onion'),(30,'Hot Curry Paste'),(31,'Red Lentils'),(32,'Cumin Seeds'),(33,'Sultanas'),(34,'Lime'),(35,'Fat Free Yoghurt'),(36,'Fresh Mint'),(37,'Chapattis');
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `measurements`
--

LOCK TABLES `measurements` WRITE;
/*!40000 ALTER TABLE `measurements` DISABLE KEYS */;
INSERT INTO `measurements` VALUES (1,'Tablespoon','tbsp'),(2,'Teaspoon','tsp'),(3,'Grams','g'),(4,'Kilograms','kg'),(5,'Litre','l'),(6,'Millilitre','ml'),(7,'Centimetre','cm'),(8,'Quarter','&frac14;'),(9,'Half','&frac12;');
/*!40000 ALTER TABLE `measurements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `method_items`
--

LOCK TABLES `method_items` WRITE;
/*!40000 ALTER TABLE `method_items` DISABLE KEYS */;
INSERT INTO `method_items` VALUES (1,'Add the vanilla to the sugar, as much as you desire, then beat the eggs and sugar together until it turns white and fluffy ',NULL,1),(2,'Melt the butter and leave it to cool ',NULL,1),(3,'Melt the Chocolate and leave it to cool, this is best done by placing a glass bowl with the chocolate in over a pan of simmering water',NULL,1),(4,'Mix all the dry ingredients, the cocoa, flour and baking soda then gradually fold the mixture into the beaten sugar and eggs ',NULL,1),(5,'Pour in the milk, mix. Pour in the melted chocolate, mix. Pour in the butter in small doses and mix until you cant see any butter floating on the top',NULL,1),(6,'Butter a rectangular cake tin, then lightly dust with flour (plain will be fine)',NULL,1),(7,'Pour in the mixture, just about half fill the height of the tin, lightly dust the top with a covering of your choice, I use finely chopped almonds and the rest of the dark chocolate sprinkles. ',NULL,1),(8,'Place in a pre-heated oven at 175°C/350°F/Gas Mark 3. Cook for 15-20 minutes depending on how gooey you like your brownies, less time = more gooey',NULL,1),(9,'Remove from oven and cut into squares in the tin, then leave to cool before you remove them.',NULL,1),(10,'Ideally serve warm with vanilla ice cream or cream or both if your feeling really wicked ',NULL,1),(11,'Pre-heat the oven to 200&deg;C/400&deg;F/Gas 6. Place the parsnips, garlic and ginger in a large, deep roasting tin, drizzle over the oil, season with salt and pepper and roast for 20 minutes until golden.',NULL,2),(12,'Pour in half the stock, the coconut milk and curry paste and return to the oven for a further 20 mins until the vegetables are tender.',NULL,2),(13,'Transfer to a liquidiser and blend until creamy and smooth. Add the remaining hot stock and the coriander and check the seasoning. Ladle into warm bowls and serve. ',NULL,2),(14,'Cube the salmon, slice the spring onions.\r\n',NULL,3),(15,'Melt the butter in a pan over a medium heat. Add the spring onions and cook for 3 minutes until they are soft.\r\n',NULL,3),(16,'Pour in the rice and stir well to coat the rice grains in the butter.\r\n',NULL,3),(17,'Pour in the hot vegetable stock, a ladleful at a time, stir continuously and allow the stock to be absorbed into the rice before adding more.\r\n',NULL,3),(18,'Cook the rice for 12 mins (approx), the rice should still be slightly nutty. Then toss in the salmon cubes and cook until the salmon turn opaque and starts to flake.',NULL,3),(19,'Stir in the lemon zest and tarragon, then season with black pepper. Watch if you add salt as Vegetable stock and salmon can be salty. Serve immediately',NULL,3),(20,'Line a 12 hole muffin tin with muffin cases.',NULL,4),(21,'Heat the oven to 200C/180C fan/gas 6. In a bowl, combine the dry ingredients, the sugar, flour, baking soda.\r\n',NULL,4),(22,'Mix the wet ingrediants in a jug then pour into the dry and stir until just combined. ',NULL,4),(23,'Don\'t over mix or muffins will be tough.\r\n',NULL,4),(24,'Chop up 2 and a half of the snickers bars and fold that in.',NULL,4),(25,'Spoon the mix into the cases, chop the rest of the Snickers bar and sprinkle in top.',NULL,4),(26,'Bake for 15-18 minutes until golden and firm. Cool on a rack and see how long you can avoid eating them.',NULL,4),(27,'Finely chop the onion, garlic and ginger. Add to a pan with the curry paste and the cumin seeds. Cook gently for 3 minutes.\r\n',NULL,5),(28,'Add the lentils and the stock to the pan, bring to the boil and simmer gently for 20 minutes.',NULL,5),(29,'Add the sultanas and cook for a further 10 minutes.',NULL,5),(30,'Add the lime and salt and pepper to taste.',NULL,5),(31,'Ladle into bowls and top each serving with a large spoonful of yoghurt and a sprinkling of mint.',NULL,5),(32,'Serve with warm chapatties',NULL,5),(33,'Test addf',NULL,0),(34,'Test',NULL,0),(41,'789999999999',NULL,1);
/*!40000 ALTER TABLE `method_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `recipe_ingredients`
--

LOCK TABLES `recipe_ingredients` WRITE;
/*!40000 ALTER TABLE `recipe_ingredients` DISABLE KEYS */;
INSERT INTO `recipe_ingredients` VALUES (1,1,NULL,4,NULL),(1,2,3,NULL,220),(1,4,3,NULL,400),(1,7,3,NULL,125),(1,8,NULL,1,NULL),(1,9,9,NULL,NULL),(1,10,1,6,NULL),(1,11,6,NULL,100),(1,12,3,NULL,150),(2,13,3,NULL,750),(2,14,NULL,6,NULL),(2,15,7,NULL,4),(2,16,1,1,NULL),(2,17,5,NULL,1),(2,18,6,1,400),(2,19,1,1,NULL),(2,20,1,1,NULL),(3,7,3,NULL,25),(3,17,5,NULL,1),(3,21,NULL,8,NULL),(3,22,3,NULL,350),(3,23,NULL,4,NULL),(3,24,NULL,1,NULL),(3,25,NULL,NULL,NULL),(4,1,NULL,2,NULL),(4,2,3,NULL,250),(4,7,3,NULL,85),(4,8,2,1,NULL),(4,11,6,NULL,250),(4,26,3,NULL,140),(4,27,2,1,NULL),(4,28,NULL,3,NULL),(5,14,NULL,2,NULL),(5,15,7,4,NULL),(5,17,5,NULL,1.5),(5,29,NULL,1,NULL),(5,30,1,1,NULL),(5,31,3,NULL,200),(5,32,2,1,NULL),(5,33,3,NULL,50),(5,34,NULL,1,NULL),(5,35,3,NULL,150),(5,36,1,2,NULL),(5,37,NULL,4,NULL);
/*!40000 ALTER TABLE `recipe_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `recipes`
--

LOCK TABLES `recipes` WRITE;
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
INSERT INTO `recipes` VALUES (1,'Chocolate Brownies',20,40,15,4,0,'2008-07-25 11:35:38','2008-07-25 12:22:22',9,1,0,0,0),(2,'Curried Parsnip Soup',45,15,6,1,1,'2008-07-25 15:20:52','2008-07-25 15:29:12',8,1,0,0,0),(3,'Salmon and Tarragon Risotto',30,10,4,3,0,'2008-09-04 15:52:01','2008-09-04 16:00:40',7,1,0,0,0),(4,'Snicker Muffins',18,15,12,2,0,'2008-09-04 16:04:47','2008-09-04 16:08:41',8,1,0,0,0),(5,'Curried Lentil & Lime Soup',40,10,5,1,1,'2008-09-04 16:13:11','2009-08-26 12:11:46',12,1,5,0,12);
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('16a0ce9fe04484163304258baaa3259c',1239818211,3600,'Default|a:1:{s:4:\"user\";N;}'),('2328c78a2785483619c084d1afa372ce',1251468647,3600,''),('41091c5abf1132c52c94c04c994c5431',1251209527,3600,'Zend_Auth|a:1:{s:7:\"storage\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"password\";s:32:\"185741fc2d690b712f659c616222515f\";s:4:\"name\";s:9:\"Catharsis\";s:5:\"email\";s:23:\"cookery@catharsis.co.uk\";s:6:\"openid\";s:44:\"http://www.flickr.com/photos/catharsisjelly/\";s:7:\"created\";s:19:\"2008-07-17 09:52:36\";s:7:\"updated\";s:19:\"2008-07-17 09:52:36\";s:10:\"last_login\";s:19:\"2009-08-20 15:59:29\";s:6:\"status\";s:6:\"active\";s:14:\"comments_count\";s:1:\"0\";s:13:\"ratings_count\";s:1:\"0\";}}'),('489ba02e20fadd7ab7d864c712760b0e',1236705220,3600,'Default|a:1:{s:4:\"user\";N;}Zend_Auth|a:1:{s:7:\"storage\";a:12:{s:2:\"id\";s:1:\"1\";s:8:\"password\";s:32:\"185741fc2d690b712f659c616222515f\";s:4:\"name\";s:9:\"Catharsis\";s:5:\"email\";s:23:\"cookery@catharsis.co.uk\";s:6:\"openid\";s:44:\"http://www.flickr.com/photos/catharsisjelly/\";s:7:\"created\";s:19:\"2008-07-17 09:52:36\";s:7:\"updated\";s:19:\"2008-07-17 09:52:36\";s:10:\"last_login\";s:19:\"2009-03-10 16:24:21\";s:6:\"status\";s:6:\"active\";s:13:\"recipes_count\";s:1:\"3\";s:14:\"comments_count\";s:1:\"0\";s:13:\"ratings_count\";s:1:\"0\";}}'),('6c5d40d2af951d57f2715251fb951b9c',1250788759,3600,'Zend_Auth|a:1:{s:7:\"storage\";a:11:{s:2:\"id\";s:1:\"1\";s:8:\"password\";s:32:\"185741fc2d690b712f659c616222515f\";s:4:\"name\";s:9:\"Catharsis\";s:5:\"email\";s:23:\"cookery@catharsis.co.uk\";s:6:\"openid\";s:44:\"http://www.flickr.com/photos/catharsisjelly/\";s:7:\"created\";s:19:\"2008-07-17 09:52:36\";s:7:\"updated\";s:19:\"2008-07-17 09:52:36\";s:10:\"last_login\";s:19:\"2009-08-20 12:07:48\";s:6:\"status\";s:6:\"active\";s:14:\"comments_count\";s:1:\"0\";s:13:\"ratings_count\";s:1:\"0\";}}'),('87e127eb02c567298f33cc468fdb92b1',1251113444,3600,''),('8b1a08933fb2facc1e866470aa832b15',1240494098,3600,''),('92456dd662963feda5c031d3598b7817',1251361499,3600,''),('b8d423f38ac596bf59fe2aae35d362bd',1250758221,3600,''),('cbf882b6079e7db089d61d9299e2c3f9',1236253454,3600,'Default|a:1:{s:4:\"user\";N;}'),('d11b89c88a30cb28d09dbe8d063c1cd9',1236860294,3600,''),('e0ac04a902d27db1092970552763d2d5',1236772508,3600,''),('e714758c9234acb00e58939f11f2a70d',1240503523,3600,''),('efa6f8b745e70e9c0698738d999bb555',1236166726,3600,'Default|a:1:{s:4:\"user\";N;}'),('f2fbb9064bceae27e7411d03465c84aa',1251898972,3600,'Zend_Auth|a:1:{s:7:\"storage\";a:14:{s:2:\"id\";s:1:\"1\";s:7:\"role_id\";s:1:\"4\";s:8:\"password\";s:32:\"185741fc2d690b712f659c616222515f\";s:4:\"name\";s:9:\"Catharsis\";s:5:\"email\";s:23:\"cookery@catharsis.co.uk\";s:6:\"openid\";s:44:\"http://www.flickr.com/photos/catharsisjelly/\";s:7:\"created\";s:19:\"2008-07-17 09:52:36\";s:7:\"updated\";s:19:\"2008-07-17 09:52:36\";s:10:\"last_login\";s:19:\"2009-09-02 11:22:27\";s:6:\"status\";s:6:\"active\";s:14:\"comments_count\";s:1:\"5\";s:13:\"ratings_count\";s:1:\"0\";s:13:\"recipes_count\";s:1:\"5\";s:4:\"role\";s:5:\"admin\";}}'),('ff0c001d5d4082816147749146e3f7bd',1236604095,3600,'Default|a:1:{s:4:\"user\";N;}'),('ff70cdb75c30edcb879a00ba669c5a8b',1251475682,3600,'');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `taggings`
--

LOCK TABLES `taggings` WRITE;
/*!40000 ALTER TABLE `taggings` DISABLE KEYS */;
INSERT INTO `taggings` VALUES (1,1,1,'Recipe'),(2,2,1,'Recipe'),(3,3,1,'Recipe'),(4,4,1,'Recipe'),(7,5,2,'Recipe'),(15,5,5,'Recipe'),(6,6,2,'Recipe'),(16,6,5,'Recipe'),(5,7,2,'Recipe'),(14,7,5,'Recipe'),(8,8,3,'Recipe'),(9,9,3,'Recipe'),(10,10,4,'Recipe');
/*!40000 ALTER TABLE `taggings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (10,'cakes'),(1,'chocolate'),(8,'fish'),(9,'italian'),(7,'lunch'),(2,'organic'),(3,'pudding'),(5,'soup'),(4,'sweet'),(6,'vegetarian');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,4,'185741fc2d690b712f659c616222515f','Catharsis','cookery@catharsis.co.uk','http://www.flickr.com/photos/catharsisjelly/','2008-07-17 09:52:36','2008-07-17 09:52:36','2009-09-02 14:32:56','active',5,0,5),(2,NULL,'185741fc2d690b712f659c616222515f','Other','other@catharsis.co.uk','','2009-08-20 15:55:40','2009-08-20 15:55:40','2009-08-20 15:56:24','active',0,0,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-09-03 16:01:28
