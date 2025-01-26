-- Users Table
CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL UNIQUE,
    `first_name` VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    `last_name` VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
    `username` VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
    `password` VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    `email` VARCHAR(60) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `address` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `contact` VARCHAR(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `picture` VARCHAR(50) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
    `role` ENUM('Admin','Manager','Vendor','Auditor') COLLATE utf8mb4_general_ci DEFAULT 'Vendor',
    `status` ENUM('Active','Inactive','Pending') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
    `company` VARCHAR(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Vendors Application Table
CREATE TABLE `vendors_application` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `vendor_id` INT NOT NULL UNIQUE,
    `business_license` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    `tin_certificate` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    `certificate` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    `other_references` VARCHAR(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `remarks` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `status` ENUM('Pending','Approved','Declined') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`vendor_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Notifications Table
CREATE TABLE `notifications` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `message` TEXT COLLATE utf8mb4_general_ci NOT NULL,
    `action` VARCHAR(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `status` ENUM('Unread','Read','Archived') COLLATE utf8mb4_general_ci DEFAULT 'Unread',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Products Table
CREATE TABLE `products` (
 `id` int NOT NULL AUTO_INCREMENT,
 `product_id` INT(11) NOT NULL,
 `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
 `description` text COLLATE utf8mb4_general_ci,
 `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
 `status` enum('Active','Inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
 `unit_price` double NOT NULL,
 `stock` int NOT NULL,
 `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `product_id` (`product_id`),
 KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

-- Purchase Requisitions Table
CREATE TABLE `purchase_requisitions` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `vendor_id` INT NOT NULL,
    `created_by` INT NULL,
    `total_quantity` DOUBLE(15,2) NOT NULL,
    `total_cost` DOUBLE(15,2) NOT NULL,
    `total_price` DOUBLE(15,2) NOT NULL,
    `priority` ENUM('Low', 'Medium', 'High') COLLATE utf8mb4_general_ci DEFAULT 'Medium',
    `request_date` DATETIME NOT NULL,
    `status` ENUM('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`user_id`) ON DELETE SET NULL,
    FOREIGN KEY (`vendor_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Purchase Items Table
CREATE TABLE `purchase_items` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `requisition_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `cost` DOUBLE(15,2) NOT NULL,
    `price` DOUBLE(15,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Budget Approvals Table
CREATE TABLE `budget_approvals` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `requisition_id` INT NOT NULL,
    `amount` DOUBLE(15,2) NOT NULL,
    `status` ENUM('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
    `approved_by` INT NULL,
    `approval_date` DATETIME NULL,
    `remarks` TEXT COLLATE utf8mb4_general_ci NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Request Quotations Table
CREATE TABLE `request_quotations` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `vendor_id` INT NOT NULL,
    `requested_qty` INT NOT NULL,
    `status` ENUM('Pending', 'Responded', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
    `response_date` DATETIME NULL,
    `remarks` TEXT COLLATE utf8mb4_general_ci NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`vendor_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

