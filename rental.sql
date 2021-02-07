-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2021 at 07:51 PM
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
CREATE DATABASE IF NOT EXISTS `rental` DEFAULT CHARACTER SET utf8;
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
  PRIMARY KEY (`code_id`),
  UNIQUE KEY `uk_codes_code_type_value` (`code_type`,`code_value`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`code_id`, `code_type`, `code_value`, `description`, `is_default`) VALUES
(1, 'user_type', 'tenant', 'Tenant', 1),
(2, 'user_type', 'landlord', 'Landlord', 0),
(3, 'user_type', 'admin', 'Administrator', 0),
(4, 'user_status', 'enabled', 'Enabled', 0),
(5, 'user_status', 'disabled', 'Disabled', 0),
(6, 'user_status', 'pending', 'Pending', 1),
(7, 'salutation', 'mr', 'Mr.', 0),
(8, 'salutation', 'mrs', 'Mrs.', 0),
(9, 'salutation', 'ms', 'Ms.', 0),
(10, 'province', 'AB', 'Alberta', 0),
(11, 'province', 'BC', 'British Colombia', 0),
(12, 'province', 'MB', 'Manitoba', 0),
(13, 'province', 'NB', 'New Brunswick', 0),
(14, 'province', 'NL', 'Newfoundland & Labrador', 0),
(15, 'province', 'NS', 'Nova Scotia', 0),
(16, 'province', 'NT', 'Northwest Territories', 0),
(17, 'province', 'NU', 'Nunavut', 0),
(18, 'province', 'ON', 'Ontario', 1),
(19, 'province', 'PE', 'Prince Edward Island', 0),
(20, 'province', 'QB', 'Quebec', 0),
(21, 'province', 'SK', 'Saskatchewan', 0),
(22, 'province', 'YT', 'Yukon', 0),
(27, 'landlord_status', 'active', 'Active', 1),
(28, 'landlord_status', 'inactive', 'Inactive', 0),
(29, 'gender', 'male', 'Male', 1),
(30, 'gender', 'female', 'Female', 0),
(31, 'tenant_status', 'active', 'Active', 1),
(32, 'tenant_status', 'inactive', 'Inactive', 0),
(35, 'property_status', 'available', 'Available', 1),
(36, 'property_status', 'leased', 'Leased', 0),
(37, 'property_status', 'inactive', 'Inactive', 0),
(38, 'property_type', 'room', 'Room', 0),
(39, 'property_type', 'house', 'House', 0),
(40, 'property_type', 'houseshare', 'House - shared', 0),
(41, 'property_type', 'apartment', 'Apartment', 0),
(42, 'parking_space', 'none', 'No parking', 1),
(43, 'parking_space', 'drive', 'Driveway', 0),
(44, 'parking_space', 'garage', 'Garage', 0),
(45, 'parking_space', 'underground', 'Underground', 0),
(46, 'parking_space', 'street', 'Street', 0),
(47, 'parking_space', 'carport', 'Carport', 0),
(48, 'rental_duration', 'monthly', 'Monthly', 1),
(49, 'rental_duration', 'weekly', 'Weekly', 0),
(50, 'rental_duration', 'daily', 'Daily', 0),
(51, 'payment_frequency', 'monthly', 'Monthly', 1),
(52, 'payment_frequency', 'weekly', 'Weekly', 0),
(53, 'payment_frequency', 'biweekly', 'Bi-weekly', 0),
(54, 'payment_type', 'etransfer', 'E-transfer', 1),
(55, 'payment_type', 'cheque', 'Cheque', 0),
(56, 'payment_type', 'post-dated', 'Post-dated Cheque', 0),
(57, 'payment_type', 'debitcredit', 'Debit/Credit', 0),
(58, 'payment_type', 'paypal', 'Paypal', 0),
(59, 'lease_status', 'active', 'Active', 1),
(60, 'lease_status', 'expired', 'Expired', 0),
(61, 'lease_status', 'cancelled', 'Cancelled', 0);

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
  `last_updated` datetime NOT NULL DEFAULT (now()),
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `landlord_properties`
--

DROP TABLE IF EXISTS `landlord_properties`;
CREATE TABLE IF NOT EXISTS `landlord_properties` (
  `landlord_rental_property_id` int NOT NULL AUTO_INCREMENT,
  `landlord_id` int NOT NULL,
  `rental_property_id` int NOT NULL,
  PRIMARY KEY (`landlord_rental_property_id`),
  KEY `fk_landlord_rental_properties_rental_properties` (`rental_property_id`),
  KEY `fk_landlord_rental_properties_landlords` (`landlord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `last_updated` datetime NOT NULL DEFAULT (now()),
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`lease_id`),
  KEY `fk_leases_rental_properties` (`rental_property_id`),
  KEY `fk_leases_tenants` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rental_properties`
--

DROP TABLE IF EXISTS `rental_properties`;
CREATE TABLE IF NOT EXISTS `rental_properties` (
  `rental_property_id` int NOT NULL AUTO_INCREMENT,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `province_code` varchar(2) NOT NULL DEFAULT (_utf8mb4'ON'),
  `postal_code` varchar(10) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `number_bedrooms` int NOT NULL,
  `property_type_code` varchar(20) NOT NULL,
  `parking_space_type_code` varchar(20) NOT NULL,
  `number_parking_space` int NOT NULL DEFAULT (0),
  `rental_duration_code` varchar(20) NOT NULL DEFAULT (_utf8mb4'monthly'),
  `smoking_allowed` tinyint(1) NOT NULL DEFAULT (0),
  `insurance_required` tinyint(1) NOT NULL DEFAULT (1),
  `status_code` varchar(20) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT (now()),
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`rental_property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `last_updated` datetime NOT NULL DEFAULT (now()),
  `last_updated_user_id` varchar(50) NOT NULL,
  PRIMARY KEY (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_type_code` varchar(10) NOT NULL,
  `status_code` varchar(10) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT (now()),
  `last_updated` datetime NOT NULL DEFAULT (now()),
  `last_updated_user_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `email`, `user_type_code`, `status_code`, `last_login`, `last_updated`, `last_updated_user_id`) VALUES
('admin', 'admin', 'admin@localhost', 'admin', 'enabled', '2021-02-06 18:06:45', '2021-02-06 18:06:45', NULL),
('landlord', 'tenant', 'landlord@localhost', 'admin', 'enabled', '2021-02-06 18:06:45', '2021-02-06 18:06:45', NULL),
('tenant', 'tenant', 'tenant@localhost', 'admin', 'enabled', '2021-02-06 18:06:45', '2021-02-06 18:06:45', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `landlord_properties`
--
ALTER TABLE `landlord_properties`
  ADD CONSTRAINT `fk_landlord_rental_properties_landlords` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`),
  ADD CONSTRAINT `fk_landlord_rental_properties_rental_properties` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`);

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `fk_leases_rental_properties` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`),
  ADD CONSTRAINT `fk_leases_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;