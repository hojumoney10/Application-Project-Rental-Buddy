-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 10, 2021 at 08:40 AM
-- Server version: 8.0.23-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental`
--
CREATE DATABASE IF NOT EXISTS `rental` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rental`;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
CREATE TABLE IF NOT EXISTS `codes` (
  `code_id` int NOT NULL AUTO_INCREMENT,
  `code_type` varchar(20) NOT NULL,
  `code_value` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `is_default` int NOT NULL DEFAULT (0),
  `sort_order` int NOT NULL DEFAULT (0),
  `is_enabled` tinyint(1) NOT NULL DEFAULT (1),
  PRIMARY KEY (`code_id`),
  UNIQUE KEY `uk_codes_code_type_value` (`code_type`,`code_value`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`code_id`, `code_type`, `code_value`, `description`, `is_default`, `sort_order`, `is_enabled`) VALUES
(1, 'user_role', 'tenant', 'Tenant', 1, 0, 1),
(2, 'user_role', 'landlord', 'Landlord', 0, 0, 1),
(3, 'user_role', 'admin', 'Administrator', 0, 0, 1),
(4, 'user_status', 'enabled', 'Enabled', 0, 0, 1),
(5, 'user_status', 'disabled', 'Disabled', 0, 0, 1),
(6, 'user_status', 'pending', 'Pending', 1, 0, 1),
(7, 'salutation', 'mr', 'Mr.', 0, 0, 1),
(8, 'salutation', 'mrs', 'Mrs.', 0, 0, 1),
(9, 'salutation', 'ms', 'Ms.', 0, 0, 1),
(10, 'province', 'AB', 'Alberta', 0, 0, 1),
(11, 'province', 'BC', 'British Colombia', 0, 0, 1),
(12, 'province', 'MB', 'Manitoba', 0, 0, 1),
(13, 'province', 'NB', 'New Brunswick', 0, 0, 1),
(14, 'province', 'NL', 'Newfoundland & Labrador', 0, 0, 1),
(15, 'province', 'NS', 'Nova Scotia', 0, 0, 1),
(16, 'province', 'NT', 'Northwest Territories', 0, 0, 1),
(17, 'province', 'NU', 'Nunavut', 0, 0, 1),
(18, 'province', 'ON', 'Ontario', 1, 0, 1),
(19, 'province', 'PE', 'Prince Edward Island', 0, 0, 1),
(20, 'province', 'QB', 'Quebec', 0, 0, 1),
(21, 'province', 'SK', 'Saskatchewan', 0, 0, 1),
(22, 'province', 'YT', 'Yukon', 0, 0, 1),
(23, 'gender', 'male', 'Male', 1, 0, 1),
(24, 'gender', 'female', 'Female', 0, 0, 1),
(25, 'landlord_status', 'active', 'Active', 1, 0, 1),
(26, 'landlord_status', 'inactive', 'Inactive', 0, 0, 1),
(27, 'tenant_status', 'active', 'Active', 1, 0, 1),
(28, 'tenant_status', 'inactive', 'Inactive', 0, 0, 1),
(29, 'property_status', 'available', 'Available', 1, 0, 1),
(30, 'property_status', 'leased', 'Leased', 0, 0, 1),
(31, 'property_status', 'inactive', 'Inactive', 0, 0, 1),
(32, 'property_type', 'room', 'Room', 0, 0, 1),
(33, 'property_type', 'house', 'House', 0, 0, 1),
(34, 'property_type', 'houseshare', 'House - shared', 0, 0, 1),
(35, 'property_type', 'apartment', 'Apartment', 0, 0, 1),
(36, 'parking_space', 'none', 'No parking', 1, 0, 1),
(37, 'parking_space', 'drive', 'Driveway', 0, 0, 1),
(38, 'parking_space', 'garage', 'Garage', 0, 0, 1),
(39, 'parking_space', 'underground', 'Underground', 0, 0, 1),
(40, 'parking_space', 'street', 'Street', 0, 0, 1),
(41, 'parking_space', 'carport', 'Carport', 0, 0, 1),
(42, 'rental_duration', 'monthly', 'Monthly', 1, 0, 1),
(43, 'rental_duration', 'weekly', 'Weekly', 0, 0, 1),
(44, 'rental_duration', 'daily', 'Daily', 0, 0, 1),
(45, 'payment_frequency', 'monthly', 'Monthly', 1, 0, 1),
(46, 'payment_frequency', 'weekly', 'Weekly', 0, 0, 1),
(47, 'payment_frequency', 'biweekly', 'Bi-weekly', 0, 0, 1),
(48, 'payment_type', 'etransfer', 'E-transfer', 1, 0, 1),
(49, 'payment_type', 'cheque', 'Cheque', 0, 0, 1),
(50, 'payment_type', 'postdated', 'Post-dated Cheque', 0, 0, 1),
(51, 'payment_type', 'debitcredit', 'Debit/Credit', 0, 0, 1),
(52, 'payment_type', 'paypal', 'Paypal', 0, 0, 1),
(53, 'lease_status', 'active', 'Active', 1, 0, 1),
(54, 'lease_status', 'expired', 'Expired', 0, 0, 1),
(55, 'lease_status', 'cancelled', 'Cancelled', 0, 0, 1),
(56, 'request_type', 'repair', 'Repair', 1, 0, 1),
(57, 'request_type', 'replacement', 'Replacement', 0, 0, 1),
(58, 'request_type', 'complaint', 'Complaint', 0, 0, 1),
(59, 'request_type', 'other', 'Other', 0, 0, 1),
(60, 'request_status', 'new', 'New', 1, 0, 1),
(61, 'request_status', 'received', 'Received', 0, 0, 1),
(62, 'request_status', 'inprogress', 'In progress', 0, 0, 1),
(63, 'request_status', 'completed', 'Completed', 0, 0, 1),
(64, 'request_priority', 'low', 'Low', 1, 0, 1),
(65, 'request_priority', 'medium', 'Medium', 0, 0, 1),
(66, 'request_priority', 'high', 'High', 0, 0, 1),
(67, 'request_solution', 'repaired', 'Repaired', 1, 0, 1),
(68, 'request_solution', 'replaced', 'Replaced', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `landlords`
--

DROP TABLE IF EXISTS `landlords`;
CREATE TABLE IF NOT EXISTS `landlords` (
  `landlord_id` int NOT NULL AUTO_INCREMENT,
  `legal_name` varchar(50) NOT NULL,
  `salutation_code` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `province_code` varchar(2) NOT NULL DEFAULT (_utf8mb4'ON'),
  `postal_code` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sms` varchar(20) NOT NULL,
  `status_code` varchar(10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landlord_rental_properties`
--

DROP TABLE IF EXISTS `landlord_rental_properties`;
CREATE TABLE IF NOT EXISTS `landlord_rental_properties` (
  `landlord_rental_property_id` int NOT NULL AUTO_INCREMENT,
  `landlord_id` int NOT NULL,
  `rental_property_id` int NOT NULL,
  PRIMARY KEY (`landlord_rental_property_id`),
  KEY `fk_landlord_rental_properties_rental_properties` (`rental_property_id`),
  KEY `fk_landlord_rental_properties_landlords` (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

DROP TABLE IF EXISTS `leases`;
CREATE TABLE IF NOT EXISTS `leases` (
  `lease_id` int NOT NULL AUTO_INCREMENT,
  `rental_property_id` int NOT NULL,
  `tenant_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `payment_day` int NOT NULL DEFAULT (1),
  `payment_frequency_code` varchar(20) NOT NULL,
  `base_rent_amount` decimal(10,2) NOT NULL DEFAULT (0),
  `parking_amount` decimal(10,2) NOT NULL DEFAULT (0),
  `other_amount` decimal(10,2) NOT NULL DEFAULT (0),
  `payable_to` varchar(50) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT (0),
  `key_deposit` decimal(10,2) NOT NULL DEFAULT (0),
  `payment_type_code` varchar(20) NOT NULL,
  `include_electricity` tinyint(1) NOT NULL DEFAULT (0),
  `include_heat` tinyint(1) NOT NULL DEFAULT (0),
  `include_water` tinyint(1) NOT NULL DEFAULT (0),
  `insurancy_policy_number` varchar(50) DEFAULT NULL,
  `status_code` varchar(20) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`lease_id`),
  KEY `fk_leases_rental_properties` (`rental_property_id`),
  KEY `fk_leases_tenants` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rental_properties`
--

DROP TABLE IF EXISTS `rental_properties`;
CREATE TABLE `rental_properties` (
  `rental_property_id` int NOT NULL,
  `address_1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (_utf8mb4'ON'),
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_bedrooms` int NOT NULL,
  `property_type_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parking_space_type_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_parking_spaces` int NOT NULL DEFAULT '0',
  `rental_duration_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (_utf8mb4'monthly'),
  `smoking_allowed` tinyint(1) NOT NULL DEFAULT (0),
  `insurance_required` tinyint(1) NOT NULL DEFAULT (1),
  `status_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `request_date` datetime NOT NULL DEFAULT (now()),
  `rental_property_id` int NOT NULL,
  `tenant_id` int NOT NULL,
  `request_type_code` varchar(20) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `status_code` varchar(20) NOT NULL DEFAULT (_utf8mb4'new'),
  `priority_code` varchar(20) NOT NULL,
  `solution_date` datetime NOT NULL,
  `solution_code` varchar(20) NOT NULL,
  `solution_description` varchar(1024) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `fk_requests_rental_properties` (`rental_property_id`),
  KEY `fk_requests_tenants` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
CREATE TABLE IF NOT EXISTS `tenants` (
  `tenant_id` int NOT NULL AUTO_INCREMENT,
  `salutation_code` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `province_code` varchar(2) NOT NULL DEFAULT (_utf8mb4'ON'),
  `postal_code` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `social_insurance_number` varchar(10) DEFAULT NULL,
  `status_code` varchar(10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_role_code` varchar(20) NOT NULL,
  `status_code` varchar(20) NOT NULL,
  `tenant_id` int DEFAULT NULL,
  `landlord_id` int DEFAULT NULL,
  `last_login` datetime NOT NULL DEFAULT (now()),
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated_user_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_tenants` (`tenant_id`),
  KEY `fk_users_landlords` (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `email`, `user_role_code`, `status_code`, `tenant_id`, `landlord_id`, `last_login`, `last_updated`, `last_updated_user_id`) VALUES
('admin', 'admin', 'admin@localhost', 'admin', 'enabled', NULL, NULL, '2021-02-10 08:31:13', '2021-02-10 08:31:13', NULL),
('landlord', 'landlord', 'landlord@localhost', 'landlord', 'enabled', NULL, NULL, '2021-02-10 08:31:13', '2021-02-10 08:31:13', NULL),
('tenant', 'tenant', 'tenant@localhost', 'tenant', 'enabled', NULL, NULL, '2021-02-10 08:31:13', '2021-02-10 08:31:13', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `fk_leases_rental_properties` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`),
  ADD CONSTRAINT `fk_leases_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `fk_requests_rental_properties` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`),
  ADD CONSTRAINT `fk_requests_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_landlords` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`),
  ADD CONSTRAINT `fk_users_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;