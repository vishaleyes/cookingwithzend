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
INSERT INTO `recipe_ingredients` VALUES 
(1, 1, NULL, 4, NULL),
(1, 2, 3, NULL, 220),
(1, 4, 3, NULL, 400),
(1, 7, 3, NULL, 125),
(1, 8, NULL, 1, NULL),
(1, 9, 9, NULL, NULL),
(1, 10, 1, 6, NULL),
(1, 11, 6, NULL, 100),
(1, 12, 3, NULL, 150),
(2, 13, 3, NULL, 750),
(2, 14, NULL, 6, NULL),
(2, 15, 7, NULL, 4),
(2, 16, 1, 1, NULL),
(2, 17, 5, NULL, 1),
(2, 18, 6, 1, 400),
(2, 19, 1, 1, NULL),
(2, 20, 1, 1, NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipe_ingredients` ENABLE KEYS */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'5b8abbfc38cda495','Catharsis','cookery@catharsis.co.uk','http://www.flickr.com/photos/catharsisjelly/','2008-07-17 09:52:36','2008-07-17 09:52:36','2008-07-17 09:52:36','pending',0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Dumping data for table `method_items`
--

/*!40000 ALTER TABLE `method_items` DISABLE KEYS */;
LOCK TABLES `method_items` WRITE;
INSERT INTO `method_items` VALUES 
(1, 'Add the vanilla to the sugar, as much as you desire, then beat the eggs and sugar together until it turns white and fluffy ', NULL, 1),
(2, 'Melt the butter and leave it to cool ', NULL, 1),
(3, 'Melt the Chocolate and leave it to cool, this is best done by placing a glass bowl with the chocolate in over a pan of simmering water', NULL, 1),
(4, 'Mix all the dry ingredients, the cocoa, flour and baking soda then gradually fold the mixture into the beaten sugar and eggs ', NULL, 1),
(5, 'Pour in the milk, mix. Pour in the melted chocolate, mix. Pour in the butter in small doses and mix until you cant see any butter floating on the top', NULL, 1),
(6, 'Butter a rectangular cake tin, then lightly dust with flour (plain will be fine)', NULL, 1),
(7, 'Pour in the mixture, just about half fill the height of the tin, lightly dust the top with a covering of your choice, I use finely chopped almonds and the rest of the dark chocolate sprinkles. ', NULL, 1),
(8, 'Place in a pre-heated oven at 175°C/350°F/Gas Mark 3. Cook for 15-20 minutes depending on how gooey you like your brownies, less time = more gooey', NULL, 1),
(9, 'Remove from oven and cut into squares in the tin, then leave to cool before you remove them.', NULL, 1),
(10, 'Ideally serve warm with vanilla ice cream or cream or both if your feeling really wicked ', NULL, 1),
(11, 'Pre-heat the oven to 200&deg;C/400&deg;F/Gas 6. Place the parsnips, garlic and ginger in a large, deep roasting tin, drizzle over the oil, season with salt and pepper and roast for 20 minutes until golden.', NULL, 2),
(12, 'Pour in half the stock, the coconut milk and curry paste and return to the oven for a further 20 mins until the vegetables are tender.', NULL, 2),
(13, 'Transfer to a liquidiser and blend until creamy and smooth. Add the remaining hot stock and the coriander and check the seasoning. Ladle into warm bowls and serve. ', NULL, 2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `method_items` ENABLE KEYS */;

--
-- Dumping data for table `recipes`
--

/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
LOCK TABLES `recipes` WRITE;
INSERT INTO `recipes` VALUES 
(1, 'Chocolate Brownies', 20, 40, 15, 4, 0, '2008-07-25 11:35:38', '2008-07-25 12:22:22', 9, 1, 0, 0, 0),
(2, 'Curried Parsnip Soup', 45, 15, 6, 1, 1, '2008-07-25 15:20:52', '2008-07-25 15:29:12', 8, 1, 0, 0, 0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;

--
-- Dumping data for table `taggings`
--

/*!40000 ALTER TABLE `taggings` DISABLE KEYS */;
LOCK TABLES `taggings` WRITE;
INSERT INTO `taggings` (`id`, `tag_id`, `taggable_id`, `taggable_type`) VALUES
(1, 1, 1, 'Recipe'),(2, 2, 1, 'Recipe'),(3, 3, 1, 'Recipe'),(4, 4, 1, 'Recipe'),(5, 7, 2, 'Recipe'),(6, 6, 2, 'Recipe'),(7, 5, 2, 'Recipe');
UNLOCK TABLES;
/*!40000 ALTER TABLE `taggings` ENABLE KEYS */;

--
-- Dumping data for table `tags`
--

/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
LOCK TABLES `tags` WRITE;
INSERT INTO `tags` VALUES 
(1, 'chocolate'),(2, 'organic'),(3, 'pudding'),(4, 'sweet'),(5,'soup'),(6, 'vegetarian'),(7, 'lunch');
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

