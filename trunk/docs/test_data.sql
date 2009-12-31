-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 06, 2009 at 11:06 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `recipes`
--

--
-- Dumping data for table `acl_roles`
--

INSERT INTO `acl_roles` (`id`, `inherit_id`, `name`) VALUES
(1, NULL, 'guest'),
(2, 1, 'member'),
(3, 2, 'admin');

--
-- Dumping data for table `acl_resources`
--

INSERT INTO `acl_resources` (`id`, `role_id`, `name`) VALUES
(1, 1, 'recipe'),
(2, 1, 'recipe:index'),
(3, 1, 'recipe:popular'),
(4, 1, 'recipe:user'),
(5, 1, 'recipe:view'),
(6, 1, 'error:error'),
(7, 1, 'user:new'),
(8, 1, 'user:view'),
(9, 2, 'ajax:get-ingredients'),
(10, 2, 'ajax:get-measurements'),
(11, 2, 'comment:new'),
(12, 2, 'recipe:new'),
(13, 2, 'ingredient:new'),
(14, 2, 'ingredient:edit'),
(15, 2, 'recipe:delete'),
(16, 2, 'recipe:edit'),
(17, 3, 'admin'),
(18, 3, 'admin:index'),
(19, 3, 'admin:resources'),
(20, 3, 'admin:add-resource'),
(21, 1, 'login:index'),
(22, 2, 'method:new'),
(23, 2, 'method:edit'),
(24, 1, 'login:confirm'),
(25, 1, 'ajax:user-lookup'),
(26, 2, 'user:account'),
(27, 1, 'index:index'),
(28, 1, 'login:reset'),
(29, 1, 'login:send-confirmation'),
(30, 2, 'rating:new');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `password`, `name`, `email`, `confirm`, `openid`, `created`, `updated`, `last_login`, `status`, `comments_count`, `ratings_count`, `recipes_count`) VALUES
(1, 3, '185741fc2d690b712f659c616222515f', 'Catharsis', 'cookery@catharsis.co.uk', 'c68d10e3e2a445676cf20733dc1b06f8', 'http://www.flickr.com/photos/catharsisjelly/', '2008-07-17 09:52:36', '2008-07-17 09:52:36', '2009-12-15 14:24:58', 'active', 5, 0, 5),
(2, 2, '185741fc2d690b712f659c616222515f', 'Other', 'other@catharsis.co.uk', 'f9c3eb2042844960ac620d52bc289886', '', '2009-08-20 15:55:40', '2009-08-20 15:55:40', '2009-08-20 15:56:24', 'active', 0, 0, 0);

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`) VALUES
(1, 'Eggs'),
(2, 'Self Raising Flour'),
(3, 'Plain Flour'),
(4, 'Caster Sugar'),
(5, 'Icing Sugar'),
(6, 'Tinned Plum Tomatoes'),
(7, 'Butter'),
(8, 'Baking Soda'),
(9, 'Vanilla pod'),
(10, 'Cocoa Powder'),
(11, 'Milk'),
(12, 'Cooking Chocolate'),
(13, 'Parsnips'),
(14, 'Garlic Cloves'),
(15, 'Root Ginger'),
(16, 'Olive Oil'),
(17, 'Vegetable Stock'),
(18, 'Canned Coconut Milk '),
(19, 'Thai Red Curry Paste '),
(20, 'Fresh Corriander'),
(21, 'Spring Onions'),
(22, 'Arborio Rice'),
(23, 'Skinless Salmon Fillet'),
(24, 'Lemon'),
(25, 'Fresh Tarragon'),
(26, 'Soft Brown Sugar'),
(27, 'Vanilla Extract'),
(28, 'Snickers Bars'),
(29, 'Onion'),
(30, 'Hot Curry Paste'),
(31, 'Red Lentils'),
(32, 'Cumin Seeds'),
(33, 'Sultanas'),
(34, 'Lime'),
(35, 'Fat Free Yoghurt'),
(36, 'Fresh Mint'),
(37, 'Chapattis'),
(38, 'Garam Masala'),
(39, 'Vegetable Oil'),
(40, 'Chicken Thighs'),
(42, 'Tomatoes'),
(43, 'Chicken Stock'),
(46, 'Double Cream'),
(47, 'Ground Almonds'),
(48, 'Dessicated Coconut'),
(49, 'Bananas'),
(50, 'Boiled Rice');
--
-- Dumping data for table `measurements`
--

INSERT INTO `measurements` (`id`, `name`, `abbreviation`) VALUES
(1, 'Tablespoon', 'tbsp'),
(2, 'Teaspoon', 'tsp'),
(3, 'Grams', 'g'),
(4, 'Kilograms', 'kg'),
(5, 'Litre', 'l'),
(6, 'Millilitre', 'ml'),
(7, 'Centimetre', 'cm'),
(8, 'Quarter', '&frac14;'),
(9, 'Half', '&frac12;');

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `creator_id`, `name`, `cooking_time`, `preparation_time`, `serves`, `difficulty`, `freezable`, `created`, `updated`, `ingredients_count`, `comments_count`, `ratings_count`, `view_count`) VALUES
(1, 1, 'Chocolate Brownies', 20, 40, 15, 4, 0, '2008-07-25 11:35:38', '2008-07-25 12:22:22', 9, 0, 0, 0),
(2, 1, 'Curried Parsnip Soup', 45, 15, 6, 1, 1, '2008-07-25 15:20:52', '2008-07-25 15:29:12', 8, 0, 0, 0),
(3, 1, 'Salmon and Tarragon Risotto', 30, 10, 4, 3, 0, '2008-09-04 15:52:01', '2008-09-04 16:00:40', 7, 0, 0, 0),
(4, 1, 'Snicker Muffins', 18, 15, 12, 2, 0, '2008-09-04 16:04:47', '2008-09-04 16:08:41', 8, 0, 0, 0),
(5, 1, 'Curried Lentil & Lime Soup', 40, 10, 5, 1, 1, '2008-09-04 16:13:11', '2009-08-26 12:11:46', 12, 5, 0, 13);

--
-- Dumping data for table `method_items`
--

INSERT INTO `method_items` (`id`, `recipe_id`, `description`, `position`) VALUES
(1, 1, 'Add the vanilla to the sugar, as much as you desire, then beat the eggs and sugar together until it turns white and fluffy ', NULL),
(2, 1, 'Melt the butter and leave it to cool ', NULL),
(3, 1, 'Melt the Chocolate and leave it to cool, this is best done by placing a glass bowl with the chocolate in over a pan of simmering water', NULL),
(4, 1, 'Mix all the dry ingredients, the cocoa, flour and baking soda then gradually fold the mixture into the beaten sugar and eggs ', NULL),
(5, 1, 'Pour in the milk, mix. Pour in the melted chocolate, mix. Pour in the butter in small doses and mix until you cant see any butter floating on the top', NULL),
(6, 1, 'Butter a rectangular cake tin, then lightly dust with flour (plain will be fine)', NULL),
(7, 1, 'Pour in the mixture, just about half fill the height of the tin, lightly dust the top with a covering of your choice, I use finely chopped almonds and the rest of the dark chocolate sprinkles. ', NULL),
(8, 1, 'Place in a pre-heated oven at 175°C/350°F/Gas Mark 3. Cook for 15-20 minutes depending on how gooey you like your brownies, less time = more gooey', NULL),
(9, 1, 'Remove from oven and cut into squares in the tin, then leave to cool before you remove them.', NULL),
(10, 1, 'Ideally serve warm with vanilla ice cream or cream or both if your feeling really wicked ', NULL),
(11, 2, 'Pre-heat the oven to 200&deg;C/400&deg;F/Gas 6. Place the parsnips, garlic and ginger in a large, deep roasting tin, drizzle over the oil, season with salt and pepper and roast for 20 minutes until golden.', NULL),
(12, 2, 'Pour in half the stock, the coconut milk and curry paste and return to the oven for a further 20 mins until the vegetables are tender.', NULL),
(13, 2, 'Transfer to a liquidiser and blend until creamy and smooth. Add the remaining hot stock and the coriander and check the seasoning. Ladle into warm bowls and serve. ', NULL),
(14, 3, 'Cube the salmon, slice the spring onions.\r\n', NULL),
(15, 3, 'Melt the butter in a pan over a medium heat. Add the spring onions and cook for 3 minutes until they are soft.\r\n', NULL),
(16, 3, 'Pour in the rice and stir well to coat the rice grains in the butter.\r\n', NULL),
(17, 3, 'Pour in the hot vegetable stock, a ladleful at a time, stir continuously and allow the stock to be absorbed into the rice before adding more.\r\n', NULL),
(18, 3, 'Cook the rice for 12 mins (approx), the rice should still be slightly nutty. Then toss in the salmon cubes and cook until the salmon turn opaque and starts to flake.', NULL),
(19, 3, 'Stir in the lemon zest and tarragon, then season with black pepper. Watch if you add salt as Vegetable stock and salmon can be salty. Serve immediately', NULL),
(20, 4, 'Line a 12 hole muffin tin with muffin cases.', NULL),
(21, 4, 'Heat the oven to 200C/180C fan/gas 6. In a bowl, combine the dry ingredients, the sugar, flour, baking soda.\r\n', NULL),
(22, 4, 'Mix the wet ingrediants in a jug then pour into the dry and stir until just combined. ', NULL),
(23, 4, 'Don''t over mix or muffins will be tough.\r\n', NULL),
(24, 4, 'Chop up 2 and a half of the snickers bars and fold that in.', NULL),
(25, 4, 'Spoon the mix into the cases, chop the rest of the Snickers bar and sprinkle in top.', NULL),
(26, 4, 'Bake for 15-18 minutes until golden and firm. Cool on a rack and see how long you can avoid eating them.', NULL),
(27, 5, 'Finely chop the onion, garlic and ginger. Add to a pan with the curry paste and the cumin seeds. Cook gently for 3 minutes.\r\n', NULL),
(28, 5, 'Add the lentils and the stock to the pan, bring to the boil and simmer gently for 20 minutes.', NULL),
(29, 5, 'Add the sultanas and cook for a further 10 minutes.', NULL),
(30, 5, 'Add the lime and salt and pepper to taste.', NULL),
(31, 5, 'Ladle into bowls and top each serving with a large spoonful of yoghurt and a sprinkling of mint.', NULL),
(32, 5, 'Serve with warm chapatties', NULL);

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_id`, `ingredient_id`, `measurement_id`, `quantity`, `amount`) VALUES
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
(2, 20, 1, 1, NULL),
(3, 7, 3, NULL, 25),
(3, 17, 5, NULL, 1),
(3, 21, NULL, 8, NULL),
(3, 22, 3, NULL, 350),
(3, 23, NULL, 4, NULL),
(3, 24, NULL, 1, NULL),
(3, 25, NULL, NULL, NULL),
(4, 1, NULL, 2, NULL),
(4, 2, 3, NULL, 250),
(4, 7, 3, NULL, 85),
(4, 8, 2, 1, NULL),
(4, 11, 6, NULL, 250),
(4, 26, 3, NULL, 140),
(4, 27, 2, 1, NULL),
(4, 28, NULL, 3, NULL),
(5, 14, NULL, 2, NULL),
(5, 15, 7, 4, NULL),
(5, 17, 5, NULL, 1.5),
(5, 29, NULL, 1, NULL),
(5, 30, 1, 1, NULL),
(5, 31, 3, NULL, 200),
(5, 32, 2, 1, NULL),
(5, 33, 3, NULL, 50),
(5, 34, NULL, 1, NULL),
(5, 35, 3, NULL, 150),
(5, 36, 1, 2, NULL),
(5, 37, NULL, 4, NULL);

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(10, 'cakes'),
(1, 'chocolate'),
(8, 'fish'),
(9, 'italian'),
(7, 'lunch'),
(2, 'organic'),
(3, 'pudding'),
(5, 'soup'),
(4, 'sweet'),
(6, 'vegetarian');

--
-- Dumping data for table `taggings`
--

INSERT INTO `taggings` (`id`, `tag_id`, `taggable_id`, `taggable_type`) VALUES
(1, 1, 1, 'Recipe'),
(2, 2, 1, 'Recipe'),
(3, 3, 1, 'Recipe'),
(4, 4, 1, 'Recipe'),
(7, 5, 2, 'Recipe'),
(15, 5, 5, 'Recipe'),
(6, 6, 2, 'Recipe'),
(16, 6, 5, 'Recipe'),
(5, 7, 2, 'Recipe'),
(14, 7, 5, 'Recipe'),
(8, 8, 3, 'Recipe'),
(9, 9, 3, 'Recipe'),
(10, 10, 4, 'Recipe');
