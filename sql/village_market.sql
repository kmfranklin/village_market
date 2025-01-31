CREATE DATABASE IF NOT EXISTS village_market;
USE village_market;

CREATE TABLE `role` (
  `role_id` INT PRIMARY KEY AUTO_INCREMENT,
  `role_name` ENUM('vendor', 'admin', 'super_admin') NOT NULL
);

CREATE TABLE `state` (
  `state_id` INT PRIMARY KEY AUTO_INCREMENT,
  `state_name` VARCHAR(50) NOT NULL,
  `state_abbreviation` CHAR(2) NOT NULL
);

CREATE TABLE `user` (
  `user_id` INT PRIMARY KEY AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email_address` VARCHAR(255) NOT NULL UNIQUE,
  `password_hashed` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20),
  `role_id` INT,
  `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `is_active` BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`)
);

CREATE TABLE `vendor` (
  `vendor_id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `business_name` VARCHAR(100) NOT NULL,
  `business_description` TEXT,
  `street_address` VARCHAR(100),
  `city` VARCHAR(50),
  `state_id` INT,
  `zip_code` VARCHAR(10),
  `business_phone_number` VARCHAR(20),
  `business_email_address` VARCHAR(255),
  `business_image_url` VARCHAR(255),
  `business_logo_url` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`)
);

CREATE TABLE `category` (
  `category_id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_name` VARCHAR(100) NOT NULL
);

CREATE TABLE `product` (
  `product_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_name` VARCHAR(100) NOT NULL,
  `product_description` TEXT,
  `vendor_id` INT NOT NULL,
  `product_image_url` VARCHAR(255),
  `date_added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `is_active` BOOLEAN DEFAULT TRUE,
  `category_id` INT,
  FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
);

CREATE TABLE `price_unit` (
  `price_unit_id` INT PRIMARY KEY AUTO_INCREMENT,
  `unit_name` ENUM('Pound', 'Dozen', 'Unit', 'Bundle', 'Gallon') NOT NULL
);

CREATE TABLE `product_price_unit` (
  `product_price_unit_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `price_unit_id` INT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  FOREIGN KEY (`price_unit_id`) REFERENCES `price_unit` (`price_unit_id`)
);

CREATE TABLE `market_date` (
  `market_date_id` INT PRIMARY KEY AUTO_INCREMENT,
  `market_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `is_active` BOOLEAN DEFAULT TRUE
);

CREATE TABLE `market_attendance` (
  `attendance_id` INT PRIMARY KEY AUTO_INCREMENT,
  `vendor_id` INT NOT NULL,
  `market_date_id` INT NOT NULL,
  `is_confirmed` BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  FOREIGN KEY (`market_date_id`) REFERENCES `market_date` (`market_date_id`)
);

CREATE TABLE `product_availability` (
  `availability_id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `attendance_id` INT NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  FOREIGN KEY (`attendance_id`) REFERENCES `market_attendance` (`attendance_id`)
);

CREATE TABLE `featured_vendor` (
  `fvendor_id` INT PRIMARY KEY AUTO_INCREMENT,
  `vendor_id` INT NOT NULL,
  `fvendor_start_date` DATETIME NOT NULL,
  `fvendor_end_date` DATETIME NOT NULL,
  FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`)
);

CREATE TABLE `featured_product` (
  `fproduct_id` INT PRIMARY KEY AUTO_INCREMENT,
  `vendor_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `fproduct_start_date` DATETIME NOT NULL,
  FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`vendor_id`),
  FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`)
);

CREATE TABLE `cms_hero_image` (
  `hero_id` INT PRIMARY KEY AUTO_INCREMENT,
  `hero_image_url` VARCHAR(255) NOT NULL,
  `date_added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `cms_announcement` (
  `announcement_id` INT PRIMARY KEY AUTO_INCREMENT,
  `announcement` TEXT NOT NULL,
  `date_added` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `homepage_content` (
  `homepage_id` INT PRIMARY KEY AUTO_INCREMENT,
  `hero_id` INT,
  `announcement_id` INT,
  `next_date` INT,
  `market_location` TEXT,
  `market_hours` TEXT,
  `contact_phone` VARCHAR(20),
  `contact_email` VARCHAR(255),
  `contact_mailing_address` VARCHAR(255),
  `contact_city` VARCHAR(50),
  `contact_state` INT,
  `contact_zip` VARCHAR(10),
  FOREIGN KEY (`hero_id`) REFERENCES `cms_hero_image` (`hero_id`),
  FOREIGN KEY (`announcement_id`) REFERENCES `cms_announcement` (`announcement_id`),
  FOREIGN KEY (`next_date`) REFERENCES `market_date` (`market_date_id`),
  FOREIGN KEY (`contact_state`) REFERENCES `state` (`state_id`)
);
