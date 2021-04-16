-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2021 at 08:00 PM
-- Server version: 10.3.25-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `rental`
--
DROP DATABASE IF EXISTS `rental`;
CREATE DATABASE IF NOT EXISTS `rental` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rental`;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
CREATE TABLE `codes` (
  `code_id` int(11) NOT NULL,
  `code_type` varchar(20) NOT NULL,
  `code_value` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `is_default` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `css_styling` varchar(200) DEFAULT NULL,
  `data_value_numeric` decimal(10,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`code_id`, `code_type`, `code_value`, `description`, `is_default`, `sort_order`, `is_enabled`, `css_styling`, `data_value_numeric`) VALUES
(1, 'user_role', 'tenant', 'Tenant', 1, 0, 1, NULL, '0'),
(2, 'user_role', 'landlord', 'Landlord', 0, 0, 1, NULL, '0'),
(3, 'user_role', 'admin', 'Administrator', 0, 0, 1, NULL, '0'),
(4, 'user_status', 'enabled', 'Enabled', 0, 0, 1, NULL, '0'),
(5, 'user_status', 'disabled', 'Disabled', 0, 0, 1, NULL, '0'),
(6, 'user_status', 'pending', 'Pending', 1, 0, 1, NULL, '0'),
(7, 'salutation', 'mr', 'Mr.', 0, 0, 1, NULL, '0'),
(8, 'salutation', 'mrs', 'Mrs.', 0, 0, 1, NULL, '0'),
(9, 'salutation', 'ms', 'Ms.', 0, 0, 1, NULL, '0'),
(10, 'province', 'AB', 'Alberta', 0, 0, 1, NULL, '0'),
(11, 'province', 'BC', 'British Colombia', 0, 0, 1, NULL, '0'),
(12, 'province', 'MB', 'Manitoba', 0, 0, 1, NULL, '0'),
(13, 'province', 'NB', 'New Brunswick', 0, 0, 1, NULL, '0'),
(14, 'province', 'NL', 'Newfoundland & Labrador', 0, 0, 1, NULL, '0'),
(15, 'province', 'NS', 'Nova Scotia', 0, 0, 1, NULL, '0'),
(16, 'province', 'NT', 'Northwest Territories', 0, 0, 1, NULL, '0'),
(17, 'province', 'NU', 'Nunavut', 0, 0, 1, NULL, '0'),
(18, 'province', 'ON', 'Ontario', 1, 0, 1, NULL, '0'),
(19, 'province', 'PE', 'Prince Edward Island', 0, 0, 1, NULL, '0'),
(20, 'province', 'QB', 'Quebec', 0, 0, 1, NULL, '0'),
(21, 'province', 'SK', 'Saskatchewan', 0, 0, 1, NULL, '0'),
(22, 'province', 'YT', 'Yukon', 0, 0, 1, NULL, '0'),
(23, 'gender', 'male', 'Male', 1, 0, 1, NULL, '0'),
(24, 'gender', 'female', 'Female', 0, 0, 1, NULL, '0'),
(25, 'landlord_status', 'active', 'Active', 1, 0, 1, NULL, '0'),
(26, 'landlord_status', 'inactive', 'Inactive', 0, 0, 1, NULL, '0'),
(27, 'tenant_status', 'active', 'Active', 1, 0, 1, NULL, '0'),
(28, 'tenant_status', 'inactive', 'Inactive', 0, 0, 1, NULL, '0'),
(29, 'property_status', 'available', 'Available', 1, 0, 1, NULL, '0'),
(30, 'property_status', 'leased', 'Leased', 0, 0, 1, NULL, '0'),
(31, 'property_status', 'inactive', 'Inactive', 0, 0, 1, NULL, '0'),
(32, 'property_type', 'room', 'Room', 0, 0, 1, NULL, '0'),
(33, 'property_type', 'house', 'House', 0, 0, 1, NULL, '0'),
(34, 'property_type', 'houseshare', 'House - shared', 0, 0, 1, NULL, '0'),
(35, 'property_type', 'apartment', 'Apartment', 0, 0, 1, NULL, '0'),
(36, 'parking_space', 'none', 'No parking', 1, 0, 1, NULL, '0'),
(37, 'parking_space', 'drive', 'Driveway', 0, 0, 1, NULL, '0'),
(38, 'parking_space', 'garage', 'Garage', 0, 0, 1, NULL, '0'),
(39, 'parking_space', 'underground', 'Underground', 0, 0, 1, NULL, '0'),
(40, 'parking_space', 'street', 'Street', 0, 0, 1, NULL, '0'),
(41, 'parking_space', 'carport', 'Carport', 0, 0, 1, NULL, '0'),
(42, 'rental_duration', 'monthly', 'Monthly', 1, 0, 1, NULL, '0'),
(43, 'rental_duration', 'weekly', 'Weekly', 0, 0, 1, NULL, '0'),
(44, 'rental_duration', 'daily', 'Daily', 0, 0, 1, NULL, '0'),
(45, 'payment_frequency', 'monthly', 'Monthly', 1, 0, 1, NULL, '0'),
(46, 'payment_frequency', 'weekly', 'Weekly', 0, 0, 1, NULL, '0'),
(47, 'payment_frequency', 'biweekly', 'Bi-weekly', 0, 0, 1, NULL, '0'),
(48, 'payment_type', 'etransfer', 'E-transfer', 1, 0, 1, NULL, '0'),
(49, 'payment_type', 'cheque', 'Cheque', 0, 0, 1, NULL, '0'),
(50, 'payment_type', 'postdated', 'Post-dated Cheque', 0, 0, 1, NULL, '0'),
(51, 'payment_type', 'debitcredit', 'Debit/Credit', 0, 0, 1, NULL, '0'),
(52, 'payment_type', 'paypal', 'Paypal', 0, 0, 1, NULL, '0'),
(53, 'lease_status', 'active', 'Active', 1, 0, 1, NULL, '0'),
(54, 'lease_status', 'expired', 'Expired', 0, 0, 1, NULL, '0'),
(55, 'lease_status', 'cancelled', 'Cancelled', 0, 0, 1, NULL, '0'),
(56, 'request_type', 'repair', 'Repair', 1, 0, 1, NULL, '0'),
(57, 'request_type', 'replacement', 'Replacement', 0, 0, 1, NULL, '0'),
(58, 'request_type', 'complaint', 'Complaint', 0, 0, 1, NULL, '0'),
(59, 'request_type', 'other', 'Other', 0, 0, 1, NULL, '0'),
(60, 'request_status', 'new', 'New', 1, 0, 1, NULL, '0'),
(61, 'request_status', 'received', 'Received', 0, 1, 1, NULL, '0'),
(62, 'request_status', 'inprogress', 'In progress', 0, 2, 1, NULL, '0'),
(63, 'request_status', 'completed', 'Completed', 0, 3, 1, NULL, '0'),
(64, 'request_priority', 'low', 'Low', 1, 0, 1, NULL, '0'),
(65, 'request_priority', 'medium', 'Medium', 0, 1, 1, NULL, '0'),
(66, 'request_priority', 'high', 'High', 0, 2, 1, NULL, '0'),
(67, 'request_solution', 'repaired', 'Repaired', 1, 0, 1, NULL, '0'),
(68, 'request_solution', 'replaced', 'Replaced', 0, 0, 1, NULL, '0'),
(69, 'request_type', 'appointment', 'Appointment', 0, 9, 1, NULL, '0'),
(70, 'request_type', 'notification', 'notification', 0, 0, 1, NULL, '0'),
(71, 'payment_status', 'paid', 'Paid', 1, 0, 1, NULL, '0'),
(72, 'payment_status', 'late', 'Late Payment', 0, 0, 1, NULL, '0'),
(73, 'discount_code', '5', '5% Early Payment', 0, 0, 1, NULL, '5'),
(74, 'discount_code', '10', '10% Promo Discount', 0, 0, 0, NULL, '10');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `landlord_id` int(11) DEFAULT NULL,
  `lease_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `document_type_code` varchar(10) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `filename` varchar(200) NOT NULL,
  `status_code` varchar(10) NOT NULL DEFAULT 'active',
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `tenant_id`, `landlord_id`, `lease_id`, `request_id`, `document_type_code`, `description`, `filename`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 1, NULL, NULL, 26, NULL, NULL, 'Test file_1617677726.docx', 'active', '2021-04-05 22:55:26', 'tenant'),
(2, 1, NULL, NULL, 27, NULL, NULL, 'Screen Shot 2021-04-05 at 10.54.56 PM_1617686353.png', 'active', '2021-04-06 01:19:13', 'tenant'),
(3, 1, NULL, NULL, 27, NULL, NULL, 'Test file_1617686353.docx', 'active', '2021-04-06 01:19:13', 'tenant'),
(4, 1, NULL, NULL, 28, NULL, NULL, 'Common-Causes-for-Leaky-Faucets_1618518495.jpg', 'active', '2021-04-15 16:28:15', 'tenant'),
(5, 1, NULL, NULL, 29, NULL, NULL, 'Common-Causes-for-Leaky-Faucets_1618529466.jpg', 'active', '2021-04-15 19:31:06', 'tenant');

-- --------------------------------------------------------

--
-- Table structure for table `landlords`
--

DROP TABLE IF EXISTS `landlords`;
CREATE TABLE `landlords` (
  `landlord_id` int(11) NOT NULL,
  `legal_name` varchar(50) NOT NULL,
  `salutation_code` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `province_code` varchar(2) NOT NULL DEFAULT 'ON',
  `postal_code` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sms` varchar(20) NOT NULL,
  `status_code` varchar(10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `landlords`
--

INSERT INTO `landlords` (`landlord_id`, `legal_name`, `salutation_code`, `first_name`, `last_name`, `address_1`, `address_2`, `city`, `province_code`, `postal_code`, `phone`, `fax`, `email`, `sms`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 'Joe Ryder Incorporated', 'mr', 'Joe', 'Ryder', '172 Wonderland Road South', NULL, 'London', 'ON', 'N5N 1V2', '5195421239', '', 'joe@ryderinc.com', '5199287267', 'active', '2021-02-10 23:17:18', 'admin'),
(2, 'Michael Davidson', 'mr', 'Michael', 'Davidson', '1502 Whisperer Lane', NULL, 'London', 'ON', 'N3X 5W1', '5192871839', '', 'm.davidson@hotmail.com', '5192659817', 'active', '2021-02-10 23:17:18', 'admin'),
(3, 'Alysha Kearns', 'ms', 'Alysha', 'Kearns', '72 Moore Street', NULL, 'London', 'ON', 'N1A 7B2', '2268272272', '', 'a.kearns@hotmail.com', '5193298373', 'active', '2021-02-10 23:17:18', 'admin'),
(4, 'Dennis Taylor Inc.', 'mr', 'Dennis', 'Taylor', '56 Heather Street', '', 'London', 'ON', 'N7H 1H5', '2265676567', '', 'dtaylor@gmail.com', '2265342525', 'active', '2021-02-22 14:57:28', 'admin'),
(5, 'Anne Murray Inc.', 'mr', 'Anne', 'Murray', '8782 Dundas Street', '', 'London', 'ON', 'N3X1H2', '2266767876', '', 'amurray@murray.com', '2269878765', 'active', '2021-02-22 15:41:02', 'admin'),
(6, 'Oscar Lara Global Properties Inc.', 'mr', 'Oscar', 'Lara', '9829 Sheridan Avenue', '', 'London', 'ON', 'N7G1J8', '226982727', '', 'o.lara@geocities.com', '226938373', 'active', '2021-02-22 15:42:17', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `landlord_rental_properties`
--

DROP TABLE IF EXISTS `landlord_rental_properties`;
CREATE TABLE `landlord_rental_properties` (
  `landlord_rental_property_id` int(11) NOT NULL,
  `landlord_id` int(11) NOT NULL,
  `rental_property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `landlord_rental_properties`
--

INSERT INTO `landlord_rental_properties` (`landlord_rental_property_id`, `landlord_id`, `rental_property_id`) VALUES
(1, 1, 3),
(2, 1, 1),
(3, 2, 2),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

DROP TABLE IF EXISTS `leases`;
CREATE TABLE `leases` (
  `lease_id` int(11) NOT NULL,
  `rental_property_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `payment_day` int(11) NOT NULL DEFAULT 1,
  `payment_frequency_code` varchar(20) NOT NULL,
  `base_rent_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `parking_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `other_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payable_to` varchar(50) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `key_deposit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_type_code` varchar(20) NOT NULL,
  `include_electricity` tinyint(1) NOT NULL DEFAULT 0,
  `include_heat` tinyint(1) NOT NULL DEFAULT 0,
  `include_water` tinyint(1) NOT NULL DEFAULT 0,
  `insurancy_policy_number` varchar(50) DEFAULT NULL,
  `status_code` varchar(20) NOT NULL,
  `file` varchar(200) DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leases`
--

INSERT INTO `leases` (`lease_id`, `rental_property_id`, `tenant_id`, `start_date`, `end_date`, `payment_day`, `payment_frequency_code`, `base_rent_amount`, `parking_amount`, `other_amount`, `payable_to`, `deposit_amount`, `key_deposit`, `payment_type_code`, `include_electricity`, `include_heat`, `include_water`, `insurancy_policy_number`, `status_code`, `file`, `last_updated`, `last_updated_user_id`) VALUES
(1, 1, 1, '2021-02-01', '2021-09-30', 1, 'monthly', '1000.00', '0.00', '0.00', 'Graham Blandford', '2000.00', '0.00', 'etransfer', 1, 1, 0, 'HABA718191', 'active', 'Rental Agreement_1618529222.pdf', '2021-04-15 19:27:02', 'landlord'),
(3, 4, 2, '2021-02-22', '2021-02-28', 1, 'weekly', '300.00', '0.00', '0.00', 'Graham Blandford', '300.00', '20.00', 'etransfer', 1, 1, 0, 'HABA839292', 'active', NULL, '2021-02-22 15:15:19', 'admin'),
(4, 5, 3, '2021-02-22', '2022-05-22', 1, 'monthly', '2000.00', '30.00', '0.00', 'Graham Blandford', '4000.00', '25.00', 'postdated', 1, 1, 1, 'JSHS827292', 'active', NULL, '2021-02-22 15:32:34', 'admin'),
(5, 4, 3, '2021-01-01', '2021-12-31', 1, 'monthly', '1000.00', '100.00', '100.00', 'David Richardson', '1000.00', '100.00', 'etransfer', 1, 0, 1, 'ABCDE12345', 'active', 'Screen Shot 2021-04-05 at 10.54.56 PM_1617688361.png', '2021-04-06 01:52:41', 'admin'),
(7, 6, 4, '2021-03-01', '2022-02-28', 1, 'monthly', '2000.00', '25.00', '0.00', 'Graham Blandford', '4000.00', '30.00', 'etransfer', 1, 1, 1, 'AFGT18272', 'active', 'Test file_1616304984.docx', '2021-03-21 01:36:24', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `parent_notification_id` int(11) DEFAULT 0,
  `sender_user_id` varchar(50) NOT NULL,
  `recipient_user_id` varchar(50) NOT NULL,
  `details` varchar(1024) NOT NULL,
  `entity_type` varchar(20) DEFAULT NULL,
  `entity_type_id` int(11) DEFAULT NULL,
  `sent_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `notification_status` varchar(10) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `parent_notification_id`, `sender_user_id`, `recipient_user_id`, `details`, `entity_type`, `entity_type_id`, `sent_datetime`, `notification_status`) VALUES
(1, 0, 'landlord', 'tenant', 'I\'ll will be visiting the property on Tuesday to fix the gutter.', NULL, 0, '2021-03-13 08:14:44', 'read'),
(2, 0, 'landlord', 'tenant', 'Your cheque bounced for last month\'s rent.', NULL, 0, '2021-03-13 08:14:44', 'read'),
(3, 0, 'tenant', 'landlord', 'I have a mouse in the house', NULL, 0, '2021-03-13 08:14:44', 'read'),
(4, 0, 'landlord', 'tenant', 'Please check new event: notification test', 'requests', 0, '2021-03-13 21:35:07', 'read'),
(5, 0, 'tenant', 'landlord', 'Please check new Appointment: notification test on demo server', 'requests', 0, '2021-03-13 22:48:00', 'read'),
(6, 0, 'landlord', 'tenant', 'Please check new event: Fire alarm Test', 'requests', 0, '2021-03-14 14:57:39', 'read'),
(7, 0, 'landlord', 'tenant', 'Please check new event: Fire alarm testing. on 2021-03-31 14:57:00', 'requests', 0, '2021-03-14 19:36:07', 'read'),
(8, 0, 'tenant', 'landlord', 'Please check new Email: Ice Problem', '', 0, '2021-03-14 21:25:30', 'unread'),
(9, 0, 'landlord', 'tenant', 'Please check new event: Smoke Detector Battery Replacement on 2021-05-06 21:49:00', 'requests', 0, '2021-03-14 21:50:24', 'read'),
(10, 0, 'tenant', 'landlord', 'Please check new Appointment: Couch being delivered. Require elevator use.', 'requests', 0, '2021-03-14 22:03:52', 'unread'),
(11, 0, 'tenant', 'landlord', 'Please check new Appointment: Couch being delivered. Require elevator use.', 'requests', 0, '2021-03-14 22:04:11', 'unread'),
(12, 0, 'tenant', 'landlord', 'Please check new Email: Mice', '', 0, '2021-03-14 22:05:04', 'unread'),
(13, 0, 'landlord', 'tenant', 'Request ID: 15, status is changed to inprogress', '', 0, '2021-03-14 22:52:07', 'read'),
(14, 0, 'landlord', 'tenant', 'Please check new event: Checking Smoke Detectors at property on 2021-05-01 16:44:00', 'requests', 0, '2021-03-15 16:44:42', 'read'),
(18, 0, 'landlord', 'tenant', 'Please check new event: Wardrobe being delivered to tenant, reserving service elevator. on 2021-05-04 17:15:00', 'requests', 0, '2021-03-15 17:15:49', 'read'),
(19, 0, 'landlord', 'tenant', 'Request ID: 5, status is changed to completed', '', 0, '2021-03-15 17:17:33', 'read'),
(20, 0, 'tenant', 'landlord', 'Please check new Appointment: Request extra parking space for the weekend', 'requests', 0, '2021-03-15 17:23:14', 'read'),
(21, 0, 'tenant', 'landlord', 'Please check new Email: I have a friend interested in renting the unit above', '', 0, '2021-03-15 17:24:15', 'read'),
(22, 0, 'landlord', 'tenant', 'Please check new event: Extra Parking Space Reserved on 2021-03-19 17:42:00', 'requests', 0, '2021-03-15 17:43:20', 'read'),
(23, 0, 'landlord', 'tenant', 'Request ID: 3, status is changed to completed', '', 0, '2021-03-15 17:44:33', 'read'),
(24, 0, 'tenant', 'landlord', 'Please check new Appointment: Can someone clean the windows, I can\'t outside.', 'requests', 0, '2021-03-15 17:48:16', 'read'),
(25, 0, 'tenant', 'landlord', 'Please check new Email: I have a friend interested in renting the unit above', '', 0, '2021-03-15 17:49:51', 'read'),
(26, 0, 'landlord', 'tenant', 'Request ID: 21, status is changed to received', '', 0, '2021-03-15 18:46:56', 'unread'),
(27, 0, 'landlord', 'tenant', 'Request ID: 21, status is changed to inprogress', '', 0, '2021-03-15 18:47:03', 'unread'),
(28, 0, 'landlord', 'tenant', 'Please check new event: windows will be cleaned on 2021-04-01 18:47:00', 'requests', 0, '2021-03-15 18:47:30', 'unread'),
(29, 0, 'tenant', 'landlord', 'Please check new Request: file upload test', 'requests', 0, '2021-03-21 19:30:14', 'unread'),
(30, 0, 'tenant', 'landlord', 'Please check new Request: file upload test', 'requests', 0, '2021-03-21 19:32:34', 'unread'),
(31, 0, 'tenant', 'landlord', 'Please check new Request: dog', 'requests', 0, '2021-04-05 18:30:43', 'unread'),
(32, 0, 'tenant', 'landlord', 'Please check new Request: upload test', 'requests', 26, '2021-04-05 22:55:26', 'unread'),
(33, 0, 'tenant', 'landlord', 'Please check new Request: File\'s\' upload test', 'requests', 27, '2021-04-06 01:19:13', 'unread'),
(34, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 10:45:18', 'unread'),
(35, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 10:48:00', 'unread'),
(36, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 10:49:06', 'unread'),
(37, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 10:57:53', 'read'),
(38, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 11:01:03', 'read'),
(39, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 12:19:15', 'read'),
(40, 0, 'tenant', 'landlord', 'Please check new Request: The hot water faucet is leaking', 'requests', 28, '2021-04-15 16:28:15', 'unread'),
(41, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 16:35:11', 'read'),
(42, 0, 'tenant', 'landlord', 'Please check new Request: leaky cold water faucet', 'requests', 29, '2021-04-15 19:31:06', 'unread'),
(43, 0, 'landlord', 'tenant', 'Rent due on 2021-05-01', ' ', 0, '2021-04-15 19:37:42', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `rental_properties`
--

DROP TABLE IF EXISTS `rental_properties`;
CREATE TABLE `rental_properties` (
  `rental_property_id` int(11) NOT NULL,
  `listing_reference` varchar(20) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `province_code` varchar(2) NOT NULL DEFAULT 'ON',
  `postal_code` varchar(10) NOT NULL,
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `number_bedrooms` int(11) NOT NULL,
  `property_type_code` varchar(20) NOT NULL,
  `parking_space_type_code` varchar(20) NOT NULL,
  `number_parking_spaces` int(11) NOT NULL DEFAULT 0,
  `rental_duration_code` varchar(20) NOT NULL DEFAULT 'monthly',
  `smoking_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `insurance_required` tinyint(1) NOT NULL DEFAULT 1,
  `status_code` varchar(20) NOT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rental_properties`
--

INSERT INTO `rental_properties` (`rental_property_id`, `listing_reference`, `address_1`, `address_2`, `city`, `province_code`, `postal_code`, `latitude`, `longitude`, `number_bedrooms`, `property_type_code`, `parking_space_type_code`, `number_parking_spaces`, `rental_duration_code`, `smoking_allowed`, `insurance_required`, `status_code`, `photo`, `last_updated`, `last_updated_user_id`) VALUES
(1, 'WILSON193MAIN', '193 Wilson Avenue', 'Main', 'London', 'ON', 'N6H 1X6', '42.98553', '-81.2602478', 2, 'houseshare', 'drive', 2, 'monthly', 0, 1, 'leased', 'room3_1617989910_1618529332.jpg', '2021-04-15 19:28:52', 'landlord'),
(2, 'WILSON193UPPER', '193 Wilson Avenue', 'Upper', 'London', 'ON', 'N6H 1X6', '42.98553', '-81.2602478', 2, 'houseshare', 'drive', 1, 'monthly', 0, 1, 'available', 'room1_1617989891.jpg', '2021-02-21 17:08:28', 'landlord'),
(3, 'MOORE24', '24 Moore Street', '', 'London', 'ON', 'N6H1X6', '42.9835', '-81.2509', 2, 'house', 'drive', 2, 'monthly', 1, 1, 'available', 'room2_1617989901.jpg', '2021-02-20 23:53:12', 'landlord'),
(4, 'ELIZABETH87LWR', '87 Elizabeth Street', 'Lower Unit', 'London', 'ON', 'N7H 1H5', '-41.8273', '0.738282', 2, 'houseshare', 'drive', 1, 'monthly', 0, 1, 'available', 'room3_1617989910.jpg', '2021-02-22 14:59:23', 'admin'),
(5, 'GRAND112MAIN', '112 Grand Avenue', 'Main Floor', 'London', 'ON', 'N6K 2N2', '-41.8373', '0.8393', 2, 'houseshare', 'drive', 1, 'monthly', 0, 1, 'available', 'room4_1617989917.jpg', '2021-02-22 15:23:49', 'admin'),
(6, 'JOHN12UPPER', '12 John Street', 'Upper Floor', 'London', 'ON', 'N9K1N2', '-43.9282', '1.6373', 2, 'houseshare', 'drive', 1, 'monthly', 0, 1, 'available', 'room5_1617989924.jpg', '2021-02-22 15:45:41', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `rental_property_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `request_type_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `priority_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_date_time` datetime DEFAULT NULL,
  `is_notification` tinyint(4) NOT NULL DEFAULT 0,
  `file` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `request_date`, `rental_property_id`, `tenant_id`, `request_type_code`, `description`, `status_code`, `priority_code`, `appointment_date_time`, `is_notification`, `file`, `last_updated`, `last_updated_user_id`) VALUES
(2, '2021-02-15 01:59:24', 1, 1, '58', 'I want to have a cat.', '63', '66', NULL, 0, NULL, '2021-03-07 13:50:50', 'landlord'),
(3, '2021-02-15 14:50:23', 1, 1, '56', 'The bathroom faucet is leaking', '63', '64', NULL, 0, NULL, '2021-03-15 17:44:33', 'landlord'),
(4, '2021-02-16 18:13:29', 1, 1, '59', 'asdfasdfasdf', '63', '65', NULL, 0, NULL, '2021-02-16 21:26:47', 'tenant'),
(5, '2021-02-21 02:51:47', 1, 1, '56', 'request new... ', '63', '65', NULL, 0, NULL, '2021-03-15 17:17:33', 'landlord'),
(6, '2021-02-22 15:57:21', 1, 1, '57', 'i need a new bedroom carpet because the old one is ripped', '63', '65', NULL, 0, NULL, '2021-03-14 22:14:57', 'landlord'),
(7, '2021-03-09 00:16:20', 1, 1, '69', 'Meeting with Manager', '63', '65', '2021-03-11 09:16:00', 0, NULL, '2021-03-09 00:17:19', 'landlord'),
(8, '2021-03-14 10:00:00', 1, NULL, '70', '[test] Fire Alarm', '63', '65', '2021-03-14 10:00:00', 1, NULL, '2021-03-13 17:52:46', 'landlord'),
(9, '2021-03-13 21:33:00', 1, NULL, '70', 'notification test', '63', '65', '2021-03-13 21:33:00', 1, NULL, '2021-03-13 21:35:07', 'landlord'),
(10, '2021-03-13 22:48:00', 1, 1, '69', 'notification test on demo server', '60', '65', '2021-03-30 22:47:00', 0, NULL, '2021-03-13 22:48:00', 'tenant'),
(11, '2021-03-25 14:57:00', 1, NULL, '70', 'Fire alarm Test', '63', '65', '2021-03-25 14:57:00', 1, NULL, '2021-03-14 14:57:39', 'landlord'),
(12, '2021-03-31 14:57:00', 1, NULL, '70', 'Fire alarm testing.', '63', '65', '2021-03-31 14:57:00', 1, NULL, '2021-03-14 19:36:07', 'landlord'),
(13, '2021-05-06 21:49:00', 1, NULL, '70', 'Smoke Detector Battery Replacement', '63', '65', '2021-05-06 21:49:00', 1, NULL, '2021-03-14 21:50:24', 'landlord'),
(14, '2021-03-14 22:03:52', 1, 1, '69', 'Couch being delivered. Require elevator use.', '60', '65', '2021-03-30 22:03:00', 0, NULL, '2021-03-14 22:03:52', 'tenant'),
(15, '2021-03-14 22:04:11', 1, 1, '69', 'Couch being delivered. Require elevator use.', '62', '65', '2021-03-30 22:03:00', 0, NULL, '2021-03-14 22:52:07', 'landlord'),
(16, '2021-05-01 16:44:00', 1, NULL, '70', 'Checking Smoke Detectors at property', '63', '65', '2021-05-01 16:44:00', 1, NULL, '2021-03-15 16:44:42', 'landlord'),
(17, '2021-03-15 16:49:32', 1, 1, '69', 'Would like elevator reserved as I have a wardrobe arriving.', '60', '65', '2021-05-31 16:49:00', 0, NULL, '2021-03-15 16:49:32', 'tenant'),
(18, '2021-05-04 17:15:00', 1, NULL, '70', 'Wardrobe being delivered to tenant, reserving service elevator.', '63', '65', '2021-05-04 17:15:00', 1, NULL, '2021-03-15 17:15:49', 'landlord'),
(19, '2021-03-15 17:23:14', 1, 1, '69', 'Request extra parking space for the weekend', '60', '65', '2021-03-27 17:22:00', 0, NULL, '2021-03-15 17:23:14', 'tenant'),
(20, '2021-03-19 17:42:00', 1, NULL, '70', 'Extra Parking Space Reserved', '63', '65', '2021-03-19 17:42:00', 1, NULL, '2021-03-15 17:43:20', 'landlord'),
(21, '2021-03-15 17:48:16', 1, 1, '69', 'Can someone clean the windows, I can\'t outside.', '62', '65', '2021-04-01 17:47:00', 0, NULL, '2021-03-15 18:47:03', 'landlord'),
(22, '2021-04-01 18:47:00', 1, NULL, '70', 'windows will be cleaned', '63', '65', '2021-04-01 18:47:00', 1, NULL, '2021-03-15 18:47:30', 'landlord'),
(23, '2021-03-21 19:30:14', 1, 1, '58', 'file upload test', '60', '65', NULL, 0, NULL, '2021-03-21 19:30:14', 'tenant'),
(24, '2021-03-21 19:32:34', 1, 1, '58', 'file upload test', '60', '65', NULL, 0, 'Test file_1616369554.docx', '2021-03-21 19:32:34', 'tenant'),
(25, '2021-04-05 18:30:43', 1, 1, '58', 'dog', '60', '65', NULL, 0, 'pexels-photo-1108099_1617661843.jpeg', '2021-04-05 18:30:43', 'tenant'),
(26, '2021-04-05 22:55:26', 1, 1, '59', 'upload test', '60', '65', NULL, 0, NULL, '2021-04-05 22:55:26', 'tenant'),
(27, '2021-04-06 01:19:13', 1, 1, '59', 'File\'s\' upload test', '60', '65', NULL, 0, NULL, '2021-04-06 01:19:13', 'tenant'),
(28, '2021-04-15 16:28:15', 1, 1, '56', 'The hot water faucet is leaking', '60', '65', NULL, 0, NULL, '2021-04-15 16:28:15', 'tenant'),
(29, '2021-04-15 19:31:06', 1, 1, '56', 'leaky cold water faucet', '60', '65', NULL, 0, NULL, '2021-04-15 19:31:06', 'tenant');

-- --------------------------------------------------------

--
-- Table structure for table `requests_detail`
--

DROP TABLE IF EXISTS `requests_detail`;
CREATE TABLE `requests_detail` (
  `request_detail_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `create_user_id` varchar(50) NOT NULL,
  `last_updated_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `requests_detail`
--

INSERT INTO `requests_detail` (`request_detail_id`, `request_id`, `description`, `create_date`, `create_user_id`, `last_updated_date`, `last_user_id`) VALUES
(7, 3, 'priority is changed to low', '2021-02-15 21:29:55', 'tenant', '2021-02-15 21:29:55', 'tenant'),
(8, 3, 'Task: Task History\r\n\r\n1.2.3.4.5.', '2021-02-16 00:16:08', 'tenant', '2021-02-16 00:16:08', 'tenant'),
(9, 4, 'Task: Testing something', '2021-02-16 18:33:48', 'tenant', '2021-02-16 18:33:48', 'tenant'),
(10, 4, 'Task: asdfasdf\r\nasdfasdfasdfasdf\r\nasdfasdfasdfadsfasdfasdf\r\nasdfasdfasdfasdfadsfasdfasdfasdfasdf\r\nasdfasdfasdfasdfasdfadsfasdfasdfadsfasdfasdfasdf', '2021-02-16 21:26:44', 'tenant', '2021-02-16 21:26:44', 'tenant'),
(11, 4, 'status is changed to completed', '2021-02-16 21:26:47', 'tenant', '2021-02-16 21:26:47', 'tenant'),
(12, 2, 'priority is changed to medium', '2021-02-19 22:02:07', 'tenant', '2021-02-19 22:02:07', 'tenant'),
(13, 2, 'priority is changed to low', '2021-02-19 22:02:09', 'tenant', '2021-02-19 22:02:09', 'tenant'),
(14, 5, 'priority is changed to low', '2021-02-21 02:52:03', 'landlord', '2021-02-21 02:52:03', 'landlord'),
(15, 5, 'Task: blah blah', '2021-02-21 02:52:16', 'tenant', '2021-02-21 02:52:16', 'tenant'),
(16, 5, 'status is changed to inprogress', '2021-02-21 02:52:24', 'landlord', '2021-02-21 02:52:24', 'landlord'),
(17, 5, 'Task: adsfads\r\nasdfasdf\r\nasdfasdfasdf\r\n\r\nasdfasdfasdfasdf', '2021-02-22 11:25:55', 'tenant', '2021-02-22 11:25:55', 'tenant'),
(18, 5, 'priority is changed to medium', '2021-02-22 11:26:17', 'landlord', '2021-02-22 11:26:17', 'landlord'),
(19, 5, 'Task: asdfadsfasdfasdfasdfasdf', '2021-02-22 12:08:25', 'tenant', '2021-02-22 12:08:25', 'tenant'),
(20, 2, 'priority is changed to high', '2021-02-22 15:53:03', 'admin', '2021-02-22 15:53:03', 'admin'),
(21, 2, 'status is changed to inprogress', '2021-02-22 15:53:08', 'admin', '2021-02-22 15:53:08', 'admin'),
(22, 2, 'Task: Reviewing your request for a cat', '2021-02-22 15:53:38', 'admin', '2021-02-22 15:53:38', 'admin'),
(23, 2, 'Task: what\'s happening? ', '2021-02-22 15:56:06', 'tenant', '2021-02-22 15:56:06', 'tenant'),
(24, 2, 'priority is changed to high', '2021-02-26 12:48:42', 'landlord', '2021-02-26 12:48:42', 'landlord'),
(25, 6, 'status is changed to inprogress', '2021-03-06 23:04:11', 'landlord', '2021-03-06 23:04:11', 'landlord'),
(26, 2, 'status is changed to completed', '2021-03-07 13:50:50', 'landlord', '2021-03-07 13:50:50', 'landlord'),
(27, 7, 'Task: Accepted', '2021-03-09 00:17:18', 'landlord', '2021-03-09 00:17:18', 'landlord'),
(28, 7, 'status is changed to completed', '2021-03-09 00:17:19', 'landlord', '2021-03-09 00:17:19', 'landlord'),
(29, 6, 'status is changed to completed', '2021-03-14 22:14:57', 'landlord', '2021-03-14 22:14:57', 'landlord'),
(30, 15, 'status is changed to inprogress', '2021-03-14 22:52:07', 'landlord', '2021-03-14 22:52:07', 'landlord'),
(31, 5, 'status is changed to completed', '2021-03-15 17:17:33', 'landlord', '2021-03-15 17:17:33', 'landlord'),
(32, 3, 'status is changed to completed', '2021-03-15 17:44:33', 'landlord', '2021-03-15 17:44:33', 'landlord'),
(33, 21, 'status is changed to received', '2021-03-15 18:46:56', 'landlord', '2021-03-15 18:46:56', 'landlord'),
(34, 21, 'status is changed to inprogress', '2021-03-15 18:47:03', 'landlord', '2021-03-15 18:47:03', 'landlord');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
CREATE TABLE `tenants` (
  `tenant_id` int(11) NOT NULL,
  `salutation_code` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address_1` varchar(50) NOT NULL,
  `address_2` varchar(50) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `province_code` varchar(2) NOT NULL DEFAULT 'ON',
  `postal_code` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `social_insurance_number` varchar(10) DEFAULT NULL,
  `contact_phone` tinyint(4) NOT NULL DEFAULT 1,
  `contact_sms` tinyint(4) NOT NULL DEFAULT 1,
  `contact_email` tinyint(4) NOT NULL DEFAULT 1,
  `status_code` varchar(10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenant_id`, `salutation_code`, `first_name`, `last_name`, `address_1`, `address_2`, `city`, `province_code`, `postal_code`, `phone`, `fax`, `email`, `date_of_birth`, `gender`, `social_insurance_number`, `contact_phone`, `contact_sms`, `contact_email`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 'mr', 'Taehyung', 'Kim', '250 Oakland Ave', '', 'London', 'ON', 'N5W 0C1', '2367777273', '', 'taehyungkim@outlook.com', '1984-08-26', 'male', '123456787', 0, 1, 1, 'active', '2021-03-15 16:37:31', 'tenant'),
(2, 'mr', 'Peter', 'Parker', '11827 Hamilton Road', '', 'London', 'ON', 'N8N 3M3', '2265262829', '', 'p.parker@spiderman.com', '2000-03-18', 'male', '716181819', 1, 1, 1, 'active', '2021-02-22 15:00:50', 'admin'),
(3, 'ms', 'Bianca', 'Jagger', '987 Dundas Street', '', 'London', 'ON', 'N3X1H2', '2266767888', '', 'jagger.bianca@myspace.com', '2000-05-12', 'female', '839383727', 1, 1, 1, 'active', '2021-02-22 15:26:20', 'admin'),
(4, 'ms', 'June', 'Brown', '965 King Street', '', 'London', 'ON', 'N6K1H2', '5198272829', '', 'brown.june@live.com', '2000-11-05', 'female', '928272827', 1, 1, 1, 'active', '2021-02-22 15:48:17', 'admin'),
(5, 'mr', 'Dennis', 'Henry', '189 Avacian Avenue', '', 'London', 'ON', 'N8H1X3', '5198982928', '', 'dhenry@myspace.com', '2001-11-01', 'male', '892762526', 1, 1, 1, 'active', '2021-03-15 15:51:31', 'landlord');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_payments`
--

DROP TABLE IF EXISTS `tenant_payments`;
CREATE TABLE `tenant_payments` (
  `tenant_payment_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `payment_type_code` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_due` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_coupon_code` varchar(20) DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `card_holder` varchar(50) DEFAULT NULL,
  `card_number` varchar(1024) DEFAULT NULL,
  `card_expiry` varchar(1024) DEFAULT NULL,
  `card_CVV` varchar(1024) DEFAULT NULL,
  `status_code` varchar(10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tenant_payments`
--

INSERT INTO `tenant_payments` (`tenant_payment_id`, `tenant_id`, `payment_type_code`, `description`, `payment_date`, `payment_due`, `discount_coupon_code`, `discount`, `payment_amount`, `card_holder`, `card_number`, `card_expiry`, `card_CVV`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 1, 'debitcredit', 'Rent Payment', '2021-04-14 09:31:21', '1000.00', '5', '50.00', '950.00', 'Taehyung Kim', '62d4dbfce475f55693860b9776e681ca', '646093bba3a18a2c2e7113b736066b66', '202cb962ac59075b964b07152d234b70', 'paid', '2021-04-14 09:31:21', 'tenant'),
(2, 1, 'debitcredit', 'Rent Payment', '2021-04-15 18:29:02', '1000.00', NULL, '0.00', '1000.00', 'Taehyung Kim', '48f5fdb455497ac7f4d447e9263597d3', '7600d9b54ac2ccc716432b48b253719c', 'c8ffe9a587b126f152ed3d89a146b445', 'paid', '2021-04-15 18:29:02', 'tenant'),
(3, 1, 'debitcredit', 'Rent Payment', '2021-04-15 18:29:50', '1000.00', '5', '50.00', '950.00', 'Taehyung Kim', 'c2c341b599dfbad9c2ea7d1853d0765c', '89c9901d51cec64f77a6b9550217535e', '202cb962ac59075b964b07152d234b70', 'paid', '2021-04-15 18:29:50', 'tenant'),
(4, 1, 'debitcredit', 'Rent Payment for May', '2021-04-15 19:34:53', '1000.00', NULL, '0.00', '1000.00', 'Taehyung Kim', 'c245d1a75ff403a4e2443dfff5e9b424', 'fd8723a5207807f3cb6f55dcf7160e43', '289dff07669d7a23de0ef88d2f7129e7', 'paid', '2021-04-15 19:34:53', 'tenant'),
(5, 1, 'paypal', 'Rent Payment', '2021-04-15 19:35:45', '1000.00', '5', '50.00', '950.00', '', 'd41d8cd98f00b204e9800998ecf8427e', 'd41d8cd98f00b204e9800998ecf8427e', 'd41d8cd98f00b204e9800998ecf8427e', 'paid', '2021-04-15 19:35:45', 'tenant');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_role_code` varchar(20) NOT NULL,
  `status_code` varchar(20) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `landlord_id` int(11) DEFAULT NULL,
  `last_login` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `email`, `user_role_code`, `status_code`, `tenant_id`, `landlord_id`, `last_login`, `last_updated`, `last_updated_user_id`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@localhost', 'admin', 'enabled', NULL, NULL, '2021-02-10 17:04:02', '2021-02-10 17:04:02', NULL),
('demouser', '91017d590a69dc49807671a51f10ab7f', 'demouser@demo.com', 'tenant', 'enabled', 5, NULL, '2021-03-15 15:58:19', '2021-03-15 17:01:14', 'landlord'),
('demouser2', 'b528cf7c28e9b05eab8725df4b575c19', 'demouser2@demo.com', 'tenant', 'enabled', 5, NULL, '2021-03-15 16:40:08', '2021-03-15 16:40:08', 'landlord'),
('landlord', 'fcfabdb510e1a2b6c8663470694c3ca3', 'landlord@localhost', 'landlord', 'enabled', NULL, 1, '2021-02-10 17:04:02', '2021-02-10 17:04:02', NULL),
('tenant', 'adfb689897b2b5255adcaee72945c791', 'tenant@localhost', 'tenant', 'enabled', 1, NULL, '2021-02-10 17:04:02', '2021-03-15 16:36:47', 'tenant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`code_id`),
  ADD UNIQUE KEY `uk_codes_code_type_value` (`code_type`,`code_value`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`);

--
-- Indexes for table `landlords`
--
ALTER TABLE `landlords`
  ADD PRIMARY KEY (`landlord_id`);

--
-- Indexes for table `landlord_rental_properties`
--
ALTER TABLE `landlord_rental_properties`
  ADD PRIMARY KEY (`landlord_rental_property_id`),
  ADD KEY `fk_landlord_rental_properties_rental_properties` (`rental_property_id`),
  ADD KEY `fk_landlord_rental_properties_landlords` (`landlord_id`);

--
-- Indexes for table `leases`
--
ALTER TABLE `leases`
  ADD PRIMARY KEY (`lease_id`),
  ADD KEY `fk_leases_rental_properties` (`rental_property_id`),
  ADD KEY `fk_leases_tenants` (`tenant_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `rental_properties`
--
ALTER TABLE `rental_properties`
  ADD PRIMARY KEY (`rental_property_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_requests_rental_properties` (`rental_property_id`),
  ADD KEY `fk_requests_tenants` (`tenant_id`);

--
-- Indexes for table `requests_detail`
--
ALTER TABLE `requests_detail`
  ADD PRIMARY KEY (`request_detail_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`tenant_id`);

--
-- Indexes for table `tenant_payments`
--
ALTER TABLE `tenant_payments`
  ADD PRIMARY KEY (`tenant_payment_id`),
  ADD KEY `fk_tenant_payments_tenant` (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_users_tenants` (`tenant_id`),
  ADD KEY `fk_users_landlords` (`landlord_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `landlords`
--
ALTER TABLE `landlords`
  MODIFY `landlord_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `landlord_rental_properties`
--
ALTER TABLE `landlord_rental_properties`
  MODIFY `landlord_rental_property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leases`
--
ALTER TABLE `leases`
  MODIFY `lease_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `rental_properties`
--
ALTER TABLE `rental_properties`
  MODIFY `rental_property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `requests_detail`
--
ALTER TABLE `requests_detail`
  MODIFY `request_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tenant_payments`
--
ALTER TABLE `tenant_payments`
  MODIFY `tenant_payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `landlord_rental_properties`
--
ALTER TABLE `landlord_rental_properties`
  ADD CONSTRAINT `fk_landlord_rental_properties_landlords` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`),
  ADD CONSTRAINT `fk_landlord_rental_properties_rental_properties` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`);

--
-- Constraints for table `leases`
--
ALTER TABLE `leases`
  ADD CONSTRAINT `fk_leases_rental_properties` FOREIGN KEY (`rental_property_id`) REFERENCES `rental_properties` (`rental_property_id`),
  ADD CONSTRAINT `fk_leases_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);

--
-- Constraints for table `tenant_payments`
--
ALTER TABLE `tenant_payments`
  ADD CONSTRAINT `fk_tenant_payments_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_landlords` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`),
  ADD CONSTRAINT `fk_users_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);
COMMIT;
