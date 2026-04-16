-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 16, 2026 at 06:11 AM
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
(66, 6, 'ORD-20260411-A4E04', 1, '10', NULL, '', 'done', 30, '2026-04-12', '2026-04-11 04:16:26', '2026-04-11 04:50:42'),
(68, 5, 'ORD-20260411-C3CC1', 15, '15', NULL, '', 'canceled', 255, '2026-04-20', '2026-04-11 06:24:38', '2026-04-13 06:18:44'),
(69, 6, 'ORD-20260411-9315A', 5, '8', NULL, '', 'done', 70, '2026-04-13', '2026-04-11 06:24:59', '2026-04-11 07:47:01'),
(81, 1, 'TEST-001', 5, 'T-Shirt', NULL, 'Urgent test order', 'done', 150, '2026-04-13', '2026-04-11 07:48:33', '2026-04-13 03:40:55'),
(82, 7, 'TEST-002', 50, '10', NULL, 'Bulk testing', 'done', 530, '2026-04-25', '2026-04-11 07:48:34', '2026-04-16 02:10:21'),
(83, 3, 'TEST-003', 20, '10', NULL, 'Standard queue test', 'done', 230, '2026-04-18', '2026-04-11 07:48:34', '2026-04-14 06:08:24'),
(84, 5, 'TEST-004', 2, 'T-Shirt', NULL, 'Quickest job', 'done', 60, '2026-04-12', '2026-04-11 07:48:34', '2026-04-13 01:43:55'),
(85, 2, 'TEST-005', 40, '10', NULL, 'Deadline stress test', 'done', 430, '2026-04-16', '2026-04-11 07:48:34', '2026-04-13 04:14:40'),
(86, 6, 'ORD-20260413-EB5B9', 2, '15', NULL, '', 'done', 60, '2026-04-21', '2026-04-13 02:06:14', '2026-04-16 01:33:53'),
(87, 2, 'ORD-20260413-D4351', 4, '13', NULL, '', 'done', 82, '2026-04-15', '2026-04-13 02:45:58', '2026-04-13 04:14:01'),
(88, 3, 'ORD-20260413-2F4AC', 9, '8', NULL, '', 'done', 102, '2026-04-14', '2026-04-13 03:26:38', '2026-04-13 06:04:37'),
(91, 4, 'ORD-20260413-68C81', 1, '10', NULL, '', 'done', 40, '2026-04-13', '2026-04-13 07:41:08', '2026-04-14 01:13:27'),
(92, 21, 'ORD-20260414-3C8AA', 20, '10', NULL, '', 'done', 230, '2026-04-19', '2026-04-14 01:40:12', '2026-04-16 01:33:53'),
(94, 1, 'ORD-20260414-EE073', 1, '10', NULL, '', 'done', 40, '2026-04-14', '2026-04-14 07:00:54', '2026-04-14 07:38:35'),
(110, 22, 'ORD-20260416-3067D', 1, '10', NULL, '', 'done', 40, '2026-04-16', '2026-04-16 02:41:21', '2026-04-16 03:30:59'),
(111, 1, 'ORD-MAY-001', 10, 'T-Shirt', NULL, 'Simulation Data 1', 'scheduled', 400, '2026-05-15', '2026-04-16 03:00:00', '2026-04-16 02:48:16'),
(112, 2, 'ORD-MAY-002', 6, '10', NULL, 'Simulation Data 2', 'scheduled', 90, '2026-05-12', '2026-04-16 03:05:00', '2026-04-16 05:08:11'),
(113, 3, 'ORD-MAY-003', 20, 'T-Shirt', NULL, 'Simulation Data 3', 'scheduled', 800, '2026-05-20', '2026-04-16 03:10:00', '2026-04-16 02:48:16'),
(114, 4, 'ORD-MAY-004', 2, '10', NULL, 'Simulation Data 4', 'done', 50, '2026-05-10', '2026-04-16 03:15:00', '2026-04-16 04:55:50'),
(115, 5, 'ORD-MAY-005', 50, '10', NULL, 'Simulation Data 5', 'scheduled', 530, '2026-05-25', '2026-04-16 03:20:00', '2026-04-16 03:52:14'),
(116, 6, 'ORD-MAY-006', 15, '10', NULL, 'Simulation Data 6', 'scheduled', 180, '2026-05-18', '2026-04-16 03:25:00', '2026-04-16 03:51:59'),
(117, 7, 'ORD-MAY-007', 8, '10', NULL, 'Simulation Data 7', 'scheduled', 110, '2026-05-14', '2026-04-16 03:30:00', '2026-04-16 03:51:55'),
(118, 21, 'ORD-MAY-008', 30, 'T-Shirt', NULL, 'Simulation Data 8', 'scheduled', 1200, '2026-05-28', '2026-04-16 03:35:00', '2026-04-16 02:47:26'),
(119, 22, 'ORD-MAY-009', 12, '10', NULL, 'Simulation Data 9', 'scheduled', 150, '2026-05-23', '2026-04-16 03:40:00', '2026-04-16 03:52:47'),
(120, 1, 'ORD-MAY-010', 4, '10', NULL, 'Simulation Data 10', 'done', 70, '2026-05-11', '2026-04-16 03:45:00', '2026-04-16 04:41:33'),
(121, 5, 'ORD-20260416-70A8B', 300, '8', NULL, '', 'scheduled', 2430, '2026-05-19', '2026-04-16 02:50:03', '2026-04-16 02:51:06'),
(122, 6, 'ORD-20260416-57667', 100, '15', NULL, '', 'scheduled', 1530, '2026-05-11', '2026-04-16 02:50:44', '2026-04-16 05:07:45'),
(123, 7, 'ORD-20260416-4C00C', 22, '10', NULL, '', 'in_progress', 250, '2026-05-08', '2026-04-16 03:20:44', '2026-04-16 04:55:50'),
(124, 23, 'ORD-20260416-22D17', 50, '13', NULL, '', 'scheduled', 680, '2026-05-11', '2026-04-16 03:22:19', '2026-04-16 03:22:30'),
(125, 3, 'ORD-20260416-9E164', 320, '10', NULL, '', 'scheduled', 3230, '2026-05-31', '2026-04-16 04:44:31', '2026-04-16 04:45:09'),
(126, 21, 'ORD-20260416-C7A80', 30, '10', NULL, '', 'scheduled', 330, '2026-05-13', '2026-04-16 04:45:05', '2026-04-16 04:45:09');

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
(96, 66, 'S', 1),
(99, 69, 'S', 2),
(100, 69, 'L', 3),
(115, 81, 'M', 2),
(116, 81, 'L', 3),
(121, 84, 'XL', 2),
(123, 86, 'S', 2),
(125, 88, 'S', 9),
(127, 87, 'S', 4),
(128, 85, 'L', 40),
(130, 68, 'S', 15),
(131, 91, 'S', 1),
(134, 83, 'M', 10),
(135, 83, 'S', 10),
(136, 82, 'L', 20),
(137, 82, 'XL', 30),
(144, 92, 'S', 20),
(149, 94, 'S', 1),
(170, 110, 'S', 1),
(171, 111, 'S', 5),
(172, 111, 'L', 5),
(174, 113, 'XL', 10),
(175, 113, 'L', 10),
(183, 118, 'L', 15),
(184, 118, 'XL', 15),
(187, 121, 'S', 300),
(192, 123, 'S', 22),
(193, 124, 'S', 50),
(212, 120, 'S', 4),
(220, 114, 'S', 2),
(221, 119, 'XL', 12),
(222, 125, 'S', 320),
(223, 126, 'S', 30),
(224, 116, 'S', 5),
(225, 116, 'M', 5),
(226, 116, 'L', 5),
(232, 117, 'XL', 8),
(237, 122, 'S', 20),
(238, 122, 'M', 30),
(239, 122, 'L', 40),
(240, 122, 'XL', 10),
(242, 112, 'M', 6),
(243, 115, 'M', 25),
(244, 115, 'L', 25);

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
(289, 63, 0, '2026-04-10 15:09:34', '2026-04-10 15:54:34', 'completed', '2026-04-10 08:09:34', '2026-04-16 03:38:03'),
(291, 61, 0, '2026-04-10 15:09:34', '2026-04-11 09:59:00', 'completed', '2026-04-10 08:09:34', '2026-04-16 03:38:03'),
(297, 60, 0, '2026-04-10 15:12:08', '2026-04-10 16:12:08', 'completed', '2026-04-10 08:12:08', '2026-04-16 03:38:03'),
(298, 62, 0, '2026-04-11 09:59:00', '2026-04-11 16:39:00', 'completed', '2026-04-10 08:12:08', '2026-04-16 03:38:03'),
(304, 64, 0, '2026-04-11 10:39:07', '2026-04-11 12:09:07', 'completed', '2026-04-11 03:39:07', '2026-04-16 03:38:03'),
(309, 66, 0, '2026-04-11 11:16:30', '2026-04-11 11:46:30', 'completed', '2026-04-11 04:16:30', '2026-04-16 03:38:03'),
(310, 58, 0, '2026-04-11 11:46:30', '2026-04-11 13:46:30', 'completed', '2026-04-11 04:16:30', '2026-04-16 03:38:03'),
(314, 59, 0, '2026-04-11 11:51:39', '2026-04-11 14:51:39', 'completed', '2026-04-11 04:51:39', '2026-04-16 03:38:03'),
(321, 69, 0, '2026-04-11 13:25:05', '2026-04-11 14:35:05', 'completed', '2026-04-11 06:25:05', '2026-04-16 03:38:03'),
(367, 84, 0, '2026-04-11 14:48:41', '2026-04-11 15:48:41', 'completed', '2026-04-11 07:48:41', '2026-04-16 03:38:03'),
(368, 81, 0, '2026-04-11 16:39:00', '2026-04-13 10:39:00', 'completed', '2026-04-11 07:48:41', '2026-04-16 03:38:03'),
(449, 87, 0, '2026-04-13 09:46:02', '2026-04-13 11:08:02', 'completed', '2026-04-13 02:46:02', '2026-04-16 03:38:03'),
(464, 88, 0, '2026-04-13 10:27:38', '2026-04-13 12:09:38', 'completed', '2026-04-13 03:27:38', '2026-04-16 03:38:03'),
(525, 57, 0, '2026-04-13 12:09:38', '2026-04-14 08:39:00', 'completed', '2026-04-13 03:29:30', '2026-04-16 03:38:03'),
(540, 85, 0, '2026-04-14 08:39:00', '2026-04-14 15:49:00', 'completed', '2026-04-13 04:14:05', '2026-04-16 03:38:03'),
(688, 91, 0, '2026-04-13 14:41:12', '2026-04-13 15:21:12', 'completed', '2026-04-13 07:41:12', '2026-04-16 03:38:03'),
(728, 83, 0, '2026-04-14 08:39:00', '2026-04-14 12:29:00', 'completed', '2026-04-14 01:16:09', '2026-04-16 03:38:03'),
(849, 92, 0, '2026-04-14 12:29:00', '2026-04-14 16:19:00', 'completed', '2026-04-14 01:40:15', '2026-04-16 03:38:03'),
(888, 94, 0, '2026-04-14 14:01:27', '2026-04-14 14:41:27', 'completed', '2026-04-14 07:01:27', '2026-04-16 03:38:03'),
(889, 86, 0, '2026-04-14 16:19:00', '2026-04-15 08:49:00', 'completed', '2026-04-14 07:01:27', '2026-04-16 03:38:03'),
(903, 82, 0, '2026-04-15 08:49:00', '2026-04-16 09:09:00', 'completed', '2026-04-14 07:38:37', '2026-04-16 03:38:03'),
(942, 110, 0, '2026-04-16 09:47:26', '2026-04-16 10:27:26', 'completed', '2026-04-16 02:47:26', '2026-04-16 03:38:03'),
(943, 120, 0, '2026-04-16 09:47:26', '2026-04-16 10:57:26', 'completed', '2026-04-16 02:47:26', '2026-04-16 04:41:33'),
(1283, 114, 0, '2026-04-16 10:57:26', '2026-04-16 11:47:26', 'completed', '2026-04-16 03:52:51', '2026-04-16 04:55:50'),
(1310, 123, 1, '2026-04-16 11:47:26', '2026-04-16 15:57:26', 'in_progress', '2026-04-16 04:45:28', '2026-04-16 04:56:03'),
(1506, 124, 2, '2026-04-16 15:57:26', '2026-04-18 10:17:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1507, 122, 3, '2026-04-18 10:17:00', '2026-04-22 10:17:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1508, 112, 4, '2026-04-22 10:17:00', '2026-04-22 11:47:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1509, 126, 5, '2026-04-22 11:47:00', '2026-04-23 08:47:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1510, 117, 6, '2026-04-23 08:47:00', '2026-04-23 10:37:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1511, 119, 7, '2026-04-23 10:37:00', '2026-04-23 13:07:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1512, 116, 8, '2026-04-23 13:07:00', '2026-04-23 16:07:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1513, 111, 9, '2026-04-23 16:07:00', '2026-04-24 14:17:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1514, 115, 10, '2026-04-24 14:17:00', '2026-04-25 14:37:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1515, 113, 11, '2026-04-25 14:37:00', '2026-04-28 10:57:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1516, 118, 12, '2026-04-28 10:57:00', '2026-04-30 13:57:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1517, 121, 13, '2026-04-30 13:57:00', '2026-05-06 11:57:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34'),
(1518, 125, 14, '2026-05-06 11:57:00', '2026-05-13 14:47:00', 'scheduled', '2026-04-16 06:10:34', '2026-04-16 06:10:34');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `production_schedule`
--
ALTER TABLE `production_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1519;

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
