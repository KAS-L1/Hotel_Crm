-- Users Table
CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `first_name` VARCHAR(20) NOT NULL,
    `last_name` VARCHAR(20) NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(60) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `address` VARCHAR(255) DEFAULT NULL,
    `contact` VARCHAR(20) DEFAULT NULL,
    `picture` VARCHAR(50) DEFAULT 'default.png',
    `role` ENUM('Admin','Manager','Vendor','Auditor') COLLATE utf8mb4_general_ci DEFAULT 'Vendor',
    `status` ENUM('Active','Inactive','Pending') COLLATE utf8mb4_general_ci DEFAULT 'Pending',
    `company` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Vendors Application Table
CREATE TABLE `vendors_application` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `vendor_id` INT NOT NULL,
    `business_license` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    `tin_certificate` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    `certificate` VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    `other_references` VARCHAR(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `remarks` VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `status` ENUM('Pending','Approved','Declined') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `vendor_id` (`vendor_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Notifications Table
CREATE TABLE `notifications` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `message` TEXT COLLATE utf8mb4_general_ci NOT NULL,
    `action` VARCHAR(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `status` ENUM('Unread','Read','Archived') COLLATE utf8mb4_general_ci DEFAULT 'Unread',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Purchase Requisitions Table
CREATE TABLE `purchase_requisitions` (
    `id` CHAR(36) PRIMARY KEY, 
    `requisition_id` INT UNIQUE AUTO_INCREMENT, 
    `vendor_id` CHAR(36) NOT NULL, 
    `created_by` CHAR(36) NULL, 
    `total_quantity` DOUBLE(15,2) NOT NULL, 
    `total_cost` DOUBLE(15,2) NOT NULL, 
    `total_price` DOUBLE(15,2) NOT NULL, 
    `priority` ENUM('Low', 'Medium', 'High') COLLATE utf8mb4_general_ci DEFAULT 'Medium', 
    `request_date` DATETIME NOT NULL, 
    `status` ENUM('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending', 
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL, 
    FOREIGN KEY (`vendor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Purchase Items Table
CREATE TABLE `purchase_items` (
    `id` CHAR(36) PRIMARY KEY, 
    `purchase_id` INT UNIQUE AUTO_INCREMENT, 
    `requisition_id` CHAR(36) NOT NULL, 
    `product_id` CHAR(36) NOT NULL, 
    `quantity` INT NOT NULL, 
    `cost` DOUBLE(15,2) NOT NULL, 
    `price` DOUBLE(15,2) NOT NULL, 
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions`(`id`) ON DELETE CASCADE, 
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Budget Approvals Table
CREATE TABLE `budget_approvals` (
    `id` CHAR(36) PRIMARY KEY, 
    `approval_id` INT UNIQUE AUTO_INCREMENT, 
    `requisition_id` CHAR(36) NOT NULL, 
    `amount` DOUBLE(15,2) NOT NULL, 
    `status` ENUM('Pending', 'Approved', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending', 
    `approved_by` VARCHAR(100) COLLATE utf8mb4_general_ci NULL, 
    `approval_date` DATETIME NULL, 
    `remarks` TEXT COLLATE utf8mb4_general_ci NULL, 
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (`requisition_id`) REFERENCES `purchase_requisitions`(`id`) ON DELETE CASCADE
) AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Request Quotations Table
CREATE TABLE `request_quotations` (
    `id` CHAR(36) PRIMARY KEY, 
    `rfq_id` INT UNIQUE AUTO_INCREMENT, 
    `product_id` CHAR(36) NOT NULL, 
    `vendor_id` CHAR(36) NOT NULL, 
    `requested_qty` INT NOT NULL, 
    `status` ENUM('Pending', 'Responded', 'Rejected') COLLATE utf8mb4_general_ci DEFAULT 'Pending', 
    `response_date` DATETIME NULL, 
    `remarks` TEXT COLLATE utf8mb4_general_ci NULL, 
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE, 
    FOREIGN KEY (`vendor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
