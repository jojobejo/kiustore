CREATE TABLE IF NOT EXISTS `zahir_stock_import_batches` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_file_name` VARCHAR(255) NOT NULL,
  `stored_file_name` VARCHAR(255) NOT NULL,
  `raw_rows` INT(11) NOT NULL DEFAULT 0,
  `processed_rows` INT(11) NOT NULL DEFAULT 0,
  `matched_rows` INT(11) NOT NULL DEFAULT 0,
  `zahir_only_rows` INT(11) NOT NULL DEFAULT 0,
  `product_only_rows` INT(11) NOT NULL DEFAULT 0,
  `status` VARCHAR(32) NOT NULL DEFAULT 'IMPORTED',
  `imported_by` BIGINT(20) DEFAULT NULL,
  `imported_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_zahir_stock_import_batches_status` (`status`),
  KEY `idx_zahir_stock_import_batches_imported_at` (`imported_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zahir_stock_import_items` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_id` BIGINT(20) UNSIGNED NOT NULL,
  `nama_barang` VARCHAR(191) NOT NULL,
  `qty` INT(11) NOT NULL DEFAULT 0,
  `product_id` BIGINT(20) DEFAULT NULL,
  `product_name` VARCHAR(191) DEFAULT NULL,
  `product_stock` INT(11) DEFAULT NULL,
  `selisih` INT(11) DEFAULT NULL,
  `match_status` VARCHAR(32) NOT NULL DEFAULT 'ZAHIR_ONLY',
  `update_status` VARCHAR(32) NOT NULL DEFAULT 'PENDING',
  `updated_product_id` BIGINT(20) DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_zahir_stock_import_items_batch` (`batch_id`),
  KEY `idx_zahir_stock_import_items_name` (`nama_barang`),
  KEY `idx_zahir_stock_import_items_match` (`match_status`),
  KEY `idx_zahir_stock_import_items_update` (`update_status`),
  CONSTRAINT `fk_zahir_stock_import_items_batch`
    FOREIGN KEY (`batch_id`) REFERENCES `zahir_stock_import_batches` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
