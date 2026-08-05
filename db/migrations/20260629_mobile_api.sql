-- KIU Store Mobile API v1
-- Jalankan sekali pada database aktif aplikasi.

CREATE TABLE IF NOT EXISTS `mobile_api_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `token_hash` CHAR(64) NOT NULL,
  `device_name` VARCHAR(100) DEFAULT NULL,
  `expires_at` DATETIME NOT NULL,
  `last_used_at` DATETIME DEFAULT NULL,
  `revoked_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mobile_api_token_hash` (`token_hash`),
  KEY `idx_mobile_api_token_user` (`user_id`),
  KEY `idx_mobile_api_token_expiry` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `mobile_cart_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `unit_type` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 = unit pertama, 2 = unit kedua',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mobile_cart_product_unit` (`user_id`, `product_id`, `unit_type`),
  KEY `idx_mobile_cart_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `mobile_shipping_quotes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `origin_id` INT UNSIGNED NOT NULL,
  `destination_id` INT UNSIGNED NOT NULL,
  `weight` INT UNSIGNED NOT NULL,
  `courier` VARCHAR(50) NOT NULL,
  `options_json` MEDIUMTEXT NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_mobile_quote_user` (`user_id`),
  KEY `idx_mobile_quote_expiry` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `mobile_account_deletions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `email_hash` CHAR(64) NOT NULL,
  `deleted_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_mobile_account_deletion_user` (`user_id`),
  KEY `idx_mobile_account_deletion_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Foreign key tidak dipaksakan agar aman pada dump lama project
-- yang memiliki perbedaan engine dan struktur historis.
