-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2026 at 11:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecocare`
--

CREATE DATABASE IF NOT EXISTS `ecocare` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ecocare`;

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `zone_label` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_schedules`
--

CREATE TABLE `collection_schedules` (
  `schedule_id` int(11) NOT NULL,
  `collector_id` int(11) DEFAULT NULL,
  `area_purok` varchar(120) NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `waste_type` varchar(80) NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `point_transactions`
--

CREATE TABLE `point_transactions` (
  `tx_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `points_delta` int(11) NOT NULL,
  `remarks` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `reward_id` int(11) NOT NULL,
  `reward_name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `points_required` int(11) NOT NULL,
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_claims`
--

CREATE TABLE `reward_claims` (
  `claim_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `points_spent` int(11) NOT NULL,
  `status` enum('pending','claimed','cancelled') NOT NULL DEFAULT 'pending',
  `claimed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(200) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` text NOT NULL,
  `lname` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `provider` varchar(20) NOT NULL DEFAULT 'local',
  `provider_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `fname`, `email`, `phone`, `address`, `password`, `role`, `lname`, `status`, `provider`, `provider_id`) VALUES
(1001, 'Jeremy', 'rabanesjeremy@gmail.com', '09636939205', 'Bayong', '$2y$10$Ampx82aHdJwqsUD423Uoaue0IbfO/IOOd8SNugYopHF1Thzk.vCtm', 'Admin', 'Rabanes', 'Active', 'google', '110445179267481487338'),
(1002, 'Brendon', 'brendon@gmail.com', '09645312354', 'Lapu Lapu', '$2y$10$6upqqRwwlicj8/0/Fb4y9u0HhISSqQtMH99JnJ8ZAavMNFJY2v496', 'Resident', 'Benitez', 'Active', 'local', NULL),
(1003, 'test', 'test@gmail.com', '09645312354', 'tetes', '$2y$10$eJKnVjoE59E9IRUWnBBDKOBmwmjoVMbnhuICFm1CwLgpQ060oscGK', 'Collector', 'Admin', 'Active', 'local', NULL),
(1004, 'kim', 'kim@gmail.com', '09645133313', 'Bayong', '$2y$10$1uQvlCiO7bkaQyRHMRU3M.Fwp7SSQhOd1kJqL8ziI8QqomKReSrK.', 'Official', 'herbias', 'active', 'local', NULL),
(1007, 'ella', 'ella@gmail.com', '09645133313', 'Purok 6', '$2y$10$POl2EN.KnvCp7QVwH2br8uGKfO/xahtA.ed/MpO7CUXEp966lV5.K', 'Pending', 'danica', 'active', 'local', NULL),
(1009, 'layla', 'layla@gmail.com', '09645133313', 'Purok 2', '$2y$10$.AZF9Z0f8lf9VvnF6yRsteVxan.q8S/lW/hX3bcFsaGDUSbhhe4Ie', 'Resident', 'layla', 'active', 'local', NULL),
(1010, 'kimberly', 'kimberlyanneherbias08@gmail.com', NULL, NULL, NULL, 'Resident', 'herbias', 'active', 'google', '103491089276363176921'),
(1011, 'Jeremy', 'rabanez.jerzmy@yahoo.com', NULL, NULL, NULL, 'Resident', 'Rabanes', 'Active', 'facebook', '3613637492130559');

-- --------------------------------------------------------

--
-- Table structure for table `waste_reports`
--

CREATE TABLE `waste_reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `location_details` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','resolved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`);

--
-- Indexes for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `fk_collection_schedules_collector` (`collector_id`);

--
-- Indexes for table `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD PRIMARY KEY (`tx_id`),
  ADD KEY `fk_point_tx_user` (`user_id`),
  ADD KEY `fk_point_tx_report` (`report_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`reward_id`);

--
-- Indexes for table `reward_claims`
--
ALTER TABLE `reward_claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `fk_reward_claims_user` (`user_id`),
  ADD KEY `fk_reward_claims_reward` (`reward_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- Indexes for table `waste_reports`
--
ALTER TABLE `waste_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_waste_reports_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_transactions`
--
ALTER TABLE `point_transactions`
  MODIFY `tx_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_claims`
--
ALTER TABLE `reward_claims`
  MODIFY `claim_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT for table `waste_reports`
--
ALTER TABLE `waste_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  ADD CONSTRAINT `fk_collection_schedules_collector` FOREIGN KEY (`collector_id`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD CONSTRAINT `fk_point_tx_report` FOREIGN KEY (`report_id`) REFERENCES `waste_reports` (`report_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_point_tx_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reward_claims`
--
ALTER TABLE `reward_claims`
  ADD CONSTRAINT `fk_reward_claims_reward` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`reward_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reward_claims_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `waste_reports`
--
ALTER TABLE `waste_reports`
  ADD CONSTRAINT `fk_waste_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Segregation observations are created after users has its primary key.
CREATE TABLE `segregation_observations` (
  `observation_id` int(11) NOT NULL AUTO_INCREMENT,
  `collector_id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `area_purok` varchar(120) NOT NULL,
  `location_details` varchar(255) NOT NULL,
  `proper_bins` int(11) NOT NULL DEFAULT 0,
  `total_bins` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`observation_id`),
  KEY `fk_observation_collector` (`collector_id`),
  KEY `fk_observation_resident` (`resident_id`),
  CONSTRAINT `fk_observation_collector` FOREIGN KEY (`collector_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_observation_resident` FOREIGN KEY (`resident_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Starter catalog rows are inserted only when that reward name is absent.
INSERT INTO `rewards` (`reward_name`, `description`, `points_required`, `stock_qty`)
SELECT 'Grocery pack', 'A barangay grocery incentive pack.', 500, 25
WHERE NOT EXISTS (SELECT 1 FROM `rewards` WHERE `reward_name` = 'Grocery pack');
INSERT INTO `rewards` (`reward_name`, `description`, `points_required`, `stock_qty`)
SELECT 'Reusable eco bag', 'Reusable shopping bag.', 200, 50
WHERE NOT EXISTS (SELECT 1 FROM `rewards` WHERE `reward_name` = 'Reusable eco bag');
INSERT INTO `rewards` (`reward_name`, `description`, `points_required`, `stock_qty`)
SELECT 'Seedling kit', 'A seedling kit for home or community planting.', 150, 30
WHERE NOT EXISTS (SELECT 1 FROM `rewards` WHERE `reward_name` = 'Seedling kit');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
