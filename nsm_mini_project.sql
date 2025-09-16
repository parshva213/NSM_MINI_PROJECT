-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 02:21 PM
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
-- Database: `nsm_mini_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

CREATE TABLE `login_errors` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `captcha` varchar(6) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_errors`
--

INSERT INTO `login_errors` (`id`, `email`, `password`, `captcha`, `error_message`, `created_at`) VALUES
(1, '123@gmo.vpmn', 'e10adc3949ba59abbe56e057f20f883e', 'oWcarl', 'No account found with this email.', '2025-09-15 04:40:34'),
(2, 'moksh@456.com', '91ccfdbd7408c547554ef082406b94e3', 'fUk2Rn', '', '2025-09-15 04:44:06'),
(3, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'gGINPM', 'Captcha is incorrect', '2025-09-15 04:45:13'),
(4, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', '2A6Gmt', '', '2025-09-15 04:45:53'),
(5, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'gGINPM', 'Captcha is incorrect', '2025-09-15 04:45:57'),
(6, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'r5eoUx', '', '2025-09-15 04:50:32'),
(7, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'iGxntO', '', '2025-09-15 05:06:27'),
(8, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'IURfs1', 'Captcha is incorrect', '2025-09-15 05:30:29'),
(9, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'HXQkPE', '', '2025-09-15 05:30:46'),
(10, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'Ajiuih', '', '2025-09-15 05:42:22'),
(11, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'hZ6pmI', 'Captcha is incorrect', '2025-09-15 10:32:59'),
(12, 'moksh@456.com', '14e1b600b1fd579f47433b88e8d85291', 'PT5DR6', 'Incorrect password.', '2025-09-15 10:33:16'),
(13, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'c7O2zF', '', '2025-09-15 10:33:30'),
(14, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', 'fWqG5D', '', '2025-09-16 05:18:56'),
(15, 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', '6wAMj0', '', '2025-09-16 05:20:33');

-- --------------------------------------------------------

--
-- Table structure for table `register_errors`
--

CREATE TABLE `register_errors` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `confirm_password` varchar(255) DEFAULT NULL,
  `captcha` varchar(12) NOT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_errors`
--

INSERT INTO `register_errors` (`id`, `email`, `full_name`, `password`, `confirm_password`, `captcha`, `error_message`, `created_at`) VALUES
(1, 'moksh@456', 'Moksh Shah', '7e2ef895c6706d662e82ded0fd90d68f', '7e2ef895c6706d662e82ded0fd90d68f', 'HjcRoy', 'Please enter a valid email address,Password must be at least 6 characters long,Captcha is incorrect', '2025-09-15 04:42:18'),
(2, 'moksh@456', 'Moksh Shah', '91ccfdbd7408c547554ef082406b94e3', 'd41d8cd98f00b204e9800998ecf8427e', 'WIzWd8', 'Please enter a valid email address,Confirm Password is required,Captcha is incorrect', '2025-09-15 04:42:48'),
(3, 'moksh@456.com', 'Moksh Shah', '91ccfdbd7408c547554ef082406b94e3', '91ccfdbd7408c547554ef082406b94e3', 'mP2w1e', '', '2025-09-15 04:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`) VALUES
(1, 'Moksh Shah', 'moksh@456.com', 'e10adc3949ba59abbe56e057f20f883e', '2025-09-15 04:43:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_errors`
--
ALTER TABLE `login_errors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_errors`
--
ALTER TABLE `register_errors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_errors`
--
ALTER TABLE `login_errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `register_errors`
--
ALTER TABLE `register_errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
