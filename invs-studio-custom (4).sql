-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2026 at 08:01 AM
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
(7, 'Jahseh Onfroy', '0812213131', NULL, '2026-04-10 07:26:07'),
(21, 'Nanda Lemon', '08327623491', NULL, '2026-04-13 03:27:00'),
(22, 'Reza Kecap', '081274313', NULL, '2026-04-13 06:15:41');

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
(57, 1, 'ORD-001', 10, 'T-Shirt', 'design1.png', 'Normal order', 'done', 300, '2026-04-15', '2026-04-10 01:30:00', '2026-04-14 01:39:02'),
(58, 2, 'ORD-002', 5, 'Hoodie', 'design2.png', 'Medium job', 'done', 120, '2026-04-12', '2026-04-10 01:35:00', '2026-04-11 06:46:40'),
(59, 3, 'ORD-003', 8, 'T-Shirt', 'design3.png', 'Standard order', 'done', 180, '2026-04-12', '2026-04-10 01:40:00', '2026-04-11 08:18:01'),
(60, 4, 'ORD-004', 2, 'T-Shirt', 'design4.png', 'Quick small order', 'done', 60, '2026-04-10', '2026-04-10 03:00:00', '2026-04-11 03:23:02'),
(61, 3, 'ORD-005', 6, 'Hoodie', 'design5.png', 'Urgent deadline', 'done', 200, '2026-04-11', '2026-04-10 02:00:00', '2026-04-11 03:23:02'),
(62, 5, 'ORD-006', 15, 'Jacket', 'design6.png', 'Big urgent order', 'done', 400, '2026-04-11', '2026-04-10 02:30:00', '2026-04-13 01:43:55'),
(63, 4, 'ORD-007', 1, 'T-Shirt', 'design7.png', 'Late quick order', 'done', 45, '2026-04-10', '2026-04-10 07:00:00', '2026-04-11 03:23:02'),
(64, 1, 'ORD-008', 3, 'T-Shirt', 'design8.png', 'Small normal job', 'done', 90, '2026-04-13', '2026-04-10 01:50:00', '2026-04-11 06:24:12'),
(65, 2, 'ORD-20260410-D6965', 97, '10', NULL, '', 'scheduled', 990, '2026-04-30', '2026-04-10 08:11:44', '2026-04-10 08:12:08'),
(66, 6, 'ORD-20260411-A4E04', 1, '10', NULL, '', 'done', 30, '2026-04-12', '2026-04-11 04:16:26', '2026-04-11 04:50:42'),
(67, 2, 'ORD-20260411-E68DA', 1, '13', NULL, '', 'scheduled', 43, '2026-04-30', '2026-04-11 04:51:35', '2026-04-11 04:51:39'),
(68, 5, 'ORD-20260411-C3CC1', 15, '15', NULL, '', 'canceled', 255, '2026-04-20', '2026-04-11 06:24:38', '2026-04-13 06:18:44'),
(69, 6, 'ORD-20260411-9315A', 5, '8', NULL, '', 'done', 70, '2026-04-13', '2026-04-11 06:24:59', '2026-04-11 07:47:01'),
(75, 1, 'SJF-001', 1, '10', NULL, 'SJF Priority 1', 'scheduled', 40, '2026-05-11', '2026-04-11 06:49:52', '2026-04-14 01:35:58'),
(76, 2, 'SJF-002', 4, '10', NULL, 'SJF Priority 2', 'scheduled', 70, '2026-05-11', '2026-04-11 06:49:52', '2026-04-14 01:35:50'),
(77, 3, 'SJF-003', 10, '10', NULL, 'SJF Priority 3', 'scheduled', 130, '2026-05-11', '2026-04-11 06:49:52', '2026-04-14 01:35:43'),
(78, 4, 'SJF-004', 20, '10', NULL, 'SJF Priority 4', 'scheduled', 230, '2026-05-11', '2026-04-11 06:49:52', '2026-04-14 01:35:38'),
(79, 5, 'SJF-005', 40, '10', NULL, 'SJF Priority 5', 'scheduled', 430, '2026-05-11', '2026-04-11 06:49:52', '2026-04-14 01:35:32'),
(80, 6, 'SJF-006', 75, '10', NULL, 'SJF Priority 6', 'scheduled', 780, '2026-05-11', '2026-04-11 06:49:52', '2026-04-14 01:35:23'),
(81, 1, 'TEST-001', 5, 'T-Shirt', NULL, 'Urgent test order', 'done', 150, '2026-04-13', '2026-04-11 07:48:33', '2026-04-13 03:40:55'),
(82, 7, 'TEST-002', 50, '10', NULL, 'Bulk testing', 'scheduled', 530, '2026-04-25', '2026-04-11 07:48:34', '2026-04-14 01:35:10'),
(83, 3, 'TEST-003', 20, '10', NULL, 'Standard queue test', 'done', 230, '2026-04-18', '2026-04-11 07:48:34', '2026-04-14 06:08:24'),
(84, 5, 'TEST-004', 2, 'T-Shirt', NULL, 'Quickest job', 'done', 60, '2026-04-12', '2026-04-11 07:48:34', '2026-04-13 01:43:55'),
(85, 2, 'TEST-005', 40, '10', NULL, 'Deadline stress test', 'done', 430, '2026-04-16', '2026-04-11 07:48:34', '2026-04-13 04:14:40'),
(86, 6, 'ORD-20260413-EB5B9', 2, '15', NULL, '', 'in_progress', 60, '2026-04-21', '2026-04-13 02:06:14', '2026-04-14 07:38:17'),
(87, 2, 'ORD-20260413-D4351', 4, '13', NULL, '', 'done', 82, '2026-04-15', '2026-04-13 02:45:58', '2026-04-13 04:14:01'),
(88, 3, 'ORD-20260413-2F4AC', 9, '8', NULL, '', 'done', 102, '2026-04-14', '2026-04-13 03:26:38', '2026-04-13 06:04:37'),
(89, 21, 'ORD-20260413-E1381', 20, '15', NULL, '', 'scheduled', 330, '2026-05-04', '2026-04-13 03:27:31', '2026-04-13 03:27:38'),
(90, 22, 'ORD-20260413-7A70A', 50, '10', NULL, 'Jawa', 'scheduled', 1330, '2026-05-09', '2026-04-13 06:15:41', '2026-04-13 06:16:29'),
(91, 4, 'ORD-20260413-68C81', 1, '10', NULL, '', 'done', 40, '2026-04-13', '2026-04-13 07:41:08', '2026-04-14 01:13:27'),
(92, 21, 'ORD-20260414-3C8AA', 20, '10', NULL, '', 'in_progress', 230, '2026-04-19', '2026-04-14 01:40:12', '2026-04-14 06:08:23'),
(93, 7, 'ORD-20260414-49ECF', 400, '10', NULL, '', 'scheduled', 4030, '2026-09-30', '2026-04-14 03:20:47', '2026-04-14 06:12:03'),
(94, 1, 'ORD-20260414-EE073', 1, '10', NULL, '', 'done', 40, '2026-04-14', '2026-04-14 07:00:54', '2026-04-14 07:38:35'),
(95, 5, 'ORD-20260414-DB94C', 20, '15', NULL, '', 'scheduled', 330, '2026-05-09', '2026-04-14 07:01:23', '2026-04-14 07:01:27');

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
(99, 69, 'S', 2),
(100, 69, 'L', 3),
(115, 81, 'M', 2),
(116, 81, 'L', 3),
(121, 84, 'XL', 2),
(123, 86, 'S', 2),
(125, 88, 'S', 9),
(126, 89, 'XL', 20),
(127, 87, 'S', 4),
(128, 85, 'L', 40),
(129, 90, 'S', 50),
(130, 68, 'S', 15),
(131, 91, 'S', 1),
(134, 83, 'M', 10),
(135, 83, 'S', 10),
(136, 82, 'L', 20),
(137, 82, 'XL', 30),
(138, 80, 'S', 75),
(139, 79, 'L', 40),
(140, 78, 'M', 20),
(141, 77, 'XL', 10),
(142, 76, 'L', 4),
(143, 75, 'M', 1),
(144, 92, 'S', 20),
(145, 93, 'S', 100),
(146, 93, 'M', 100),
(147, 93, 'L', 100),
(148, 93, 'XL', 100),
(149, 94, 'S', 1),
(150, 95, 'S', 20);

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
(298, 62, 5, '2026-04-11 09:59:00', '2026-04-11 16:39:00', 'completed', '2026-04-10 08:12:08', '2026-04-13 01:43:55'),
(304, 64, 6, '2026-04-11 10:39:07', '2026-04-11 12:09:07', 'completed', '2026-04-11 03:39:07', '2026-04-11 06:24:12'),
(309, 66, 7, '2026-04-11 11:16:30', '2026-04-11 11:46:30', 'completed', '2026-04-11 04:16:30', '2026-04-11 04:50:42'),
(310, 58, 8, '2026-04-11 11:46:30', '2026-04-11 13:46:30', 'completed', '2026-04-11 04:16:30', '2026-04-11 06:46:40'),
(314, 59, 9, '2026-04-11 11:51:39', '2026-04-11 14:51:39', 'completed', '2026-04-11 04:51:39', '2026-04-11 08:18:01'),
(321, 69, 10, '2026-04-11 13:25:05', '2026-04-11 14:35:05', 'completed', '2026-04-11 06:25:05', '2026-04-11 07:47:01'),
(367, 84, 10, '2026-04-11 14:48:41', '2026-04-11 15:48:41', 'completed', '2026-04-11 07:48:41', '2026-04-13 01:43:55'),
(368, 81, 11, '2026-04-11 16:39:00', '2026-04-13 10:39:00', 'completed', '2026-04-11 07:48:41', '2026-04-13 03:40:55'),
(449, 87, 12, '2026-04-13 09:46:02', '2026-04-13 11:08:02', 'completed', '2026-04-13 02:46:02', '2026-04-13 04:14:01'),
(464, 88, 13, '2026-04-13 10:27:38', '2026-04-13 12:09:38', 'completed', '2026-04-13 03:27:38', '2026-04-13 06:04:37'),
(525, 57, 1, '2026-04-13 12:09:38', '2026-04-14 08:39:00', 'completed', '2026-04-13 03:29:30', '2026-04-14 01:39:02'),
(540, 85, 15, '2026-04-14 08:39:00', '2026-04-14 15:49:00', 'completed', '2026-04-13 04:14:05', '2026-04-13 04:14:40'),
(688, 91, 2, '2026-04-13 14:41:12', '2026-04-13 15:21:12', 'completed', '2026-04-13 07:41:12', '2026-04-14 01:13:27'),
(728, 83, 1, '2026-04-14 08:39:00', '2026-04-14 12:29:00', 'completed', '2026-04-14 01:16:09', '2026-04-14 06:08:24'),
(849, 92, 1, '2026-04-14 12:29:00', '2026-04-14 16:19:00', 'in_progress', '2026-04-14 01:40:15', '2026-04-14 06:12:03'),
(888, 94, 2, '2026-04-14 14:01:27', '2026-04-14 14:41:27', 'completed', '2026-04-14 07:01:27', '2026-04-14 07:38:35'),
(889, 86, 2, '2026-04-14 16:19:00', '2026-04-15 08:49:00', 'in_progress', '2026-04-14 07:01:27', '2026-04-14 07:38:37'),
(903, 82, 3, '2026-04-15 08:49:00', '2026-04-16 09:09:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(904, 65, 4, '2026-04-16 09:09:00', '2026-04-18 08:39:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(905, 67, 5, '2026-04-18 08:39:00', '2026-04-18 09:22:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(906, 89, 6, '2026-04-18 09:22:00', '2026-04-18 14:52:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(907, 75, 7, '2026-04-18 14:52:00', '2026-04-18 15:32:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(908, 76, 8, '2026-04-18 15:32:00', '2026-04-18 16:42:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(909, 77, 9, '2026-04-18 16:42:00', '2026-04-20 10:22:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(910, 78, 10, '2026-04-20 10:22:00', '2026-04-20 14:12:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(911, 95, 11, '2026-04-20 14:12:00', '2026-04-21 11:12:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(912, 79, 12, '2026-04-21 11:12:00', '2026-04-22 09:52:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(913, 80, 13, '2026-04-22 09:52:00', '2026-04-23 14:22:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(914, 90, 14, '2026-04-23 14:22:00', '2026-04-27 11:02:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37'),
(915, 93, 15, '2026-04-27 11:02:00', '2026-05-06 10:12:00', 'scheduled', '2026-04-14 07:38:37', '2026-04-14 07:38:37');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `production_schedule`
--
ALTER TABLE `production_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=916;

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
