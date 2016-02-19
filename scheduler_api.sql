-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 19, 2016 at 02:43 AM
-- Server version: 5.6.27
-- PHP Version: 5.5.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduler_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `break` float NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `manager_id`, `employee_id`, `break`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 1, '2016-03-01 08:00:00', '2016-03-01 12:00:00', '2016-02-19 05:12:18', '2016-02-19 05:12:18'),
(2, 5, 2, 1, '2016-03-01 08:00:00', '2016-03-01 12:00:00', '2016-02-19 05:12:42', '2016-02-19 05:12:42'),
(3, 5, 2, 1, '2016-03-02 08:00:00', '2016-03-02 16:00:00', '2016-02-19 05:12:45', '2016-02-19 05:12:45'),
(4, 5, 2, 1, '2016-03-03 08:00:00', '2016-03-03 16:00:00', '2016-02-19 05:12:49', '2016-02-19 05:12:49'),
(5, 5, 2, 1, '2016-03-04 08:00:00', '2016-03-04 16:00:00', '2016-02-19 05:12:53', '2016-02-19 05:12:53'),
(6, 5, 2, 1, '2016-03-05 08:00:00', '2016-03-05 16:00:00', '2016-02-19 05:12:57', '2016-02-19 05:12:57'),
(7, 5, 1, 0, '2016-03-03 08:00:00', '2016-03-03 12:00:00', '2016-02-19 05:09:11', '2016-02-19 05:09:11'),
(8, 5, 1, 0, '2016-03-05 12:00:00', '2016-03-05 16:00:00', '2016-02-19 05:09:11', '2016-02-19 05:09:11'),
(9, 6, 3, 1, '2016-03-01 12:00:00', '2016-03-01 20:00:00', '2016-02-19 04:14:08', '0000-00-00 00:00:00'),
(10, 6, 3, 1, '2016-03-02 12:00:00', '2016-03-02 20:00:00', '2016-02-19 04:14:47', '0000-00-00 00:00:00'),
(11, 6, 3, 1, '2016-03-03 12:00:00', '2016-03-03 20:00:00', '2016-02-19 04:14:53', '0000-00-00 00:00:00'),
(12, 6, 3, 1, '2016-03-04 12:00:00', '2016-03-04 20:00:00', '2016-02-19 05:18:53', '2016-02-19 05:18:53'),
(13, 6, 3, 1, '2016-03-05 12:00:00', '2016-03-05 16:00:00', '2016-02-19 05:13:30', '2016-02-19 05:13:30'),
(15, 6, 4, 0, '2016-03-01 12:00:00', '2016-03-01 16:00:00', '2016-02-19 05:09:11', '2016-02-19 05:09:11'),
(16, 6, 4, 0, '2016-03-03 16:00:00', '2016-03-03 20:00:00', '2016-02-19 05:09:11', '2016-02-19 05:09:11'),
(17, 6, 4, 0, '2016-03-05 12:00:00', '2016-03-05 16:00:00', '2016-02-19 05:09:11', '2016-02-19 05:09:11'),
(20, 5, 1, 1.5, '2016-03-06 08:00:00', '2016-03-06 16:00:00', '2016-02-19 05:18:09', '2016-02-19 05:18:09'),
(21, 5, 1, 0, '2016-03-08 08:00:00', '2016-03-08 16:00:00', '2016-02-19 07:10:44', '2016-02-19 07:10:44'),
(22, 5, 1, 1, '2016-03-16 08:00:00', '2016-03-16 16:00:00', '2016-02-19 07:11:22', '2016-02-19 07:11:22'),
(23, 5, 1, 1, '2016-03-24 08:00:00', '2016-03-24 16:00:00', '2016-02-19 07:11:42', '2016-02-19 07:11:42'),
(24, 5, 2, 1, '2016-03-24 12:00:00', '2016-03-24 16:00:00', '2016-02-19 07:11:59', '2016-02-19 07:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `role` enum('employee','manager') NOT NULL,
  `email` tinytext,
  `phone` tinytext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Paul McCartney', 'employee', 'paul@beatles.com', '555-555-5551', '2016-02-18 23:04:22', '0000-00-00 00:00:00'),
(2, 'Ringo Starr', 'employee', NULL, '555-555-5552', '2016-02-18 23:04:47', '0000-00-00 00:00:00'),
(3, 'John Lennon', 'employee', 'john@beatles.com', NULL, '2016-02-18 23:05:16', '0000-00-00 00:00:00'),
(4, 'George Harrison', 'employee', 'george@beatles.com', '555-555-5555', '2016-02-18 23:05:56', '0000-00-00 00:00:00'),
(5, 'Darth Vader', 'manager', 'kittens@rainbow.net', NULL, '2016-02-18 23:07:18', '0000-00-00 00:00:00'),
(6, 'Obi-wan Kenobi', 'manager', 'queenbey@gmail.com', NULL, '2016-02-18 23:08:22', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
