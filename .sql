CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `first_name` varchar(20) NOT NULL,
 `last_name` varchar(20) NOT NULL,
 `username` varchar(50) NOT NULL,
 `password` varchar(255) NOT NULL,
 `email` varchar(60) NOT NULL,
 `email_verified_at` timestamp NULL DEFAULT NULL,
 `address` varchar(255) DEFAULT NULL,
 `contact` varchar(20) DEFAULT NULL,
 `picture` varchar(50) DEFAULT 'default.png',
 `role` enum('Admin','Manager','Vendor','Auditor') DEFAULT 'Vendor',
 `status` enum('Active','Inactive','Pending') DEFAULT 'Pending',
 `company` varchar(50) DEFAULT NULL,
 `created_at` timestamp NULL DEFAULT current_timestamp(),
 `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 UNIQUE KEY `user_id` (`user_id`),
 UNIQUE KEY `username` (`username`),
 UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `vendors_application` (
 `id` int NOT NULL AUTO_INCREMENT,
 `vendor_id` int NOT NULL,
 `business_license` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
 `tin_certificate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
 `certificate` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
 `other_references` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
 `remarks` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
 `status` enum('Pending','Approved','Declined') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
 `created_at` datetime NOT NULL,
 `updated_at` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `vendor_id` (`vendor_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `notifications` (
 `id` int NOT NULL AUTO_INCREMENT,
 `user_id` int NOT NULL,
 `message` text COLLATE utf8mb4_general_ci NOT NULL,
 `action` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
 `status` enum('Unread','Read','Archived') COLLATE utf8mb4_general_ci DEFAULT 'Unread',
 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci