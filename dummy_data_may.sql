-- Dummy data for simulating scheduling in May 2026
-- Based on invs-studio-custom (4).sql schema

SET AUTOCOMMIT = 0;
START TRANSACTION;

-- Order 1
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (1, 'ORD-MAY-001', 10, 'T-Shirt', NULL, 'Simulation Data 1', 'ordered', 400, '2026-05-15', '2026-04-16 10:00:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 5), (@order_id, 'L', 5);

-- Order 2
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (2, 'ORD-MAY-002', 5, 'Hoodie', NULL, 'Simulation Data 2', 'ordered', 200, '2026-05-12', '2026-04-16 10:05:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'M', 5);

-- Order 3
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (3, 'ORD-MAY-003', 20, 'T-Shirt', NULL, 'Simulation Data 3', 'ordered', 800, '2026-05-20', '2026-04-16 10:10:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'XL', 10), (@order_id, 'L', 10);

-- Order 4
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (4, 'ORD-MAY-004', 2, 'Polo', NULL, 'Simulation Data 4', 'ordered', 100, '2026-05-10', '2026-04-16 10:15:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 2);

-- Order 5
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (5, 'ORD-MAY-005', 50, 'Jersey', NULL, 'Simulation Data 5', 'ordered', 1500, '2026-05-25', '2026-04-16 10:20:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'M', 25), (@order_id, 'L', 25);

-- Order 6
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (6, 'ORD-MAY-006', 15, 'T-Shirt', NULL, 'Simulation Data 6', 'ordered', 600, '2026-05-18', '2026-04-16 10:25:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 5), (@order_id, 'M', 5), (@order_id, 'L', 5);

-- Order 7
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (7, 'ORD-MAY-007', 8, 'Hoodie', NULL, 'Simulation Data 7', 'ordered', 320, '2026-05-14', '2026-04-16 10:30:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'XL', 8);

-- Order 8
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (21, 'ORD-MAY-008', 30, 'T-Shirt', NULL, 'Simulation Data 8', 'ordered', 1200, '2026-05-28', '2026-04-16 10:35:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'L', 15), (@order_id, 'XL', 15);

-- Order 9
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (22, 'ORD-MAY-009', 12, 'Jacket', NULL, 'Simulation Data 9', 'ordered', 480, '2026-05-16', '2026-04-16 10:40:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'XL', 12);

-- Order 10
INSERT INTO `orders` (`customer_id`, `order_code`, `qty`, `product_type`, `design_file`, `notes`, `status`, `est_duration`, `deadline`, `created_at`) 
VALUES (1, 'ORD-MAY-010', 4, 'T-Shirt', NULL, 'Simulation Data 10', 'ordered', 160, '2026-05-11', '2026-04-16 10:45:00');
SET @order_id = LAST_INSERT_ID();
INSERT INTO `order_items` (`order_id`, `size`, `qty`) VALUES (@order_id, 'S', 4);

COMMIT;
SET AUTOCOMMIT = 1;
