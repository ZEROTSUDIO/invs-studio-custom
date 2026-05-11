-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2026 at 06:13 AM
-- Server version: 8.0.30
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `base_duration` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `base_duration`, `created_at`, `updated_at`) VALUES
(1, 'Kaos', 10, '2026-04-16 07:38:34', '2026-04-16 07:38:34'),
(2, 'Crop Top', 12, '2026-04-16 07:38:34', '2026-04-16 07:38:34'),
(3, 'Hoodie', 15, '2026-04-16 07:38:34', '2026-04-16 07:38:34'),
(4, 'Topi', 8, '2026-04-16 07:38:34', '2026-04-16 07:38:34'),
(5, 'Custom (model cutsom)', 20, '2026-04-16 07:38:34', '2026-04-16 07:38:34');

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
(22, 'Reza Kecap', '081274313', NULL, '2026-04-13 06:15:41'),
(23, 'Nigga Melon', '08213012', NULL, '2026-04-16 03:22:18'),
(24, 'Raja Solo', '081293813131', NULL, '2026-04-17 06:20:22'),
(25, 'skqjsl', '081282121', NULL, '2026-05-11 05:43:07');

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
(20260417000004);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `order_code` varchar(50) NOT NULL,
  `qty` int NOT NULL,
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

INSERT INTO `orders` (`id`, `customer_id`, `category_id`, `order_code`, `qty`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`, `updated_at`) VALUES
(152, 1, 1, 'ORD-SIM-001', 20, NULL, NULL, 'done', 200, '2026-05-10', '2026-04-17 01:00:00', '2026-04-20 06:19:16'),
(153, 2, 3, 'ORD-SIM-002', 10, NULL, NULL, 'done', 150, '2026-05-12', '2026-04-17 01:05:00', '2026-05-11 04:57:15'),
(155, 4, 2, 'ORD-SIM-004', 15, NULL, NULL, 'done', 180, '2026-05-15', '2026-04-17 01:15:00', '2026-05-11 04:57:15'),
(156, 5, 5, 'ORD-SIM-005', 5, NULL, NULL, 'done', 100, '2026-05-20', '2026-04-17 01:20:00', '2026-05-11 04:57:15'),
(157, 6, 1, 'ORD-SIM-006', 100, NULL, NULL, 'done', 1000, '2026-06-01', '2026-04-17 01:25:00', '2026-05-11 04:57:15'),
(158, 7, 3, 'ORD-SIM-007', 30, NULL, NULL, 'done', 450, '2026-05-25', '2026-04-17 01:30:00', '2026-05-11 04:57:15'),
(159, 21, 4, 'ORD-SIM-008', 25, NULL, NULL, 'done', 200, '2026-05-18', '2026-04-17 01:35:00', '2026-05-11 04:57:15'),
(160, 22, 2, 'ORD-SIM-009', 12, NULL, NULL, 'done', 144, '2026-05-22', '2026-04-17 01:40:00', '2026-05-11 04:57:15'),
(161, 1, 5, 'ORD-SIM-010', 40, NULL, NULL, 'done', 800, '2026-06-15', '2026-04-17 01:45:00', '2026-05-11 04:57:15'),
(162, 2, 1, 'ORD-SIM-011', 8, NULL, NULL, 'done', 80, '2026-05-05', '2026-04-17 01:50:00', '2026-05-11 04:57:15'),
(163, 3, 3, 'ORD-SIM-012', 4, NULL, NULL, 'done', 60, '2026-05-06', '2026-04-17 01:55:00', '2026-05-11 04:57:15'),
(164, 4, 1, 'ORD-SIM-013', 200, NULL, NULL, 'done', 2000, '2026-06-30', '2026-04-17 02:00:00', '2026-05-11 05:37:33'),
(165, 5, 4, 'ORD-SIM-014', 10, NULL, NULL, 'done', 80, '2026-05-11', '2026-04-17 02:05:00', '2026-05-11 04:57:15'),
(166, 6, 2, 'ORD-SIM-015', 6, NULL, NULL, 'done', 72, '2026-05-09', '2026-04-17 02:10:00', '2026-05-11 04:57:15'),
(167, 7, 3, 'ORD-SIM-016', 15, NULL, NULL, 'done', 225, '2026-05-28', '2026-04-17 02:15:00', '2026-05-11 04:57:15'),
(168, 21, 1, 'ORD-SIM-017', 25, NULL, NULL, 'done', 250, '2026-05-20', '2026-04-17 02:20:00', '2026-05-11 04:57:15'),
(169, 22, 5, 'ORD-SIM-018', 2, NULL, NULL, 'done', 40, '2026-05-07', '2026-04-17 02:25:00', '2026-04-17 03:20:42'),
(170, 1, 4, 'ORD-SIM-019', 100, NULL, NULL, 'done', 800, '2026-06-10', '2026-04-17 02:30:00', '2026-05-11 04:57:15'),
(171, 2, 1, 'ORD-SIM-020', 12, NULL, NULL, 'done', 120, '2026-05-14', '2026-04-17 02:35:00', '2026-05-11 04:57:15'),
(172, 23, 1, 'ORD-20260417-C8C13', 200, NULL, '', 'done', 2030, '2026-04-25', '2026-04-17 03:04:59', '2026-04-20 03:59:00'),
(173, 2, 4, 'ORD-20260417-2720F', 50, NULL, '', 'done', 430, '2026-04-25', '2026-04-17 03:05:16', '2026-04-20 01:24:11'),
(174, 1, 5, 'ORD-20260417-5C62A', 100, NULL, '', 'done', 2030, '2026-04-30', '2026-04-17 03:05:47', '2026-04-25 01:12:58'),
(175, 4, 3, 'ORD-20260417-CAD29', 20, NULL, '', 'done', 330, '2026-04-18', '2026-04-17 03:06:05', '2026-04-20 01:24:11'),
(176, 6, 2, 'ORD-20260417-C0199', 1, NULL, '', 'done', 42, '2026-04-17', '2026-04-17 03:22:56', '2026-04-17 03:46:10'),
(177, 4, 4, 'ORD-20260417-56F4B', 30, NULL, '', 'canceled', 270, '2026-04-18', '2026-04-17 03:46:50', '2026-04-17 03:48:10'),
(178, 4, 4, 'ORD-20260417-AF275', 25, NULL, '', 'done', 230, '2026-04-18', '2026-04-17 03:48:41', '2026-04-17 07:09:04'),
(179, 24, 1, 'ORD-20260417-514DA', 20, NULL, '', 'done', 230, '2026-05-02', '2026-04-17 06:20:24', '2026-05-11 04:57:15'),
(180, 7, 1, 'ORD-20260417-979B6', 2, NULL, '', 'done', 50, '2026-04-18', '2026-04-17 08:16:10', '2026-04-20 01:24:11'),
(181, 5, 4, 'ORD-20260417-67E8B', 11, NULL, '', 'done', 118, '2026-05-04', '2026-04-17 08:17:17', '2026-05-11 04:57:15'),
(182, 7, 2, 'ORD-20260420-48514', 3, NULL, '', 'done', 66, '2026-04-21', '2026-04-20 01:24:46', '2026-04-20 03:14:11'),
(183, 3, 2, 'ORD-20260420-AF41D', 5, NULL, '', 'done', 90, '2026-04-22', '2026-04-20 01:24:59', '2026-04-20 03:58:13'),
(184, 2, 1, 'ORD-20260420-DFE7E', 10, NULL, '', 'done', 130, '2026-04-24', '2026-04-20 01:25:21', '2026-04-22 03:03:44'),
(185, 22, 5, 'ORD-20260420-E2CAF', 1, NULL, '', 'canceled', 50, '2026-04-23', '2026-04-20 01:25:40', '2026-04-20 02:09:54'),
(186, 7, 3, 'ORD-20260422-606B8', 10, NULL, '', 'done', 180, '2026-04-25', '2026-04-22 03:04:01', '2026-04-25 01:12:58'),
(187, 7, 1, 'ORD-20260422-7E235', 1, NULL, '', 'done', 40, '2026-04-23', '2026-04-22 03:04:13', '2026-04-22 03:24:22'),
(188, 3, 2, 'ORD-20260422-F25AA', 1, NULL, '', 'done', 42, '2026-04-22', '2026-04-22 03:04:26', '2026-04-22 05:05:26'),
(189, 22, 4, 'ORD-20260422-28E39', 5, NULL, '', 'done', 70, '2026-04-23', '2026-04-22 03:04:42', '2026-04-22 05:05:26'),
(190, 23, 1, 'ORD-20260422-63D51', 10, NULL, '', 'done', 130, '2026-04-24', '2026-04-22 03:05:36', '2026-04-22 06:10:14'),
(191, 24, 3, 'ORD-20260424-AD22A', 20, NULL, '', 'done', 330, '2026-04-28', '2026-04-24 02:21:26', '2026-05-11 04:57:15'),
(192, 2, 3, 'ORD-20260511-7EC5F', 59, NULL, '', 'canceled', 915, '2026-05-15', '2026-05-11 05:34:52', '2026-05-11 05:44:06'),
(193, 6, 1, 'ORD-20260511-48D4A', 200, NULL, '', 'done', 2030, '2026-05-20', '2026-05-11 05:39:20', '2026-05-11 05:44:34'),
(194, 23, 4, 'ORD-20260511-3A2E7', 1, NULL, '', 'canceled', 38, '2026-05-11', '2026-05-11 05:39:37', '2026-05-11 05:44:22'),
(195, 25, 1, 'ORD-20260511-9BA04', 9, NULL, '', 'canceled', 120, '2026-05-11', '2026-05-11 05:43:13', '2026-05-11 05:44:14');

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
(287, 152, 'S', 10),
(288, 152, 'M', 10),
(289, 153, 'L', 10),
(291, 155, 'S', 7),
(292, 155, 'M', 8),
(293, 156, 'XL', 5),
(294, 157, 'L', 50),
(295, 157, 'XL', 50),
(296, 158, 'M', 15),
(297, 158, 'L', 15),
(298, 159, 'All Size', 25),
(299, 160, 'S', 6),
(300, 160, 'M', 6),
(301, 161, 'L', 20),
(302, 161, 'XL', 20),
(303, 162, 'M', 8),
(304, 163, 'XL', 4),
(305, 164, 'S', 50),
(306, 164, 'M', 100),
(307, 164, 'L', 50),
(308, 165, 'All Size', 10),
(309, 166, 'M', 6),
(310, 167, 'L', 15),
(311, 168, 'S', 25),
(312, 169, 'XXL', 2),
(313, 170, 'All Size', 100),
(314, 171, 'L', 12),
(315, 172, 'S', 200),
(316, 173, 'S', 50),
(317, 174, 'S', 100),
(318, 175, 'S', 20),
(319, 176, 'S', 1),
(321, 177, 'S', 30),
(322, 178, 'S', 25),
(323, 179, 'S', 10),
(324, 179, 'M', 10),
(325, 180, 'S', 2),
(326, 181, 'S', 11),
(327, 182, 'S', 3),
(328, 183, 'S', 5),
(329, 184, 'S', 10),
(330, 185, 'S', 1),
(331, 186, 'S', 10),
(332, 187, 'L', 1),
(333, 188, 'L', 1),
(334, 189, 'S', 5),
(335, 190, 'XL', 10),
(336, 191, 'XL', 4),
(337, 191, 'S', 3),
(338, 191, 'M', 13),
(339, 192, 'S', 59),
(340, 193, 'S', 200),
(341, 194, 'S', 1),
(342, 195, 'S', 9);

-- --------------------------------------------------------

--
-- Table structure for table `production_schedule`
--

CREATE TABLE `production_schedule` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `schedule_tier` varchar(50) NOT NULL DEFAULT 'normal',
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

INSERT INTO `production_schedule` (`id`, `order_id`, `schedule_tier`, `queue_position`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1807, 169, 'normal', 0, '2026-04-17 09:40:12', '2026-04-17 10:20:12', 'completed', '2026-04-17 02:40:12', '2026-04-17 03:20:42'),
(1865, 175, 'urgent', 0, '2026-04-17 10:20:12', '2026-04-17 15:50:12', 'completed', '2026-04-17 03:06:13', '2026-04-20 01:24:11'),
(1932, 176, 'quick_insert', 0, '2026-04-17 10:22:59', '2026-04-17 11:04:59', 'completed', '2026-04-17 03:22:59', '2026-04-17 03:46:10'),
(2220, 178, 'quick_insert', 0, '2026-04-17 10:48:45', '2026-04-17 14:38:45', 'completed', '2026-04-17 03:48:45', '2026-04-17 07:09:04'),
(2310, 173, 'urgent', 0, '2026-04-17 15:50:12', '2026-04-18 15:00:00', 'completed', '2026-04-17 07:28:48', '2026-04-20 01:24:11'),
(2377, 180, 'quick_insert', 0, '2026-04-17 15:16:14', '2026-04-17 16:06:14', 'completed', '2026-04-17 08:16:14', '2026-04-20 01:24:11'),
(2400, 172, 'urgent', 0, '2026-04-18 15:00:00', '2026-04-20 10:59:00', 'completed', '2026-04-17 08:17:20', '2026-04-20 03:59:00'),
(2449, 182, 'quick_insert', 0, '2026-04-20 09:00:00', '2026-04-20 10:06:00', 'completed', '2026-04-20 01:40:47', '2026-04-20 03:14:11'),
(2475, 183, 'quick_insert', 0, '2026-04-20 09:00:00', '2026-04-20 10:30:00', 'completed', '2026-04-20 01:41:00', '2026-04-20 03:58:13'),
(2759, 152, 'urgent', 0, '2026-04-20 09:09:35', '2026-04-20 12:29:35', 'completed', '2026-04-20 02:09:10', '2026-04-20 06:19:16'),
(2858, 184, 'urgent', 0, '2026-04-20 12:29:35', '2026-04-20 14:39:35', 'completed', '2026-04-20 03:59:05', '2026-04-22 03:03:44'),
(2859, 174, 'urgent', 0, '2026-04-20 14:39:35', '2026-04-24 16:29:00', 'completed', '2026-04-20 03:59:05', '2026-04-25 01:12:58'),
(2879, 187, 'quick_insert', 0, '2026-04-22 10:05:01', '2026-04-22 10:24:22', 'completed', '2026-04-22 03:05:01', '2026-04-22 03:24:22'),
(2902, 188, 'quick_insert', 0, '2026-04-22 10:05:39', '2026-04-22 10:47:39', 'completed', '2026-04-22 03:05:39', '2026-04-22 05:05:26'),
(2925, 189, 'quick_insert', 0, '2026-04-22 10:24:06', '2026-04-22 11:34:06', 'completed', '2026-04-22 03:24:06', '2026-04-22 05:05:26'),
(2947, 190, 'quick_insert', 0, '2026-04-22 10:24:22', '2026-04-22 12:34:22', 'completed', '2026-04-22 03:24:22', '2026-04-22 06:10:14'),
(2968, 186, 'urgent', 0, '2026-04-24 09:00:00', '2026-04-24 12:00:00', 'completed', '2026-04-22 03:24:27', '2026-04-25 01:12:58'),
(3026, 191, 'urgent', 0, '2026-04-24 16:29:00', '2026-04-25 13:59:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3027, 179, 'urgent', 0, '2026-04-25 13:59:00', '2026-04-27 09:49:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3028, 181, 'urgent', 0, '2026-04-27 09:49:00', '2026-04-27 11:47:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3029, 162, 'urgent', 0, '2026-04-27 11:47:00', '2026-04-27 13:07:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3030, 163, 'urgent', 0, '2026-04-27 13:07:00', '2026-04-27 14:07:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3031, 166, 'urgent', 0, '2026-04-27 14:07:00', '2026-04-27 15:19:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3032, 165, 'urgent', 0, '2026-04-27 15:19:00', '2026-04-27 16:39:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3033, 153, 'urgent', 0, '2026-04-27 16:39:00', '2026-04-28 11:09:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3034, 171, 'normal', 0, '2026-04-28 11:09:00', '2026-04-28 13:09:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3035, 155, 'normal', 0, '2026-04-28 13:09:00', '2026-04-28 16:09:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3036, 159, 'normal', 0, '2026-04-28 16:09:00', '2026-04-29 11:29:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3037, 156, 'normal', 0, '2026-04-29 11:29:00', '2026-04-29 13:09:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3038, 168, 'normal', 0, '2026-04-29 13:09:00', '2026-04-30 09:19:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3039, 160, 'normal', 0, '2026-04-30 09:19:00', '2026-04-30 11:43:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3040, 158, 'normal', 0, '2026-04-30 11:43:00', '2026-05-01 11:13:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3041, 167, 'normal', 0, '2026-05-01 11:13:00', '2026-05-01 14:58:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3042, 157, 'normal', 0, '2026-05-01 14:58:00', '2026-05-04 15:38:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3043, 170, 'normal', 0, '2026-05-04 15:38:00', '2026-05-06 12:58:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3044, 161, 'normal', 0, '2026-05-06 12:58:00', '2026-05-08 10:18:00', 'completed', '2026-04-24 02:21:32', '2026-05-11 04:57:15'),
(3045, 164, 'normal', 0, '2026-05-08 10:18:00', '2026-05-11 12:37:33', 'completed', '2026-04-24 02:21:32', '2026-05-11 05:37:33'),
(3056, 193, 'normal', 0, '2026-05-11 12:44:22', '2026-05-11 12:44:34', 'completed', '2026-05-11 05:44:22', '2026-05-11 05:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `label` varchar(100) NOT NULL,
  `description` text,
  `type` varchar(20) NOT NULL DEFAULT 'text',
  `group` varchar(50) NOT NULL DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`, `label`, `description`, `type`, `group`) VALUES
('business_hour_end', '17:00', 'Business End Time', 'When your production day ends (e.g., 17:00). Tasks exceeding this will roll over to the next business day.', 'time', 'business'),
('business_hour_start', '09:00', 'Business Start Time', 'When your production day begins (e.g., 08:30). Tasks will never be scheduled to start before this.', 'time', 'business'),
('quick_insert_deadline_days', '2', 'Quick-Insert Deadline Window', 'Only jobs due within this many days are eligible for the Quick-Insert priority bypass.', 'number', 'algorithm'),
('quick_insert_threshold', '120', 'Quick-Insert Threshold (Minutes)', 'Jobs shorter than this limit can \"cut in line\" and finish today if there is remaining time. 480 mins = 1 full shift.', 'number', 'algorithm'),
('urgency_slack_buffer', '0.15', 'Urgency Slack Buffer', 'The safety margin (decimal percentage) required before an order is considered \"Safe.\" For example, 0.25 means the system wants a 25% safety gap relative to the job duration.', 'number', 'algorithm');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`),
  ADD UNIQUE KEY `key` (`key`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT for table `production_schedule`
--
ALTER TABLE `production_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3057;

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
