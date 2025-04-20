-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: pdb1048.awardspace.net
-- Generation Time: Apr 20, 2025 at 09:21 PM
-- Server version: 8.0.32
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `4383021_villagemarket`
--
CREATE DATABASE IF NOT EXISTS `4383021_villagemarket` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `4383021_villagemarket`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Fruits'),
(2, 'Vegetables'),
(3, 'Meats'),
(4, 'Poultry'),
(5, 'Dairy'),
(6, 'Baked Goods'),
(7, 'Craft Goods');

-- --------------------------------------------------------

--
-- Table structure for table `cms_image`
--

CREATE TABLE `cms_image` (
  `image_id` int NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cms_image`
--

INSERT INTO `cms_image` (`image_id`, `image_url`, `alt_text`, `uploaded_at`) VALUES
(7, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744850722/hero_images/hero_68004f215f8df.jpg', 'Village Market hero image.', '2025-04-17 00:45:22');

-- --------------------------------------------------------

--
-- Table structure for table `homepage_content`
--

CREATE TABLE `homepage_content` (
  `homepage_id` int NOT NULL,
  `announcement_text` text COLLATE utf8mb4_general_ci,
  `market_hours` text COLLATE utf8mb4_general_ci,
  `contact_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_mailing_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_state` int DEFAULT NULL,
  `contact_zip` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hero_image_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_content`
--

INSERT INTO `homepage_content` (`homepage_id`, `announcement_text`, `market_hours`, `contact_phone`, `contact_email`, `contact_mailing_address`, `contact_city`, `contact_state`, `contact_zip`, `hero_image_id`) VALUES
(1, 'Welcome to your local market!', 'Saturdays, 9 AM - 6 PM', '980-989-9999', 'info@villagemarket.com', '111 Market Lane', 'Village', 1, '28999', 7);

-- --------------------------------------------------------

--
-- Table structure for table `market_attendance`
--

CREATE TABLE `market_attendance` (
  `attendance_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `market_date_id` int NOT NULL,
  `is_confirmed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_attendance`
--

INSERT INTO `market_attendance` (`attendance_id`, `vendor_id`, `market_date_id`, `is_confirmed`) VALUES
(27, 65, 7, 1),
(28, 65, 8, 1),
(29, 65, 9, 1),
(30, 65, 11, 1),
(31, 65, 13, 1),
(32, 73, 7, 1),
(33, 73, 8, 1),
(36, 75, 7, 1),
(37, 75, 8, 1),
(38, 70, 7, 1),
(39, 70, 8, 1),
(40, 70, 9, 1),
(41, 70, 11, 1),
(42, 77, 8, 1),
(43, 77, 9, 1),
(44, 66, 8, 1),
(45, 66, 9, 1),
(46, 66, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `market_date`
--

CREATE TABLE `market_date` (
  `market_date_id` int NOT NULL,
  `market_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_date`
--

INSERT INTO `market_date` (`market_date_id`, `market_date`, `is_active`) VALUES
(1, '2025-03-08 05:00:00', 1),
(2, '2025-03-15 04:00:00', 1),
(3, '2025-03-22 04:00:00', 1),
(4, '2025-03-29 04:00:00', 1),
(5, '2025-04-05 04:00:00', 1),
(6, '2025-04-12 04:00:00', 1),
(7, '2025-04-19 04:00:00', 1),
(8, '2025-04-26 04:00:00', 1),
(9, '2025-05-03 04:00:00', 1),
(10, '2025-05-10 04:00:00', 1),
(11, '2025-05-17 04:00:00', 1),
(12, '2025-05-24 04:00:00', 1),
(13, '2025-05-31 04:00:00', 1),
(14, '2025-06-07 04:00:00', 1),
(15, '2025-06-14 04:00:00', 1),
(16, '2025-06-21 04:00:00', 1),
(17, '2025-06-28 04:00:00', 1),
(18, '2025-07-05 04:00:00', 1),
(19, '2025-07-12 04:00:00', 1),
(20, '2025-07-19 04:00:00', 1),
(21, '2025-07-26 04:00:00', 1),
(22, '2025-08-02 04:00:00', 1),
(23, '2025-08-09 04:00:00', 1),
(24, '2025-08-16 04:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_unit`
--

CREATE TABLE `price_unit` (
  `price_unit_id` int NOT NULL,
  `unit_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_unit`
--

INSERT INTO `price_unit` (`price_unit_id`, `unit_name`) VALUES
(1, 'pound'),
(2, 'ounce'),
(3, 'dozen'),
(4, 'half dozen'),
(5, 'each'),
(6, 'gallon'),
(7, 'quart'),
(8, 'pint'),
(9, 'cup'),
(10, 'bushel'),
(11, 'bundle');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `product_description` text COLLATE utf8mb4_general_ci,
  `vendor_id` int NOT NULL,
  `product_image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_description`, `vendor_id`, `product_image_url`, `date_added`, `is_active`, `category_id`) VALUES
(45, 'Peaches', 'Juicy peaches', 65, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744855749/product_images/product_680062c45928a.jpg', '2025-04-16 22:09:08', 1, 1),
(46, 'Strawberries', 'Fresh strawberries', 65, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744855785/product_images/product_680062e8d7b49.jpg', '2025-04-16 22:09:44', 1, 1),
(47, 'Wooden Spoons', 'Hand-carved wooden spoons in various shapes, sizes, and designs', 73, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744856141/product_images/product_6800644cc7749.jpg', '2025-04-16 22:15:40', 1, 7),
(48, 'Wheat Bread', 'Hearty and wholesome, our wheat bread is made with whole grains for a nutty flavor and soft, chewy texture.', 70, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745181078/product_images/product_6805599593d96.jpg', '2025-04-17 21:05:57', 1, 6),
(49, 'White Bread', 'Light and fluffy with a crisp golden crust, our French-style bread is perfect for sandwiches or dipping in olive oil.', 70, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745181097/product_images/product_680559a88d543.jpg', '2025-04-17 21:07:00', 1, 6),
(50, 'Sub Rolls', 'Soft, elongated sandwich rolls with a slightly chewy bite, perfect for hoagies, grinders, or subs.', 70, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744938480/product_images/product_6801a5ef55817.jpg', '2025-04-17 21:07:59', 1, 6),
(51, 'Sourdough', 'Crafted with a natural starter, our sourdough features a tangy flavor and airy crumb, finished with a perfectly crisp crust.', 70, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744938605/product_images/product_6801a66c2dac7.jpg', '2025-04-17 21:10:04', 1, 6),
(52, 'Eggs', 'Free range, organic large eggs', 72, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745167464/product_images/product_68052467eb9c1.jpg', '2025-04-20 12:44:23', 1, 4),
(53, 'Whole Chicken ', 'A pasture-raised whole chicken, perfect for roasting, slow-cooking, or smoking, packed with rich flavor and raised without antibiotics or hormones.', 77, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745168399/product_images/product_6805280f063cf.jpg', '2025-04-20 12:59:59', 1, 4),
(54, 'Strawberry Jam', 'Made with sun-ripened North Carolina strawberries and just the right amount of sugar, this classic jam is rich, bright, and bursting with fresh berry flavor. Perfect on toast, biscuits, or as a topping for cheesecake.', 66, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745168865/product_images/product_680529e04e8db.jpg', '2025-04-20 13:07:44', 1, 1),
(55, 'Peach Jam', 'Crafted from juicy summer peaches picked at their peak, our peach jam delivers a smooth, sweet flavor with a hint of tang. Try it on a warm croissant, or mix it into yogurt for a seasonal treat.', 66, 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745181039/product_images/product_6805596edf3dc.jpg', '2025-04-20 13:08:19', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_availability`
--

CREATE TABLE `product_availability` (
  `availability_id` int NOT NULL,
  `product_id` int NOT NULL,
  `attendance_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_unit`
--

CREATE TABLE `product_price_unit` (
  `product_price_unit_id` int NOT NULL,
  `product_id` int NOT NULL,
  `price_unit_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_price_unit`
--

INSERT INTO `product_price_unit` (`product_price_unit_id`, `product_id`, `price_unit_id`, `price`) VALUES
(47, 45, 1, 0.90),
(48, 46, 1, 0.75),
(49, 47, 5, 5.00),
(50, 48, 5, 6.00),
(51, 49, 5, 5.50),
(52, 50, 4, 7.00),
(53, 51, 5, 7.00),
(54, 52, 3, 10.00),
(55, 53, 1, 2.00),
(56, 54, 8, 8.00),
(57, 55, 8, 7.50);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int NOT NULL,
  `role_name` enum('vendor','admin','super_admin') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'vendor'),
(2, 'admin'),
(3, 'super_admin');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` int NOT NULL,
  `state_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `state_abbreviation` char(2) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `state_name`, `state_abbreviation`) VALUES
(1, 'North Carolina', 'NC'),
(2, 'South Carolina', 'SC'),
(3, 'Georgia', 'GA'),
(4, 'Tennessee', 'TN'),
(5, 'Virginia', 'VA');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hashed` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_status` enum('pending','active','suspended','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email_address`, `password_hashed`, `phone_number`, `role_id`, `registration_date`, `account_status`) VALUES
(9, 'Super', 'Admin', 'super@admin.com', '$2y$10$X3OZknKawSWtqn1x0fni2eR5b5i0oZt.b488tQYq7m6WsvDhSmcp2', '9999999999', 3, '2025-02-08 03:00:46', 'active'),
(13, 'Kevin', 'Franklin', 'kfranklin134@gmail.com', '$2y$10$IihR6a/bz0kQHR/40ftELeXvkPtNkvMbcfd550JzvoEym2sBHjOmi', '9999999999', 2, '2025-02-14 01:05:26', 'active'),
(72, 'Angela', 'Brewer', 'angela.brewer@yahoo.com', '$2y$10$WINXMw7qIClGZuhEJqFblOmQiyswpu/4JIUaDy6LvR7/FFfGKyzp2', '8056931661', 1, '2025-04-16 21:08:06', 'active'),
(73, 'Katherine', 'Wyatt', 'katherine.wyatt@gmail.com', '$2y$10$cID8U7OCwR2Gh1Zqz93NReEjP9iu0/5WYT3gd6Q3SA3P1u4zOgTRS', '9424545182', 1, '2025-04-16 21:11:30', 'active'),
(74, 'Michael', 'Foster', 'michael.foster@gmail.com', '$2y$10$AZrqBy6IHGzXA2GEbt4Md.mntMuLAhs40BCJk8qnu.Bi8hLTBanSC', '8236598305', 1, '2025-04-16 21:14:15', 'active'),
(75, 'Raymond', 'Richards', 'raymond.richards@hotmail.com', '$2y$10$TFTN.xXB5YuJLIjmFPRBB.dRVLFoFadwJO7hYboniyJaLZQ80iBMm', '4705089782', 1, '2025-04-16 21:16:33', 'active'),
(76, 'Eric', 'Ochoa', 'eric.ochoa@yahoo.com', '$2y$10$EExkYhxKUigvyQur0H6ju.6j41Hkq.wVyIQdgI2CFou3gguRQG9oy', '7772339164', 1, '2025-04-16 21:55:24', 'active'),
(77, 'David', 'Wilson', 'david.wilson@gmail.com', '$2y$10$P/dyjO9NX78FW7wvcnX3D.i3KkUrT69bNlbiwHkMgEJ.5WP7IAM4O', '9519004144', 1, '2025-04-16 21:58:05', 'active'),
(78, 'Danielle', 'Brown', 'danielle.brown@gmail.com', '$2y$10$SF6zq5lf4LmEeQUu40CH8e6oUfTorTtRvcRQMO/C57jhmQvU0UZ/i', '4602585952', 1, '2025-04-16 22:00:30', 'active'),
(79, 'John', 'Powell', 'john.powell@gmail.com', '$2y$10$8WpFcm8wABRR3w7BwHlufegYdY2..yvkx4geFgR4r6uYa7xxF8HTy', '8716835976', 1, '2025-04-16 22:02:38', 'active'),
(80, 'Manuel', 'Reid', 'manuel.reid@gmail.com', '$2y$10$C.lu8GK98jwBbnM0amfTAeYGMoV1lxLOjZbz1MgMaMQqbtv7vqXxe', '5839348345', 1, '2025-04-16 22:14:07', 'active'),
(81, 'Kayla', 'Lutz', 'kayla.lutz@hotmail.com', '$2y$10$plD.zGL.23hv2bVTCkpEA.iUDw6C8/HH15IIgIGlTetKzNOKksr9a', '2832571767', 1, '2025-04-17 19:42:52', 'active'),
(82, 'Stacy', 'Conway', 'stacy.conway@yahoo.com', '$2y$10$RlONqQB7PVFTkMvgKXH9qup.86m.F0TlHnA53FA4QXD1uELh7yzUy', '4380079013', 1, '2025-04-17 19:46:48', 'active'),
(83, 'Test', 'Admin', 'test@admin.com', '$2y$10$hWYCyuRi6jixzuOvzlFTHeDCnB7qc0sQAqAw6pWZDYwCNeVFnR6NC', '1234567890', 2, '2025-04-20 12:26:28', 'active'),
(84, 'Test', 'Vendor', 'test@vendor.com', '$2y$10$utjtmpAi092HOq/zPe7bne5oOF/U3yKrNsP0tlwZiuGCbi81cLI62', '1234567890', 1, '2025-04-20 12:29:12', 'active'),
(85, 'Chuck', 'Chickens', 'chuck@chickenfarm.com', '$2y$10$Su1jlO/4u6ZrM5J8njH/Me4By4rj4VH8FnphqG3Pn7BWtQRz3/Wzy', '8283339044', 1, '2025-04-20 12:49:28', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` int NOT NULL,
  `user_id` int NOT NULL,
  `business_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `business_description` text COLLATE utf8mb4_general_ci,
  `street_address` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state_id` int DEFAULT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_phone_number` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_email_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_logo_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `show_email` tinyint(1) DEFAULT '0',
  `show_phone` tinyint(1) DEFAULT '0',
  `show_address` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendor_id`, `user_id`, `business_name`, `business_description`, `street_address`, `city`, `state_id`, `zip_code`, `business_phone_number`, `business_email_address`, `business_image_url`, `business_logo_url`, `show_email`, `show_phone`, `show_address`) VALUES
(65, 72, 'Whispering Winds Farm', 'Our small farm focuses on regenerative agriculture, producing a wide range of fresh goods and preserves for the community.', '7925 Schmidt Union', 'Penashire', 3, '30059', '3139012851', 'angela@whisperingwindsfarm.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744852815/vendor_images/vendor_img_6800574e1513c.jpg', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745182966/vendor_logos/vendor_logo_680560f5723bc.jpg', 1, 0, 0),
(66, 73, 'Fern Hill Homestead', 'Our small farm focuses on regenerative agriculture, producing a wide range of fresh goods and preserves for the community.', '19 Gonzalez Divide', 'Lake Courtney', 1, '28524', '6033332792', 'info@fernhillhomestead.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744853825/vendor_images/vendor_img_68005b3e18218.jpg', '', 0, 1, 1),
(67, 74, 'Willow Goat Dairy', 'Our farm is home to a small herd of dairy goats. We craft all of our cheeses with care.', '9 Lisa Ramp', 'Jameston', 2, '29669', '5327281124', 'info@willowgoatdairy.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744852758/vendor_images/vendor_img_680057153194e.jpg', '', 1, 0, 1),
(68, 75, 'Back Porch Acres', 'We grow a variety of seasonal fruits and vegetables using organic methods on our family-run land.', '4000 Turner Mews', 'Carolynberg', 5, '22560', '8741544170', 'info@backporchacres.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744853877/vendor_images/vendor_img_68005b74587ae.jpg', '', 1, 1, 1),
(69, 76, 'Sunrise Honeyworks', 'We offer raw, local honey and beeswax products from our sustainable hives.', '27992 Amy Ford', 'Lake Daniel', 2, '29795', '3524402120', 'info@sunrisehoneyworks.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744855459/vendor_images/vendor_img_680061a251543.jpg', '', 0, 0, 0),
(70, 77, 'Golden Bakery', 'We bake sourdough and heritage grain breads using old-world techniques in our wood-fired oven.', '2671 Campbell Boulevard', 'East James', 1, '28814', '8349089603', 'info@goldenbakery.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744938280/vendor_images/vendor_img_6801a5272bc12.jpg', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745182479/vendor_logos/vendor_logo_68055f0d6dcff.jpg', 0, 0, 0),
(71, 78, 'Rose Hill Orchard', 'We grow a variety of seasonal fruits and vegetables using organic methods on our family-run land.', '4557 Rodriguez Boulevard', 'Blackburgh', 5, '23980', '4403462041', 'info@rosehillorchard.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744855517/vendor_images/vendor_img_680061dccf5f3.jpg', '', 0, 1, 0),
(72, 79, 'Cedar Grove Homestead', 'Our small farm focuses on regenerative agriculture, producing a wide range of fresh goods and preserves for the community.', '469 Leonard Way', 'Brittneyberg', 5, '23769', '8007901318', 'info@cedargrovehomestead.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744855532/vendor_images/vendor_img_680061eb72752.jpg', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745182098/vendor_logos/vendor_logo_68055d9186322.jpg', 1, 0, 0),
(73, 80, 'Twin Pines Woodworks', 'We offer hand-carved, handcrafted wooden goods, made with sustainable lumber sourcing and regenerative practices.', '41 Mcdaniel Track', 'Logantown', 1, '27934', '5721521951', 'info@twinpines.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1744856161/vendor_images/vendor_img_68006460a2b1d.jpg', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745182724/vendor_logos/vendor_logo_6805600386b4c.jpg', 1, 0, 1),
(74, 81, 'Maple Herbals', 'We offer herbal teas, dried flowers, and wellness products inspired by traditional remedies and wildcrafted ingredients.', '2606 Richard Hill', 'East Kristin', 4, '38206', '7892711304', 'info@mapleherbals.com', '', '', 1, 0, 0),
(75, 82, 'Bear Ridge Soap Co.', 'Hand-poured in small batches, our soaps are made from all-natural ingredients with gentle scents and no synthetic additives.', '327 Woods Drive', 'Asheville', 1, '28806', '4415860495', 'info@bearridgesoap.com', '', '', 0, 1, 0),
(76, 84, 'Demo Business', 'This is a test business for final presentation/demonstration purposes.', '289 Demonstration Lane', 'Asheville', 1, '28801', '1234567890', 'demo@business.com', '', '', 1, 1, 1),
(77, 85, 'Chuck\'s Chickens', 'At Chuck’s Chickens, we’re all about happy hens and healthier eggs. Nestled in the rolling hills of western North Carolina, our small, family-run farm raises free-range chickens with love, fresh air, and plenty of pasture. We believe better care means better flavor—so whether you\'re after rich, golden-yolk eggs or fresh, locally raised broiler chickens, you’ll taste the difference. No antibiotics, no hormones—just real food from real birds. Come say hi at the next market and meet the faces behind the feathers!', '123 Chicken Coop Court', 'Swannanoa', 1, '28778', '8283319245', 'info@chuckschickens.com', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745167806/vendor_images/vendor_img_680525bd86f5f.jpg', 'https://res.cloudinary.com/dykbjvtfu/image/upload/v1745182285/vendor_logos/vendor_logo_68055e4ca602b.jpg', 1, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `cms_image`
--
ALTER TABLE `cms_image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `homepage_content`
--
ALTER TABLE `homepage_content`
  ADD PRIMARY KEY (`homepage_id`),
  ADD KEY `announcement_id` (`announcement_text`(768)),
  ADD KEY `contact_state` (`contact_state`),
  ADD KEY `fk_hero_image` (`hero_image_id`);

--
-- Indexes for table `market_attendance`
--
ALTER TABLE `market_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `market_date_id` (`market_date_id`);

--
-- Indexes for table `market_date`
--
ALTER TABLE `market_date`
  ADD PRIMARY KEY (`market_date_id`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `price_unit`
--
ALTER TABLE `price_unit`
  ADD PRIMARY KEY (`price_unit_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_availability`
--
ALTER TABLE `product_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `attendance_id` (`attendance_id`);

--
-- Indexes for table `product_price_unit`
--
ALTER TABLE `product_price_unit`
  ADD PRIMARY KEY (`product_price_unit_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `price_unit_id` (`price_unit_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD KEY `user_ibfk_1` (`role_id`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `state_id` (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cms_image`
--
ALTER TABLE `cms_image`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `homepage_content`
--
ALTER TABLE `homepage_content`
  MODIFY `homepage_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `market_attendance`
--
ALTER TABLE `market_attendance`
  MODIFY `attendance_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `market_date`
--
ALTER TABLE `market_date`
  MODIFY `market_date_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `price_unit`
--
ALTER TABLE `price_unit`
  MODIFY `price_unit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `product_availability`
--
ALTER TABLE `product_availability`
  MODIFY `availability_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_price_unit`
--
ALTER TABLE `product_price_unit`
  MODIFY `product_price_unit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `homepage_content`
--
ALTER TABLE `homepage_content`
  ADD CONSTRAINT `fk_hero_image` FOREIGN KEY (`hero_image_id`) REFERENCES `cms_image` (`image_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `homepage_content_ibfk_4` FOREIGN KEY (`contact_state`) REFERENCES `state` (`state_id`);

--
-- Constraints for table `market_attendance`
--
ALTER TABLE `market_attendance`
  ADD CONSTRAINT `market_attendance_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `market_attendance_ibfk_2` FOREIGN KEY (`market_date_id`) REFERENCES `market_date` (`market_date_id`);

--
-- Constraints for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD CONSTRAINT `password_reset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `product_availability`
--
ALTER TABLE `product_availability`
  ADD CONSTRAINT `product_availability_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `product_availability_ibfk_2` FOREIGN KEY (`attendance_id`) REFERENCES `market_attendance` (`attendance_id`);

--
-- Constraints for table `product_price_unit`
--
ALTER TABLE `product_price_unit`
  ADD CONSTRAINT `product_price_unit_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `product_price_unit_ibfk_2` FOREIGN KEY (`price_unit_id`) REFERENCES `price_unit` (`price_unit_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON UPDATE CASCADE;

--
-- Constraints for table `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `vendor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `vendor_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
