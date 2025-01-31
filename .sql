CREATE TABLE `budget_approvals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `requisition_id` int NOT NULL,
  `amount` double(15,2) NOT NULL,
  `status` enum('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `approved_by` int DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `requisition_id` (`requisition_id`),  -- Index for foreign key
  KEY `approved_by` (`approved_by`),  -- Index for foreign key
  CONSTRAINT `budget_approvals_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`requisition_id`) ON DELETE CASCADE,
  CONSTRAINT `budget_approvals_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Unread', 'Read', 'Archived') COLLATE utf8mb4_general_ci DEFAULT 'Unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),  -- Index for foreign key
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL,
  `product_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(60) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `status` enum('Active', 'Inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `unit_price` double NOT NULL,
  `stock` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`),  -- Index for foreign key
  KEY `category_id` (`category_id`),  -- Index for foreign key
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `product_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  KEY `requisition_id` (`requisition_id`),  -- Index for foreign key
  KEY `product_id` (`product_id`),  -- Index for foreign key
  CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions` (`requisition_id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `purchase_requisitions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `requisition_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `created_by` int DEFAULT NULL,
  `total_quantity` double(15,2) NOT NULL,
  `total_cost` double(15,2) NOT NULL,
  `total_price` double(15,2) NOT NULL,
  `priority` enum('Low', 'Medium', 'High') COLLATE utf8mb4_general_ci DEFAULT 'Medium',
  `request_date` datetime NOT NULL,
  `status` enum('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfq_response_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),  -- Index for foreign key
  KEY `vendor_id` (`vendor_id`),  -- Index for foreign key
  KEY `rfq_response_id` (`rfq_response_id`),  -- Index for optional field
  KEY `requisition_id` (`requisition_id`),  -- Index for requisition_id
  CONSTRAINT `purchase_requisitions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_requisitions_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `rfq_requests` (
  `rfq_id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int DEFAULT NULL,
  `detailed_req` text COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `request_date` datetime NOT NULL,
  `delivery_date` datetime NOT NULL,
  `preferred_terms` text COLLATE utf8mb4_general_ci,
  `status` enum('Open', 'Closed', 'Awarded') COLLATE utf8mb4_general_ci DEFAULT 'Open',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rfq_id`),
  KEY `category_id` (`category_id`),  -- Index for foreign key
  KEY `created_by` (`created_by`)  -- Index for foreign key
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `rfq_responses` (
  `response_id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `rfq_id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `vendor_id` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `available_qty` int NOT NULL,
  `delivery_lead_time` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `moq` int DEFAULT NULL,
  `total_cost` decimal(15,2) GENERATED ALWAYS AS ((`unit_price` * `available_qty`)) VIRTUAL,
  `vendor_terms` text COLLATE utf8mb4_general_ci,
  `status` enum('Pending', 'Submitted', 'Accepted', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `response_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`response_id`),
  UNIQUE KEY `unique_rfq_vendor` (`rfq_id`, `vendor_id`),  -- Ensures unique responses for each vendor
  KEY `vendor_id` (`vendor_id`)  -- Index for foreign key
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `picture` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `role` enum('Admin', 'Manager', 'Vendor', 'Auditor', 'Procurement Manager', 'Vendor Manager') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Vendor',
  `status` enum('Active', 'Inactive', 'Pending') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `company` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `vendors_application` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL,
  `business_license` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tin_certificate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `certificate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `other_references` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Pending', 'Approved', 'Declined') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_id` (`vendor_id`),  -- Unique constraint for vendor_id
  CONSTRAINT `vendors_application_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
