SET @now = NOW();

ALTER TABLE `zahir_stock_product_aliases` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

UPDATE `zahir_stock_product_aliases` za JOIN `products` p ON (LOWER(TRIM(REPLACE(p.`name`, '*', ''))) COLLATE utf8mb4_general_ci) = (LOWER(TRIM(REPLACE(za.`product_name`, '*', ''))) COLLATE utf8mb4_general_ci) SET za.`product_id` = p.`id`, za.`updated_at` = @now WHERE za.`product_id` IS NULL OR za.`product_id` <> p.`id`;

SELECT
  COUNT(*) AS total_alias,
  SUM(CASE WHEN `product_id` IS NOT NULL THEN 1 ELSE 0 END) AS resolved_alias,
  SUM(CASE WHEN `product_id` IS NULL THEN 1 ELSE 0 END) AS unresolved_alias
FROM `zahir_stock_product_aliases`;
