-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 22, 2021 at 12:33 PM
-- Server version: 10.3.25-MariaDB-0ubuntu0.20.04.1
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
CREATE DATABASE IF NOT EXISTS `rental` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rental`;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `code_id` int(11) NOT NULL,
  `code_type` varchar(20) NOT NULL,
  `code_value` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `is_default` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `css_styling` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`code_id`, `code_type`, `code_value`, `description`, `is_default`, `sort_order`, `is_enabled`, `css_styling`) VALUES
(1, 'user_role', 'tenant', 'Tenant', 1, 0, 1, NULL),
(2, 'user_role', 'landlord', 'Landlord', 0, 0, 1, NULL),
(3, 'user_role', 'admin', 'Administrator', 0, 0, 1, NULL),
(4, 'user_status', 'enabled', 'Enabled', 0, 0, 1, NULL),
(5, 'user_status', 'disabled', 'Disabled', 0, 0, 1, NULL),
(6, 'user_status', 'pending', 'Pending', 1, 0, 1, NULL),
(7, 'salutation', 'mr', 'Mr.', 0, 0, 1, NULL),
(8, 'salutation', 'mrs', 'Mrs.', 0, 0, 1, NULL),
(9, 'salutation', 'ms', 'Ms.', 0, 0, 1, NULL),
(10, 'province', 'AB', 'Alberta', 0, 0, 1, NULL),
(11, 'province', 'BC', 'British Colombia', 0, 0, 1, NULL),
(12, 'province', 'MB', 'Manitoba', 0, 0, 1, NULL),
(13, 'province', 'NB', 'New Brunswick', 0, 0, 1, NULL),
(14, 'province', 'NL', 'Newfoundland & Labrador', 0, 0, 1, NULL),
(15, 'province', 'NS', 'Nova Scotia', 0, 0, 1, NULL),
(16, 'province', 'NT', 'Northwest Territories', 0, 0, 1, NULL),
(17, 'province', 'NU', 'Nunavut', 0, 0, 1, NULL),
(18, 'province', 'ON', 'Ontario', 1, 0, 1, NULL),
(19, 'province', 'PE', 'Prince Edward Island', 0, 0, 1, NULL),
(20, 'province', 'QB', 'Quebec', 0, 0, 1, NULL),
(21, 'province', 'SK', 'Saskatchewan', 0, 0, 1, NULL),
(22, 'province', 'YT', 'Yukon', 0, 0, 1, NULL),
(23, 'gender', 'male', 'Male', 1, 0, 1, NULL),
(24, 'gender', 'female', 'Female', 0, 0, 1, NULL),
(25, 'landlord_status', 'active', 'Active', 1, 0, 1, NULL),
(26, 'landlord_status', 'inactive', 'Inactive', 0, 0, 1, NULL),
(27, 'tenant_status', 'active', 'Active', 1, 0, 1, NULL),
(28, 'tenant_status', 'inactive', 'Inactive', 0, 0, 1, NULL),
(29, 'property_status', 'available', 'Available', 1, 0, 1, NULL),
(30, 'property_status', 'leased', 'Leased', 0, 0, 1, NULL),
(31, 'property_status', 'inactive', 'Inactive', 0, 0, 1, NULL),
(32, 'property_type', 'room', 'Room', 0, 0, 1, NULL),
(33, 'property_type', 'house', 'House', 0, 0, 1, NULL),
(34, 'property_type', 'houseshare', 'House - shared', 0, 0, 1, NULL),
(35, 'property_type', 'apartment', 'Apartment', 0, 0, 1, NULL),
(36, 'parking_space', 'none', 'No parking', 1, 0, 1, NULL),
(37, 'parking_space', 'drive', 'Driveway', 0, 0, 1, NULL),
(38, 'parking_space', 'garage', 'Garage', 0, 0, 1, NULL),
(39, 'parking_space', 'underground', 'Underground', 0, 0, 1, NULL),
(40, 'parking_space', 'street', 'Street', 0, 0, 1, NULL),
(41, 'parking_space', 'carport', 'Carport', 0, 0, 1, NULL),
(42, 'rental_duration', 'monthly', 'Monthly', 1, 0, 1, NULL),
(43, 'rental_duration', 'weekly', 'Weekly', 0, 0, 1, NULL),
(44, 'rental_duration', 'daily', 'Daily', 0, 0, 1, NULL),
(45, 'payment_frequency', 'monthly', 'Monthly', 1, 0, 1, NULL),
(46, 'payment_frequency', 'weekly', 'Weekly', 0, 0, 1, NULL),
(47, 'payment_frequency', 'biweekly', 'Bi-weekly', 0, 0, 1, NULL),
(48, 'payment_type', 'etransfer', 'E-transfer', 1, 0, 1, NULL),
(49, 'payment_type', 'cheque', 'Cheque', 0, 0, 1, NULL),
(50, 'payment_type', 'postdated', 'Post-dated Cheque', 0, 0, 1, NULL),
(51, 'payment_type', 'debitcredit', 'Debit/Credit', 0, 0, 1, NULL),
(52, 'payment_type', 'paypal', 'Paypal', 0, 0, 1, NULL),
(53, 'lease_status', 'active', 'Active', 1, 0, 1, NULL),
(54, 'lease_status', 'expired', 'Expired', 0, 0, 1, NULL),
(55, 'lease_status', 'cancelled', 'Cancelled', 0, 0, 1, NULL),
(56, 'request_type', 'repair', 'Repair', 1, 0, 1, NULL),
(57, 'request_type', 'replacement', 'Replacement', 0, 0, 1, NULL),
(58, 'request_type', 'complaint', 'Complaint', 0, 0, 1, NULL),
(59, 'request_type', 'other', 'Other', 0, 0, 1, NULL),
(60, 'request_status', 'new', 'New', 1, 0, 1, NULL),
(61, 'request_status', 'received', 'Received', 0, 1, 1, NULL),
(62, 'request_status', 'inprogress', 'In progress', 0, 2, 1, NULL),
(63, 'request_status', 'completed', 'Completed', 0, 3, 1, NULL),
(64, 'request_priority', 'low', 'Low', 1, 0, 1, NULL),
(65, 'request_priority', 'medium', 'Medium', 0, 1, 1, NULL),
(66, 'request_priority', 'high', 'High', 0, 2, 1, NULL),
(67, 'request_solution', 'repaired', 'Repaired', 1, 0, 1, NULL),
(68, 'request_solution', 'replaced', 'Replaced', 0, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `landlords`
--

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
(3, 'Alysha Kearns', 'ms', 'Alysha', 'Kearns', '72 Moore Street', NULL, 'London', 'ON', 'N1A 7B2', '2268272272', '', 'a.kearns@hotmail.com', '5193298373', 'active', '2021-02-10 23:17:18', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `landlord_rental_properties`
--

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
(3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `leases`
--

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
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leases`
--

INSERT INTO `leases` (`lease_id`, `rental_property_id`, `tenant_id`, `start_date`, `end_date`, `payment_day`, `payment_frequency_code`, `base_rent_amount`, `parking_amount`, `other_amount`, `payable_to`, `deposit_amount`, `key_deposit`, `payment_type_code`, `include_electricity`, `include_heat`, `include_water`, `insurancy_policy_number`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 1, 1, '2021-02-01', '2021-09-30', 1, 'monthly', '1000.00', '0.00', '0.00', '', '1000.00', '0.00', 'etransfer', 0, 0, 0, NULL, 'active', '2021-02-21 02:10:32', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `rental_properties`
--

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
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rental_properties`
--

INSERT INTO `rental_properties` (`rental_property_id`, `listing_reference`, `address_1`, `address_2`, `city`, `province_code`, `postal_code`, `latitude`, `longitude`, `number_bedrooms`, `property_type_code`, `parking_space_type_code`, `number_parking_spaces`, `rental_duration_code`, `smoking_allowed`, `insurance_required`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 'WILSON193MAIN', '193 Wilson Avenue', 'Main', 'London', 'ON', 'N6H 1X6', '42.98553', '-81.2602478', 2, 'houseshare', 'drive', 2, 'monthly', 0, 1, 'leased', '2021-02-16 12:12:49', 'admin'),
(2, 'WILSON193UPPER', '193 Wilson Avenue', 'Upper', 'London', 'ON', 'N6H 1X6', '42.98553', '-81.2602478', 2, 'houseshare', 'drive', 1, 'monthly', 0, 1, 'available', '2021-02-21 17:08:28', 'landlord'),
(3, 'MOORE24', '24 Moore Street', '', 'London', 'ON', 'N6H1X6', '', '', 2, 'house', 'drive', 2, 'monthly', 1, 1, 'available', '2021-02-20 23:53:12', 'landlord');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `rental_property_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `request_type_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `priority_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `request_date`, `rental_property_id`, `tenant_id`, `request_type_code`, `description`, `status_code`, `priority_code`, `last_updated`, `last_updated_user_id`) VALUES
(2, '2021-02-15 01:59:24', 1, 1, '58', 'I want to have a cat.', '61', '64', '2021-02-19 22:02:09', 'tenant'),
(3, '2021-02-15 14:50:23', 1, 1, '56', 'The bathroom faucet is leaking', '60', '64', '2021-02-15 21:29:55', 'tenant'),
(4, '2021-02-16 18:13:29', 1, 1, '59', 'asdfasdfasdf', '63', '65', '2021-02-16 21:26:47', 'tenant'),
(5, '2021-02-21 02:51:47', 1, 1, '56', 'request new... ', '62', '65', '2021-02-22 11:26:17', 'landlord');

-- --------------------------------------------------------

--
-- Table structure for table `requests_detail`
--

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
(19, 5, 'Task: asdfadsfasdfasdfasdfasdf', '2021-02-22 12:08:25', 'tenant', '2021-02-22 12:08:25', 'tenant');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

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
  `status_code` varchar(10) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `last_updated_user_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenant_id`, `salutation_code`, `first_name`, `last_name`, `address_1`, `address_2`, `city`, `province_code`, `postal_code`, `phone`, `fax`, `email`, `date_of_birth`, `gender`, `social_insurance_number`, `status_code`, `last_updated`, `last_updated_user_id`) VALUES
(1, 'mr', 'Taehyung', 'Kim', '250 Oakland Ave', '', 'London', 'ON', 'N5W 0C1', '2367777272', '', 'taehyungkim@outlook.com', '1984-08-26', 'male', '123456787', 'active', '2021-02-21 17:38:03', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
('admin', 'admin', 'admin@localhost', 'admin', 'enabled', NULL, NULL, '2021-02-10 17:04:02', '2021-02-10 17:04:02', NULL),
('landlord', 'landlord', 'landlord@localhost', 'landlord', 'enabled', NULL, 1, '2021-02-10 17:04:02', '2021-02-10 17:04:02', NULL),
('tenant', 'tenant', 'tenant@localhost', 'tenant', 'enabled', 1, NULL, '2021-02-10 17:04:02', '2021-02-10 17:04:02', NULL);

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
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `landlords`
--
ALTER TABLE `landlords`
  MODIFY `landlord_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `landlord_rental_properties`
--
ALTER TABLE `landlord_rental_properties`
  MODIFY `landlord_rental_property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leases`
--
ALTER TABLE `leases`
  MODIFY `lease_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_properties`
--
ALTER TABLE `rental_properties`
  MODIFY `rental_property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `requests_detail`
--
ALTER TABLE `requests_detail`
  MODIFY `request_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_landlords` FOREIGN KEY (`landlord_id`) REFERENCES `landlords` (`landlord_id`),
  ADD CONSTRAINT `fk_users_tenants` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
