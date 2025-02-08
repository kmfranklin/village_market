-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 07, 2025 at 11:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `village_market`
--
CREATE DATABASE IF NOT EXISTS `village_market` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `village_market`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
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
-- Table structure for table `cms_announcement`
--

CREATE TABLE `cms_announcement` (
  `announcement_id` int(11) NOT NULL,
  `announcement` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_hero_image`
--

CREATE TABLE `cms_hero_image` (
  `hero_id` int(11) NOT NULL,
  `hero_image_url` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_product`
--

CREATE TABLE `featured_product` (
  `fproduct_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `fproduct_start_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_vendor`
--

CREATE TABLE `featured_vendor` (
  `fvendor_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `fvendor_start_date` datetime NOT NULL,
  `fvendor_end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homepage_content`
--

CREATE TABLE `homepage_content` (
  `homepage_id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `next_date` int(11) DEFAULT NULL,
  `market_location` text DEFAULT NULL,
  `market_hours` text DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_mailing_address` varchar(255) DEFAULT NULL,
  `contact_city` varchar(50) DEFAULT NULL,
  `contact_state` int(11) DEFAULT NULL,
  `contact_zip` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_attendance`
--

CREATE TABLE `market_attendance` (
  `attendance_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `market_date_id` int(11) NOT NULL,
  `is_confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_date`
--

CREATE TABLE `market_date` (
  `market_date_id` int(11) NOT NULL,
  `market_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_unit`
--

CREATE TABLE `price_unit` (
  `price_unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` text DEFAULT NULL,
  `vendor_id` int(11) NOT NULL,
  `product_image_url` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_availability`
--

CREATE TABLE `product_availability` (
  `availability_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_price_unit`
--

CREATE TABLE `product_price_unit` (
  `product_price_unit_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price_unit_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` enum('vendor','admin','super_admin') NOT NULL
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
  `state_id` int(11) NOT NULL,
  `state_name` varchar(50) NOT NULL,
  `state_abbreviation` char(2) NOT NULL
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
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password_hashed` varchar(255) NOT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `business_description` text DEFAULT NULL,
  `street_address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `business_phone_number` varchar(10) DEFAULT NULL,
  `business_email_address` varchar(255) DEFAULT NULL,
  `business_image_url` varchar(255) DEFAULT NULL,
  `business_logo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `cms_announcement`
--
ALTER TABLE `cms_announcement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `cms_hero_image`
--
ALTER TABLE `cms_hero_image`
  ADD PRIMARY KEY (`hero_id`);

--
-- Indexes for table `featured_product`
--
ALTER TABLE `featured_product`
  ADD PRIMARY KEY (`fproduct_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `featured_vendor`
--
ALTER TABLE `featured_vendor`
  ADD PRIMARY KEY (`fvendor_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `homepage_content`
--
ALTER TABLE `homepage_content`
  ADD PRIMARY KEY (`homepage_id`),
  ADD KEY `hero_id` (`hero_id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `next_date` (`next_date`),
  ADD KEY `contact_state` (`contact_state`);

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
  ADD UNIQUE KEY `unique_email` (`email_address`),
  ADD KEY `role_id` (`role_id`);

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cms_announcement`
--
ALTER TABLE `cms_announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_hero_image`
--
ALTER TABLE `cms_hero_image`
  MODIFY `hero_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_product`
--
ALTER TABLE `featured_product`
  MODIFY `fproduct_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_vendor`
--
ALTER TABLE `featured_vendor`
  MODIFY `fvendor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homepage_content`
--
ALTER TABLE `homepage_content`
  MODIFY `homepage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_attendance`
--
ALTER TABLE `market_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_date`
--
ALTER TABLE `market_date`
  MODIFY `market_date_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_unit`
--
ALTER TABLE `price_unit`
  MODIFY `price_unit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_availability`
--
ALTER TABLE `product_availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_price_unit`
--
ALTER TABLE `product_price_unit`
  MODIFY `product_price_unit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `featured_product`
--
ALTER TABLE `featured_product`
  ADD CONSTRAINT `featured_product_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `featured_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `featured_vendor`
--
ALTER TABLE `featured_vendor`
  ADD CONSTRAINT `featured_vendor_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`);

--
-- Constraints for table `homepage_content`
--
ALTER TABLE `homepage_content`
  ADD CONSTRAINT `homepage_content_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `cms_hero_image` (`hero_id`),
  ADD CONSTRAINT `homepage_content_ibfk_2` FOREIGN KEY (`announcement_id`) REFERENCES `cms_announcement` (`announcement_id`),
  ADD CONSTRAINT `homepage_content_ibfk_3` FOREIGN KEY (`next_date`) REFERENCES `market_date` (`market_date_id`),
  ADD CONSTRAINT `homepage_content_ibfk_4` FOREIGN KEY (`contact_state`) REFERENCES `state` (`state_id`);

--
-- Constraints for table `market_attendance`
--
ALTER TABLE `market_attendance`
  ADD CONSTRAINT `market_attendance_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  ADD CONSTRAINT `market_attendance_ibfk_2` FOREIGN KEY (`market_date_id`) REFERENCES `market_date` (`market_date_id`);

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
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

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
