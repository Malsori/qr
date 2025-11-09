-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 09, 2025 at 10:28 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u355644601_qroom`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `nr_people` int(11) DEFAULT 2,
  `checked_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `userID`, `hotel_name`, `check_in`, `check_out`, `price`, `nr_people`, `checked_in`) VALUES
(1, 2, 'Economy Stay Salzburg', '2025-11-09', '2025-11-14', 70.00, 1, 1),
(2, 2, 'Economy Stay Salzburg', '2025-11-09', '2025-11-14', 70.00, 1, 0),
(3, 2, 'Value Hotel Salzburg', '2025-11-09', '2025-11-14', 125.00, 1, 0),
(4, 2, 'Palace Salzburg', '2025-11-09', '2025-11-14', 320.00, 1, 0),
(5, 2, 'Midtown Vienna', '2025-11-09', '2025-11-14', 140.00, 1, 0),
(6, 2, 'Midtown Vienna', '2025-11-09', '2025-11-14', 140.00, 1, 1),
(7, 2, 'Grand Elite Vienna', '2025-11-09', '2025-11-14', 340.00, 1, 0),
(8, 2, 'Midtown Salzburg', '2025-11-09', '2025-11-14', 140.00, 1, 0),
(9, 2, 'Midtown Vienna', '2025-11-09', '2025-11-14', 140.00, 1, 0),
(10, 2, 'Le Value Paris', '2025-11-14', '2025-11-21', 145.00, 2, 1),
(11, 2, 'Le Value Paris', '2025-11-14', '2025-11-21', 145.00, 2, 0),
(12, 2, 'Midtown Vienna', '2025-11-14', '2025-11-21', 140.00, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `passport_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `surname`, `birthday`, `passport_number`) VALUES
(2, 'gacaferierza0@gmail.com', '$2y$10$Mjv6vfkEPpUB4dA279YGa.2nqUuwjslrMUtWh7p7vBdtolXBGvuX6', 'Erza', 'Gacaferi', '2007-03-20', 'P02346999');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
