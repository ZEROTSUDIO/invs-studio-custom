-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2026 at 06:34 AM
-- Server version: 8.0.43
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invs-studio-custom`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `notes`, `created_at`) VALUES
(1, 'rahmat', '083168136813', NULL, '2026-04-07 07:16:49'),
(2, 'Rusdi Ngawi', '0812281812', NULL, '2026-04-07 07:34:28'),
(3, 'Rio Jombang', '081371334', NULL, '2026-04-07 07:35:50'),
(4, 'Mursyid Mursalin', '087125313', NULL, '2026-04-07 07:37:02'),
(5, 'Kevin Lontong', '0318381', NULL, '2026-04-07 08:20:15'),
(6, 'Fuad', '089999999', NULL, '2026-04-08 06:52:16'),
(7, 'Jahseh Onfroy', '0812213131', NULL, '2026-04-10 07:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(20260406000001);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `qty` int NOT NULL,
  `product_type` varchar(50) DEFAULT NULL,
  `design_file` varchar(255) DEFAULT NULL,
  `notes` text,
  `status` enum('waiting','scheduled','in_progress','done','canceled','ordered') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'waiting',
  `est_duration` int NOT NULL,
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`, `updated_at`) VALUES
(57, 1, 'ORD-001', 10, 'T-Shirt', 'design1.png', 'Normal order', 'scheduled', 300, '2026-04-15', '2026-04-10 01:30:00', '2026-04-10 08:09:34'),
(58, 2, 'ORD-002', 5, 'Hoodie', 'design2.png', 'Medium job', 'in_progress', 120, '2026-04-12', '2026-04-10 01:35:00', '2026-04-11 04:50:42'),
(59, 3, 'ORD-003', 8, 'T-Shirt', 'design3.png', 'Standard order', 'in_progress', 180, '2026-04-12', '2026-04-10 01:40:00', '2026-04-11 04:51:39'),
(60, 4, 'ORD-004', 2, 'T-Shirt', 'design4.png', 'Quick small order', 'done', 60, '2026-04-10', '2026-04-10 03:00:00', '2026-04-11 03:23:02'),
(61, 3, 'ORD-005', 6, 'Hoodie', 'design5.png', 'Urgent deadline', 'done', 200, '2026-04-11', '2026-04-10 02:00:00', '2026-04-11 03:23:02'),
(62, 5, 'ORD-006', 15, 'Jacket', 'design6.png', 'Big urgent order', 'in_progress', 400, '2026-04-11', '2026-04-10 02:30:00', '2026-04-11 03:23:01'),
(63, 4, 'ORD-007', 1, 'T-Shirt', 'design7.png', 'Late quick order', 'done', 45, '2026-04-10', '2026-04-10 07:00:00', '2026-04-11 03:23:02'),
(64, 1, 'ORD-008', 3, 'T-Shirt', 'design8.png', 'Small normal job', 'done', 90, '2026-04-13', '2026-04-10 01:50:00', '2026-04-11 06:24:12'),
(65, 2, 'ORD-20260410-D6965', 97, '10', NULL, '', 'scheduled', 990, '2026-04-30', '2026-04-10 08:11:44', '2026-04-10 08:12:08'),
(66, 6, 'ORD-20260411-A4E04', 1, '10', NULL, '', 'done', 30, '2026-04-12', '2026-04-11 04:16:26', '2026-04-11 04:50:42'),
(67, 2, 'ORD-20260411-E68DA', 1, '13', NULL, '', 'scheduled', 43, '2026-04-30', '2026-04-11 04:51:35', '2026-04-11 04:51:39'),
(68, 5, 'ORD-20260411-C3CC1', 10, '15', NULL, '', 'scheduled', 180, '2026-04-20', '2026-04-11 06:24:38', '2026-04-11 06:25:05'),
(69, 6, 'ORD-20260411-9315A', 5, '8', NULL, '', 'in_progress', 70, '2026-04-13', '2026-04-11 06:24:59', '2026-04-11 06:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `size`, `qty`) VALUES
(92, 65, 'L', 30),
(93, 65, 'S', 40),
(94, 65, 'S', 20),
(95, 65, 'XL', 7),
(96, 66, 'S', 1),
(97, 67, 'S', 1),
(98, 68, 'S', 10),
(99, 69, 'S', 2),
(100, 69, 'L', 3);

-- --------------------------------------------------------

--
-- Table structure for table `production_schedule`
--

CREATE TABLE `production_schedule` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `queue_position` int NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed') DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `production_schedule`
--

INSERT INTO `production_schedule` (`id`, `order_id`, `queue_position`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(289, 63, 1, '2026-04-10 15:09:34', '2026-04-10 15:54:34', 'completed', '2026-04-10 08:09:34', '2026-04-11 03:23:02'),
(291, 61, 3, '2026-04-10 15:09:34', '2026-04-11 09:59:00', 'completed', '2026-04-10 08:09:34', '2026-04-11 03:23:02'),
(297, 60, 4, '2026-04-10 15:12:08', '2026-04-10 16:12:08', 'completed', '2026-04-10 08:12:08', '2026-04-11 03:23:02'),
(298, 62, 5, '2026-04-11 09:59:00', '2026-04-11 16:39:00', 'in_progress', '2026-04-10 08:12:08', '2026-04-11 03:23:02'),
(304, 64, 6, '2026-04-11 10:39:07', '2026-04-11 12:09:07', 'completed', '2026-04-11 03:39:07', '2026-04-11 06:24:12'),
(309, 66, 7, '2026-04-11 11:16:30', '2026-04-11 11:46:30', 'completed', '2026-04-11 04:16:30', '2026-04-11 04:50:42'),
(310, 58, 8, '2026-04-11 11:46:30', '2026-04-11 13:46:30', 'in_progress', '2026-04-11 04:16:30', '2026-04-11 04:50:42'),
(314, 59, 9, '2026-04-11 11:51:39', '2026-04-11 14:51:39', 'in_progress', '2026-04-11 04:51:39', '2026-04-11 04:51:39'),
(321, 69, 10, '2026-04-11 13:25:05', '2026-04-11 14:35:05', 'in_progress', '2026-04-11 06:25:05', '2026-04-11 06:25:05'),
(322, 57, 11, '2026-04-11 16:39:00', '2026-04-13 13:09:00', 'scheduled', '2026-04-11 06:25:05', '2026-04-11 06:25:05'),
(323, 67, 12, '2026-04-13 13:09:00', '2026-04-13 13:52:00', 'scheduled', '2026-04-11 06:25:05', '2026-04-11 06:25:05'),
(324, 68, 13, '2026-04-13 13:52:00', '2026-04-13 16:52:00', 'scheduled', '2026-04-11 06:25:05', '2026-04-11 06:25:05'),
(325, 65, 14, '2026-04-13 16:52:00', '2026-04-15 16:22:00', 'scheduled', '2026-04-11 06:25:05', '2026-04-11 06:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '25d55ad283aa400af464c76d713c07ad', '2026-04-03 03:19:00', '2026-04-03 03:19:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_customer` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`);

--
-- Indexes for table `production_schedule`
--
ALTER TABLE `production_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_schedule_order` (`order_id`);

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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `production_schedule`
--
ALTER TABLE `production_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_schedule`
--
ALTER TABLE `production_schedule`
  ADD CONSTRAINT `fk_schedule_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
