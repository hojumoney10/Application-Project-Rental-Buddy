-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 07, 2021 at 01:58 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

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
(56, 'payment_type', 'postdated', 'Post-dated Cheque', 0),
(57, 'payment_type', 'debitcredit', 'Debit/Credit', 0),
(58, 'payment_type', 'paypal', 'Paypal', 0),
(59, 'lease_status', 'active', 'Active', 1),
(60, 'lease_status', 'expired', 'Expired', 0),
(61, 'lease_status', 'cancelled', 'Cancelled', 0),
(62, 'request_type', 'repair', 'Repair', 1),
(63, 'request_type', 'replacement', 'Replacement', 0),
(64, 'request_type', 'complaint', 'Complaint', 0),
(65, 'request_type', 'other', 'Other', 0),
(66, 'request_status', 'new', 'New', 1),
(67, 'request_status', 'received', 'Received', 0),
(68, 'request_status', 'inprogress', 'In progress', 0),
(69, 'request_status', 'completed', 'Completed', 0),
(70, 'request_priority', 'low', 'Low', 1),
(71, 'request_priority', 'medium', 'Medium', 0),
(72, 'request_priority', 'high', 'High', 0),
(73, 'request_solution', 'repaired', 'Repaired', 1),
(74, 'request_solution', 'replaced', 'Replaced', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;