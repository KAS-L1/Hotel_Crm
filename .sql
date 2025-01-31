-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 31, 2025 at 03:21 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotelcrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `budget_approvals`
--

DROP TABLE IF EXISTS `budget_approvals`;
CREATE TABLE IF NOT EXISTS `budget_approvals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `requisition_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `approved_by` int DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `requisition_id` (`requisition_id`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Unread','Read','Archived') COLLATE utf8mb4_unicode_ci DEFAULT 'Unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `unit_price` decimal(15,2) NOT NULL,
  `stock` int NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=492 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `category_id`, `category`, `created_at`, `updated_at`) VALUES
(457, 440123, 'Breakfast Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(458, 440456, 'Lunch Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(459, 440789, 'Dinner Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(460, 440234, 'Appetizers & Starters', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(461, 440557, 'Main Course', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(462, 440890, 'Desserts', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(463, 440345, 'Beverages', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(464, 440699, 'Bar Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(465, 440901, 'Room Service Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(466, 440112, 'Kids Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(467, 440223, 'Special Diet Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(468, 440334, 'Buffet Items', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(469, 440567, 'Weekend Specials', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(470, 440899, 'Seasonal Menu', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(471, 440678, 'Banquet Packages', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(472, 441001, 'Room Types', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(473, 441002, 'Room Amenities', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(474, 441003, 'Housekeeping Supplies', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(475, 441004, 'Laundry Services', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(476, 441005, 'Spa Services', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(477, 441006, 'Fitness Center', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(478, 441007, 'Event Spaces', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(479, 441008, 'Business Center', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(480, 441009, 'Gift Shop Items', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(481, 441010, 'Pool Services', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(482, 441011, 'Concierge Services', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(483, 441012, 'Valet Parking', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(484, 441013, 'Airport Shuttle', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(485, 441014, 'Meeting Rooms', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(486, 441015, 'Banquet Services', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(487, 441016, 'Room Maintenance', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(488, 441017, 'Facility Maintenance', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(489, 441018, 'Kitchen Equipment', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(490, 441019, 'HVAC Systems', '2025-01-31 02:56:56', '2025-01-31 02:56:56'),
(491, 441020, 'Safety Equipment', '2025-01-31 02:56:56', '2025-01-31 02:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `requisition_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `requisition_id` (`requisition_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

DROP TABLE IF EXISTS `purchase_requisitions`;
CREATE TABLE IF NOT EXISTS `purchase_requisitions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `requisition_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `created_by` int DEFAULT NULL,
  `total_quantity` decimal(15,2) NOT NULL,
  `total_cost` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `priority` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci DEFAULT 'Medium',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `rfq_response_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `requisition_id` (`requisition_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `created_by` (`created_by`),
  KEY `rfq_response_id` (`rfq_response_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rfq_requests`
--

DROP TABLE IF EXISTS `rfq_requests`;
CREATE TABLE IF NOT EXISTS `rfq_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rfq_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int DEFAULT NULL,
  `detailed_req` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` timestamp NOT NULL,
  `preferred_terms` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Open','Closed','Awarded') COLLATE utf8mb4_unicode_ci DEFAULT 'Open',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfq_id` (`rfq_id`),
  KEY `category_id` (`category_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rfq_responses`
--

DROP TABLE IF EXISTS `rfq_responses`;
CREATE TABLE IF NOT EXISTS `rfq_responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `response_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfq_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `available_qty` int NOT NULL,
  `delivery_lead_time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moq` int DEFAULT NULL,
  `total_cost` decimal(15,2) GENERATED ALWAYS AS ((`unit_price` * `available_qty`)) STORED,
  `vendor_terms` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Submitted','Accepted','Rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `response_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `response_id` (`response_id`),
  UNIQUE KEY `unique_rfq_vendor` (`rfq_id`,`vendor_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `contact` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `role` enum('Admin','Manager','Vendor','Auditor','Procurement Manager','Vendor Manager') COLLATE utf8mb4_unicode_ci DEFAULT 'Vendor',
  `status` enum('Active','Inactive','Pending') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `first_name`, `last_name`, `username`, `password`, `email`, `email_verified_at`, `address`, `contact`, `picture`, `role`, `status`, `company`, `created_at`, `updated_at`) VALUES
(1, 116088, 'Julian', 'Short', 'admin', '$2y$10$dp2/MAobH2wWvX24MxGnBew6hSReJ.H4rGTslAIyuF8E5r4TmhNx.', 'zopo@mailinator.com', NULL, 'Et sunt sint dolorem', '09082546789', 'default.png', 'Admin', 'Active', 'Mack and Guerrero Plc', '2025-01-31 01:54:31', '2025-01-31 01:55:00'),
(2, 113225, 'Sara', 'Lamb', 'procurement', '$2y$10$dp2/MAobH2wWvX24MxGnBew6hSReJ.H4rGTslAIyuF8E5r4TmhNx.', 'tavukyko@mailinator.com', NULL, 'Iure eos itaque cupi', '02002202484', 'default.png', 'Procurement Manager', 'Active', 'Oliver White Associates', '2025-01-31 01:56:54', '2025-01-31 01:59:34'),
(3, 117525, 'Chloe', 'Key', 'vendor.manager', '$2y$10$dp2/MAobH2wWvX24MxGnBew6hSReJ.H4rGTslAIyuF8E5r4TmhNx.', 'pepetir@mailinator.com', NULL, 'Inventore eius molli', '9944039575', 'default.png', 'Vendor Manager', 'Active', 'Zimmerman Hayes Plc', '2025-01-31 02:03:10', '2025-01-31 02:04:12'),
(4, 111404, 'Freya', 'Hewitt', 'vendor01', '$2y$10$mXf80z4I80WPI210/yb64evSin8YUSUcxy0icrizpMwPvfMordq6O', 'qagonofoc@mailinator.com', NULL, 'Minus aut error accu', '09945039685', 'default.png', 'Vendor', 'Active', 'Torres and Odom Co', '2025-01-31 02:06:15', '2025-01-31 03:16:56'),
(5, 116862, 'Ora', 'Jackson', 'vendor02', '$2y$10$cZZfnUZc26niZ60mP.4q5OcTZ9CQRAWryxLddoS/..VaDgUF8ddiC', 'tipikixih@mailinator.com', NULL, 'Mollit et aliquip te', '56', 'default.png', 'Vendor', 'Pending', 'England Guy Plc', '2025-01-31 02:26:37', '2025-01-31 02:26:37'),
(6, 119417, 'Kylie', 'Price', 'vendor03', '$2y$10$b.uQLOq6cGRrRA88kZbzEuDjd6RhbsuwvR4nzkIQC/byTb2jmkIdS', 'dodiwybame@mailinator.com', NULL, 'Voluptatem atque mag', '16', 'default.png', 'Vendor', 'Pending', 'Harper and Pacheco Trading', '2025-01-31 02:27:45', '2025-01-31 02:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `vendors_application`
--

DROP TABLE IF EXISTS `vendors_application`;
CREATE TABLE IF NOT EXISTS `vendors_application` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL,
  `business_license` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tin_certificate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_references` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Approved','Declined') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget_approvals`
--
ALTER TABLE `budget_approvals`
  ADD CONSTRAINT `budget_approvals_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`requisition_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_approvals_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`requisition_id`),
  ADD CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `purchase_requisitions_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `purchase_requisitions_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `purchase_requisitions_ibfk_3` FOREIGN KEY (`rfq_response_id`) REFERENCES `rfq_responses` (`response_id`);

--
-- Constraints for table `rfq_requests`
--
ALTER TABLE `rfq_requests`
  ADD CONSTRAINT `rfq_requests_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`),
  ADD CONSTRAINT `rfq_requests_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rfq_responses`
--
ALTER TABLE `rfq_responses`
  ADD CONSTRAINT `rfq_responses_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rfq_responses_ibfk_2` FOREIGN KEY (`rfq_id`) REFERENCES `rfq_requests` (`rfq_id`);

--
-- Constraints for table `vendors_application`
--
ALTER TABLE `vendors_application`
  ADD CONSTRAINT `vendors_application_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
