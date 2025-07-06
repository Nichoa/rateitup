-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 07:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rateitup`
--

-- --------------------------------------------------------

--
-- Table structure for table `check_ins`
--

CREATE TABLE `check_ins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_in_time` time NOT NULL,
  `number_of_people` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `address`, `created_at`) VALUES
(1, 'Warung Padang Sederhana', 'Jl. Sudirman No. 123', '2025-07-02 13:04:30'),
(2, 'Bakso Pak Kumis', 'Jl. Ahmad Yani No. 45', '2025-07-02 13:04:30'),
(3, 'Ayam Geprek Bensu', 'Jl. Diponegoro No. 67', '2025-07-02 13:04:30'),
(4, 'Mie Ayam Pak Gendut', 'Jl. Gatot Subroto No. 89', '2025-07-02 13:04:30'),
(5, 'Sate Kambing Pak Kumis', 'Jl. Pahlawan No. 12', '2025-07-02 13:04:30'),
(6, 'Nasi padang Nganjuk', 'jl wilis no 64', '2025-07-03 05:21:53'),
(7, 'Warung Kopi dan Makan Pak Parjo', 'Jl.Sudirman no 64 Loceret Nganjuk', '2025-07-04 11:54:06'),
(8, 'Nasi Goreng Mas Mat', 'Jl.Anngrek no 55 ds.Winong Nganjuk', '2025-07-04 11:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `restaurant_id`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, 3, 5, 'enak banget\r\n', 'approved', '2025-07-02 13:32:34', '2025-07-02 13:33:53'),
(4, 5, 2, 5, 'enakkk bangett', 'approved', '2025-07-03 05:20:42', '2025-07-03 05:22:01'),
(9, 6, 1, 5, 'Tempat bersih , makanan enak', 'approved', '2025-07-04 11:59:56', '2025-07-04 12:20:44'),
(10, 7, 7, 5, 'makanan nya enak banget', 'approved', '2025-07-04 12:38:45', '2025-07-04 12:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin123', 'admin', '2025-07-02 13:04:30', '2025-07-06 12:02:27'),
(2, 'user1', 'user123', 'user', '2025-07-02 13:04:30', '2025-07-06 12:06:46'),
(3, 'user2', 'user123', 'user', '2025-07-02 13:04:30', '2025-07-04 14:24:34'),
(4, 'user', 'user123', 'user', '2025-07-02 13:32:09', NULL),
(5, 'demo', 'demo123', 'user', '2025-07-03 05:19:25', NULL),
(6, 'nicho', 'nicho123', 'user', '2025-07-04 11:59:24', '2025-07-05 17:44:18'),
(7, 'intan', 'intan123', 'user', '2025-07-04 12:38:15', '2025-07-04 19:38:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `check_ins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_ins_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
