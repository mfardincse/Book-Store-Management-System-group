-- Book Haven Database Schema (XAMPP/MySQL/MariaDB friendly)
-- Database name: bookhaven

CREATE DATABASE IF NOT EXISTS `bookhaven` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bookhaven`;

-- USERS: Admin, Employee, Customer
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role` ENUM('admin','employee','customer') NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(20) NULL,
  `address` TEXT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SHIPPING ADDRESSES (for customer checkout)
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `address` TEXT NOT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_address` (`user_id`),
  CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOKS
CREATE TABLE IF NOT EXISTS `books` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `author` VARCHAR(120) NULL,
  `isbn` VARCHAR(30) NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `quantity` INT NOT NULL DEFAULT 0,
  `available` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_books_available` (`available`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ORDERS (kept compatible with the existing session-cart checkout flow)
-- NOTE: This design stores one row per ordered book title (as the current PHP code inserts).
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `book_title` VARCHAR(200) NOT NULL,
  `order_date` DATE NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` DECIMAL(10,2) NOT NULL DEFAULT 100.00,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(40) NOT NULL DEFAULT 'On the way',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_orders_user` (`user_id`),
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- EMPLOYEE WORK SCHEDULE
CREATE TABLE IF NOT EXISTS `employee_schedules` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `employee_id` INT NOT NULL,
  `working_day` VARCHAR(20) NOT NULL,
  `working_time` VARCHAR(50) NOT NULL,
  `working_place` VARCHAR(80) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_schedule_employee` (`employee_id`),
  CONSTRAINT `fk_schedule_employee` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ACTIVITY LOGS (admin system overview)
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `activity` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_activity_user` (`user_id`),
  CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED DATA
-- NOTE: Passwords are stored in plain text here so you can log in immediately.
-- On first successful login, the app auto-upgrades them to secure password_hash() values.
INSERT INTO `users` (`role`,`name`,`email`,`password`,`mobile`,`address`,`status`) VALUES
('admin','Admin','admin@bookhaven.test','admin1234',NULL,NULL,'active'),
('employee','Employee One','employee@bookhaven.test','employee1234','01700000000','Dhaka','active'),
('customer','Customer One','customer@bookhaven.test','customer1234','01800000000','Dhaka','active')
ON DUPLICATE KEY UPDATE `updated_at`=`updated_at`;

INSERT INTO `books` (`title`,`author`,`isbn`,`price`,`quantity`,`available`) VALUES
('Clean Code','Robert C. Martin','9780132350884',650.00,10,1),
('The Pragmatic Programmer','Andrew Hunt','9780201616224',700.00,8,1),
('Design Patterns','GoF','9780201633610',850.00,5,1)
;
