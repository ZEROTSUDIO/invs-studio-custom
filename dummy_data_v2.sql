-- Dummy data v2 for simulating scheduling with categories
-- Based on the refactored schema (Migration_Refactor_categories)

SET AUTOCOMMIT = 0;
START TRANSACTION;

-- Categories context:
-- 1: Kaos (10)
-- 2: Crop Top (12)
-- 3: Hoodie (15)
-- 4: Topi (8)
-- 5: Custom (20)

-- Generating 20 orders

-- Order 1: Kaos
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (1, 1, 'ORD-SIM-001', 20, 'waiting', 200, '2026-05-10', '2026-04-17 08:00:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 10), (@order_id, 'M', 10);

-- Order 2: Hoodie
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (2, 3, 'ORD-SIM-002', 10, 'waiting', 150, '2026-05-12', '2026-04-17 08:05:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'L', 10);

-- Order 3: Topi
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (3, 4, 'ORD-SIM-003', 50, 'waiting', 400, '2026-05-08', '2026-04-17 08:10:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'All Size', 50);

-- Order 4: Crop Top
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (4, 2, 'ORD-SIM-004', 15, 'waiting', 180, '2026-05-15', '2026-04-17 08:15:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 7), (@order_id, 'M', 8);

-- Order 5: Custom
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (5, 5, 'ORD-SIM-005', 5, 'waiting', 100, '2026-05-20', '2026-04-17 08:20:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'XL', 5);

-- Order 6: Kaos
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (6, 1, 'ORD-SIM-006', 100, 'waiting', 1000, '2026-06-01', '2026-04-17 08:25:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'L', 50), (@order_id, 'XL', 50);

-- Order 7: Hoodie
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (7, 3, 'ORD-SIM-007', 30, 'waiting', 450, '2026-05-25', '2026-04-17 08:30:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'M', 15), (@order_id, 'L', 15);

-- Order 8: Topi
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (21, 4, 'ORD-SIM-008', 25, 'waiting', 200, '2026-05-18', '2026-04-17 08:35:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'All Size', 25);

-- Order 9: Crop Top
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (22, 2, 'ORD-SIM-009', 12, 'waiting', 144, '2026-05-22', '2026-04-17 08:40:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 6), (@order_id, 'M', 6);

-- Order 10: Custom
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (1, 5, 'ORD-SIM-010', 40, 'waiting', 800, '2026-06-15', '2026-04-17 08:45:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'L', 20), (@order_id, 'XL', 20);

-- Order 11: Kaos
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (2, 1, 'ORD-SIM-011', 8, 'waiting', 80, '2026-05-05', '2026-04-17 08:50:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'M', 8);

-- Order 12: Hoodie
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (3, 3, 'ORD-SIM-012', 4, 'waiting', 60, '2026-05-06', '2026-04-17 08:55:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'XL', 4);

-- Order 13: Kaos
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (4, 1, 'ORD-SIM-013', 200, 'waiting', 2000, '2026-06-30', '2026-04-17 09:00:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 50), (@order_id, 'M', 100), (@order_id, 'L', 50);

-- Order 14: Topi
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (5, 4, 'ORD-SIM-014', 10, 'waiting', 80, '2026-05-11', '2026-04-17 09:05:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'All Size', 10);

-- Order 15: Crop Top
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (6, 2, 'ORD-SIM-015', 6, 'waiting', 72, '2026-05-09', '2026-04-17 09:10:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'M', 6);

-- Order 16: Hoodie
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (7, 3, 'ORD-SIM-016', 15, 'waiting', 225, '2026-05-28', '2026-04-17 09:15:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'L', 15);

-- Order 17: Kaos
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (21, 1, 'ORD-SIM-017', 25, 'waiting', 250, '2026-05-20', '2026-04-17 09:20:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 25);

-- Order 18: Custom
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (22, 5, 'ORD-SIM-018', 2, 'waiting', 40, '2026-05-07', '2026-04-17 09:25:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'XXL', 2);

-- Order 19: Topi
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (1, 4, 'ORD-SIM-019', 100, 'waiting', 800, '2026-06-10', '2026-04-17 09:30:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'All Size', 100);

-- Order 20: Kaos
INSERT INTO `orders` (`customer_id`, `category_id`, `order_code`, `qty`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (2, 1, 'ORD-SIM-020', 12, 'waiting', 120, '2026-05-14', '2026-04-17 09:35:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'L', 12);

COMMIT;
SET AUTOCOMMIT = 1;
