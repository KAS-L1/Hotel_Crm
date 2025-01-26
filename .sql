-- Users Table
CREATE TABLE `users` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `first_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `last_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `email` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `email_verified_at` timestamp NULL DEFAULT NULL,
 `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'default.png',
 `role` enum('Admin','Manager','Vendor','Auditor') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Vendor',
 `status` enum('Active','Inactive','Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
 `company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_id` (`user_id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Vendors Application Table
CREATE TABLE `vendors_application` (
 `id` int NOT NULL AUTO_INCREMENT,
 `vendor_id` int NOT NULL,
 `business_license` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `tin_certificate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `certificate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `other_references` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `status` enum('Pending','Approved','Declined') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
 `created_at` datetime NOT NULL,
 `updated_at` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `vendor_id` (`vendor_id`),
 CONSTRAINT `vendors_application_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Notifications Table
CREATE TABLE `notifications` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
 `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
 `status` enum('Unread','Read','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Unread',
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Products Table
CREATE TABLE `products` (
 `id` int NOT NULL AUTO_INCREMENT,
 `vendor_id` int NOT NULL,
 `product_id` int NOT NULL,
 `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
 `description` text COLLATE utf8mb4_general_ci,
 `image` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'default.png',
 `status` enum('Active','Inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
 `unit_price` double NOT NULL,
 `stock` int NOT NULL,
 `category_id` int NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `product_id` (`product_id`),
 KEY `vendor_id` (`vendor_id`),
 KEY `category_id` (`category_id`),
 CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
 CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Product Categories Table
CREATE TABLE `product_categories` (
 `id` int NOT NULL AUTO_INCREMENT,
 `category_id` int NOT NULL,
 `category` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
 `created_at` datetime NOT NULL,
 `updated_at` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3

-- Purchase Requisitions Table
CREATE TABLE `purchase_requisitions` (
 `id` int NOT NULL AUTO_INCREMENT,
 `vendor_id` int NOT NULL,
 `created_by` int DEFAULT NULL,
 `total_quantity` double(15,2) NOT NULL,
 `total_cost` double(15,2) NOT NULL,
 `total_price` double(15,2) NOT NULL,
 `priority` enum('Low','Medium','High') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Medium',
 `request_date` datetime NOT NULL,
 `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `created_by` (`created_by`),
 KEY `vendor_id` (`vendor_id`),
 CONSTRAINT `purchase_requisitions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
 CONSTRAINT `purchase_requisitions_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Purchase Items Table
CREATE TABLE `purchase_items` (
 `id` int NOT NULL AUTO_INCREMENT,
 `requisition_id` int NOT NULL,
 `product_id` int NOT NULL,
 `quantity` int NOT NULL,
 `cost` double(15,2) NOT NULL,
 `price` double(15,2) NOT NULL,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `requisition_id` (`requisition_id`),
 KEY `product_id` (`product_id`),
 CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE,
 CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Budget Approvals Table
CREATE TABLE `budget_approvals` (
 `id` int NOT NULL AUTO_INCREMENT,
 `requisition_id` int NOT NULL,
 `amount` double(15,2) NOT NULL,
 `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
 `approved_by` int DEFAULT NULL,
 `approval_date` datetime DEFAULT NULL,
 `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `requisition_id` (`requisition_id`),
 KEY `approved_by` (`approved_by`),
 CONSTRAINT `budget_approvals_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE,
 CONSTRAINT `budget_approvals_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Request Quotations Table
CREATE TABLE `request_quotations` (
 `id` int NOT NULL AUTO_INCREMENT,
 `product_id` int NOT NULL,
 `vendor_id` int NOT NULL,
 `requested_qty` int NOT NULL,
 `status` enum('Pending','Responded','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
 `response_date` datetime DEFAULT NULL,
 `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `product_id` (`product_id`),
 KEY `vendor_id` (`vendor_id`),
 CONSTRAINT `request_quotations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
 CONSTRAINT `request_quotations_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci