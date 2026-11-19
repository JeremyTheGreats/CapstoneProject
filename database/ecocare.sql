-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2026 at 05:34 PM
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
(1003, 'test', 'test@gmail.com', '09645312354', 'tetes', '$2y$10$eJKnVjoE59E9IRUWnBBDKOBmwmjoVMbnhuICFm1CwLgpQ060oscGK', 'Collector', 'Admin', 'active', 'local', NULL),
(1004, 'kim', 'kim@gmail.com', '09645133313', 'Bayong', '$2y$10$1uQvlCiO7bkaQyRHMRU3M.Fwp7SSQhOd1kJqL8ziI8QqomKReSrK.', 'Official', 'herbias', 'active', 'local', NULL),
(1007, 'ella', 'ella@gmail.com', '09645133313', 'Purok 6', '$2y$10$POl2EN.KnvCp7QVwH2br8uGKfO/xahtA.ed/MpO7CUXEp966lV5.K', 'Pending', 'danica', 'active', 'local', NULL),
(1009, 'layla', 'layla@gmail.com', '09645133313', 'Purok 2', '$2y$10$.AZF9Z0f8lf9VvnF6yRsteVxan.q8S/lW/hX3bcFsaGDUSbhhe4Ie', 'Resident', 'layla', 'active', 'local', NULL),
(1010, 'kimberly', 'kimberlyanneherbias08@gmail.com', NULL, NULL, NULL, 'Resident', 'herbias', 'active', 'google', '103491089276363176921'),
(1011, 'Jeremy', 'rabanez.jerzmy@yahoo.com', NULL, NULL, NULL, 'Resident', 'Rabanes', 'Inactive', 'facebook', '3613637492130559');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
